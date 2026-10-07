<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\StreamedResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Models\Tickets;
use App\Models\Divisions;
use App\Models\TechnicalServices;
use App\Models\ITPersonnel;
use App\Models\ReassignedTicket;
use App\Models\Notification;
use App\Models\User;

use App\Traits\RoundRobinAssignable;

use App\Mail\TicketUpdated;
use App\Mail\TicketResolved;
use App\Mail\TicketReassigned;
use App\Mail\TicketReassignedRequester;

class TicketsController extends Controller
{
    use RoundRobinAssignable;

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    /**
     * Returns a base Tickets query scoped to the authenticated user's role and region.
     * Super Admin → all tickets.
     * ICTS Admin   → tickets within their region (it_area).
     * ICTD / ICTS  → tickets assigned to them personally (it_email).
     * Others       → tickets within their region if set, otherwise all.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getTicketQuery()
    {
        $query = Tickets::query();
        $user  = Auth::user();

        if (! $user) {
            return $query;
        }

        if ($user->hasRole('Super Admin')) {
            // No additional constraints — see all tickets.
        } elseif ($user->hasRole('ICTS Admin')) {
            if (! empty($user->region)) {
                $query->where('it_area', $user->region);
            }
        } elseif ($user->hasAnyRole(['ICTD', 'ICTS'])) {
            $query->where('it_email', $user->email);
        } else {
            // Fallback: scope to region if the user has one.
            if (! empty($user->region)) {
                $query->where('it_area', $user->region);
            }
        }

        return $query;
    }

    /**
     * Resolves the IT personnel's email address.
     * Uses the provided email if valid; otherwise looks up the email from the
     * ITPersonnel table by matching against the provided full name.
     * Falls back to the ticket's current it_email, and finally a placeholder.
     *
     * @param  string|null  $inputEmail
     * @param  string       $personnelName
     * @param  Tickets      $ticket
     * @return string
     */
    private function resolvePersonnelEmail(?string $inputEmail, string $personnelName, Tickets $ticket): string
    {
        if (! empty($inputEmail)) {
            return $inputEmail;
        }

        if ($personnelName === 'Unassigned') {
            return $ticket->it_email ?? 'no-email@cda.gov.ph';
        }

        // Query by name instead of loading all records into memory.
        $personnel = ITPersonnel::where(function ($q) use ($personnelName) {
            // Try "firstname middle_initial lastname" and "firstname lastname" variants.
            $q->whereRaw("TRIM(CONCAT(firstname, ' ', COALESCE(middle_initial,''), ' ', lastname)) = ?", [$personnelName])
              ->orWhereRaw("TRIM(CONCAT(firstname, ' ', lastname)) = ?", [$personnelName]);
        })->first();

        return $personnel?->it_email ?? $ticket->it_email ?? 'no-email@cda.gov.ph';
    }

    /**
     * Dispatches in-app notifications to the reassigned IT personnel and the
     * ticket requester. Silently skips if neither user account exists.
     *
     * @param  Tickets  $ticket
     * @param  string   $reAssignedTo
     * @param  string   $reAssignedEmail
     * @return void
     */
    private function dispatchReassignmentNotifications(Tickets $ticket, string $reAssignedTo, string $reAssignedEmail): void
    {
        // Notify the newly assigned IT personnel.
        $itUser = User::where('email', $reAssignedEmail)->first();
        if ($itUser) {
            Notification::create([
                'user_id'   => $itUser->id,
                'ticket_id' => $ticket->ticket_id,
                'type'      => 'ticket_reassigned',
                'message'   => "Ticket #{$ticket->ticket_number} has been reassigned to you.",
            ]);
        }

        // Notify the ticket requester.
        $requesterUser = User::where('email', $ticket->email)->first();
        if ($requesterUser) {
            Notification::create([
                'user_id'   => $requesterUser->id,
                'ticket_id' => $ticket->ticket_id,
                'type'      => 'ticket_reassigned_requester',
                'message'   => "Your ticket #{$ticket->ticket_number} has been reassigned to {$reAssignedTo}.",
            ]);
        }
    }

