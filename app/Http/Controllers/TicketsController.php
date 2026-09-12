<?php

namespace App\Http\Controllers;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    /**
     * Helper Method: Generates a base ticket query scoped by the user's assigned role and region.
     */
    private function getTicketQuery()
    {
        $query = Tickets::query();
        $user = Auth::user();

        if ($user) {
            // Helper closure to check roles safely (supports both Spatie hasRole and column-based role)
            $hasRole = function($roleName) use ($user) {
                return method_exists($user, 'hasRole') 
                    ? $user->hasRole($roleName) 
                    : (isset($user->role) && strcasecmp((string)$user->role, $roleName) === 0);
            };

            $isSuperAdmin = $hasRole('Super Admin');
            $isIctsAdmin  = $hasRole('ICTS Admin');
            $isIctd       = $hasRole('ICTD');
            $isIcts       = $hasRole('ICTS');

            if ($isSuperAdmin) {
                // 1. Super Admin: View ALL tickets (no filters applied)
            } elseif ($isIctsAdmin) {
                // 2. ICTS Admin: View all tickets assigned to their region
                if (!empty($user->region)) {
                    $query->where('it_area', $user->region);
                }
            } elseif ($isIctd || $isIcts) {
                // 3. ICTD and ICTS: View ONLY tickets assigned to them
                $query->where('it_email', $user->email);
                
            } else {
                // 4. Default Fallback for any other roles (scopes to region if they have one)
                if (!empty($user->region)) {
                    $query->where('it_area', $user->region);
                }
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        // 1. Initialize base query scoped by user role/region
        $query = $this->getTicketQuery();

        $currentTime = Carbon::now('Asia/Manila');

        // 2. Load all Technical Services into memory indexed by lowercased service name
        $allTechServices = TechnicalServices::all()->keyBy(function ($item) {
            return strtolower(trim($item->technical_services));
        });

        // Exact pending status strings as saved in the tickets.status database column
        $pendingStatuses = [
            'Pending',
            'Pending/Re-Assigned',
            'Pending / Re-Assigned',
            'Pending/Reassigned',
            'pending',
            'pending/re-assigned'
        ];

        // 3. SLA Overdue Calculation Closure
        $isTicketOverdue = function ($ticket) use ($allTechServices, $currentTime, $pendingStatuses) {
            $status = trim($ticket->status ?? '');

            // Check if ticket status matches pending states
            $isPending = in_array($status, $pendingStatuses, true) || 
                         in_array(strtolower($status), array_map('strtolower', $pendingStatuses), true);

            if (!$isPending) {
                return false;
            }

            $serviceName = strtolower(trim($ticket->service ?? ''));
            $priorityKey = strtolower(trim($ticket->priority ?? ''));

            // Match against technical_services table record
            if (!isset($allTechServices[$serviceName])) {
                return false;
            }

            $serviceConfig = $allTechServices[$serviceName];

            // Validate priority column exists ('low', 'medium', 'high', 'critical')
            if (!in_array($priorityKey, ['low', 'medium', 'high', 'critical'], true)) {
                return false;
            }

            $slaTimeStr = $serviceConfig->{$priorityKey} ?? null;

            // Skip calculation if SLA is set to N/A, empty, or null
            if (empty($slaTimeStr) || strtoupper(trim($slaTimeStr)) === 'N/A') {
                return false;
            }

            // Parse datetime created from tickets.date_created
            try {
                $createdAt = Carbon::parse($ticket->date_created, 'Asia/Manila');
            } catch (\Exception $e) {
                return false;
            }

            $deadline = $createdAt->copy();

            // Extract days, hours, and minutes from SLA duration string (e.g. "3 days 30 mins")
            if (preg_match('/(\d+)\s*days?/', $slaTimeStr, $matches)) {
                $deadline->addDays((int)$matches[1]);
            }
            if (preg_match('/(\d+)\s*hours?/', $slaTimeStr, $matches)) {
                $deadline->addHours((int)$matches[1]);
            }
            if (preg_match('/(\d+)\s*mins?/', $slaTimeStr, $matches)) {
                $deadline->addMinutes((int)$matches[1]);
            }

            // Return true if current time has passed SLA deadline
            return $currentTime->greaterThan($deadline);
        };

        // 4. Calculate total count strictly based on role/regional scope
        $ticketsCount = (clone $query)->count();

        // 5. Fetch pending tickets to compute overdue count
        $pendingScopedTickets = (clone $query)
            ->whereIn('status', $pendingStatuses)
            ->get();

        $overdueCount = $pendingScopedTickets->filter($isTicketOverdue)->count();

        // 6. Apply Request Filters
        $isOverdueFilterActive = ($request->input('filter') === 'overdue');

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

        // 7. Search Query Filter across schema columns
        if ($request->filled('search_query')) {
            $search = trim($request->input('search_query'));
            $query->where(function ($q) use ($search) {
                $q->where('ticket_id', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('middle_initial', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%")
                  ->orWhere('it_area', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('device', 'like', "%{$search}%")
                  ->orWhere('service', 'like', "%{$search}%")
                  ->orWhere('request', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('it_personnel', 'like', "%{$search}%")
                  ->orWhere('action_taken', 'like', "%{$search}%")
                  ->orWhere('priority', 'like', "%{$search}%");
            });
        }

        // 8. CSV Export Handler
        if ($request->input('action') === 'generate') {
            $exportRecords = $query->get();
            if ($isOverdueFilterActive) {
                $exportRecords = $exportRecords->filter($isTicketOverdue);
            }
            return $this->generateCSVReport($exportRecords);
        }

        // 9. Pagination Handling with Primary Key (ticket_id)
        if ($isOverdueFilterActive) {
            $filteredOverdue = $query->get()->filter($isTicketOverdue);
            
            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 10;
            
            $tickets = new \Illuminate\Pagination\LengthAwarePaginator(
                $filteredOverdue->forPage($page, $perPage)->values(),
                $filteredOverdue->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10);
            $tickets->appends($request->all());
        }

        $ticket = null;

        // 10. Fetch Dropdowns & Round-Robin Data required by the embedded Add Ticket Modal
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

        // Fetch Reassignable IT Personnel and group purely by IT Area
        $reassignable_personnel = ITPersonnel::all(['firstname', 'middle_initial', 'lastname', 'it_email', 'it_area']);
        $reassignable_it_area = $reassignable_personnel->pluck('it_area')->unique()->values();
        $reassignable_it_mapping = $reassignable_personnel->groupBy('it_area')
            ->map(fn($group) => 
                $group->values()->map(fn($p) => [
                    'name'  => trim("{$p->firstname} {$p->middle_initial} {$p->lastname}"),
                    'email' => $p->it_email,
                ])
            )->toArray();

        // 11. Render View
        return view('tickets.index', compact(
            'request',
            'ticketsCount',
            'overdueCount',
            'tickets',
            'ticket',
            'sections_divisions',
            'technical_services',
            'it_area',
            'reassignable_it_area',
            'reassignable_it_mapping',
            'nextAssignment'
        ));
    }

    /**
     * Generate downloadable CSV report.
     */
    public function generateCSVReport($tickets)
    {
        $filename = "tickets_report_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Ticket Number', 'First Name', 'Last Name', 'Division', 'IT Area', 'Email',
            'Device', 'Service', 'Request', 'Status', 'Date Created', 'Date Resolved', 'IT Personnel', 'Priority', 'Action Taken'
        ];

        $callback = function () use ($tickets, $columns) {
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
                    $ticket->action_taken
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Reassign ticket to another IT personnel with validation, priority update, and notifications.
     */
    public function re_assign(Request $request)
    {
        // 1. Validate the request with flexible nullable rules
        $request->validate([
            'ticket_id'             => 'required|integer|exists:tickets,ticket_id',
            're_assigned_to'        => 'nullable|string', 
            're_assigned_it_email'  => 'nullable|email',  
            'priority'              => 'nullable|string|max:255',
            'notes'                 => 'nullable|string',
        ]);

        $ticket = Tickets::findOrFail($request->ticket_id);

        // 2. Prevent assigning if ticket is resolved
        if ($ticket->status === 'Resolved') {
            return redirect()->back()->with('error', 'Cannot assign a resolved ticket.');
        }

        // Determine personnel name
        $reAssignedTo = $request->input('re_assigned_to') ?: $ticket->it_personnel;
        if (empty($reAssignedTo)) {
            $reAssignedTo = 'Unassigned';
        }

        // Determine email (auto-lookup if email input was empty)
        $reAssignedEmail = $request->input('re_assigned_it_email');
        if (empty($reAssignedEmail) && $reAssignedTo !== 'Unassigned') {
            $personnelObj = ITPersonnel::all()->first(function ($p) use ($reAssignedTo) {
                $fullName = trim("{$p->firstname} {$p->middle_initial} {$p->lastname}");
                return strcasecmp($fullName, $reAssignedTo) === 0 || strcasecmp("{$p->firstname} {$p->lastname}", $reAssignedTo) === 0;
            });
            $reAssignedEmail = $personnelObj ? $personnelObj->it_email : $ticket->it_email;
        }

        if (empty($reAssignedEmail)) {
            $reAssignedEmail = 'no-email@cda.gov.ph';
        }

        // 3. Prevent assigning same personnel without any changes
        if (
            $ticket->it_personnel &&
            $ticket->it_personnel === $reAssignedTo &&
            $ticket->it_email === $reAssignedEmail &&
            (!$request->filled('priority') || $ticket->priority === $request->priority)
        ) {
            return redirect()->back()->with('error', 'You cannot reassign the same personnel without changes. Please select another personnel or priority.');
        }

        // Save previous assigned personnel
        $previous_assigned = $ticket->it_personnel ?? 'N/A';
        $assignedBy = Auth::user() ? Auth::user()->name : 'System';

        // 4. Prepare ticket update data
        $updateData = [
            'status'                => 'Pending/Re-Assigned',
            'it_personnel'          => $reAssignedTo,
            'it_email'              => $reAssignedEmail,
            're_assigned_to'        => $reAssignedTo,
            're_assigned_it_email'  => $reAssignedEmail,
            'notes'                 => $request->notes,
            're_assigned_at'        => now('Asia/Manila'),
        ];

        if ($request->filled('priority')) {
            $updateData['priority'] = $request->priority;
        }

        $ticket->update($updateData);

        // 5. Log reassignment history with priority and NOT-NULL safe fallbacks
        ReassignedTicket::create([
            'ticket_number'     => $ticket->ticket_number,
            'requested_by'      => $ticket->firstname . ' ' . $ticket->lastname,
            'request'           => $ticket->request ?? 'N/A',
            'assigned_by'       => $assignedBy,
            'previous_assigned' => $previous_assigned,
            're_assigned_to'    => $reAssignedTo,
            'priority'          => $ticket->priority ?? 'Normal',
            'notes'             => $request->notes ?? 'No notes provided', 
            're_assigned_at'    => now('Asia/Manila'),
            'status'            => 'Pending/Re-Assigned',
        ]);

        // 6. Safe Mail dispatch to IT personnel
        if ($ticket->it_email && filter_var($ticket->it_email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($ticket->it_email)->send(new TicketReassigned($ticket));
            } catch (\Exception $e) {
                Log::error('Failed sending ticket reassignment email to IT personnel: ' . $e->getMessage());
            }
        }

        // Send email to the Ticket Requester
        if ($ticket->email && filter_var($ticket->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($ticket->email)->send(new TicketReassignedRequester($ticket, $assignedBy));
            } catch (\Exception $e) {
                Log::error('Failed sending ticket reassignment email to requester: ' . $e->getMessage());
            }
        }

        // 7. Notify the reassigned personnel
        $user = User::where('email', $reAssignedEmail)->first();
        if ($user) {
            Notification::create([
                'user_id'   => $user->id,
                'ticket_id' => $ticket->ticket_id,
                'type'      => 'ticket_reassigned',
                'message'   => "Ticket #{$ticket->ticket_number} has been reassigned to you",
            ]);
        }

        // 8. Notify the requestee (ticket owner)
        $requesterUser = User::where('email', $ticket->email)->first();
        if ($requesterUser) {
            Notification::create([
                'user_id'   => $requesterUser->id,
                'ticket_id' => $ticket->ticket_id,
                'type'      => 'ticket_reassigned_requester',
                'message'   => "Your ticket #{$ticket->ticket_number} has been reassigned to {$reAssignedTo}",
            ]);
        }

        return redirect()->back()->with('success', 'Ticket successfully re-assigned.');
    }

    /**
     * Show the edit form for a specific ticket.
     */
    public function edit($ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);

        $it_personnel = ITPersonnel::all();
        $it_area = $it_personnel->pluck('it_area')->unique()->values();
        $sections_divisions = Divisions::pluck('sections_divisions')->toArray();
        $technical_services = TechnicalServices::pluck('technical_services')->toArray();

        return view('tickets.index', compact('ticket', 'it_personnel', 'it_area', 'sections_divisions', 'technical_services'));
    }

    /**
     * Update ticket details with validation, file handling, and notifications.
     */
    public function update(Request $request, $ticket_id)
    {
        $validatedData = $request->validate([
            'priority'      => 'required|string|max:255',
            'status'        => 'required|string|max:255',
            'date_resolved' => 'required|date',
            'action_taken'  => 'required|string',
            'photo'         => 'nullable|image|max:10240',
        ]);

        $ticket = Tickets::findOrFail($ticket_id);

        $validatedData['date_resolved'] = Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('ticket_photos', 'public');
        }

        $ticket->update($validatedData);

        if ($ticket->email && $ticket->it_email) {
            // Send email notification to both requester and IT personnel
            Mail::to($ticket->email)->send(new TicketUpdated($ticket));
            Mail::to($ticket->it_email)->send(new TicketResolved($ticket));
        }

        if ($ticket->status === 'Resolved') {
            $requesterUser = User::where('email', $ticket->email)->first();
            if ($requesterUser) {
                Notification::create([
                    'user_id' => $requesterUser->id,
                    'ticket_id' => $ticket->ticket_id,
                    'type' => 'ticket_resolved',
                    'message' => "Your ticket #{$ticket->ticket_number} has been resolved",
                ]);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Delete a ticket.
     */
    public function destroy($ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);
        
        // Save ticket number before deleting
        $ticketNumber = $ticket->ticket_number;

        // Delete photo if it exists
        if ($ticket->photo && Storage::disk('public')->exists($ticket->photo)) {
            Storage::disk('public')->delete($ticket->photo);
        }

        // Delete the ticket record
        $ticket->delete();

        // Delete reassigned records
        ReassignedTicket::where('ticket_number', $ticketNumber)->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }
}