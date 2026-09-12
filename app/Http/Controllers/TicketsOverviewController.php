<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Tickets;
use App\Models\TechnicalServices;
use App\Models\RegionEmail; // ADDED: Missing model import for regions

use App\PDF\TSARpdf;
use FPDF;

class TicketsOverviewController extends Controller
{
    /**
     * Helper Method: Generates a base ticket query scoped by the user's role and email/region.
     */
    private function getTicketQuery()
    {
        $query = Tickets::query();
        $user = Auth::user();
        
        // Fetch the selected region from the dropdown request
        $requestedRegion = request('region');

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
                // 1. Super Admin: Access all tickets. Filter by region if selected in dropdown.
                if (!empty($requestedRegion)) {
                    $query->where('it_area', trim($requestedRegion));
                }
            } elseif ($isIctsAdmin) {
                // 2. ICTS Admin: Scope to user's assigned region
                if (!empty($user->region)) {
                    $query->where('it_area', $user->region);
                }
            } elseif ($isIctd || $isIcts) {
                // 3. ICTD and ICTS: Scope strictly to tickets matching their email
                $query->where('it_email', $user->email);
            } else {
                // 4. Fallback for any other non-super admin roles with a region
                if (!empty($user->region)) {
                    $query->where('it_area', $user->region);
                }
            }
        }

        return $query;
    }

    public function index(Request $request) 
    {
        // Fetch regions for the dropdown
        $regions = RegionEmail::pluck('region')->filter()->toArray();

        // Total ticket counts
        $total = $this->getTicketQuery()->count();
        $pending = $this->getTicketQuery()->whereIn('status', ['Pending', 'Pending/Re-Assigned', 'Pending / Re-Assigned', 'Pending/Reassigned', 'pending', 'pending/re-assigned'])->count();
        $resolved = $this->getTicketQuery()->where('status', 'Resolved')->count();

        // Calculate dynamic overdue tickets
        $overdueCollection = $this->getOverdueTicketsCollection();
        $overdue = $overdueCollection->count();

        // Group by IT Area
        $byItArea = $this->getTicketQuery()
            ->select('it_area', DB::raw('count(*) as total'))
            ->groupBy('it_area')
            ->get();

        // Group by IT Personnel
        $byItPersonnel = $this->getTicketQuery()
            ->select('it_personnel', DB::raw('COUNT(*) as total'))
            ->groupBy('it_personnel')
            ->get();

        // Group by Service Type
        $byService = $this->getTicketQuery()
            ->select('service')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('service')
            ->get();

        // Recently Resolved (latest 5)
        $recentlyResolved = $this->getTicketQuery()
            ->where('status', 'Resolved')
            ->orderByDesc('date_resolved')
            ->limit(5)
            ->get();

        // Overdue Tickets per Personnel (grouped by IT Personnel)
        $overdueTickets = $overdueCollection->sortBy('it_personnel')->groupBy('it_personnel');

        return view('tickets.overview_tickets', compact(
            'regions', 
            'total',
            'pending',
            'resolved',
            'overdue',
            'byItArea',
            'byItPersonnel',
            'byService',
            'recentlyResolved',
            'overdueTickets'
        ));
    }

    public function exportPdf()
    {
        $user = Auth::user();
        
        $hasRole = function($roleName) use ($user) {
            return $user && (method_exists($user, 'hasRole') 
                ? $user->hasRole($roleName) 
                : (isset($user->role) && strcasecmp((string)$user->role, $roleName) === 0));
        };

        $isSuperAdmin = $hasRole('Super Admin');
        $isIctd       = $hasRole('ICTD');
        $isIcts       = $hasRole('ICTS');

        // Dynamically update the scope text based on selected region
        if ($isSuperAdmin) {
            $scopeText = request()->filled('region') ? 'Scope: ' . request('region') : 'Scope: All CDA Offices';
        } elseif ($isIctd || $isIcts) {
            $scopeText = 'Scope: Assigned to ' . ($user->email ?? 'User');
        } else {
            $scopeText = empty($user->region) ? 'Scope: All CDA Offices' : 'Scope: ' . $user->region;
        }

        $regions = RegionEmail::pluck('region')->filter()->toArray();

        // 1. Fetch Metrics & Data
        $total    = $this->getTicketQuery()->count();
        $pending  = $this->getTicketQuery()->whereIn('status', ['Pending', 'Pending/Re-Assigned', 'Pending / Re-Assigned', 'Pending/Reassigned', 'pending', 'pending/re-assigned'])->count();
        $resolved = $this->getTicketQuery()->where('status', 'Resolved')->count();

        // Calculate dynamic overdue tickets
        $overdueCollection = $this->getOverdueTicketsCollection();
        $overdue = $overdueCollection->count();

        // Group by IT Area
        $byItArea = $this->getTicketQuery()
            ->select('it_area', DB::raw('count(*) as total'))
            ->groupBy('it_area')
            ->get();

        // Group by IT Personnel
        $byItPersonnel = $this->getTicketQuery()
            ->select('it_personnel', DB::raw('COUNT(*) as total'))
            ->groupBy('it_personnel')
            ->get();

        // Group by Service Type
        $byService = $this->getTicketQuery()
            ->select('service')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('service')
            ->get();

        // Overdue Tickets List (Flat indexed collection for PDF iteration)
        $overdueTickets = $overdueCollection->sortBy('it_personnel')->values();

        // 2. Initialize FPDF Instance
        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        // Page and layout settings
        $pageWidth = $pdf->GetPageWidth();
        $margin = 10; 
        $pdf->SetMargins($margin, 10, $margin);

        // Logo sizes & positioning
        $logoTop = 10;
        $cdaWidth = 18;
        $bpWidth  = 22;
        $sideGap  = 4;
        $textBlockWidth = 110;

        $pageCenterX = $pageWidth / 2;
        $textX = $pageCenterX - ($textBlockWidth / 2);
        $leftLogoX  = $textX - $sideGap - $cdaWidth;
        $rightLogoX = $textX + $textBlockWidth + $sideGap;

        // Render Logos
        if (file_exists(public_path('images/CDA-logo-RA11364-PNG.png'))) {
            $pdf->Image(public_path('images/CDA-logo-RA11364-PNG.png'), $leftLogoX, $logoTop, $cdaWidth);
        }
        if (file_exists(public_path('images/Bagong_Pilipinas_logo.png'))) {
            $pdf->Image(public_path('images/Bagong_Pilipinas_logo.png'), $rightLogoX, $logoTop, $bpWidth);
        }

        // Header Letterhead Text
        $pdf->SetXY($textX, $logoTop);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->MultiCell($textBlockWidth, 4.5, "REPUBLIC OF THE PHILIPPINES\nCOOPERATIVE DEVELOPMENT AUTHORITY", 0, 'C');

        $pdf->SetX($textX);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell($textBlockWidth, 4, "HEAD OFFICE", 0, 'C');

        $pdf->SetX($textX);
        $pdf->SetFont('Arial', '', 7.5);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->MultiCell($textBlockWidth, 3.5, "827 Aurora Blvd., Service Road, Brgy. Immaculate Conception Cubao, Quezon City\nWebsite: www.cda.gov.ph", 0, 'C');

        // --- HEADER BANNER ---
        $bannerY = 36;
        $pdf->SetFillColor(15, 23, 42); // Navy Dark (#0f172a)
        $pdf->Rect($margin, $bannerY, 190, 18, 'F');

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY($margin + 4, $bannerY + 3);
        $pdf->Cell(110, 5, 'Tickets Overview Report Summary', 0, 0, 'L');

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(203, 213, 225);
        $pdf->SetXY(120, $bannerY + 3);
        $pdf->Cell(75, 5, 'Generated: ' . Carbon::now('Asia/Manila')->format('M d, Y h:i A'), 0, 1, 'R');

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(148, 163, 184);
        $pdf->SetXY($margin + 4, $bannerY + 9.5);
        $pdf->Cell(110, 5, 'Cooperative Development Authority - ICT Helpdesk', 0, 0, 'L');

        $pdf->SetXY(120, $bannerY + 9.5);
        $pdf->Cell(75, 5, $scopeText, 0, 1, 'R');

        // --- STAT CARDS ROW ---
        $cards = [
            ['lbl' => 'TOTAL TICKETS', 'val' => $total, 'r' => 79, 'g' => 70, 'b' => 229],
            ['lbl' => 'PENDING TICKETS', 'val' => $pending, 'r' => 217, 'g' => 119, 'b' => 6],
            ['lbl' => 'RESOLVED TICKETS', 'val' => $resolved, 'r' => 5, 'g' => 150, 'b' => 105],
            ['lbl' => 'OVERDUE TICKETS', 'val' => $overdue, 'r' => 220, 'g' => 38, 'b' => 38],
        ];

        $cardsY = $bannerY + 22;
        $startX = $margin;
        $cardWidth = 44.5;
        $cardGap = 4;

        foreach ($cards as $i => $card) {
            $x = $startX + ($i * ($cardWidth + $cardGap));
            
            // Box Background & Border
            $pdf->SetFillColor(248, 250, 252);
            $pdf->Rect($x, $cardsY, $cardWidth, 18, 'F');
            $pdf->SetDrawColor(226, 232, 240);
            $pdf->Rect($x, $cardsY, $cardWidth, 18, 'D');

            // Top Color Strip Indicator
            $pdf->SetFillColor($card['r'], $card['g'], $card['b']);
            $pdf->Rect($x, $cardsY, $cardWidth, 2, 'F');

            // Label
            $pdf->SetXY($x + 3, $cardsY + 3.5);
            $pdf->SetFont('Arial', 'B', 6.5);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell($cardWidth - 6, 3, $card['lbl'], 0, 1, 'L');

            // Value
            $pdf->SetXY($x + 3, $cardsY + 7.5);
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->SetTextColor(15, 23, 42);
            $pdf->Cell($cardWidth - 6, 7, (string)$card['val'], 0, 1, 'L');
        }

        // --- SECTION 1: REGION & TECHNICAL PERSONNEL (SIDE-BY-SIDE) ---
        $startY1 = $cardsY + 23;

        // Header Titles
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetDrawColor(226, 232, 240);

        // Left Table Header Title
        $pdf->SetTextColor(55, 48, 163);
        $pdf->SetXY($margin, $startY1);
        $pdf->Cell(92, 6.5, '  Tickets by Region (IT Area)', 1, 0, 'L', true);

        // Right Table Header Title
        $pdf->SetTextColor(6, 95, 70);
        $pdf->SetXY(108, $startY1);
        $pdf->Cell(92, 6.5, '  Tickets by Technical Personnel', 1, 1, 'L', true);

        // Column Titles
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->SetFillColor(248, 250, 252);

        $pdf->SetXY($margin, $startY1 + 6.5);
        $pdf->Cell(67, 5.5, ' IT Area', 1, 0, 'L', true);
        $pdf->Cell(25, 5.5, 'Total ', 1, 0, 'R', true);

        $pdf->SetXY(108, $startY1 + 6.5);
        $pdf->Cell(67, 5.5, ' Technical Personnel', 1, 0, 'L', true);
        $pdf->Cell(25, 5.5, 'Total ', 1, 1, 'R', true);

        // Data Rows Loop
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(51, 65, 85);

        $maxRows1 = max(count($byItArea), count($byItPersonnel));
        $currY1 = $startY1 + 12;

        for ($i = 0; $i < $maxRows1; $i++) {
            $pdf->SetY($currY1);

            // Left Side: Region
            $pdf->SetX($margin);
            if (isset($byItArea[$i])) {
                $pdf->Cell(67, 5.5, ' ' . substr($byItArea[$i]->it_area, 0, 36), 1, 0, 'L');
                $pdf->Cell(25, 5.5, $byItArea[$i]->total . ' ', 1, 0, 'R');
            } else {
                $pdf->Cell(67, 5.5, '', 1, 0, 'L');
                $pdf->Cell(25, 5.5, '', 1, 0, 'R');
            }

            // Right Side: Personnel
            $pdf->SetX(108);
            if (isset($byItPersonnel[$i])) {
                $personnelName = $byItPersonnel[$i]->it_personnel ?? 'Unassigned';
                $pdf->Cell(67, 5.5, ' ' . substr($personnelName, 0, 36), 1, 0, 'L');
                $pdf->Cell(25, 5.5, $byItPersonnel[$i]->total . ' ', 1, 1, 'R');
            } else {
                $pdf->Cell(67, 5.5, '', 1, 0, 'L');
                $pdf->Cell(25, 5.5, '', 1, 1, 'R');
            }

            $currY1 += 5.5;
        }

        // --- SECTION 2: SERVICES & OVERDUE TICKETS (SIDE-BY-SIDE) ---
        $startY2 = $currY1 + 6;

        // Header Titles
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(241, 245, 249);

        // Left Table Header Title
        $pdf->SetTextColor(146, 64, 14);
        $pdf->SetXY($margin, $startY2);
        $pdf->Cell(92, 6.5, '  Tickets by Technical Service', 1, 0, 'L', true);

        // Right Table Header Title
        $pdf->SetTextColor(153, 27, 27);
        $pdf->SetXY(108, $startY2);
        $pdf->Cell(92, 6.5, '  Overdue Tickets Summary', 1, 1, 'L', true);

        // Column Titles
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetTextColor(71, 85, 105);

        $pdf->SetXY($margin, $startY2 + 6.5);
        $pdf->Cell(67, 5.5, ' Service Category', 1, 0, 'L', true);
        $pdf->Cell(25, 5.5, 'Total ', 1, 0, 'R', true);

        $pdf->SetXY(108, $startY2 + 6.5);
        $pdf->Cell(60, 5.5, ' Request Details', 1, 0, 'L', true);
        $pdf->Cell(32, 5.5, 'Assigned To ', 1, 1, 'L', true);

        // Data Rows Loop
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(51, 65, 85);

        $maxRows2 = max(count($byService), count($overdueTickets));
        $currY2 = $startY2 + 12;

        for ($i = 0; $i < $maxRows2; $i++) {
            $pdf->SetY($currY2);

            // Left Side: Services
            $pdf->SetX($margin);
            if (isset($byService[$i])) {
                $pdf->Cell(67, 5.5, ' ' . substr($byService[$i]->service, 0, 36), 1, 0, 'L');
                $pdf->Cell(25, 5.5, $byService[$i]->total . ' ', 1, 0, 'R');
            } else {
                $pdf->Cell(67, 5.5, '', 1, 0, 'L');
                $pdf->Cell(25, 5.5, '', 1, 0, 'R');
            }

            // Right Side: Overdue Tickets
            $pdf->SetX(108);
            if (isset($overdueTickets[$i])) {
                $reqDetail = $overdueTickets[$i]->request ?? 'Ticket #' . ($overdueTickets[$i]->ticket_id ?? $overdueTickets[$i]->ticket_number);
                $pdf->Cell(60, 5.5, ' ' . substr($reqDetail, 0, 30), 1, 0, 'L');
                $personnel = $overdueTickets[$i]->it_personnel ?? 'Unassigned';
                $pdf->Cell(32, 5.5, ' ' . substr($personnel, 0, 17), 1, 1, 'L');
            } else {
                $pdf->Cell(60, 5.5, '', 1, 0, 'L');
                $pdf->Cell(32, 5.5, '', 1, 1, 'L');
            }

            $currY2 += 5.5;
        }

        // --- FOOTER NOTE ---
        $pdf->SetY(-15);
        $pdf->SetFont('Arial', 'I', 7.5);
        $pdf->SetTextColor(148, 163, 184);
        $pdf->Cell(190, 4, 'This document is an automatically generated report from the CDA-ICT Helpdesk System.', 0, 1, 'C');

        // Output to Browser Download
        $fileName = 'CDA_ICT_Tickets_Overview_Report_' . Carbon::now()->format('Y_m_d_His') . '.pdf';

        return response($pdf->Output('S', $fileName), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Helper Method: Fetches all Pending tickets that have exceeded their dynamic SLA deadline.
     * Uses getTicketQuery() so role/email restrictions apply automatically.
     */
    private function getOverdueTicketsCollection()
    {
        $currentTime = Carbon::now('Asia/Manila');

        // Fetch Technical Services mapped by technical_services name
        $allTechServices = TechnicalServices::all()->keyBy(function ($item) {
            return strtolower(trim($item->technical_services));
        });

        $pendingStatuses = [
            'Pending',
            'Pending/Re-Assigned',
            'Pending / Re-Assigned',
            'Pending/Reassigned',
            'pending',
            'pending/re-assigned'
        ];

        // Retrieve pending tickets with role scope applied
        $pendingTickets = $this->getTicketQuery()->whereIn('status', $pendingStatuses)->get();

        return $pendingTickets->filter(function ($ticket) use ($allTechServices, $currentTime) {
            $serviceName = strtolower(trim($ticket->service ?? ''));
            $priorityKey = strtolower(trim($ticket->priority ?? ''));

            if (!isset($allTechServices[$serviceName])) {
                return false;
            }

            if (!in_array($priorityKey, ['low', 'medium', 'high', 'critical'], true)) {
                return false;
            }

            $slaTimeStr = $allTechServices[$serviceName]->{$priorityKey} ?? null;

            if (empty($slaTimeStr) || strtoupper(trim($slaTimeStr)) === 'N/A') {
                return false;
            }

            try {
                $createdAt = Carbon::parse($ticket->date_created, 'Asia/Manila');
            } catch (\Exception $e) {
                return false;
            }

            $deadline = $createdAt->copy();

            // Extract SLA duration values
            if (preg_match('/(\d+)\s*days?/', $slaTimeStr, $matches)) {
                $deadline->addDays((int)$matches[1]);
            }
            if (preg_match('/(\d+)\s*hours?/', $slaTimeStr, $matches)) {
                $deadline->addHours((int)$matches[1]);
            }
            if (preg_match('/(\d+)\s*mins?/', $slaTimeStr, $matches)) {
                $deadline->addMinutes((int)$matches[1]);
            }

            // Flag as overdue if current time exceeds calculated SLA deadline
            return $currentTime->greaterThan($deadline);
        });
    }
}