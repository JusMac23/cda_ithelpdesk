<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Mail\TicketReassigned;
use App\Mail\TicketResolved;
use App\Mail\TicketUpdated;
use App\Mail\NewTicketSubmitted;
use App\Mail\CLientTicketNotification; 

use App\Models\Divisions;
use App\Models\ITPersonnel;
use App\Models\ReassignedTicket;
use App\Models\TechnicalServices;
use App\Models\Tickets;
use App\Models\Notification;
use App\Models\User;

use App\Traits\RoundRobinAssignable;

class MyRequestedTicketsController extends Controller
{
    use RoundRobinAssignable; 

    public function index(Request $request)
    {
        $loggedInEmail = Auth::user()->email;

        $tickets = Tickets::where('email', $loggedInEmail)
                    ->orderBy('ticket_id', 'desc')
                    ->paginate(10);

        // Load all Technical Services into memory indexed by lowercased service name
        $allTechServices = TechnicalServices::all()->keyBy(function ($item) {
            return strtolower(trim($item->technical_services));
        });            

        $ticket = null;

        // Fetch Dropdowns & Clean strings (trim) to prevent frontend key mismatches
        $sections_divisions = Divisions::pluck('sections_divisions')
            ->map(fn($item) => trim($item))
            ->filter()
            ->values()
            ->toArray();

        $technical_services = TechnicalServices::pluck('technical_services')
            ->map(fn($item) => trim($item))
            ->filter()
            ->values()
            ->toArray();

        $it_personnel = ITPersonnel::all();

        $it_area = ITPersonnel::whereNotNull('it_area')
            ->where('it_area', '!=', '')
            ->pluck('it_area')
            ->map(fn($item) => trim($item))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

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

        $it_mapping = $nextAssignment;

        return view('tickets.myrequested_tickets', compact(
            'tickets',
            'it_area',
            'it_personnel',
            'sections_divisions',
            'technical_services',
            'it_mapping',
            'nextAssignment',
            'ticket'
        ));
    }

    /**
     * Store and process private ticket creation submitted from the modal.
     */
    public function store(Request $request)
    {
        if ($request->filled('it_area')) {
            $area = trim($request->input('it_area'));
            $service = $request->filled('service') ? trim($request->input('service')) : null;
            $assigned = $this->getNextAssignedPersonnel($area, $service);

            if ($assigned) {
                $request->merge([
                    'it_personnel' => $this->formatFullName($assigned),
                    'it_email'     => $assigned->it_email,
                ]);
            }
        }

        $validatedData = $request->validate([
            'firstname'        => 'required|string|max:255',
            'lastname'         => 'required|string|max:255',
            'middle_initial'   => 'nullable|string|max:10',
            'email'            => 'required|email|max:255',
            'date_created'     => 'required|date',
            'division'         => 'required|string|max:255',
            'device'           => 'required|string|max:255',
            'service'          => 'required|string|max:255',
            'request'          => 'required|string',
            'it_area'          => 'required|string|max:255',
            'it_personnel'     => 'required|string',
            'it_email'         => 'required|string|email',
            'status'           => 'required|string|max:255',
            'photo'            => 'nullable|image|max:10240',
            'priority'         => 'required|string|max:255',
        ]);

        $validatedData['date_created']  = Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');
        $validatedData['date_resolved'] = null;

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('ticket_photos', 'public');
        }

        $ticket = Tickets::create($validatedData);

        $orgName = 'CDA'; 
        $currentYear = now()->year;

        do {
            $randomNumber = random_int(1000, 9999);
            $ticket_number = "{$orgName}-ICT-{$currentYear}-{$randomNumber}";
        } while (Tickets::where('ticket_number', $ticket_number)->exists());

        $ticket->ticket_number = $ticket_number;
        $ticket->save();

        if ($ticket->it_email && filter_var($ticket->it_email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($ticket->it_email)->send(new NewTicketSubmitted($ticket));
            } catch (\Exception $e) {
                Log::error('Failed sending private ticket notification to IT: ' . $e->getMessage());
            }

            $this->createNotification(
                $ticket,
                $ticket->it_email,
                'ticket_assigned',
                "New ticket #{$ticket->ticket_number} assigned to you"
            );
        }

        if ($ticket->email && filter_var($ticket->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($ticket->email)->send(new CLientTicketNotification($ticket));
            } catch (\Exception $e) {
                Log::error('Failed sending private ticket notification to client: ' . $e->getMessage());
            }

            $this->createNotification(
                $ticket,
                $ticket->email,
                'ticket_created',
                "Your ticket #{$ticket->ticket_number} has been created successfully."
            );
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => "Ticket #{$ticket->ticket_number} created successfully. Confirmation emails sent."
            ]);
        }

        return redirect()->back()->with('success', "Ticket #{$ticket->ticket_number} submitted successfully. Confirmation emails sent to you and assigned IT personnel.");
    }

    private function createNotification($ticket, $email, $type, $message)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            Notification::create([
                'user_id'   => $user->id,
                'ticket_id' => $ticket->getKey(),
                'type'      => $type,
                'message'   => $message,
            ]);
        }
    }

    /**
     * Safely view ticket details.
     */
    public function view(Request $request, $ticket_id)
    {
        $loggedInEmail = Auth::user()->email;

        // Fetch ticket belonging to the logged-in user
        $ticket = Tickets::where('ticket_id', $ticket_id)
            ->where('email', $loggedInEmail)
            ->first();

        // Fallback lookup if custom primary key resolution is required
        if (!$ticket) {
            $ticket = Tickets::where('email', $loggedInEmail)->find($ticket_id);
        }

        if (!$ticket) {
            abort(404, 'Ticket record not found or access denied.');
        }

        // Process Client's Attached Issue Photo / Screenshot (LONGBLOB)
        $ticketIssuePhotoEvidenceDataUri = null;
        if (!empty($ticket->photo)) {
            $base64Image = base64_encode($ticket->photo);
            $ticketIssuePhotoEvidenceDataUri = 'data:image/jpeg;base64,' . $base64Image;

            // Clear raw binary data from model to prevent UTF-8 encoding errors during JSON rendering
            unset($ticket->photo);
        }

        // Process IT Personnel's Resolved Ticket Photo Evidence (LONGBLOB)
        $resolvedTicketPhotoEvidenceDataUri = null;
        if (!empty($ticket->photo_evidence)) {
            $base64Image = base64_encode($ticket->photo_evidence);
            $resolvedTicketPhotoEvidenceDataUri = 'data:image/jpeg;base64,' . $base64Image;

            // Clear raw binary data from model to prevent UTF-8 encoding errors during JSON rendering
            unset($ticket->photo_evidence);
        }

        $viewName = 'tickets.view_details_myrequestedtickets';

        // Handle AJAX/JSON requests for modals
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'                             => 'success',
                'ticket'                             => $ticket,
                'ticketIssuePhotoEvidenceDataUri'    => $ticketIssuePhotoEvidenceDataUri,
                'resolvedTicketPhotoEvidenceDataUri' => $resolvedTicketPhotoEvidenceDataUri,
                'html'                               => view($viewName, compact('ticket', 'ticketIssuePhotoEvidenceDataUri', 'resolvedTicketPhotoEvidenceDataUri'))->render(),
            ]);
        }

        // Standard View Response
        return view($viewName, compact('ticket', 'ticketIssuePhotoEvidenceDataUri', 'resolvedTicketPhotoEvidenceDataUri'));
    }
}