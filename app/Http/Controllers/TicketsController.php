<?php

namespace App\Http\Controllers;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

use App\Mail\TicketSubmitted;
use App\Mail\TicketUpdated;
use App\Mail\TicketResolved;
use App\Mail\TicketReassigned;

class TicketsController extends Controller
{   
    // Display Ticket
    public function index(Request $request)
    {
        // Handle filters
        $query = Tickets::query();

        if ($request->filled('it_area')) {
            $query->where('it_area', $request->it_area);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date_created', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('date_created', '<=', $request->end_date);
        }

        // Search filter
        if ($request->filled('search_query')) {
            $search = $request->search_query;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_id', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%")
                  ->orWhere('it_area', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('device', 'like', "%{$search}%")
                  ->orWhere('service', 'like', "%{$search}%")
                  ->orWhere('request', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('it_personnel', 'like', "%{$search}%");
            });
        }

        // Export to CSV
        if ($request->input('action') === 'generate') {
            return $this->generateCSVReport($query->get());
        }

        // Paginate filtered tickets
        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10);

        // Fetch reference data
        $sections_divisions = Divisions::pluck('sections_divisions')->toArray();
        $technical_services = TechnicalServices::pluck('technical_services')->toArray();

        $it_personnel = ITPersonnel::all(['firstname', 'middle_initial', 'lastname', 'it_email', 'it_area']);
        $it_area = $it_personnel->pluck('it_area')->unique()->values();

        // Generate mapping for IT personnel autocomplete
        $it_mapping = $it_personnel->groupBy('it_area')
            ->map(fn($group) =>
                $group->map(fn($p) => [
                    'name' => trim("{$p->firstname} {$p->middle_initial} {$p->lastname}"),
                    'email' => $p->it_email
                ])
            );

        $ticket = null;    

