<?php

namespace App\Http\Controllers;

use App\Mail\CLientTicketNotification;
use App\Mail\NewTicketSubmitted;
use App\Models\Divisions;
use App\Models\ITPersonnel;
use App\Models\Notification;
use App\Models\TechnicalServices;
use App\Models\Tickets;
use App\Models\User;
use App\Traits\RoundRobinAssignable;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class MyRequestedTicketsController extends Controller
{
    use RoundRobinAssignable;

    // -------------------------------------------------------------------------
    // Private Helpers
    // -------------------------------------------------------------------------

    /**
     * Creates an in-app notification for the user matching the given email.
     * Silently catches and logs any errors so ticket processing never fails.
     *
     * @param  Tickets  $ticket
     * @param  string|null  $email
     * @param  string   $type
     * @param  string   $message
     * @return void
     */
    private function createNotification(Tickets $ticket, ?string $email, string $type, string $message): void
    {
        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            $user = User::where('email', $email)->first();

            if ($user) {
                Notification::create([
                    'user_id'   => $user->id,
                    'ticket_id' => $ticket->getKey(),
                    'type'      => $type,
                    'message'   => $message,
                ]);
            }
        } catch (Throwable $e) {
            Log::warning("Failed to create in-app notification ({$type}) for {$email}: " . $e->getMessage());
        }
    }

    /**
     * Safely attempts to send a mailable, logging failures without interrupting
     * the application flow.
     *
     * @param  string|null                $toEmail
     * @param  \Illuminate\Mail\Mailable  $mailable
     * @param  string                     $context  Human-readable label for log messages.
     * @return void
     */
    private function sendMailSafely(?string $toEmail, $mailable, string $context = ''): void
    {
        if (empty($toEmail) || ! filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            Mail::to($toEmail)->send($mailable);
        } catch (Throwable $e) {
            Log::error("Mail send failed [{$context}] for {$toEmail}: " . $e->getMessage());
        }
    }

    /**
     * Generates a unique ticket number in the format CDA-ICT-{YEAR}-{RAND}.
     * Loops until a collision-free number is found.
     *
     * @return string
     */
    private function generateTicketNumber(): string
    {
        $year = now()->year;

        do {
            $number = "CDA-ICT-{$year}-" . random_int(1000, 9999);
        } while (Tickets::where('ticket_number', $number)->exists());

        return $number;
    }

    /**
     * Builds the round-robin next-assignment map indexed by "{area}_{service}" and "{area}_default".
     *
     * @param  array  $it_area
     * @param  array  $technical_services
     * @return array
     */
    private function buildNextAssignment(array $it_area, array $technical_services): array
    {
        $nextAssignment = [];

        foreach ($it_area as $area) {
            foreach ($technical_services as $service) {
                $assigned = $this->getNextAssignedPersonnel($area, $service);
                if ($assigned) {
                    $nextAssignment["{$area}_{$service}"] = [
                        'name'  => $this->formatFullName($assigned),
                        'email' => $assigned->it_email ?? '',
                    ];
                }
            }

            $default = $this->getNextAssignedPersonnel($area, null);
            if ($default) {
                $nextAssignment["{$area}_default"] = [
                    'name'  => $this->formatFullName($default),
                    'email' => $default->it_email ?? '',
                ];
            }
        }

        return $nextAssignment;
    }

    /**
     * Decodes a LONGBLOB binary image column or stored file path to a base64 data URI
     * and unsets the raw binary from the model instance to prevent JSON / UTF-8 encoding errors.
     *
     * @param  Tickets  $ticket
     * @param  string   $column
     * @return string|null
     */
    private function blobToDataUri(Tickets $ticket, string $column): ?string
    {
        $data = $ticket->{$column} ?? null;
        if (empty($data)) {
            return null;
        }

        // Unset from model to prevent JSON serialization errors
        unset($ticket->{$column});

        // 1. If stored as a relative file path on the public storage disk
        if (is_string($data) && ! str_contains($data, "\0") && strlen($data) < 260 && Storage::disk('public')->exists($data)) {
            $mime     = Storage::disk('public')->mimeType($data) ?: 'image/jpeg';
            $contents = Storage::disk('public')->get($data);

            return "data:{$mime};base64," . base64_encode($contents);
        }

        // 2. Raw binary data (LONGBLOB) with dynamic MIME type sniffing
        $mime = 'image/jpeg';
        if (function_exists('finfo_open')) {
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $detected = finfo_buffer($finfo, $data);
            finfo_close($finfo);

            if ($detected && str_starts_with($detected, 'image/')) {
                $mime = $detected;
            }
        }

        return "data:{$mime};base64," . base64_encode($data);
    }

    // -------------------------------------------------------------------------
    // Public Actions
    // -------------------------------------------------------------------------

    /**
     * Display the authenticated user's requested tickets with search, pagination,
     * dropdown options, and round-robin assignment data for the Add Ticket modal.
     *
     * @param  Request  $request
     * @return View
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        abort_if(! $user, 401, 'Unauthorized');

        $query = Tickets::query()
            ->where('email', $user->email)
            ->orderBy('ticket_id', 'desc');

        // Search across relevant ticket columns
        if ($request->filled('search_query')) {
            $search = trim($request->input('search_query'));
            $query->where(function ($q) use ($search) {
                $columns = [
                    'ticket_id', 'ticket_number', 'firstname', 'middle_initial',
                    'lastname', 'division', 'it_area', 'device',
                    'service', 'request', 'status', 'it_personnel',
                    'action_taken', 'priority',
                ];
                foreach ($columns as $i => $column) {
                    $method = $i === 0 ? 'where' : 'orWhere';
                    $q->{$method}($column, 'like', "%{$search}%");
                }
                $q->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$search}%"]);
            });
        }

        $tickets = $query->paginate(10)->withQueryString();

        $sections_divisions = Divisions::pluck('sections_divisions')
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $technical_services = TechnicalServices::pluck('technical_services')
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $it_personnel = ITPersonnel::all();

        $it_area = ITPersonnel::whereNotNull('it_area')
            ->where('it_area', '!=', '')
            ->pluck('it_area')
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $nextAssignment = $this->buildNextAssignment($it_area, $technical_services);

        return view('tickets.myrequested_tickets', compact(
            'tickets',
            'it_area',
            'it_personnel',
            'sections_divisions',
            'technical_services',
            'nextAssignment',
        ));
    }

    /**
     * Store a new ticket submitted by the authenticated user from the modal form.
     * Applies round-robin auto-assignment, stores raw image BLOB & backup file,
     * generates a unique ticket number, and dispatches email + in-app notifications.
     *
     * @param  Request  $request
     * @return RedirectResponse|JsonResponse
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        abort_if(! $user, 401, 'Unauthorized');

        // 1. Server-side Round-Robin Auto-Assignment
        $assignedName  = null;
        $assignedEmail = null;

        if ($request->filled('it_area')) {
            $area    = trim($request->input('it_area'));
            $service = $request->filled('service') ? trim($request->input('service')) : null;

            $assigned = $this->getNextAssignedPersonnel($area, $service);

            if ($assigned) {
                $assignedName  = $this->formatFullName($assigned);
                $assignedEmail = $assigned->it_email ?? null;
            }
        }

        // Fallback to request input if round-robin calculation returned empty
        if (empty($assignedName)) {
            $assignedName = trim($request->input('it_personnel', ''));
        }
        if (empty($assignedEmail)) {
            $assignedEmail = trim($request->input('it_email', ''));
        }

        // Fallback to first available personnel in the area
        if ((empty($assignedName) || empty($assignedEmail)) && $request->filled('it_area')) {
            $defaultPersonnel = ITPersonnel::where('it_area', trim($request->input('it_area')))->first();
            if ($defaultPersonnel) {
                $assignedName  = $assignedName ?: $this->formatFullName($defaultPersonnel);
                $assignedEmail = $assignedEmail ?: ($defaultPersonnel->it_email ?? null);
            }
        }

        // Pre-merge auto-assigned values so validation succeeds
        $request->merge([
            'it_personnel' => $assignedName,
            'it_email'     => $assignedEmail,
        ]);

        // 2. Validate form inputs
        $validated = $request->validate([
            'firstname'      => 'required|string|max:255',
            'lastname'       => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'email'          => 'required|email|max:255',
            'division'       => 'required|string|max:255',
            'device'         => 'required|string|max:255',
            'service'        => 'required|string|max:255',
            'request'        => 'required|string',
            'it_area'        => 'required|string|max:255',
            'it_personnel'   => 'required|string|max:255',
            'it_email'       => 'required|email|max:255',
            'photo'          => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'priority'       => 'required|string|in:Low,Medium,High,Critical',
            'terms_agree'    => 'accepted',
        ]);

        // Enforce server-side security defaults
        $validated['email']         = $user->email;
        $validated['status']        = 'Pending';
        $validated['date_created']  = Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');
        $validated['date_resolved'] = null;
        $validated['ticket_number'] = $this->generateTicketNumber();

        // Remove non-column input
        unset($validated['terms_agree']);

        // 3. Handle attached photo (Stores raw binary into LONGBLOB and saves a file backup)
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file               = $request->file('photo');
            $validated['photo'] = file_get_contents($file->getRealPath());
            $file->store('ticket_photos', 'public');
        } else {
            $validated['photo'] = null;
        }

        // 4. Create ticket atomically in database
        $ticket = DB::transaction(fn() => Tickets::create($validated));

        // 5. Send notifications outside transaction to prevent blocking
        $this->sendMailSafely($ticket->it_email, new NewTicketSubmitted($ticket), 'new-ticket IT personnel');
        $this->createNotification($ticket, $ticket->it_email, 'ticket_assigned', "New ticket #{$ticket->ticket_number} assigned to you.");

        $this->sendMailSafely($ticket->email, new CLientTicketNotification($ticket), 'new-ticket requester');
        $this->createNotification($ticket, $ticket->email, 'ticket_created', "Your ticket #{$ticket->ticket_number} has been created successfully.");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => "Ticket #{$ticket->ticket_number} created successfully. Confirmation emails sent.",
                'ticket'  => $ticket,
            ]);
        }

        return redirect()->route('myrequested_tickets.index')
            ->with('success', "Ticket #{$ticket->ticket_number} submitted successfully. Confirmation emails sent to you and the assigned IT personnel.");
    }

    /**
     * Show the details of a ticket owned by the authenticated user.
     * Supports both full-page and AJAX/modal requests.
     * Prevents IDOR by scoping to the user's email unless they are a Super Admin.
     *
     * @param  Request     $request
     * @param  int|string  $ticket_id
     * @return View|JsonResponse
     */
    public function view(Request $request, $ticket_id): View|JsonResponse
    {
        $user = Auth::user();
        abort_if(! $user, 401, 'Unauthorized');

        $query = Tickets::where('ticket_id', $ticket_id);

        // IDOR protection: Non-super-admins can only view their own tickets
        if (! $user->hasRole('Super Admin')) {
            $query->where('email', $user->email);
        }

        $ticket = $query->first();

        abort_if(! $ticket, 404, 'Ticket record not found or access denied.');

        // Decode LONGBLOB binary / stored image columns to base64 data URIs
        $ticketIssuePhotoEvidenceDataUri    = $this->blobToDataUri($ticket, 'photo');
        $resolvedTicketPhotoEvidenceDataUri = $this->blobToDataUri($ticket, 'photo_evidence');

        $viewName = 'tickets.view_details_myrequestedtickets';

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
}