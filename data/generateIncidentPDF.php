<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'getIncidentsDetails.php';
require_once '../fpdf/fpdf.php';

if (!isset($_POST['num'])) {
    echo "No report selected.";
    exit;
}

$num = $_POST['num'];
$incident = obtenerDetallesIncidencias($num);

if (!$incident) {
    echo "Incident not found.";
    exit;
}

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(50, 50, 150);
        $this->Cell(0, 10, 'Equipment Report', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}


$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$headerColor = [230, 230, 250];
$cellColor = [240, 248, 255];

$pdf->SetFillColor(...$headerColor);
$pdf->SetTextColor(0, 0, 0);

$fields = [
    'No. Incident' => $incident['num'],
    'Description' => $incident['description'],
    'Type of problem' => $incident['problem'],
    'Date of the report' => $incident['startDate'],
    'Finished on' => $incident['finishedDate'],
    'Current status' => $incident['status'],
    'Priority' => $incident['priority'],
    'Assigned Techncician' => $incident['technicianName'],
    'Operator that reported' => $incident['operatorName'],
    'Equipment' => $incident['equipment'],
    'Serial Number' => $incident['noEquipment'],
];

foreach ($fields as $key => $value) {
    $pdf->SetFillColor(...$cellColor);
    $pdf->Cell(60, 10, $key . ':', 1, 0, 'L', true);
    $pdf->Cell(0, 10, $value, 1, 1, 'L', false);
}

$pdf->Ln(5);
$pdf->SetTextColor(50, 50, 150);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'C');

$pdf->Output('D', 'Incident_' . $num . '.pdf');
?>