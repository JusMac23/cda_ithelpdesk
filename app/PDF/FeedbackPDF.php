<?php

namespace App\PDF;

require_once base_path('vendor/setasign/fpdf/fpdf.php');

use FPDF;

class FeedbackPDF extends FPDF
{
    /**
     * Draw an ellipse
     */
    public function Ellipse($x, $y, $rx, $ry, $style = 'D')
    {
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }
        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
        $k  = $this->k;
        $h  = $this->h;
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $rx) * $k, ($h - $y) * $k,
            ($x + $rx) * $k, ($h - ($y - $ly)) * $k,
            ($x + $lx) * $k, ($h - ($y - $ry)) * $k,
            $x * $k, ($h - ($y - $ry)) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $lx) * $k, ($h - ($y - $ry)) * $k,
            ($x - $rx) * $k, ($h - ($y - $ly)) * $k,
            ($x - $rx) * $k, ($h - $y) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $rx) * $k, ($h - ($y + $ly)) * $k,
            ($x - $lx) * $k, ($h - ($y + $ry)) * $k,
            $x * $k, ($h - ($y + $ry)) * $k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c %s',
            ($x + $lx) * $k, ($h - ($y + $ry)) * $k,
            ($x + $rx) * $k, ($h - ($y + $ly)) * $k,
            ($x + $rx) * $k, ($h - $y) * $k,
            $op));
    }

    /**
     * Draw a circle
     */
    public function Circle($x, $y, $r, $style = 'D')
    {
        $this->Ellipse($x, $y, $r, $r, $style);
    }

    /**
     * Draw a checkmark inside a bounding box
     */
    public function DrawCheckmark($x, $y, $size = 3.2)
    {
        $oldWidth = $this->LineWidth;
        $this->SetLineWidth(0.4);
        $this->Line($x + ($size * 0.15), $y + ($size * 0.55), $x + ($size * 0.42), $y + ($size * 0.85));
        $this->Line($x + ($size * 0.42), $y + ($size * 0.85), $x + ($size * 0.88), $y + ($size * 0.15));
        $this->SetLineWidth($oldWidth);
    }

    /**
     * Draw a square checkbox matching the CSM web & print style:
     * - Unchecked: clean square with #374151 (dark slate) border and white background.
     * - Checked: solid black filled square with a crisp white checkmark in the center.
     */
    public function Checkbox($x, $y, $checked = false, $size = 3.2)
    {
        $oldW = $this->LineWidth;
        if ($checked) {
            $this->SetFillColor(0, 0, 0);
            $this->SetDrawColor(0, 0, 0);
            $this->Rect($x, $y, $size, $size, 'F');

            $this->SetDrawColor(255, 255, 255);
            $this->SetLineWidth(0.45);
            $this->Line($x + ($size * 0.20), $y + ($size * 0.52), $x + ($size * 0.44), $y + ($size * 0.76));
            $this->Line($x + ($size * 0.44), $y + ($size * 0.76), $x + ($size * 0.82), $y + ($size * 0.22));
        } else {
            $this->SetFillColor(255, 255, 255);
            $this->SetDrawColor(55, 65, 81);
            $this->SetLineWidth(0.3);
            $this->Rect($x, $y, $size, $size, 'DF');
        }
        $this->SetLineWidth($oldW);
        $this->SetDrawColor(0, 0, 0);
    }

    /**
     * Draw a radio circle with optional checkmark
     */
    public function RadioButton($x, $y, $checked = false, $size = 3.2)
    {
        $r = $size / 2;
        $this->Circle($x + $r, $y + $r, $r);
        if ($checked) {
            $this->DrawCheckmark($x, $y, $size);
        }
    }

    /**
     * Draw Emoji Face for SQD table headers
     * Types: 1 = strongly_disagree, 2 = disagree, 3 = neutral, 4 = agree, 5 = strongly_agree
     */
    public function DrawFace($x, $y, $type)
    {
        $r = 3.6;
        $cx = $x + $r;
        $cy = $y + $r;
        $this->Circle($cx, $cy, $r);

        // Eyes
        $eyeR = 0.4;
        $this->Circle($cx - 1.2, $cy - 0.7, $eyeR, 'F');
        $this->Circle($cx + 1.2, $cy - 0.7, $eyeR, 'F');

        $oldW = $this->LineWidth;
        $this->SetLineWidth(0.3);

        if ($type == 1) { // Strongly Disagree (frown + brows)
            $this->Line($cx - 1.8, $cy - 1.6, $cx - 0.7, $cy - 1.2);
            $this->Line($cx + 1.8, $cy - 1.6, $cx + 0.7, $cy - 1.2);
            $this->Line($cx - 1.4, $cy + 1.7, $cx, $cy + 0.9);
            $this->Line($cx, $cy + 0.9, $cx + 1.4, $cy + 1.7);
        } elseif ($type == 2) { // Disagree (frown)
            $this->Line($cx - 1.2, $cy + 1.5, $cx, $cy + 1.0);
            $this->Line($cx, $cy + 1.0, $cx + 1.2, $cy + 1.5);
        } elseif ($type == 3) { // Neutral (straight line)
            $this->Line($cx - 1.3, $cy + 1.2, $cx + 1.3, $cy + 1.2);
        } elseif ($type == 4) { // Agree (smile)
            $this->Line($cx - 1.2, $cy + 1.0, $cx, $cy + 1.6);
            $this->Line($cx, $cy + 1.6, $cx + 1.2, $cy + 1.0);
        } elseif ($type == 5) { // Strongly Agree (big smile)
            $this->Line($cx - 1.5, $cy + 0.8, $cx, $cy + 1.8);
            $this->Line($cx, $cy + 1.8, $cx + 1.5, $cy + 0.8);
        }

        $this->SetLineWidth($oldW);
    }
}
