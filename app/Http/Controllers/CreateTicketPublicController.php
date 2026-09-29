<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

use Carbon\Carbon;

use App\Models\Tickets;
use App\Models\Divisions;
use App\Models\TechnicalServices;
use App\Models\ITPersonnel;
use App\Models\Notification;
use App\Models\User;

use App\Mail\ITPersonnelTicketNotification;
use App\Mail\CLientTicketNotification;

class CreateTicketPublicController extends Controller
{
    // Safely formats the IT personnel's full name, handling NULL middle initials.
    private function formatFullName($personnel): string
    {
        if (!$personnel) {
            return '';
        }

        $parts = array_filter([
            $personnel->firstname ?? null,
            $personnel->middle_initial ?? null,
            $personnel->lastname ?? null
        ], fn($val) => !is_null($val) && trim($val) !== '');

        return implode(' ', $parts);
    }

    // Comprehensive email lookup across model attributes and user relationships
    private function extractPersonnelEmail($personnel): ?string
    {
        if (!$personnel) {
            return null;
        }

        // 1. Direct column checks on ITPersonnel model
        $email = $personnel->it_email 
            ?? $personnel->email 
            ?? $personnel->email_address 
            ?? $personnel->re_assigned_it_email 
            ?? null;

        // 2. Check linked User relationship if available
        if (empty($email) && method_exists($personnel, 'user') && $personnel->user) {
            $email = $personnel->user->email ?? null;
        }

        // 3. Fallback: Search User table by full name match
        if (empty($email)) {
            $fullName = $this->formatFullName($personnel);
            if (!empty($fullName)) {
                $user = User::whereRaw("CONCAT(firstname, ' ', lastname) = ?", [$fullName])
                    ->orWhere('name', $fullName)
                    ->first();
                if ($user) {
                    $email = $user->email;
                }
            }
        }

        return $email ? trim($email) : null;
    }

    // Calculates the next IT personnel in line using Round-Robin logic for a given area & technical service.
    private function getNextAssignedPersonnel(string $area, ?string $service = null)
    {
        if (empty($area)) {
            return null;
        }

        // 1. Fetch all personnel in this area ordered by ID sequence
        $allAreaPersonnel = ITPersonnel::where('it_area', $area)
            ->orderBy('id', 'asc')
            ->get();

        if ($allAreaPersonnel->isEmpty()) {
            return null;
        }

        $cleanService = $service ? trim($service) : null;
        $usedServiceFilter = false;

        // 2. Filter personnel matching the technical service category
        $pool = collect();
        if ($cleanService) {
            $pool = $allAreaPersonnel->filter(function ($p) use ($cleanService) {
                $p_services = array_map('trim', explode(',', $p->tech_services_category ?? ''));
                return in_array($cleanService, $p_services, true);
            })->values();

            if ($pool->isNotEmpty()) {
                $usedServiceFilter = true;
            }
        }

        // Fallback to all area personnel if no exact service match exists
        if ($pool->isEmpty()) {
            $pool = $allAreaPersonnel->values();
        }

        $totalCount = $pool->count();
        if ($totalCount === 0) {
            return null;
        }

        // 3. Find last submitted ticket for this area and service
        $ticketPk = (new Tickets)->getKeyName();
        $lastTicketQuery = Tickets::where('it_area', $area)->orderBy($ticketPk, 'desc');

        if ($cleanService && $usedServiceFilter) {
            $lastTicketQuery->where('service', $cleanService);
        }

        $lastTicket = $lastTicketQuery->first();

        // 4. Calculate next index
        $nextIndex = 0;

        if ($lastTicket && $lastTicket->it_email) {
            $lastEmail = trim($lastTicket->it_email);
            $lastIndex = $pool->search(function ($p) use ($lastEmail) {
                $pEmail = $this->extractPersonnelEmail($p);
                return strtolower(trim($pEmail ?? '')) === strtolower($lastEmail);
            });

            if ($lastIndex !== false) {
                $nextIndex = ($lastIndex + 1) % $totalCount;
            }
        }

        return $pool->get($nextIndex);
    }

