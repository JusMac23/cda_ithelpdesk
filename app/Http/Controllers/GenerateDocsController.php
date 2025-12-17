<?php

namespace App\Http\Controllers;

use App\Models\DataBreachNotification;
use FPDF;

class GenerateDocsController extends Controller
{
    public function generatePdf($dbn_id)
    {
        $notification = DataBreachNotification::findOrFail($dbn_id);

        $safe = function ($value) {
            return is_null($value) ? '' : (string)$value;
        };

        // Initialize PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);

        // Get page width
        $pageWidth = $pdf->GetPageWidth();
        $marginInInches = 1;
        $margin = $marginInInches * 25.4;

        // Logos
        $logoMarginTop = 8;
        $cdaWidth = 18;
        $bpWidth  = 18;
        $logoSpacing = 3; 

        // CDA logo (leftmost)
        $pdf->Image(public_path('images/CDA-logo-RA11364-PNG.png'), $margin, $logoMarginTop, $cdaWidth);

        $pdf->Image(
            public_path('images/Bagong_Pilipinas_logo.png'),
            $margin + $cdaWidth + $logoSpacing,
            $logoMarginTop,
            $bpWidth
        );

        // Right logo (IYC)
        $rightLogoWidth = 22;
        $pdf->Image(
            public_path('images/iyc.png'),
            $pageWidth - $margin - $rightLogoWidth,
            $logoMarginTop,
            $rightLogoWidth
        );

        $margin = 25.4; // your margin
        $headerShift = 12; // shift to the right

        $centerWidth = $pageWidth - ($margin * 2) - 10;

        // First line
        $pdf->SetXY($margin + $headerShift, 10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell($centerWidth, 5, 'COOPERATIVE DEVELOPMENT AUTHORITY', 0, 1, 'C');

        // Second line
        $pdf->SetX($margin + $headerShift);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($centerWidth, 5, 'HEAD OFFICE', 0, 1, 'C');

        // Third line
        $pdf->SetX($margin + $headerShift);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell($centerWidth, 5, '827 Aurora Blvd., Service Road, Brgy. Immaculate Conception Cubao', 0, 1, 'C');

        // Fourth line
        $pdf->SetX($margin + $headerShift);
        $pdf->Cell($centerWidth, 5, '1111 Quezon City, Philippines', 0, 1, 'C');


        $pdf->Ln(15); // smaller gap before next section

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'DATA BREACH INCIDENT REPORT', 0, 1, 'C');
        $pdf->Ln(5); // gap before content


        // Facts / Scenario
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,'Facts / Scenario:',0,1);
        $pdf->SetFont('Arial','',12);
        $pdf->MultiCell(0,7,$safe($notification->brief_summary));
        $pdf->Ln(3);

        // Notification Type Table
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,'NOTIFICATION TYPE',0,1);
        $pdf->SetFont('Arial','',12);

        $tableData = [
            'PIC' => $notification->pic,
            'Email Address' => $notification->email,
            'Representative' => $notification->representative,
            'Representative Email' => $notification->representative_email_address,
            'Date/Time of Occurrence' => $notification->date_occurrence ? $notification->date_occurrence->format('F d, Y - h:i A') : '',
            'Date/Time of Discovery' => $notification->date_discovery ? $notification->date_discovery->format('F d, Y - h:i A') : '',
        ];

        foreach ($tableData as $label => $value) {
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(60,7,$label,1);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0,7,$safe($value),1,1);
        }

        // Notification Type Description
        if (!empty($notification->notification_type_description)) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,7,'Notification Type Description:',0,1);
            $pdf->SetFont('Arial','',12);

            $types = json_decode($notification->notification_type_description, true);
            if (!is_array($types)) {
                $types = explode(',', $notification->notification_type_description);
            }

            foreach ($types as $type) {
                $pdf->Cell(5);
                $pdf->Cell(0,6,'- '.$safe(trim($type)),0,1);
            }
        }

        // Data Breach Details
        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,'DATA BREACH NOTIFICATION DETAILS',0,1);

        $pdf->SetFont('Arial','',12);
        $fields = [
            'Sector Name' => $notification->sector_name,
            'Subsector Name' => $notification->subsector_name,
            'Type of Notification' => $notification->notification_type,
            'General Cause' => $notification->general_cause,
            'Specific Cause' => $notification->specific_cause,
            'With Request (YES/NO)' => $notification->with_request,
            'Justification for Request' => $notification->num_records_provide_details,
        ];

        foreach ($fields as $label => $value) {
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(60,7,$label,1);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(0,7,$safe($value),1,1);
        }

        // Long Text Fields
        $longFields = [
            'How the Breach Occurred + DPS Vulnerability' => $notification->how_breach_occured,
            'Chronology of Events' => $notification->chronology,
            'Description / Nature of Personal Data Breach' => $notification->description_nature,
            'Likely Consequences' => $notification->likely_consequences,
            'DPO Details' => $notification->dpo,
            'Types of Sensitive Personal Info' => $notification->spi,
            'Other Info That May Enable Identity Fraud' => $notification->other_info,
        ];

        foreach ($longFields as $title => $content) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,7,$title.':',0,1);
            $pdf->SetFont('Arial','',12);
            $pdf->MultiCell(0,6,$safe($content));
        }

        // Measures Taken
        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,'MEASURES TAKEN TO ADDRESS THE BREACH',0,1);

        $measures = [
            'Measures to address breach' => $notification->measures_to_address,
            'Measures to secure / recover personal data' => $notification->measures_to_secure,
            'Actions to mitigate harm' => $notification->actions_to_mitigate,
            'Actions to inform data subjects' => $notification->actions_to_inform,
            'Measures to prevent recurrence' => $notification->actions_to_prevent,
        ];

        foreach ($measures as $label => $value) {
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,7,$label.':',0,1);
            $pdf->SetFont('Arial','',12);
            $pdf->MultiCell(0,6,$safe($value));
        }

        // Record Type & Data Subjects
        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,'RECORD TYPE & DATA SUBJECTS',0,1);

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,7,'Record Type:',0,1);
        $pdf->SetFont('Arial','',12);
        $pdf->MultiCell(0,6,$safe($notification->record_type));

        if(!empty($notification->data_subjects)) {
            $pdf->Ln(2);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,7,'Data Subjects:',0,1);
            $pdf->SetFont('Arial','',12);

            foreach(explode(',', $notification->data_subjects) as $subject) {
                $pdf->Cell(5);
                $pdf->Cell(0,6,'- '.$safe(trim($subject)),0,1);
            }
        }

        $fileName = 'Data_Breach_Incident_Report' . $notification->id . '.pdf';

        // Output PDF for download
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
    }
}
