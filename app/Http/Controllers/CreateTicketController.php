<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Tickets;
use App\Models\Divisions;
use App\Models\TechnicalServices;
use App\Models\ITPersonnel;
use App\Models\Notification;
use App\Models\User;
use App\Mail\TicketSubmitted;

class CreateTicketController extends Controller
{
    // Show the ticket form
    public function showForm()
    {
        $sections_divisions = Divisions::pluck('sections_divisions')->toArray();
        $technical_services = TechnicalServices::pluck('technical_services')->toArray();

        $it_personnel = ITPersonnel::all(['firstname', 'middle_initial', 'lastname', 'it_email', 'it_area']);
        $it_area = $it_personnel->pluck('it_area')->unique()->values();

        $it_mapping = $it_personnel->groupBy('it_area')->map(function ($group) {
            return $group->map(function ($p) {
                return [
                    'name'  => trim("{$p->firstname} {$p->middle_initial} {$p->lastname}"),
                    'email' => $p->it_email,
                ];
            })->values();
        });

        return view('tickets.create_ticket', [
            'sections_divisions' => $sections_divisions,
            'technical_services' => $technical_services,
            'it_area'            => $it_area,
            'it_mapping'         => $it_mapping,
        ]);
    }

    // Store the ticket
    public function store(Request $request)
    {
        // CAPTCHA Validation
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!($response->json()['success'] ?? false)) {
            return back()->withErrors(['g-recaptcha-response' => 'CAPTCHA verification failed. Please try again.'])
                         ->withInput();
        }

        // Validate form inputs
        $validatedData = $request->validate([
            'firstname'    => 'required|string|max:255',
            'lastname'     => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'date_created' => 'required|date',
            'division'     => 'required|string|max:255',
            'device'       => 'required|string|max:255',
            'service'      => 'required|string|max:255',
            'request'      => 'required|string',
            'it_area'      => 'required|string|max:255',
            'it_personnel' => 'required|string|max:255',
            'it_email'     => 'required|email|max:255',
            'status'       => 'required|string|max:255',
            'photo'        => 'nullable|image|max:10240',
        ]);

        $validatedData['date_created']  = Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');
        $validatedData['date_resolved'] = null;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('ticket_photos', 'public');
        }

        // Create ticket
        $ticket = Tickets::create($validatedData);

        // Generate unique ticket number
        do {
            $ticket_number = strtoupper(Str::random(6));
        } while (Tickets::where('ticket_number', $ticket_number)->exists());

        $ticket->ticket_number = $ticket_number;
        $ticket->save();

        // Send email notification to IT personnel
        if ($ticket->it_email) {
            Mail::to($ticket->it_email)->send(new TicketSubmitted($ticket));
        }

        // Create in-app notification
        $this->createNotification(
            $ticket,
            'ticket_created',
            "New ticket #{$ticket->ticket_number} assigned to you"
        );

        return redirect()->back()->with('success', 'Ticket submitted successfully and email sent to assigned IT personnel.');
    }

    // Create in-app notification
    private function createNotification($ticket, $type, $message)
    {
        $user = User::where('email', $ticket->it_email)->first();

        if ($user) {
            Notification::create([
                'user_id'   => $user->id,
                'ticket_id' => $ticket->ticket_id,
                'type'      => $type,
                'message'   => $message,
            ]);
        }
    }
}
