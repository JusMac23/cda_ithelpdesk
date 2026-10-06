<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Mail\TicketReassigned;
use App\Mail\TicketResolved;
use App\Mail\TicketUpdated;

use App\Models\Divisions;
use App\Models\ITPersonnel;
use App\Models\ReassignedTicket;
use App\Models\TechnicalServices;
use App\Models\Tickets;
use App\Models\Notification;
use App\Models\User;

class AssignedToMeController extends Controller
{
    // Display tickets assigned to the logged-in user
    public function index(Request $request)
    {
        $loggedInEmail = Auth::user()->email;

        // Start query scoped to logged-in user
        $query = Tickets::where('it_email', $loggedInEmail);

        // Apply search filter
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

        // Finalize query with ordering and pagination
        $tickets = $query->orderBy('ticket_id', 'desc')->paginate(10);

        // Fetch additional data for dropdowns
        $it_personnel = ITPersonnel::all();
        $it_area = $it_personnel->pluck('it_area')->unique()->values();
        $sections_divisions = Divisions::pluck('sections_divisions')->toArray();
        $technical_services = TechnicalServices::pluck('technical_services')->toArray();

        // Mapping IT personnel
        $it_mapping = $it_personnel->groupBy('it_area')
            ->map(fn($group) =>
                $group->map(fn($p) => [
                    'name' => trim("{$p->firstname} {$p->middle_initial} {$p->lastname}"),
                    'email' => $p->it_email
                ])
            );

        $ticket = null;

        return view('tickets.assignedtome_tickets', compact(
            'request',
            'tickets',
            'it_area',
            'it_personnel',
            'sections_divisions',
            'technical_services',
            'it_mapping',
            'ticket'
        ));
}

    // Assign Ticket
    public function assign(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|integer|exists:tickets,ticket_id',
            'assigned_to' => 'required|string',
            'assigned_it_email' => 'required|email',
            'notes' => 'nullable|string'
        ]);

        $ticket = Tickets::findOrFail($request->ticket_id);

        $ticket->status = 'Pending/Re-Assigned';
        $ticket->assigned_to = $request->assigned_to;
        $ticket->assigned_it_email = $request->assigned_it_email;
        $ticket->notes = $request->notes;
        $ticket->save();

        ReassignedTicket::create([
            'ticket_number'  => $ticket->ticket_number,
            'requested_by'   => $ticket->firstname . ' ' . $ticket->lastname,
            'request'        => $ticket->request,
            'assigned_by'    => Auth::user()->name,
            'assigned_to'    => $request->assigned_to,
            'notes'          => $request->notes,
            'assigned_at'    => now()
        ]);

        if ($ticket->it_email) {
            Mail::to($ticket->it_email)->send(new TicketReassigned($ticket));
        }

        // Create notification
        $this->createNotification(
            $ticket,
            'ticket_reassigned',
            "Ticket #{$ticket->ticket_number} was reassigned to {$ticket->assigned_to}"
        );

        return redirect()->route('assignedtome_tickets.index')->with('success', 'Ticket successfully re-assigned.');
    }

    // Edit Ticket
    public function edit($ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);

        $it_personnel = ITPersonnel::all();
        $it_area = $it_personnel->pluck('it_area')->unique()->values();
        $sections_divisions = Divisions::pluck('sections_divisions')->toArray();
        $technical_services = TechnicalServices::pluck('technical_services')->toArray();

        return view('tickets.assignedtome_tickets', compact(
            'ticket',
            'it_personnel',
            'it_area',
            'sections_divisions',
            'technical_services'
        ));
    }

    // Update Ticket
    public function update(Request $request, $ticket_id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|max:255',
            'date_resolved' => 'required|date',
            'action_taken' => 'nullable|string',
            'photo' => 'nullable|image|max:10240',
        ]);

        $ticket = Tickets::findOrFail($ticket_id);

        $validatedData['date_resolved'] = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('ticket_photos', 'public');
        }

        $ticket->update($validatedData);

        if ($ticket->email && $ticket->it_email) {
            Mail::to($ticket->email)->send(new TicketUpdated($ticket));
            Mail::to($ticket->it_email)->send(new TicketResolved($ticket));
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

        return redirect()->route('assignedtome_tickets.index')->with('success', 'Ticket updated successfully.');
    }

    // Delete Ticket
    public function destroy($ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);

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

        $ticketNumber = $ticket->ticket_number;
        $ticket->delete();

        // Create notification
        try {
            $this->createNotification(
                $ticket,
                'ticket_deleted',
                "Ticket #{$ticketNumber} was deleted"
            );
        } catch (\Throwable $e) {
            // Suppress notification creation errors
        }

        return redirect()->route('assignedtome_tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    //Create a notification record
    private function createNotification(Tickets $ticket, string $type, string $message)
    {
        Notification::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->ticket_id ?? null,
            'type' => $type,
            'message' => $message,
            'is_read' => false,
        ]);
    }
}
