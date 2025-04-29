<?php

function formatNumber($number) {
    if ($number == 0) {
        return "0";
    }
    
    if (abs($number) >= 1000000) {
        return number_format($number / 1000000, 1, '.', ' ') . "M";
    } else if (abs($number) >= 1000) {
        return number_format($number / 1000, 1, '.', ' ') . "k";
    } else {
        return number_format($number, 0, '.', ' ');
    }
}

function limitText($text, $maxLength) {
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength - 3) . '...';
    }
    return $text;
}

function exportBudgetPDF($idDepartement) {
    require_once('../lib/fpdf.php');
    
    $departement = getDepartementById($idDepartement);
    $periodes = getAllPeriodes();
    $previsions = getPrevisionDepartement($idDepartement);
    $categories = getCtgDept($idDepartement);
    
    class PDF extends FPDF {
        function CheckPageBreak($h) {
            if($this->GetY() + $h > $this->PageBreakTrigger)
                $this->AddPage($this->CurOrientation);
        }
    }
    
    // Create PDF in landscape orientation
    $pdf = new PDF('L', 'mm', 'A4'); 
    $pdf->AddPage();
    
    $pdf->SetFont('Arial', 'B', 16);
    
    $pdf->Cell(0, 10, 'Budget du departement: ' . $departement['nom'], 0, 1, 'C');
    $pdf->Ln(5);
    
    $pdf->SetFont('Arial', 'B', 9); // Slightly larger font for headers
    
    $nbPeriodes = count($periodes);
    $firstColWidth = 50; // Increased width for the first column
    $totalWidth = 277; // Total width available (A4 landscape minus margins)
    $colWidth = ($totalWidth - $firstColWidth) / ($nbPeriodes * 3); 
    
    // Adjust font size based on number of periods
    if ($nbPeriodes > 6) {
        $pdf->SetFont('Arial', 'B', 7); 
        $firstColWidth = 45; // Slightly smaller first column for many periods
        $colWidth = ($totalWidth - $firstColWidth) / ($nbPeriodes * 3);
    } elseif ($nbPeriodes > 4) {
        $pdf->SetFont('Arial', 'B', 8); 
    }
    
    // Header row with period names
    $pdf->Cell($firstColWidth, 8, 'Rubrique', 1, 0, 'C');
    foreach ($periodes as $periode) {
        $periodeLabel = limitText($periode['nom'], 15); // Limit period name length
        $pdf->Cell($colWidth * 3, 8, $periodeLabel, 1, 0, 'C');
    }
    $pdf->Ln();
    
    // Sub-header row with Prev/Real/Ecart
    $pdf->Cell($firstColWidth, 8, '', 1, 0, 'C');
    foreach ($periodes as $periode) {
        $pdf->Cell($colWidth, 8, 'Prev.', 1, 0, 'C'); 
        $pdf->Cell($colWidth, 8, 'Real.', 1, 0, 'C');
        $pdf->Cell($colWidth, 8, 'Ecart', 1, 0, 'C');
    }
    $pdf->Ln();
    
    $pdf->SetFont('Arial', '', 8); // Slightly larger font for data
    
    // Beginning balance
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell($firstColWidth, 7, 'Solde debut', 1, 0, 'L', true);
    foreach ($periodes as $periode) {
        $pdf->Cell($colWidth, 7, formatNumber(getSoldeDebutPrevision($idDepartement, $periode['idPeriode'])), 1, 0, 'R', true);
        $pdf->Cell($colWidth, 7, formatNumber(getSoldeDebutRealise($idDepartement, $periode['idPeriode'])), 1, 0, 'R', true);
        $pdf->Cell($colWidth, 7, '-', 1, 0, 'C', true);
    }
    $pdf->Ln();
    
    // Expenses
    foreach ($categories['depenses'] as $cat) {
        $pdf->SetFillColor(255, 235, 235); 
        $pdf->Cell($firstColWidth, 7, limitText($cat['nature'], 30), 1, 0, 'L', true); 
        foreach ($periodes as $periode) {
            $prev = array_filter($previsions, function($p) use ($cat, $periode) {
                return $p['idCategorie'] == $cat['idCategorie'] && $p['idPeriode'] == $periode['idPeriode'];
            });
            $prev = reset($prev);
            $prevision = $prev['prevision'] ?? 0;
            $realisation = $prev['realisation'] ?? 0;
            $ecart = $prevision - $realisation;
            
            $pdf->Cell($colWidth, 7, formatNumber($prevision), 1, 0, 'R', true);
            $pdf->Cell($colWidth, 7, formatNumber($realisation), 1, 0, 'R', true);
            $pdf->Cell($colWidth, 7, formatNumber($ecart), 1, 0, 'R', true);
        }
        $pdf->Ln();
    }
    
    // Total Expenses
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetFillColor(255, 200, 200); 
    $pdf->Cell($firstColWidth, 7, 'Total Depense', 1, 0, 'L', true);
    foreach ($periodes as $periode) {
        $pdf->Cell($colWidth, 7, formatNumber(getTotalDepensePrevue($idDepartement, $periode['idPeriode'])), 1, 0, 'R', true);
        $pdf->Cell($colWidth, 7, formatNumber(getTotalDepenseRealisee($idDepartement, $periode['idPeriode'])), 1, 0, 'R', true);
        $pdf->Cell($colWidth, 7, '-', 1, 0, 'C', true);
    }
    $pdf->Ln();
    
    // Revenues
    $pdf->SetFont('Arial', '', 8);
    foreach ($categories['recettes'] as $cat) {
        $pdf->SetFillColor(235, 255, 235);
        $pdf->Cell($firstColWidth, 7, limitText($cat['types'] . ' - ' . $cat['nature'], 30), 1, 0, 'L', true);
        foreach ($periodes as $periode) {
            $prev = array_filter($previsions, function($p) use ($cat, $periode) {
                return $p['idCategorie'] == $cat['idCategorie'] && $p['idPeriode'] == $periode['idPeriode'];
            });
            $prev = reset($prev);
            $prevision = $prev['prevision'] ?? 0;
            $realisation = $prev['realisation'] ?? 0;
            $ecart = $prevision - $realisation;
            
            $pdf->Cell($colWidth, 7, formatNumber($prevision), 1, 0, 'R', true);
            $pdf->Cell($colWidth, 7, formatNumber($realisation), 1, 0, 'R', true);
            $pdf->Cell($colWidth, 7, formatNumber($ecart), 1, 0, 'R', true);
        }
        $pdf->Ln();
    }
    
    // Total Revenues
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetFillColor(200, 255, 200); 
    $pdf->Cell($firstColWidth, 7, 'Total Recette', 1, 0, 'L', true);
    foreach ($periodes as $periode) {
        $pdf->Cell($colWidth, 7, formatNumber(getTotalRecettePrevue($idDepartement, $periode['idPeriode'])), 1, 0, 'R', true);
        $pdf->Cell($colWidth, 7, formatNumber(getTotalRecetteRealisee($idDepartement, $periode['idPeriode'])), 1, 0, 'R', true);
        $pdf->Cell($colWidth, 7, '-', 1, 0, 'C', true);
    }
    $pdf->Ln();
    
    // Ending balance
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell($firstColWidth, 7, 'Solde Fin', 1, 0, 'L', true);
    foreach ($periodes as $periode) {
        $pdf->Cell($colWidth, 7, formatNumber(getSoldeFinPrevision($idDepartement, $periode['idPeriode'])), 1, 0, 'R', true);
        $pdf->Cell($colWidth, 7, formatNumber(getSoldeFinRealise($idDepartement, $periode['idPeriode'])), 1, 0, 'R', true);
        $pdf->Cell($colWidth, 7, '-', 1, 0, 'C', true);
    }
    $pdf->Ln();
    
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 5, 'Document genere le ' . date('d/m/Y à H:i'), 0, 0, 'R');
    
    $filename = 'Budget_' . $departement['nom'] . '_' . date('Y-m-d') . '.pdf';
    $pdf->Output('D', $filename);
    exit;
}