    /**
     * Safely attempts to send a mailable, logging any failure without
     * interrupting the application flow.
     *
     * @param  string    $toEmail
     * @param  \Illuminate\Mail\Mailable  $mailable
     * @param  string    $context   Human-readable label used in log messages.
     * @return void
     */
    private function sendMailSafely(string $toEmail, $mailable, string $context = ''): void
    {
        if (empty($toEmail) || ! filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            Mail::to($toEmail)->send($mailable);
        } catch (\Exception $e) {
            Log::error("Mail send failed [{$context}]: " . $e->getMessage());
        }
    }

    // -------------------------------------------------------------------------
    // Public Actions
    // -------------------------------------------------------------------------

    /**
     * Display the paginated ticket list with filters, SLA overdue detection,
     * CSV export, and dropdown data for the Add / Reassign modals.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query       = $this->getTicketQuery();
        $currentTime = Carbon::now('Asia/Manila');

        // Load SLA configuration once, keyed by lowercase service name.
        $allTechServices = TechnicalServices::all()->keyBy(
            fn($item) => strtolower(trim($item->technical_services))
        );

        $pendingStatuses = [
            'Pending',
            'Pending/Re-Assigned',
            'Pending / Re-Assigned',
            'Pending/Reassigned',
        ];

        // Closure: determines whether a ticket has breached its SLA deadline.
        $isTicketOverdue = function ($ticket) use ($allTechServices, $currentTime, $pendingStatuses): bool {
            $status = trim($ticket->status ?? '');

            $isPending = collect($pendingStatuses)
                ->contains(fn($s) => strcasecmp($s, $status) === 0);

            if (! $isPending) {
                return false;
            }

            $serviceName = strtolower(trim($ticket->service ?? ''));
            $priorityKey = strtolower(trim($ticket->priority ?? ''));

            $serviceConfig = $allTechServices[$serviceName] ?? null;

            if (! $serviceConfig) {
                return false;
            }

            if (! in_array($priorityKey, ['low', 'medium', 'high', 'critical'], true)) {
                return false;
            }

            $slaTimeStr = $serviceConfig->{$priorityKey} ?? null;

            if (empty($slaTimeStr) || strtoupper(trim($slaTimeStr)) === 'N/A') {
                return false;
            }

            try {
                $deadline = Carbon::parse($ticket->date_created, 'Asia/Manila');
            } catch (\Exception) {
                return false;
            }

            // Parse SLA duration string e.g. "1 year/s, 1 month/s, 3 day/s, 2 hour/s, 30 min/s".
            if (preg_match('/(\d+)\s*years?/i', $slaTimeStr, $m)) {
                $deadline->addYears((int) $m[1]);
            }
            if (preg_match('/(\d+)\s*months?/i', $slaTimeStr, $m)) {
                $deadline->addMonths((int) $m[1]);
            }
            if (preg_match('/(\d+)\s*weeks?/i', $slaTimeStr, $m)) {
                $deadline->addWeeks((int) $m[1]);
            }
            if (preg_match('/(\d+)\s*days?/i', $slaTimeStr, $m)) {
                $deadline->addDays((int) $m[1]);
            }
            if (preg_match('/(\d+)\s*hours?/i', $slaTimeStr, $m)) {
                $deadline->addHours((int) $m[1]);
            }
            if (preg_match('/(\d+)\s*mins?/i', $slaTimeStr, $m)) {
                $deadline->addMinutes((int) $m[1]);
            }

            return $currentTime->greaterThan($deadline);
        };

        // Counts (pre-filter, scoped to role).
        $ticketsCount = (clone $query)->count();

        $overdueCount = (clone $query)
            ->whereIn('status', $pendingStatuses)
            ->get()
            ->filter($isTicketOverdue)
            ->count();

        // ── Request Filters ──────────────────────────────────────────────────
        if ($request->filled('it_area')) {
            $query->where('it_area', trim($request->input('it_area')));
        }

        if ($request->filled('status')) {
            $query->where('status', trim($request->input('status')));
        }

        if ($request->filled('priority')) {
            $query->where('priority', trim($request->input('priority')));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date_created', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date_created', '<=', $request->input('end_date'));
        }

        if ($request->filled('search_query')) {
            $search = trim($request->input('search_query'));
            $query->where(function ($q) use ($search) {
                $columns = [
                    'ticket_id', 'ticket_number', 'firstname', 'middle_initial',
                    'lastname', 'division', 'it_area', 'email', 'device',
                    'service', 'request', 'status', 'it_personnel',
                    'action_taken', 'priority',
                ];
                foreach ($columns as $i => $column) {
                    $method = $i === 0 ? 'where' : 'orWhere';
                    $q->{$method}($column, 'like', "%{$search}%");
                }
            });
        }

        // ── CSV Export ───────────────────────────────────────────────────────
        if ($request->input('action') === 'generate') {
            $exportRecords = $query->get();
            if ($request->input('filter') === 'overdue') {
                $exportRecords = $exportRecords->filter($isTicketOverdue);
            }
            return $this->generateCSVReport($exportRecords);
        }

        // ── Pagination ───────────────────────────────────────────────────────
        $isOverdueFilterActive = ($request->input('filter') === 'overdue');

        if ($isOverdueFilterActive) {
            $allFiltered = $query->get()->filter($isTicketOverdue)->values();
            $page        = Paginator::resolveCurrentPage() ?: 1;
            $perPage     = 10;

            $tickets = new LengthAwarePaginator(
                $allFiltered->forPage($page, $perPage)->values(),
                $allFiltered->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10)->appends($request->all());
        }

        // ── Dropdown & Round-Robin Data ──────────────────────────────────────
        $sections_divisions = Divisions::pluck('sections_divisions')->filter()->toArray();
        $technical_services = TechnicalServices::pluck('technical_services')->filter()->toArray();

        $it_area = ITPersonnel::whereNotNull('it_area')
            ->where('it_area', '!=', '')
            ->distinct()
            ->pluck('it_area')
            ->values();

        $nextAssignment = [];
        foreach ($it_area as $area) {
            foreach ($technical_services as $service) {
                $assigned = $this->getNextAssignedPersonnel($area, $service);
                if ($assigned) {
                    $nextAssignment["{$area}_{$service}"] = [
                        'name'  => $this->formatFullName($assigned),
                        'email' => $assigned->it_email,
                    ];
                }
            }

            $assignedDefault = $this->getNextAssignedPersonnel($area, null);
            if ($assignedDefault) {
                $nextAssignment["{$area}_default"] = [
                    'name'  => $this->formatFullName($assignedDefault),
                    'email' => $assignedDefault->it_email,
                ];
            }
        }

        $reassignable_personnel = ITPersonnel::all(['firstname', 'middle_initial', 'lastname', 'it_email', 'it_area']);
        $reassignable_it_area   = $reassignable_personnel->pluck('it_area')->unique()->values();
        $reassignable_it_mapping = $reassignable_personnel->groupBy('it_area')->map(
            fn($group) => $group->values()->map(fn($p) => [
                'name'  => trim("{$p->firstname} {$p->middle_initial} {$p->lastname}"),
                'email' => $p->it_email,
            ])
        )->toArray();

        return view('tickets.index', compact(
            'request',
            'ticketsCount',
            'overdueCount',
            'tickets',
            'sections_divisions',
            'technical_services',
            'it_area',
            'reassignable_it_area',
            'reassignable_it_mapping',
            'nextAssignment',
        ));
    }

    /**
     * Generate and stream a downloadable CSV report of the given ticket collection.
     *
     * @param  \Illuminate\Support\Collection  $tickets
     * @return StreamedResponse
     */
    public function generateCSVReport($tickets): StreamedResponse
    {
        $filename = 'tickets_report_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'Ticket Number', 'First Name', 'Middle Initial', 'Last Name',
            'Division', 'Region', 'Email', 'Device', 'Service', 'Request',
            'Status', 'Date Created', 'Date Resolved', 'IT Personnel',
            'Priority', 'Action Taken',
        ];

