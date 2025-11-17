<?php

namespace App\Http\Controllers;

use App\Models\DataBreachNotification;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class GenerateDocsController extends Controller
{
    public function generateDocx($dbn_id)
    {
        // Clean ALL output buffers (prevent DOCX corruption)
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $notification = DataBreachNotification::findOrFail($dbn_id);

        $phpWord = new PhpWord();
        $phpWord->getCompatibility()->setOoxmlVersion(12);

        $section = $phpWord->addSection();

        // Helper to ensure all data are strings
        $safe = function ($value) {
            return is_null($value) ? '' : (string)$value;
        };

        // Title
        $section->addText('DATA BREACH INCIDENT REPORT', ['bold' => true, 'size' => 18], ['alignment' => 'center']);

        $section->addTextBreak(1);
        $section->addText('Facts / Scenario:', ['bold' => true, 'size' => 14]);
        $section->addText($safe($notification->brief_summary));

        // Notification Type Table
        $section->addTextBreak(1);
        $section->addText('NOTIFICATION TYPE', ['bold' => true, 'size' => 14]);

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80
        ]);

        $addRow = function ($label, $value) use ($table, $safe) {
            $table->addRow();
            $table->addCell(4000)->addText($label);
            $table->addCell(8000)->addText($safe($value));
        };

        $addRow('PIC', $notification->pic);
        $addRow('Email Address', $notification->email);
        $addRow('Representative', $notification->representative);
        $addRow('Representative Email', $notification->representative_email_address);

        $addRow(
            'Date/Time of Occurrence',
            $notification->date_occurrence ? $notification->date_occurrence->format('F d, Y - h:i A') : ''
        );

        $addRow(
            'Date/Time of Discovery',
            $notification->date_discovery ? $notification->date_discovery->format('F d, Y - h:i A') : ''
        );

        // Notification Type Description
        if (!empty($notification->notification_type_description)) {
            $section->addTextBreak(1);
            $section->addText('Notification Type Description:', ['bold' => true]);

            $types = json_decode($notification->notification_type_description, true);
            if (!is_array($types)) {
                $types = explode(',', $notification->notification_type_description);
            }

            foreach ($types as $type) {
                $section->addText("• " . $safe(trim($type)));
            }
        }

        // Data Breach Details
        $section->addTextBreak(2);
        $section->addText('DATA BREACH NOTIFICATION DETAILS', ['bold' => true, 'size' => 14]);

        $detailsTable = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80
        ]);

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
            $detailsTable->addRow();
            $detailsTable->addCell(4000)->addText($label);
            $detailsTable->addCell(8000)->addText($safe($value));
        }

        // Long text fields
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
            $section->addTextBreak(1);
            $section->addText($title . ':', ['bold' => true]);

            // Use TextRun for safer long text handling
            $textRun = $section->addTextRun();
            $textRun->addText($safe($content));
        }

        // Measures Taken
        $section->addTextBreak(2);
        $section->addText('MEASURES TAKEN TO ADDRESS THE BREACH', ['bold' => true, 'size' => 14]);

        $measures = [
            'Measures to address breach' => $notification->measures_to_address,
            'Measures to secure / recover personal data' => $notification->measures_to_secure,
            'Actions to mitigate harm' => $notification->actions_to_mitigate,
            'Actions to inform data subjects' => $notification->actions_to_inform,
            'Measures to prevent recurrence' => $notification->actions_to_prevent,
        ];

        foreach ($measures as $label => $value) {
            $section->addTextBreak(1);
            $section->addText($label . ':', ['bold' => true]);
            $textRun = $section->addTextRun();
            $textRun->addText($safe($value));
        }

        // Record Type & Data Subjects
        $section->addTextBreak(2);
        $section->addText('RECORD TYPE & DATA SUBJECTS', ['bold' => true, 'size' => 14]);

        $section->addText('Record Type:', ['bold' => true]);
        $section->addText($safe($notification->record_type));

        $section->addTextBreak(1);
        $section->addText('Data Subjects:', ['bold' => true]);

        $subjects = !empty($notification->data_subjects)
            ? explode(',', $notification->data_subjects)
            : [];

        foreach ($subjects as $subject) {
            $section->addText("• " . $safe(trim($subject)));
        }

        // File name
        $fileName = 'Data_Breach_Report_' . $notification->id . '.docx';

        // Save to temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'docx');
        $writer = new \PhpOffice\PhpWord\Writer\Word2007($phpWord);
        $writer->save($tempFile);

        // Force download
        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }
}
