<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'getRequestDetails.php';
require_once '../fpdf/fpdf.php';

if (!isset($_POST['num'])) {
    echo "No report selected.";
    exit;
}

$num = $_POST['num'];
$report = obtenerDetallesSolicitud($num);

if (!$report) {
    echo "Report not found.";
    exit;
}

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(50, 50, 150); // Azul oscuro
        $this->Cell(0, 10, 'Equipment Report', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128); // Gris
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
    'No. Report' => $report['num'],
    'Details' => $report['details'],
    'Date' => $report['date'],
    'Type' => $report['type'],
    'Name' => $report['name'] . ' ' . $report['lastName'],
    'Serial Number' => $report['noEquipment'],
    'Equipment' => $report['equipment'],
    'Functionality' => $report['funcionability'] . '%',
    'Down Time' => $report['down'] . ' hours',
    'Running Time' => $report['running'] . ' hours',
    'Cost of the equipment to date' => '$' . $report['cost'],
    'Current status' => $report['status'],
    'Model' => $report['model'],
    'Brand' => $report['brand'],
    'Price of Purchase' => '$' . $report['price'],
    'Area' => $report['area'],
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

$pdf->Output('D', 'Report_' . $num . '.pdf');
?>
