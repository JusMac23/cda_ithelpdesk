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

use App\Mail\NewTicketSubmitted;

class CreateTicketPrivateController extends Controller
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
        // ONLY merging it_personnel and it_email to avoid prepopulating re-assignment fields
        $request->merge([
            'it_personnel' => $assignedName,
            'it_email'     => $assignedEmail,
        ]);

        // 2. Validate form inputs
        $validatedData = $request->validate([
            'firstname'    => 'required|string|max:255',
            'lastname'     => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'date_created' => 'nullable|date',
            'division'     => 'required|string|max:255',
            'device'       => 'required|string|max:255',
            'service'      => 'required|string|max:255',
            'request'      => 'required|string',
            'it_area'      => 'required|string|max:255',
            'it_personnel' => 'required|string',
            'it_email'     => 'required|string|email',
            'status'       => 'required|string|max:255',
            'photo'        => 'nullable|image|max:10240',
            'priority'     => 'required|string|max:255',
        ]);

        $validatedData['date_created']  = Carbon::now('Asia/Manila')->format('Y-m-d H:i:s');
        $validatedData['date_resolved'] = null;

        // 3. Handle photo upload
        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('ticket_photos', 'public');
        }

        // 4. Generate unique ticket number
        $orgName = 'CDA'; 
        $currentYear = now()->year;

        do {
            // Generate a 4-digit random number (1000 to 9999)
            $randomNumber = random_int(100000, 999999);
            
            // Format: CDA-ICT-2026-1234
            $ticket_number = "{$orgName}-ICT-{$currentYear}-{$randomNumber}";
            
        } while (Tickets::where('ticket_number', $ticket_number)->exists());

        $validatedData['ticket_number'] = $ticket_number;

        // 5. Create ticket in database
        $ticket = Tickets::create($validatedData);

        // 6. Send email notification to assigned IT personnel
        $emailRecipient = $ticket->it_email;
        $emailSent = false;

        if ($emailRecipient && filter_var($emailRecipient, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($emailRecipient)->send(new NewTicketSubmitted($ticket));
                $emailSent = true;

                $this->createNotification(
                    $ticket,
                    $emailRecipient,
                    'new_ticket_created',
                    "New ticket #{$ticket->ticket_number} assigned to you"
                );
            } catch (\Throwable $e) {
                Log::error("Failed sending private ticket notification to {$emailRecipient}: " . $e->getMessage(), [
                    'exception' => $e
                ]);
            }
        } else {
            Log::warning("No valid email address found to send ticket notification for Ticket #{$ticket->ticket_number}");
        }

        // 7. Handle JSON/AJAX or standard redirects based on actual mail status
        if ($request->ajax() || $request->wantsJson()) {
            if ($emailSent) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Ticket created successfully and email notification sent to assigned IT personnel.',
                    'ticket'  => $ticket
                ]);
            }

            return response()->json([
                'status'  => 'warning',
                'message' => 'Ticket created successfully, but failed to send email notification to IT personnel.',
                'ticket'  => $ticket
            ]);
        }

        if ($emailSent) {
            return redirect()->back()->with('success', 'Ticket submitted successfully. Email notification sent to assigned IT personnel.');
        }

        return redirect()->back()->with('warning', 'Ticket submitted successfully, but email notification could not be sent.');
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