    // Show the ticket form
    public function showForm()
    {
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
                        'email' => $this->extractPersonnelEmail($assigned),
                    ];
                }
            }

            $assignedDefault = $this->getNextAssignedPersonnel($area, null);
            if ($assignedDefault) {
                $nextAssignment["{$area}_default"] = [
                    'name'  => $this->formatFullName($assignedDefault),
                    'email' => $this->extractPersonnelEmail($assignedDefault),
                ];
            }
        }

        return view('tickets.create_ticket', [
            'sections_divisions' => $sections_divisions,
            'technical_services' => $technical_services,
            'it_area'            => $it_area,
            'nextAssignment'     => $nextAssignment,
        ]);
    }

    // Store the ticket
    public function store(Request $request)
    {
        $assignedName = null;
        $assignedEmail = null;

        // 1. Server-side Round-Robin Auto-Assignment
        if ($request->filled('it_area')) {
            $area = $request->input('it_area');
            $service = $request->input('service');

            $assigned = $this->getNextAssignedPersonnel($area, $service);

            if ($assigned) {
                $assignedEmail = $this->extractPersonnelEmail($assigned);
                $assignedName  = $this->formatFullName($assigned);
            }
        }

        // Fallback to request input if round-robin didn't return personnel
        if (empty($assignedEmail)) {
            $assignedEmail = trim($request->input('it_email', ''));
        }
        if (empty($assignedName)) {
            $assignedName = trim($request->input('it_personnel', ''));
        }

        // Force merged assignment data into request prior to validation
        $request->merge([
            'it_personnel' => $assignedName,
            'it_email'     => $assignedEmail,
        ]);

        // 2. Validate form inputs
        $validatedData = $request->validate([
            'firstname'         => 'required|string|max:255',
            'lastname'          => 'required|string|max:255',
            'middle_initial'    => 'nullable|string|max:10',
            'email'             => 'required|email|max:255',
            'date_created'      => 'nullable|date',
            'division'          => 'required|string|max:255',
            'device'            => 'required|string|max:255',
            'service'           => 'required|string|max:255',
            'request'           => 'required|string',
            'it_area'           => 'required|string|max:255',
            'it_personnel'      => 'required|string',
            'it_email'          => 'required|string|email',
            'status'            => 'required|string|max:255',
            'photo'             => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'priority'          => 'required|string|max:255',
        ]);

        $validatedData['date_created']  = Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');
        $validatedData['date_resolved'] = null;

        // 3. Handle photo upload (Stores raw binary data for LONGBLOB)
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            // Reads raw image binary content to save directly into MySQL LONGBLOB
            $validatedData['photo'] = file_get_contents($file->getRealPath());

            // Optional: Backup copy saved to local disk storage
            $file->store('ticket_photos', 'public');
        } else {
            $validatedData['photo'] = null;
        }

        // 4. Generate unique ticket number
        $orgName = 'CDA'; 
        $currentYear = now()->year;

        do {
            $randomNumber = random_int(1000, 9999);
            $ticket_number = "{$orgName}-ICT-{$currentYear}-{$randomNumber}";
        } while (Tickets::where('ticket_number', $ticket_number)->exists());

        $validatedData['ticket_number'] = $ticket_number;

        // 5. Create ticket in database
        $ticket = Tickets::create($validatedData);

        // 6. Resolve IT recipient email
        $targetEmail = trim($ticket->it_email ?? '');

        if (empty($targetEmail) || !filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
            if (!empty($ticket->it_personnel)) {
                $userMatch = User::where('name', $ticket->it_personnel)
                    ->orWhereRaw("CONCAT(firstname, ' ', lastname) = ?", [$ticket->it_personnel])
                    ->first();
                if ($userMatch && filter_var($userMatch->email, FILTER_VALIDATE_EMAIL)) {
                    $targetEmail = $userMatch->email;
                }
            }
        }

        $itEmailSent = false;
        $clientEmailSent = false;

        // Send Email to IT Personnel
        if (!empty($targetEmail) && filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($targetEmail)->send(new ITPersonnelTicketNotification($ticket));
                Log::info("Ticket notification email with PDF successfully sent to IT personnel: {$targetEmail}");
                $itEmailSent = true;
            } catch (Throwable $e) {
                Log::error("IT Personnel email dispatch failed for {$targetEmail}: " . $e->getMessage());
            }
        } else {
            Log::warning("Ticket #{$ticket->ticket_number} created, but no valid IT email found for '{$ticket->it_personnel}'.");
        }

        // Send Email to Client
        if (!empty($ticket->email) && filter_var($ticket->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($ticket->email)->send(new CLientTicketNotification($ticket));
                Log::info("Confirmation email successfully sent to client: {$ticket->email}");
                $clientEmailSent = true;
            } catch (Throwable $e) {
                Log::error("Client email dispatch failed for {$ticket->email}: " . $e->getMessage());
            }
        }

        // Create In-App Notification for Client if user exists
        $this->createNotification($ticket, $ticket->email, 'ticket_created', "Your ticket #{$ticket->ticket_number} has been created successfully.");

        // Return user feedback
        if ($itEmailSent && $clientEmailSent) {
            return redirect()->back()->with('success', "Ticket #{$ticket->ticket_number} submitted successfully. Confirmation emails sent to you and assigned IT personnel.");
        } elseif ($itEmailSent || $clientEmailSent) {
            return redirect()->back()->with('success', "Ticket #{$ticket->ticket_number} submitted successfully, but one notification email failed to deliver. Check system logs for details.");
        }

        return redirect()->back()->with('warning', "Ticket #{$ticket->ticket_number} was created, but email notifications failed to send. Check logs for SMTP details.");
    }

    // Create in-app notification
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
}