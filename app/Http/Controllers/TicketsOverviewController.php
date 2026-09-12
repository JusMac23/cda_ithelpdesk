<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Tickets;
use App\Models\TechnicalServices;
use App\Models\RegionEmail; 

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
        $user = Auth::user();
        $isSuperAdmin = false;
        
        if ($user) {
            $hasRole = function($roleName) use ($user) {
                return method_exists($user, 'hasRole') 
                    ? $user->hasRole($roleName) 
                    : (isset($user->role) && strcasecmp((string)$user->role, $roleName) === 0);
            };
            $isSuperAdmin = $hasRole('Super Admin');
        }

        // Fetch regions for the dropdown ONLY if Super Admin
        $regions = $isSuperAdmin ? RegionEmail::pluck('region')->filter()->toArray() : [];

        // Total ticket counts
        $total = $this->getTicketQuery()->count();
        $pending = $this->getTicketQuery()->whereIn('status', ['Pending', 'Pending/Re-Assigned', 'Pending / Re-Assigned', 'Pending/Reassigned', 'pending', 'pending/re-assigned'])->count();
        $resolved = $this->getTicketQuery()->where('status', 'Resolved')->count();

        // Calculate dynamic overdue tickets
        $overdueCollection = $this->getOverdueTicketsCollection();
        $overdue = $overdueCollection->count();

        // Group by IT Area (Region) - RESTRICTED to Super Admin
        $byItArea = collect();
        if ($isSuperAdmin) {
            $byItArea = $this->getTicketQuery()
                ->select('it_area', DB::raw('count(*) as total'))
                ->groupBy('it_area')
                ->get();
        }

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

        // Dynamically update the scope value based on selected region
        if ($isSuperAdmin) {
            $scopeValue = request()->filled('region') ? request('region') : 'All CDA Offices';
        } elseif ($isIctd || $isIcts) {
            $scopeValue = 'Assigned to ' . ($user->email ?? 'User');
        } else {
            $scopeValue = empty($user->region) ? 'All CDA Offices' : $user->region;
        }

        // 1. Fetch Metrics & Data
        $total    = $this->getTicketQuery()->count();
        $pending  = $this->getTicketQuery()->whereIn('status', ['Pending', 'Pending/Re-Assigned', 'Pending / Re-Assigned', 'Pending/Reassigned', 'pending', 'pending/re-assigned'])->count();
        $resolved = $this->getTicketQuery()->where('status', 'Resolved')->count();

        // Calculate dynamic overdue tickets
        $overdueCollection = $this->getOverdueTicketsCollection();
        $overdue = $overdueCollection->count();

        // Group by IT Area (Region) - RESTRICTED to Super Admin
        $byItArea = collect();
        if ($isSuperAdmin) {
            $byItArea = $this->getTicketQuery()
                ->select('it_area', DB::raw('count(*) as total'))
                ->groupBy('it_area')
                ->get();
        }

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

        // Page and layout settings (1 inch = 25.4 mm)
        $pageWidth = $pdf->GetPageWidth();
        $margin = 25.4; 
        $contentWidth = $pageWidth - ($margin * 2); // 159.2 mm

        $pdf->SetMargins($margin, 10, $margin);
        $pdf->AddPage();

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
        $pdf->MultiCell($textBlockWidth, 3.5, "827 Aurora Blvd., Service Road, Brgy. Immaculate Conception Cubao, Quezon City\n1111 Quezon City, Philippines", 0, 'C');

        // --- HEADER BANNER (PLAIN TEXT, NO BORDER) ---
        $bannerY = 36;

        // 1. Report Title (Bold)
        $pdf->SetTextColor(15, 23, 42);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY($margin, $bannerY + 1);
        $pdf->Cell(95, 5, 'Tickets Overview Report Summary', 0, 0, 'L');

        // 2. Generated Line (Right aligned; Bold for dynamic data)
        $genLabel = 'Generated: ';
        $genValue = Carbon::now('Asia/Manila')->format('M d, Y h:i A');

        $pdf->SetFont('Arial', '', 8);
        $wGenLabel = $pdf->GetStringWidth($genLabel);
        $pdf->SetFont('Arial', 'B', 8);
        $wGenValue = $pdf->GetStringWidth($genValue);
        $totalGenW = $wGenLabel + $wGenValue;

        $pdf->SetXY($margin + $contentWidth - $totalGenW, $bannerY + 1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->Cell($wGenLabel, 5, $genLabel, 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell($wGenValue, 5, $genValue, 0, 1, 'L');

        // 3. Subtitle (Left aligned)
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->SetXY($margin, $bannerY + 7.5);
        $pdf->Cell(95, 5, 'Cooperative Development Authority - ICT Helpdesk', 0, 0, 'L');

        // 4. Scope Line (Right aligned; Bold for dynamic data)
        $scopeLabel = 'Scope: ';

        $pdf->SetFont('Arial', '', 8);
        $wScopeLabel = $pdf->GetStringWidth($scopeLabel);
        $pdf->SetFont('Arial', 'B', 8);
        $wScopeValue = $pdf->GetStringWidth($scopeValue);
        $totalScopeW = $wScopeLabel + $wScopeValue;

        $pdf->SetXY($margin + $contentWidth - $totalScopeW, $bannerY + 7.5);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->Cell($wScopeLabel, 5, $scopeLabel, 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetTextColor(15, 23, 42);
        $pdf->Cell($wScopeValue, 5, $scopeValue, 0, 1, 'L');

        // --- STAT CARDS ROW ---
        $cards = [
            ['lbl' => 'TOTAL TICKETS', 'val' => $total, 'r' => 79, 'g' => 70, 'b' => 229],
            ['lbl' => 'PENDING TICKETS', 'val' => $pending, 'r' => 217, 'g' => 119, 'b' => 6],
            ['lbl' => 'RESOLVED TICKETS', 'val' => $resolved, 'r' => 5, 'g' => 150, 'b' => 105],
            ['lbl' => 'OVERDUE TICKETS', 'val' => $overdue, 'r' => 220, 'g' => 38, 'b' => 38],
        ];

        $cardsY = $bannerY + 17;
        $startX = $margin;
        $cardGap = 3;
        $cardWidth = ($contentWidth - ($cardGap * 3)) / 4;

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
            $pdf->SetXY($x + 2, $cardsY + 3.5);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->SetTextColor(100, 116, 139);
            $pdf->Cell($cardWidth - 4, 3, $card['lbl'], 0, 1, 'L');

            // Value
            $pdf->SetXY($x + 2, $cardsY + 7.5);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(15, 23, 42);
            $pdf->Cell($cardWidth - 4, 7, (string)$card['val'], 0, 1, 'L');
        }

        $nextSectionY = $cardsY + 23;
        $col1Width = $contentWidth - 25;
        $col2Width = 25;

        // --- SECTION 1: TICKETS BY REGION (IT AREA) - Restricted to Super Admin ---
        if ($isSuperAdmin) {
            $pdf->SetY($nextSectionY);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetFillColor(241, 245, 249);
            $pdf->SetDrawColor(226, 232, 240);
            $pdf->SetTextColor(55, 48, 163);
            $pdf->Cell($contentWidth, 6.5, '  Tickets by Region', 1, 1, 'L', true);

            // Column Titles
            $pdf->SetFont('Arial', 'B', 7.5);
            $pdf->SetTextColor(71, 85, 105);
            $pdf->SetFillColor(248, 250, 252);
            $pdf->Cell($col1Width, 5.5, ' IT Area', 1, 0, 'L', true);
            $pdf->Cell($col2Width, 5.5, 'Total ', 1, 1, 'R', true);

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetTextColor(51, 65, 85);

            foreach ($byItArea as $area) {
                if ($pdf->GetY() + 5.5 > $pdf->GetPageHeight() - 15) {
                    $pdf->AddPage();
                }
                $pdf->Cell($col1Width, 5.5, ' ' . substr($area->it_area, 0, 80), 1, 0, 'L');
                $pdf->Cell($col2Width, 5.5, $area->total . ' ', 1, 1, 'R');
            }
            
            $pdf->Ln(6);
        } else {
            $pdf->SetY($nextSectionY);
        }

        // --- SECTION 2: TICKETS BY TECHNICAL PERSONNEL ---
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(6, 95, 70);
        $pdf->Cell($contentWidth, 6.5, '  Tickets by Technical Personnel', 1, 1, 'L', true);

        // Column Titles
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->SetFillColor(248, 250, 252);
        $pdf->Cell($col1Width, 5.5, ' Technical Personnel', 1, 0, 'L', true);
        $pdf->Cell($col2Width, 5.5, 'Total ', 1, 1, 'R', true);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(51, 65, 85);

        foreach ($byItPersonnel as $personnel) {
            if ($pdf->GetY() + 5.5 > $pdf->GetPageHeight() - 15) {
                $pdf->AddPage();
            }
            $personnelName = $personnel->it_personnel ?? 'Unassigned';
            $pdf->Cell($col1Width, 5.5, ' ' . substr($personnelName, 0, 80), 1, 0, 'L');
            $pdf->Cell($col2Width, 5.5, $personnel->total . ' ', 1, 1, 'R');
        }

        // --- SECTION 3: TICKETS BY TECHNICAL SERVICE ---
        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(146, 64, 14);
        $pdf->Cell($contentWidth, 6.5, '  Tickets by Technical Service', 1, 1, 'L', true);

        // Column Titles
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->SetFillColor(248, 250, 252);
        $pdf->Cell($col1Width, 5.5, ' Service Category', 1, 0, 'L', true);
        $pdf->Cell($col2Width, 5.5, 'Total ', 1, 1, 'R', true);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(51, 65, 85);

        foreach ($byService as $svc) {
            $serviceText = $svc->service;
            
            $approxLines = ceil(strlen($serviceText) / 75);
            $estHeight = max($approxLines, 1) * 5.5;
            if ($pdf->GetY() + $estHeight > $pdf->GetPageHeight() - 15) {
                $pdf->AddPage();
            }

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            
            $pdf->MultiCell($col1Width, 5.5, ' ' . $serviceText, 1, 'L');
            $newY = $pdf->GetY();
            $actualHeight = $newY - $y;
            
            $pdf->SetXY($x + $col1Width, $y);
            $pdf->Cell($col2Width, $actualHeight, $svc->total . ' ', 1, 1, 'R');
            
            $pdf->SetY($newY);
        }

        // --- SECTION 4: OVERDUE TICKETS SUMMARY ---
        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(241, 245, 249);
        $pdf->SetTextColor(153, 27, 27);
        $pdf->Cell($contentWidth, 6.5, '  Overdue Tickets Summary', 1, 1, 'L', true);

        $colOverdue1 = $contentWidth - 50;
        $colOverdue2 = 50;

        // Column Titles
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->SetTextColor(71, 85, 105);
        $pdf->SetFillColor(248, 250, 252);
        $pdf->Cell($colOverdue1, 5.5, ' Request Details', 1, 0, 'L', true);
        $pdf->Cell($colOverdue2, 5.5, ' Assigned To', 1, 1, 'L', true);

        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(51, 65, 85);

        foreach ($overdueTickets as $ticket) {
            $reqDetail = $ticket->request ?? 'Ticket #' . ($ticket->ticket_id ?? $ticket->ticket_number);
            $personnel = $ticket->it_personnel ?? 'Unassigned';

            $approxLines = ceil(strlen($reqDetail) / 60);
            $estHeight = max($approxLines, 1) * 5.5;
            if ($pdf->GetY() + $estHeight > $pdf->GetPageHeight() - 15) {
                $pdf->AddPage();
            }

            $x = $pdf->GetX();
            $y = $pdf->GetY();
            
            $pdf->MultiCell($colOverdue1, 5.5, ' ' . $reqDetail, 1, 'L');
            $newY = $pdf->GetY();
            $actualHeight = $newY - $y;
            
            $pdf->SetXY($x + $colOverdue1, $y);
            $pdf->Cell($colOverdue2, $actualHeight, ' ' . substr($personnel, 0, 30), 1, 1, 'L');
            
            $pdf->SetY($newY);
        }

        // --- FOOTER NOTE ---
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'I', 7.5);
        $pdf->SetTextColor(148, 163, 184);
        $pdf->Cell($contentWidth, 4, 'This document is an automatically generated report from the CDA-ICT Helpdesk System.', 0, 1, 'C');

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