        return view('tickets.index', compact(
            'request', 
            'tickets', 
            'sections_divisions', 
            'technical_services', 
            'it_personnel', 
            'it_area', 
            'it_mapping', 
            'ticket'
        ));
    }

    // Generate Report
    public function generateCSVReport($tickets)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=tickets_report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Ticket Number', 'First Name', 'Last Name', 'Division', 'IT Area', 'Email',
            'Device', 'Service', 'Request', 'Status', 'Date Created', 'Date Resolved', 'IT Personnel'
        ];

        $callback = function () use ($tickets, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_number,
                    $ticket->firstname,
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
                    $ticket->it_personnel
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Create Ticket
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'date_created' => 'required|date',
            'division' => 'required|string|max:255',
            'it_area' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'device' => 'required|string|max:255',
            'service' => 'required|string',
            'request' => 'required|string',
            'it_personnel' => 'required|string|max:255',
            'it_email' => 'required|email|max:255',
            'photo' => 'nullable|image|max:10240',
        ]);

        $validatedData['date_created'] = \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        $validatedData['date_resolved'] = null;

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('ticket_photos', 'public');
        }

        // First save the ticket to get ticket_id
        $ticket = Tickets::create($validatedData);

        // Generate unique 6-letter ticket number
        do {
            $ticket_number = strtoupper(Str::random(6));
        } while (Tickets::where('ticket_number', $ticket_number)->exists());

        $ticket->ticket_number = $ticket_number;
        $ticket->save();

        // Send email notification
        if ($ticket->it_email) {
            Mail::to($ticket->it_email)->send(new TicketSubmitted($ticket));
        }

        $this->createNotification(
            $ticket, 
            'ticket_created', 
            "New ticket #{$ticket->ticket_number} assigned to you"
        );

        return redirect()->route('tickets.index')->with('success', 'Ticket submitted and Email Sent to Requesting Personnel.');
    }

    private function createNotification($ticket, $type, $message)
    {
        // Find IT personnel user by email
        $user = User::where('email', $ticket->it_email)->first();
        
        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'ticket_id' => $ticket->ticket_id,
                'type' => $type,
                'message' => $message,
            ]);
        }
    }

    //Reassign Ticket
    public function assign(Request $request)
    {
        $it_personnel = ITPersonnel::all(['firstname', 'middle_initial', 'lastname', 'it_email', 'it_area']);
        $it_area = $it_personnel->pluck('it_area')->unique()->values();

        // Generate mapping for IT personnel autocomplete
        $it_mapping = $it_personnel->groupBy('it_area')
            ->map(fn($group) =>
                $group->map(fn($p) => [
                    'name' => trim("{$p->firstname} {$p->middle_initial} {$p->lastname}"),
                    'email' => $p->it_email
                ])
            );

        // ✅ Validate the request
        $request->validate([
        'ticket_id' => 'required|integer|exists:tickets,ticket_id',
        'assigned_to' => 'required|string',
        'assigned_it_email' => 'required|email',
        'notes' => 'nullable|string'
        ]);

        $ticket = Tickets::findOrFail($request->ticket_id);

        // Prevent assigning if ticket is resolved
        if ($ticket->status === 'Resolved') {
            return redirect()->back()->with('error', 'Cannot assign a resolved ticket.');
        }

        // Prevent assigning same personnel
        if (
            $ticket->it_personnel &&
            $ticket->it_personnel === $request->assigned_to &&
            $ticket->it_email === $request->assigned_it_email
        ) {
            return redirect()->back()->with('error', 'You cannot reassign the same personnel. Please select another personnel.');
        }

        // Save previous assigned personnel
        $previous_assigned = $ticket->it_personnel ?? 'N/A';

        // Update ticket with new assignee
        $ticket->update([
            'status' => 'Pending/Re-Assigned',
            'it_personnel' => $request->assigned_to,
            'it_email' => $request->assigned_it_email,
            'assigned_to' => $request->assigned_to,
            'assigned_it_email' => $request->assigned_it_email,
            'notes' => $request->notes,
        ]);

        // Log reassignment
        ReassignedTicket::create([
            'ticket_number' => $ticket->ticket_number,
            'requested_by' => $ticket->firstname . ' ' . $ticket->lastname,
            'request' => $ticket->request,
            'assigned_by' => Auth::user()->name,
            'previous_assigned' => $previous_assigned,
            'assigned_to' => $request->assigned_to,
            'notes' => $request->notes,
            'assigned_at' => now(),
        ]);

        if ($ticket->it_email) {
            Mail::to($ticket->it_email)->send(new TicketReassigned($ticket));
        }

        // Notify the reassigned personnel
        $user = User::where('email', $request->assigned_it_email)->first();
        if ($user) {
            Notification::create([
                'user_id' => $user->id,
                'ticket_id' => $ticket->ticket_id,
                'type' => 'ticket_reassigned',
                'message' => "Ticket #{$ticket->ticket_number} has been reassigned to you",
            ]);
        }

        // Notify the requestee (ticket owner)
        $requesterUser = User::where('email', $ticket->email)->first();
        if ($requesterUser) {
            Notification::create([
                'user_id' => $requesterUser->id,
                'ticket_id' => $ticket->ticket_id,
                'type' => 'ticket_reassigned_requester',
                'message' => "Your ticket #{$ticket->ticket_number} has been reassigned to {$request->assigned_to}",
            ]);
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket successfully re-assigned.');
    }

    //Edit Ticket
    public function edit($ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);

        $it_personnel = ITPersonnel::all();
        $it_area = $it_personnel->pluck('it_area')->unique()->values();
        $sections_divisions = Divisions::pluck('sections_divisions')->toArray();
        $technical_services = TechnicalServices::pluck('technical_services')->toArray();

        return view('tickets.index', compact('ticket', 'it_personnel', 'it_area', 'sections_divisions', 'technical_services'));
    }

    //Update Ticket
    public function update(Request $request, $ticket_id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|max:255',
            'date_resolved' => 'required|date',
            'action_taken' => 'required|string',
            'photo' => 'nullable|image|max:10240',
        ]);

        $ticket = Tickets::findOrFail($ticket_id);

        $validatedData['date_resolved'] = \Carbon\Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

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

    // Delete Ticket
    public function destroy($ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);
        
        // Save ticket number before deleting
        $ticketNumber = $ticket->ticket_number;

        // Delete photo if it exists
        if ($ticket->photo && \Storage::disk('public')->exists($ticket->photo)) {
            \Storage::disk('public')->delete($ticket->photo);
        }

        // Delete the ticket record
        $ticket->delete();

        // Create notification
        $this->createNotification(
            $ticket,
            'ticket_deleted',
            "Ticket #{$ticketNumber} was deleted"
        );

        // Delete reassigned records
        ReassignedTicket::where('ticket_number', $ticketNumber)->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully.');
    }

}