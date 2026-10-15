<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Carbon\Carbon;

use App\Models\ClientFeedback;
use App\Models\Tickets;
use App\PDF\FeedbackPDF;

class GenerateFeedbackController extends Controller
{
    /**
     * Generate and stream the Client Satisfaction Measurement (CSM) Feedback Form PDF.
     *
     * @param  int|string  $ticket_id
     * @return \Illuminate\Http\Response
     */
    public function generateFeedback($ticket_id)
    {
        $ticket = Tickets::findOrFail($ticket_id);
        $feedback = ClientFeedback::where('ticket_id', $ticket_id)->first();

        // Safe resolution of data fields
        $clientType = trim($feedback->client_type ?? 'Government (Employee or another agency)');
        $sex        = trim($feedback->sex ?? '');
        $ageStr     = trim($feedback->age ?? '');
        $dateStr    = $feedback->feedback_date ?? ($ticket->date_resolved ? Carbon::parse($ticket->date_resolved)->format('F d, Y') : Carbon::now('Asia/Manila')->format('F d, Y'));
        $regionStr  = $feedback->region ?? ($ticket->it_area ?? 'CDA HO');
        $emailStr   = $feedback->email ?? ($ticket->email ?? '');
        $suggestions = trim($feedback->suggestions ?? '');
        $otherService = trim($feedback->service_other ?? '');

        // Resolve services array
        $servicesSelected = $feedback->services ?? [];
        if (is_string($servicesSelected)) {
            $decoded = json_decode($servicesSelected, true);
            $servicesSelected = is_array($decoded) ? $decoded : [$servicesSelected];
        } elseif (!is_array($servicesSelected)) {
            $servicesSelected = [];
        }

        // If no feedback recorded yet, default to ticket's service if applicable
        if (empty($servicesSelected) && !empty($ticket->service)) {
            $servicesSelected[] = $ticket->service;
        }

        // Citizen's Charter answers
        $cc1 = (string)($feedback->cc1 ?? '');
        $cc2 = (string)($feedback->cc2 ?? '');
        $cc3 = (string)($feedback->cc3 ?? '');

        // Initialize PDF (A4 Portrait, 13mm left/right margin matching @page print view)
        $pdf = new FeedbackPDF('P', 'mm', 'A4');
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(13, 10, 13);
        $pdf->AddPage();

        // ══════════════════════════════════════════════════════════
        // 1. HEADER (Logo + Title Centered, Doc Code Top-Right)
        // ══════════════════════════════════════════════════════════
        $logoPath = public_path('images/CDA-logo-RA11364-PNG.png');
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 50, 10, 16, 16);
        }

        $pdf->SetFont('Arial', 'B', 12.5);
        $pdf->SetXY(69, 15.0);
        $pdf->Cell(95, 6, 'COOPERATIVE DEVELOPMENT AUTHORITY', 0, 0, 'L');

        // Document Code on Top Right (CDA-CC-FR-008 / Revision No. 2 / June 16, 2023)
        $pdf->SetFont('Arial', 'B', 6.8);
        $pdf->SetXY(162, 10.5);
        $pdf->Cell(35, 3.4, 'CDA-CC-FR-008', 0, 1, 'R');
        $pdf->SetX(162);
        $pdf->Cell(35, 3.4, 'Revision No. 2', 0, 1, 'R');
        $pdf->SetX(162);
        $pdf->Cell(35, 3.4, 'June 16, 2023', 0, 1, 'R');

        // ══════════════════════════════════════════════════════════
        // 2. INTRO TEXT
        // ══════════════════════════════════════════════════════════
        $pdf->SetXY(13, 28.5);
        $pdf->SetFont('Arial', '', 7.2);
        $pdf->Write(3.5, 'This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices. Your feedback on your ');
        $pdf->SetFont('Arial', 'BU', 7.2);
        $pdf->Write(3.5, 'recently concluded transaction');
        $pdf->SetFont('Arial', '', 7.2);
        $pdf->Write(3.5, ' will help this office provide a better service. Personal information shared will be kept confidential and you always have the option to not answer this form.');

        // ══════════════════════════════════════════════════════════
        // 3. CLIENT TYPE
        // ══════════════════════════════════════════════════════════
        $yClient = 39.0;
        $pdf->SetXY(13, $yClient);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(17, 4, 'Client type:', 0, 0, 'L');

        // Citizen
        $pdf->Checkbox(32, $yClient + 0.4, strcasecmp($clientType, 'Citizen') === 0);
        $pdf->SetXY(36, $yClient);
        $pdf->SetFont('Arial', '', 7.2);
        $pdf->Cell(15, 4, 'Citizen', 0, 0);

        // Business
        $pdf->Checkbox(52, $yClient + 0.4, strcasecmp($clientType, 'Business') === 0);
        $pdf->SetXY(56, $yClient);
        $pdf->Cell(17, 4, 'Business', 0, 0);

        // Government
        $isGov = (stripos($clientType, 'Government') !== false);
        $pdf->Checkbox(75, $yClient + 0.4, $isGov);
        $pdf->SetXY(79, $yClient);
        $pdf->Cell(85, 4, 'Government (Employee or another agency)', 0, 1);

        // ══════════════════════════════════════════════════════════
        // 4. DATE · SEX · AGE · REGION
        // ══════════════════════════════════════════════════════════
        $yMeta = 45.0;
        $pdf->SetXY(13, $yMeta);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(8, 4, 'Date:', 0, 0);
        $pdf->SetFont('Arial', '', 7.2);
        $pdf->Cell(28, 4, $dateStr, 'B', 0, 'C');

        // Sex
        $pdf->SetX(52);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(8, 4, 'Sex:', 0, 0);
        $pdf->Checkbox(61, $yMeta + 0.4, strcasecmp($sex, 'Male') === 0);
        $pdf->SetXY(65, $yMeta);
        $pdf->SetFont('Arial', '', 7.2);
        $pdf->Cell(11, 4, 'Male', 0, 0);

        $pdf->Checkbox(78, $yMeta + 0.4, strcasecmp($sex, 'Female') === 0);
        $pdf->SetXY(82, $yMeta);
        $pdf->Cell(13, 4, 'Female', 0, 0);

        // Age
        $pdf->SetX(98);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(7, 4, 'Age:', 0, 0);
        $pdf->SetFont('Arial', '', 7.2);
        $pdf->Cell(12, 4, $ageStr, 'B', 0, 'C');

        // Region
        $pdf->SetX(120);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(29, 4, 'Region of residence:', 0, 0);
        $pdf->SetFont('Arial', '', 7.2);
        $pdf->Cell(48, 4, $regionStr, 'B', 1, 'L');

        // Thin Separator Line
        $pdf->SetLineWidth(0.25);
        $pdf->SetDrawColor(55, 65, 81);
        $pdf->Line(13, 51.0, 197, 51.0);
        $pdf->SetDrawColor(0, 0, 0);

        // ══════════════════════════════════════════════════════════
        // 5. SERVICES SECTION
        // ══════════════════════════════════════════════════════════
        $pdf->SetXY(13, 53.0);
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(184, 3.4, 'SERVICES', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 6.8);
        $pdf->SetTextColor(55, 65, 81);
        $pdf->Cell(184, 3.0, 'Please put a mark in the appropriate box corresponding to the service you have acquired.', 0, 1, 'C');
        $pdf->SetTextColor(0, 0, 0);

        $leftServices = [
            'Registration of Cooperatives',
            'Amendment of Articles of Cooperation and/or Bylaws',
            'Establishment of Branch/Satellite',
            'Merger/Consolidation',
            'Recognition of Laboratory Cooperative',
            'Issuance of Certificate of Compliance',
        ];

        $rightServices = [
            'Issuance of Accreditation of Cooperative External Auditor',
            'Issuance of Accreditation of Cooperative External Provider',
            'Request for Legal Services',
            'Request for Technical Assistance',
        ];

        $ySvcStart = 60.5;
        $svcRowHeight = 3.6;

        // Render Left Column
        foreach ($leftServices as $i => $svc) {
            $yPos = $ySvcStart + ($i * $svcRowHeight);
            $isChecked = in_array($svc, $servicesSelected, true);
            $pdf->Checkbox(13, $yPos + 0.3, $isChecked);
            $pdf->SetXY(17, $yPos);
            $pdf->SetFont('Arial', '', 6.8);
            $pdf->Cell(84, $svcRowHeight, $svc, 0, 0, 'L');
        }

        // Render Right Column
        foreach ($rightServices as $i => $svc) {
            $yPos = $ySvcStart + ($i * $svcRowHeight);
            $isChecked = in_array($svc, $servicesSelected, true);
            $pdf->Checkbox(107, $yPos + 0.3, $isChecked);
            $pdf->SetXY(111, $yPos);
            $pdf->SetFont('Arial', '', 6.8);
            $pdf->Cell(86, $svcRowHeight, $svc, 0, 0, 'L');
        }

        // OTHERS field in right column
        $yOthers = $ySvcStart + (count($rightServices) * $svcRowHeight) + 0.5;
        $pdf->SetXY(107, $yOthers);
        $pdf->SetFont('Arial', 'B', 6.8);
        $pdf->Cell(13, $svcRowHeight, 'OTHERS,', 0, 0);
        $pdf->SetFont('Arial', '', 6.8);
        $pdf->Cell(19, $svcRowHeight, 'please specify', 0, 0);
        $pdf->Cell(58, $svcRowHeight, $otherService, 'B', 0, 'L');

        // Extra underline beneath OTHERS row (matches blade border-bottom div)
        $yOthersUnderline = $yOthers + $svcRowHeight + 2.0;
        $pdf->SetLineWidth(0.25);
        $pdf->Line(107, $yOthersUnderline, 197, $yOthersUnderline);

        // Divider below SERVICES
        $yDivider2 = $yOthersUnderline + 3.0;
        $pdf->SetLineWidth(0.45);
        $pdf->Line(13, $yDivider2, 197, $yDivider2);
        $pdf->SetLineWidth(0.2);

        // ══════════════════════════════════════════════════════════
        // 6. CITIZEN'S CHARTER (CC) SECTION
        // ══════════════════════════════════════════════════════════
        $yCCIntro = $yDivider2 + 2.5;
        $pdf->SetXY(13, $yCCIntro);
        $pdf->SetFont('Arial', 'B', 6.8);
        $pdf->Write(3.2, 'INSTRUCTIONS: ');
        $pdf->SetFont('Arial', '', 6.8);
        $pdf->Write(3.2, 'Please put a ');
        $pdf->SetFont('Arial', 'B', 6.8);
        $pdf->Write(3.2, 'checkmark ( / )');
        $pdf->SetFont('Arial', '', 6.8);
        $pdf->Write(3.2, " on your answer to the Citizen's Charter (CC) questions. The Citizen's Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times among others.\n");

        // CC1
        $yCC1 = $pdf->GetY() + 1.2;
        $pdf->SetXY(13, $yCC1);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(8, 3.4, 'CC1', 0, 0);
        $pdf->SetFont('Arial', 'B', 6.8);
        $pdf->Cell(176, 3.4, 'Which of the following best describes your awareness of a CC?', 0, 1);

        $cc1Options = [
            '1' => "1. I know what a CC is and I saw this office's CC.",
            '2' => "2. I know what a CC is but I did NOT see this office's CC.",
            '3' => "3. I learned of the CC only when I saw this office's CC.",
            '4' => "4. I do not know what a CC is and I did not see one in this office. (Answer 'N/A' on CC2 and CC3)",
        ];

        $yOpt = $yCC1 + 3.8;
        foreach ($cc1Options as $key => $label) {
            $pdf->Checkbox(21, $yOpt + 0.3, (string)$cc1 === (string)$key);
            $pdf->SetXY(25, $yOpt);
            $pdf->SetFont('Arial', '', 6.8);
            $pdf->Cell(172, 3.3, $label, 0, 1);
            $yOpt += 3.4;
        }

        // CC2
        $yCC2 = $yOpt + 1.2;
        $pdf->SetXY(13, $yCC2);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(8, 3.4, 'CC2', 0, 0);
        $pdf->SetFont('Arial', 'B', 6.8);
        $pdf->Cell(176, 3.4, 'If aware of CC (answered 1-3 in CC1), would you say that the CC of this office was ...?', 0, 1);

        // CC2 options — 2-column grid matching blade layout:
        // Left col: 1, 2, 3   |   Right col: 4, 5
        $cc2Rows = [
            ['left' => ['key' => '1', 'label' => '1. Easy to see'],          'right' => ['key' => '4', 'label' => '4. Not visible at all']],
            ['left' => ['key' => '2', 'label' => '2. Somewhat easy to see'],  'right' => ['key' => '5', 'label' => '5. N/A']],
            ['left' => ['key' => '3', 'label' => '3. Difficult to see'],      'right' => null],
        ];

        $yOpt2 = $yCC2 + 3.8;
        foreach ($cc2Rows as $row) {
            $left = $row['left'];
            $pdf->Checkbox(21, $yOpt2 + 0.3, (string)$cc2 === (string)$left['key']);
            $pdf->SetXY(25, $yOpt2);
            $pdf->SetFont('Arial', '', 6.8);
            $pdf->Cell(80, 3.3, $left['label'], 0, 0);

            if (!empty($row['right'])) {
                $right = $row['right'];
                $pdf->Checkbox(107, $yOpt2 + 0.3, (string)$cc2 === (string)$right['key']);
                $pdf->SetXY(111, $yOpt2);
                $pdf->Cell(80, 3.3, $right['label'], 0, 0);
            }

            $pdf->Ln(3.4);
            $yOpt2 += 3.4;
        }

        // CC3
        $yCC3 = $yOpt2 + 1.2;
        $pdf->SetXY(13, $yCC3);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(8, 3.4, 'CC3', 0, 0);
        $pdf->SetFont('Arial', 'B', 6.8);
        $pdf->Cell(176, 3.4, 'If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?', 0, 1);

        // CC3 options — 2-column grid matching blade layout:
        // Left col: 1, 2   |   Right col: 3, 4
        $cc3Rows = [
            ['left' => ['key' => '1', 'label' => '1. Helped very much'], 'right' => ['key' => '3', 'label' => '3. Did not help']],
            ['left' => ['key' => '2', 'label' => '2. Somewhat helped'],  'right' => ['key' => '4', 'label' => '4. N/A']],
        ];

        $yOpt3 = $yCC3 + 3.8;
        foreach ($cc3Rows as $row) {
            $left = $row['left'];
            $pdf->Checkbox(21, $yOpt3 + 0.3, (string)$cc3 === (string)$left['key']);
            $pdf->SetXY(25, $yOpt3);
            $pdf->SetFont('Arial', '', 6.8);
            $pdf->Cell(80, 3.3, $left['label'], 0, 0);

            if (!empty($row['right'])) {
                $right = $row['right'];
                $pdf->Checkbox(107, $yOpt3 + 0.3, (string)$cc3 === (string)$right['key']);
                $pdf->SetXY(111, $yOpt3);
                $pdf->Cell(80, 3.3, $right['label'], 0, 0);
            }

            $pdf->Ln(3.4);
            $yOpt3 += 3.4;
        }

        // ══════════════════════════════════════════════════════════
        // 7. SQD RATING TABLE
        // ══════════════════════════════════════════════════════════
        $yTableIntro = $yOpt3 + 1.8;
        $pdf->SetXY(13, $yTableIntro);
        $pdf->SetFont('Arial', 'B', 7.2);
        $pdf->Cell(184, 3.2, 'INSTRUCTIONS:', 0, 1);
        $pdf->SetFont('Arial', '', 7.0);
        $pdf->SetX(13);
        $pdf->Write(3.2, 'Please put a ');
        $pdf->SetFont('Arial', 'B', 7.0);
        $pdf->Write(3.2, 'checkmark ( / )');
        $pdf->SetFont('Arial', '', 7.0);
        $pdf->Write(3.2, ' on the column that best corresponds to your answer.');

        $yTbl = $pdf->GetY() + 4.0;
        $colQ = 66.0; // 36% of 184mm
        $colWidths = [19.3, 19.3, 20.4, 19.3, 19.3, 20.4]; // 10.5%, 10.5%, 11%, 10.5%, 10.5%, 11%
        $tblHeaderH = 14.0;

        $pdf->SetLineWidth(0.25);
        $pdf->SetDrawColor(55, 65, 81);

        // Header Background Boxes
        $pdf->Rect(13, $yTbl, $colQ, $tblHeaderH);
        $curX = 13 + $colQ;
        for ($c = 0; $c < 6; $c++) {
            $w = $colWidths[$c];
            $pdf->Rect($curX, $yTbl, $w, $tblHeaderH);
            if ($c < 5) {
                // Faces 1 to 5
                $xFaceCenter = $curX + ($w / 2) - 3.4;
                $pdf->DrawFace($xFaceCenter, $yTbl + 1.2, $c + 1);
            }
            $curX += $w;
        }

        // Column Labels in Header
        $headers = [
            ['Strongly', 'Disagree'],
            ['', 'Disagree'],
            ['Neither Agree', 'nor Disagree'],
            ['', 'Agree'],
            ['Strongly', 'Agree'],
            ['N/A', 'Not Applicable'],
        ];

        $curX = 13 + $colQ;
        foreach ($headers as $idx => $lines) {
            $w = $colWidths[$idx];
            if ($idx === 5) {
                // N/A header — bold "N/A" then "Not" then "Applicable"
                $pdf->SetXY($curX, $yTbl + 2.0);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell($w, 3.5, 'N/A', 0, 1, 'C');
                $pdf->SetXY($curX, $yTbl + 5.5);
                $pdf->SetFont('Arial', '', 5.6);
                $pdf->Cell($w, 2.5, 'Not', 0, 1, 'C');
                $pdf->SetX($curX);
                $pdf->Cell($w, 2.5, 'Applicable', 0, 0, 'C');
            } else {
                $pdf->SetFont('Arial', '', 5.8);
                $pdf->SetXY($curX, $yTbl + 8.5);
                if (!empty($lines[0])) {
                    $pdf->Cell($w, 2.5, $lines[0], 0, 1, 'C');
                    $pdf->SetX($curX);
                    $pdf->Cell($w, 2.5, $lines[1], 0, 0, 'C');
                } else {
                    $pdf->Cell($w, 4.5, $lines[1], 0, 0, 'C');
                }
            }
            $curX += $w;
        }

        // Table Rows
        $questions = [
            1 => 'My requested service is delivered in a timely manner (Responsiveness)',
            2 => "The procedures of services are clearly indicated in the Citizen's Charter displayed in the lobby. (Reliability)",
            3 => 'The facilities are easily accessible and convenient for transactions. (access and facilities)',
            4 => 'My queries/ needs are answered in a language that can be easily understood. (Communication)',
            5 => 'I paid an acceptable amount of fees for my transaction. (Costs)',
            6 => 'The CDA personnel is honest and fair in his/her delivery of the CDA programs/services. (Integrity)',
            7 => "The CDA personnel is knowledgeable, understanding, and helpful with the citizen/ client's needs. (Assurance)",
            8 => 'I am satisfied with the service rendered. (Outcome)',
        ];

        $rowH = 7.0;
        $currentRowY = $yTbl + $tblHeaderH;

        foreach ($questions as $qNum => $qText) {
            // Draw row borders
            $pdf->SetDrawColor(55, 65, 81);
            $pdf->Rect(13, $currentRowY, $colQ, $rowH);
            $curX = 13 + $colQ;
            for ($c = 0; $c < 6; $c++) {
                $w = $colWidths[$c];
                $pdf->Rect($curX, $currentRowY, $w, $rowH);
                $curX += $w;
            }

            // Question Text
            $pdf->SetXY(14.5, $currentRowY + 0.7);
            $pdf->SetFont('Arial', '', 6.2);
            $pdf->MultiCell($colQ - 3.0, 2.8, $qText, 0, 'L');

            // Square checkboxes for 1, 2, 3, 4, 5, N/A (matches blade csm-rate style)
            $sqdVal = (string)($feedback->{'sqd' . $qNum} ?? '');
            $curX = 13 + $colQ;

            for ($rVal = 1; $rVal <= 6; $rVal++) {
                $w = $colWidths[$rVal - 1];
                $ratingKey = ($rVal === 6) ? 'N/A' : (string)$rVal;
                $isRated = (strcasecmp($sqdVal, $ratingKey) === 0);

                $xCbCenter = $curX + ($w / 2) - 1.8;
                $yCbCenter = $currentRowY + ($rowH / 2) - 1.8;

                $pdf->Checkbox($xCbCenter, $yCbCenter, $isRated, 3.6);
                $curX += $w;
            }

            $currentRowY += $rowH;
        }

        // ══════════════════════════════════════════════════════════
        // 8. SUGGESTIONS & EMAIL & THANK YOU
        // ══════════════════════════════════════════════════════════
        $yBottom = $currentRowY + 3.5;
        $pdf->SetXY(13, $yBottom);
        $pdf->SetFont('Arial', '', 7.0);
        $pdf->Cell(184, 3.2, 'Suggestions on how we can further improve our services (optional):', 0, 1);

        // Suggestions lines — two full-width underlines matching blade layout
        $sugLine1 = '';
        $sugLine2 = '';
        if (!empty($suggestions)) {
            if (strlen($suggestions) > 130) {
                $sugLine1 = substr($suggestions, 0, 130);
                $sugLine2 = substr($suggestions, 130, 130);
            } else {
                $sugLine1 = $suggestions;
            }
        }

        $ySug1 = $yBottom + 4.2;
        $pdf->SetXY(13, $ySug1);
        $pdf->SetFont('Arial', '', 7.0);
        $pdf->Cell(184, 3.8, $sugLine1, 'B', 1, 'L');

        $ySug2 = $ySug1 + 5.5;
        $pdf->SetXY(13, $ySug2);
        $pdf->Cell(184, 3.8, $sugLine2, 'B', 1, 'L');

        // Email address
        $yEmail = $ySug2 + 6.5;
        $pdf->SetXY(13, $yEmail);
        $pdf->SetFont('Arial', '', 7.0);
        $pdf->Cell(28, 4.0, 'Email address (optional):', 0, 0);
        $pdf->Cell(65, 4.0, $emailStr, 'B', 1, 'L');

        // THANK YOU!
        $yThankYou = $yEmail + 7.5;
        $pdf->SetXY(13, $yThankYou);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(184, 6.0, 'THANK YOU!', 0, 1, 'C');

        // Output PDF
        $filename = 'Feedback_' . $ticket->ticket_number . '.pdf';
        $pdfOutput = $pdf->Output($filename, 'S');

        return response($pdfOutput, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}