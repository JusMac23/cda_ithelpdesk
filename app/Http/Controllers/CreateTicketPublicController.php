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

    // Safely generates the TSAR PDF string, preventing rendering exceptions from stopping email delivery
    private function generateTsarPdf($ticket): ?string
    {
        try {
            if (!class_exists('TSARpdf')) {
                Log::warning("TSARpdf class not found.");
                return null;
            }

            $t = $ticket;
            $pdf = new \TSARpdf();
            $pdf->AddPage();
            $pdf->SetMargins(25.4, 10, 25.4);
            $pdf->SetFont('Arial', '', 8);

            $pageWidth = $pdf->GetPageWidth();

            $logoPath = public_path('images/CDA-logo-RA11364-PNG.png');
            $codedFormPath = public_path('images/codedform.png');

            if (file_exists($logoPath)) {
                $pdf->Image($logoPath, 25.4, 25, 25);
            }
            if (file_exists($codedFormPath)) {
                $pdf->Image($codedFormPath, $pageWidth - 25.4 - 30, 10, 30);
            }

            $pdf->SetXY(25.4, 30);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(159.2, 5, 'COOPERATIVE DEVELOPMENT AUTHORITY', 0, 1, 'C');

            $pdf->SetXY(25.4, 35);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(159.2, 5, 'HEAD OFFICE', 0, 1, 'C');

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(25.4, 40);
            $pdf->Cell(159.2, 5, '827 Aurora Blvd., Service Road, Brgy. Immaculate Conception Cubao,', 0, 1, 'C');

            $pdf->SetXY(25.4, 44);
            $pdf->Cell(159.2, 5, '1111 Quezon City, Philippines', 0, 1, 'C');

            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(159.2, 6, 'INFORMATION AND COMMUNICATIONS TECHNOLOGY (ICT) OFFICE', 0, 1, 'C');
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(159.2, 8, 'TECHNICAL SUPPORT ASSISTANCE REQUEST (TSAR) FORM', 0, 1, 'C');

            $pdf->Ln(8);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(79.6, 7, 'Division/Unit/Section:', 'LTR', 0);
            $pdf->Cell(79.6, 7, 'Date Request:', 'LTR', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(79.6, 10, utf8_decode($t->division ?? ''), 'LR', 0);
            $pdf->Cell(79.6, 10, Carbon::parse($t->date_created)->format('M d, Y h:i A'), 'LR', 1);
            $pdf->Cell(159.2, 0, '', 'T', 1);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(159.2, 7, 'Employee Name:', 'LTR', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(159.2, 10, utf8_decode(($t->firstname ?? '') . ' ' . ($t->lastname ?? '')), 'LRB', 1);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(79.6, 7, 'Email Address:', 'LTR', 0);
            $pdf->Cell(79.6, 7, 'Request Number:', 'LTR', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(79.6, 10, utf8_decode($t->email ?? ''), 'LRB', 0);
            $pdf->Cell(79.6, 10, utf8_decode($t->ticket_number ?? ''), 'LRB', 1);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(79.6, 7, 'Equipment Repairs:', 'LTR', 0);
            $pdf->Cell(79.6, 7, 'Technical Support Services:', 'LTR', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(79.6, 21, utf8_decode($t->device ?? ''), 'LRB', 0);
            $pdf->Cell(79.6, 21, utf8_decode($t->service ?? ''), 'LRB', 1);

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(159.2, 7, 'Technical Support Request Description/Definition:', 'LTR', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(159.2, 21, utf8_decode($t->request ?? ''), 'LRB');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(159.2, 7, 'Action Taken/Recommendation:', 'LTR', 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(159.2, 21, utf8_decode($t->action ?? 'Pending'), 'LRB');
            $pdf->Ln(14);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(79.6, 7, strtoupper(utf8_decode(($t->firstname ?? '') . ' ' . ($t->lastname ?? '') . ' ' . Carbon::parse($t->date_created)->format('m/d/Y g:i A'))), 0, 0, 'C');
            $pdf->Cell(79.6, 7, strtoupper(utf8_decode($t->it_personnel ?? '')), 0, 1, 'C');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(79.6, 7, 'Name/Signature of Responsible Staff/Date', 0, 0, 'C');
            $pdf->Cell(79.6, 7, 'Name/Signature of ICT Personnel/Date', 0, 1, 'C');

            $pdf->Ln(10);

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetLineWidth(0.4);
            $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 160, $pdf->GetY());
            $pdf->SetLineWidth(0.4);

            $pdf->Cell(129.6, 7, 'Softcopy ICT coded forms can be downloaded here:', 0, 0, 'R');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(29.6, 7, 'https://bit.ly/3NCfJCV', 0, 1);

            return $pdf->Output('S');
        } catch (Throwable $e) {
            Log::error("TSAR PDF generation failed: " . $e->getMessage());
            return null;
        }
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
        $orgName = 'CDA'; // Replace with your dynamic organization name if needed
        $currentYear = now()->year;

        do {
            // Generate a 4-digit random number (1000 to 9999)
            $randomNumber = random_int(1000, 9999);
            
            // Format: CDA-ICT-2026-1234
            $ticket_number = "{$orgName}-ICT-{$currentYear}-{$randomNumber}";
            
        } while (Tickets::where('ticket_number', $ticket_number)->exists());

        $validatedData['ticket_number'] = $ticket_number;

        // 5. Create ticket in database
        $ticket = Tickets::create($validatedData);

        // 6. Resolve final recipient email
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

        $emailSentSuccessfully = false;

        if (!empty($targetEmail) && filter_var($targetEmail, FILTER_VALIDATE_EMAIL)) {

            // Pre-generate TSAR PDF string
            $pdfData = $this->generateTsarPdf($ticket);
            $fileName = "TSAR_Form_" . $ticket->ticket_number . ".pdf";

            // Attempt 1: Standard Mailable Class
            try {
                $mailable = new NewTicketSubmitted($ticket);

                // Attach generated PDF binary directly to the Mailable instance
                if (!empty($pdfData)) {
                    $mailable->attachData($pdfData, $fileName, [
                        'mime' => 'application/pdf',
                    ]);
                }

                Mail::to($targetEmail)->send($mailable);
                Log::info("Ticket notification email with PDF successfully sent via Mailable to: {$targetEmail}");
                $emailSentSuccessfully = true;
            } catch (Throwable $e) {
                Log::error("Mailable dispatch failed for {$targetEmail}: " . $e->getMessage());

                // Attempt 2: Fallback HTML Email
                try {
                    $priorityColor = match (strtolower($ticket->priority)) {
                        'urgent', 'high' => '#dc2626',
                        'medium'         => '#d97706',
                        default          => '#2563eb',
                    };

                    $priorityBg = match (strtolower($ticket->priority)) {
                        'urgent', 'high' => '#fef2f2',
                        'medium'         => '#fffbe1',
                        default          => '#eff6ff',
                    };

                    $htmlContent = "
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset='utf-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    </head>
                    <body style='margin:0; padding:20px; background-color:#f4f6f9; font-family:Arial, sans-serif; color:#333333;'>
                        <table role='presentation' width='100%' cellspacing='0' cellpadding='0' border='0'>
                            <tr>
                                <td align='center'>
                                    <table role='presentation' width='600' cellspacing='0' cellpadding='0' border='0' style='background-color:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #e5e7eb;'>
                                        <tr>
                                            <td style='background-color:#1e293b; padding:24px; text-align:center;'>
                                                <h1 style='margin:0; font-size:20px; color:#ffffff; font-weight:600;'>New IT Ticket Assigned</h1>
                                                <p style='margin:4px 0 0 0; color:#94a3b8; font-size:14px;'>Ticket #" . e($ticket->ticket_number) . "</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='padding:24px;'>
                                                <p style='margin-top:0; font-size:15px; color:#1f2937;'>Hello <strong>" . e($ticket->it_personnel) . "</strong>,</p>
                                                <p style='font-size:14px; color:#4b5563; margin-bottom:20px;'>A new IT support ticket has been assigned to you. Details are provided below:</p>
                                                
                                                <table role='presentation' width='100%' cellspacing='0' cellpadding='10' border='0' style='border-collapse:collapse; background-color:#f8fafc; border-radius:6px; border:1px solid #e2e8f0; font-size:14px;'>
                                                    <tr>
                                                        <td width='30%' style='font-weight:bold; color:#475569; border-bottom:1px solid #e2e8f0;'>Requester:</td>
                                                        <td style='color:#0f172a; border-bottom:1px solid #e2e8f0;'>" . e($ticket->firstname . ' ' . $ticket->lastname) . "</td>
                                                    </tr>
                                                    <tr>
                                                        <td style='font-weight:bold; color:#475569; border-bottom:1px solid #e2e8f0;'>Service:</td>
                                                        <td style='color:#0f172a; border-bottom:1px solid #e2e8f0;'>" . e($ticket->service) . "</td>
                                                    </tr>
                                                    <tr>
                                                        <td style='font-weight:bold; color:#475569; border-bottom:1px solid #e2e8f0;'>Priority:</td>
                                                        <td style='border-bottom:1px solid #e2e8f0;'>
                                                            <span style='display:inline-block; padding:3px 8px; border-radius:4px; font-weight:bold; font-size:12px; text-transform:uppercase; background-color:{$priorityBg}; color:{$priorityColor}; border:1px solid {$priorityColor};'>" . e($ticket->priority) . "</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style='font-weight:bold; color:#475569; vertical-align:top;'>Request Details:</td>
                                                        <td style='color:#0f172a; white-space:pre-wrap; line-height:1.5;'>" . nl2br(e($ticket->request)) . "</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='background-color:#f8fafc; padding:16px; text-align:center; font-size:12px; color:#64748b; border-top:1px solid #e2e8f0;'>
                                                This is an automated notification from the ICT Support Helpdesk System.
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </body>
                    </html>
                    ";

                    Mail::html($htmlContent, function ($message) use ($targetEmail, $ticket, $pdfData, $fileName) {
                        $message->to($targetEmail)
                                ->subject("New Ticket Assigned: #{$ticket->ticket_number}");

                        if (!empty($pdfData)) {
                            $message->attachData($pdfData, $fileName, [
                                'mime' => 'application/pdf',
                            ]);
                        }
                    });

                    Log::info("Fallback HTML email with PDF successfully sent to: {$targetEmail}");
                    $emailSentSuccessfully = true;
                } catch (Throwable $fallbackEx) {
                    Log::error("Fallback HTML email delivery failed for {$targetEmail}: " . $fallbackEx->getMessage());
                }
            }

            $this->createNotification(
                $ticket,
                $targetEmail,
                'ticket_created',
                "New ticket #{$ticket->ticket_number} assigned to you"
            );
        } else {
            Log::warning("Ticket #{$ticket->ticket_number} created, but no valid email address could be matched for '{$ticket->it_personnel}'.");
        }

        // Return user feedback based on delivery state
        if ($emailSentSuccessfully) {
            return redirect()->back()->with('success', "Ticket submitted successfully. Email notification sent to assigned IT personnel.");
        }

        return redirect()->back()->with('warning', "Ticket #{$ticket->ticket_number} was created, but the email server failed to deliver to '{$targetEmail}'. Check storage/logs/laravel.log for the exact SMTP error.");
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