        return response()->stream(function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->firstname,
                    $ticket->middle_initial,
                    $ticket->lastname,
                    $ticket->division,
                    $ticket->it_area,
                    $ticket->email,
                    $ticket->device,
                    $ticket->service,
                    $ticket->request,
                    $ticket->status,
                    $ticket->date_created,
                    $ticket->date_resolved,
                    $ticket->it_personnel,
                    $ticket->priority,
                    $ticket->action_taken,
                ]);
            }

            fclose($file);
        }, 200, $headers);
    }

    /**
     * Show a ticket's detail view (supports both full-page and AJAX/modal requests).
     *
     * @param  Request  $request
     * @param  int      $ticket_id
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function view(Request $request, $ticket_id)
    {
        $ticket = $this->getTicketQuery()
            ->where('ticket_id', $ticket_id)
            ->first();

        abort_if(! $ticket, 404, 'Ticket not found or access denied.');

        // Decode LONGBLOB photo fields to base64 data URIs and clear raw binary.
        $ticketIssuePhotoEvidenceDataUri = null;
        if (! empty($ticket->photo)) {
            $ticketIssuePhotoEvidenceDataUri = 'data:image/jpeg;base64,' . base64_encode($ticket->photo);
            unset($ticket->photo);
        }

        $resolvedTicketPhotoEvidenceDataUri = null;
        if (! empty($ticket->photo_evidence)) {
            $resolvedTicketPhotoEvidenceDataUri = 'data:image/jpeg;base64,' . base64_encode($ticket->photo_evidence);
            unset($ticket->photo_evidence);
        }

        $viewName = 'tickets.view_details_tickets';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'                             => 'success',
                'ticket'                             => $ticket,
                'ticketIssuePhotoEvidenceDataUri'    => $ticketIssuePhotoEvidenceDataUri,
                'resolvedTicketPhotoEvidenceDataUri' => $resolvedTicketPhotoEvidenceDataUri,
                'html'                               => view($viewName, compact(
                    'ticket',
                    'ticketIssuePhotoEvidenceDataUri',
                    'resolvedTicketPhotoEvidenceDataUri'
                ))->render(),
            ]);
        }

        return view($viewName, compact(
            'ticket',
            'ticketIssuePhotoEvidenceDataUri',
            'resolvedTicketPhotoEvidenceDataUri'
        ));
    }

    /**
     * Reassign a ticket to another IT personnel, log the history, and dispatch
     * email + in-app notifications. The entire operation runs in a DB transaction.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function re_assign(Request $request): RedirectResponse
    {
        $request->validate([
            'ticket_id'            => 'required|integer|exists:tickets,ticket_id',
            're_assigned_to'       => 'nullable|string|max:255',
            're_assigned_it_email' => 'nullable|email|max:255',
            'priority'             => 'nullable|string|max:255',
            'notes'                => 'nullable|string|max:2000',
        ]);

        $ticket = Tickets::findOrFail($request->ticket_id);

        if ($ticket->status === 'Resolved') {
            return back()->with('error', 'Cannot reassign a resolved ticket.');
        }

        // Determine the target personnel name.
        $reAssignedTo = trim($request->input('re_assigned_to') ?: $ticket->it_personnel ?: 'Unassigned');

        // Resolve the email via helper (avoids loading all ITPersonnel into memory).
        $reAssignedEmail = $this->resolvePersonnelEmail(
            $request->input('re_assigned_it_email'),
            $reAssignedTo,
            $ticket
        );

        // Guard: prevent reassigning to the same person with no other changes.
        if (
            $ticket->it_personnel &&
            $ticket->it_personnel === $reAssignedTo &&
            $ticket->it_email     === $reAssignedEmail &&
            (! $request->filled('priority') || $ticket->priority === $request->input('priority'))
        ) {
            return back()->with('error', 'No changes detected. Please select a different personnel or priority.');
        }

        $previousAssigned = $ticket->it_personnel ?? 'N/A';
        $assignedBy       = Auth::user()->name;

        DB::transaction(function () use ($ticket, $request, $reAssignedTo, $reAssignedEmail, $previousAssigned, $assignedBy) {
            // Update the ticket.
            $updateData = [
                'status'               => 'Pending/Re-Assigned',
                'it_personnel'         => $reAssignedTo,
                'it_email'             => $reAssignedEmail,
                're_assigned_to'       => $reAssignedTo,
                're_assigned_it_email' => $reAssignedEmail,
                'notes'                => $request->input('notes'),
                're_assigned_at'       => now('Asia/Manila'),
            ];

            if ($request->filled('priority')) {
                $updateData['priority'] = $request->input('priority');
            }

            $ticket->update($updateData);

            // Log the reassignment history.
            ReassignedTicket::create([
                'ticket_number'    => $ticket->ticket_number,
                'requested_by'     => trim("{$ticket->firstname} {$ticket->lastname}"),
                'request'          => $ticket->request          ?? 'N/A',
                'assigned_by'      => $assignedBy,
                'previous_assigned'=> $previousAssigned,
                're_assigned_to'   => $reAssignedTo,
                'priority'         => $ticket->priority         ?? 'Normal',
                'notes'            => $request->input('notes')  ?? 'No notes provided',
                're_assigned_at'   => now('Asia/Manila'),
                'status'           => 'Pending/Re-Assigned',
            ]);
        });

        // Refresh ticket after transaction so email uses latest data.
        $ticket->refresh();

        // Send email notifications outside the transaction to avoid long locks.
        $this->sendMailSafely($reAssignedEmail, new TicketReassigned($ticket), 'IT personnel reassignment');
        $this->sendMailSafely($ticket->email, new TicketReassignedRequester($ticket, $assignedBy), 'requester reassignment');

        // Dispatch in-app notifications.
        $this->dispatchReassignmentNotifications($ticket, $reAssignedTo, $reAssignedEmail);

        return back()->with('success', 'Ticket successfully re-assigned.');
    }

    /**
     * Show the edit form for a specific ticket.
     *
     * @param  int  $ticket_id
     * @return \Illuminate\View\View
     */
    public function edit($ticket_id)
    {
        $ticket             = Tickets::findOrFail($ticket_id);
        $it_personnel       = ITPersonnel::all();
        $it_area            = $it_personnel->pluck('it_area')->unique()->values();
        $sections_divisions = Divisions::pluck('sections_divisions')->toArray();
        $technical_services = TechnicalServices::pluck('technical_services')->toArray();

        return view('tickets.index', compact('ticket', 'it_personnel', 'it_area', 'sections_divisions', 'technical_services'));
    }

    /**
     * Update ticket resolution details, handle photo evidence, and send notifications.
     * Wraps DB writes in a transaction and guards mail sends against invalid emails.
     *
     * @param  Request  $request
     * @param  int      $ticket_id
     * @return RedirectResponse
     */
    public function update(Request $request, $ticket_id): RedirectResponse
    {
        $validated = $request->validate([
            'priority'       => 'required|string|max:255',
            'status'         => 'required|string|max:255',
            'date_resolved'  => 'required|date',
            'action_taken'   => 'required|string',
            'photo_evidence' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'link_evidence'  => 'nullable|string|max:2000',
        ]);

        $ticket = Tickets::findOrFail($ticket_id);

        // Always stamp resolution time server-side (ignore client-submitted value).
        $validated['date_resolved'] = Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');

        if ($request->hasFile('photo_evidence') && $request->file('photo_evidence')->isValid()) {
            $file = $request->file('photo_evidence');
            $validated['photo_evidence'] = file_get_contents($file->getRealPath());
            $file->store('ticket_photos', 'public');
        } else {
            $validated['photo_evidence'] = null;
        }

        DB::transaction(fn() => $ticket->update($validated));

        // Send resolution emails outside the transaction.
        $this->sendMailSafely($ticket->email,       new TicketUpdated($ticket),  'ticket-updated requester');
        $this->sendMailSafely($ticket->it_email,    new TicketResolved($ticket), 'ticket-resolved IT personnel');

        // In-app notification for the requester on resolution.
        if ($ticket->status === 'Resolved') {
            $requesterUser = User::where('email', $ticket->email)->first();
            if ($requesterUser) {
                Notification::create([
                    'user_id'   => $requesterUser->id,
                    'ticket_id' => $ticket->ticket_id,
                    'type'      => 'ticket_resolved',
                    'message'   => "Your ticket #{$ticket->ticket_number} has been resolved.",
                ]);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Delete a ticket and its associated reassignment history records.
     *
     * @param  int  $ticket_id
     * @return RedirectResponse
     */
    public function destroy($ticket_id): RedirectResponse
    {
        $ticket       = Tickets::findOrFail($ticket_id);
        $ticketNumber = $ticket->ticket_number;

        // Remove attached photo files from storage if stored as file paths (BLOB data is stored directly in DB).
        foreach (['photo', 'photo_evidence'] as $column) {
            $path = $ticket->{$column} ?? null;
            if (is_string($path) && strlen($path) < 260 && ! str_contains($path, "\0") && ! preg_match('/[\r\n]/', $path)) {
                try {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                } catch (\Throwable $e) {
                    // Suppress any storage / Flysystem path validation exceptions
                }
            }
        }

        DB::transaction(function () use ($ticket, $ticketNumber) {
            $ticket->delete();
            ReassignedTicket::where('ticket_number', $ticketNumber)->delete();
        });

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}