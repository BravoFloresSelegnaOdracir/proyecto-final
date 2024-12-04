<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "getRequestDetails.php";

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/stylesReport.css">
    <title>Report Details</title>
</head>
<body>
    <div class="report-card">
        <h3>Report <?php echo htmlspecialchars($report['num']).' Details'?></h3>
        <p><strong>Details:</strong> <?php echo htmlspecialchars($report['details']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($report['date']); ?></p>
        <p><strong>Type:</strong> <?php echo htmlspecialchars($report['type']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($report['name'] . ' ' . $report['lastName']); ?></p>
        <p><strong>Serial Number:</strong> <?php echo htmlspecialchars($report['noEquipment']); ?></p>
        <p><strong>Equipment:</strong> <?php echo htmlspecialchars($report['equipment']); ?></p>
        <p><strong>Functionality:</strong> <?php echo htmlspecialchars($report['funcionability']); ?></p>
        <p><strong>Down Time:</strong> <?php echo htmlspecialchars($report['down']); ?> hours</p>
        <p><strong>Running Time:</strong> <?php echo htmlspecialchars($report['running']); ?> hours</p>
        <p><strong>Cost of the equiment to date:</strong> <?php echo htmlspecialchars($report['cost']); ?></p>
        <p><strong>Current status:</strong> <?php echo htmlspecialchars($report['status']); ?></p>
        <p><strong>Model:</strong> <?php echo htmlspecialchars($report['model']); ?></p>
        <p><strong>Brand:</strong> <?php echo htmlspecialchars($report['brand']); ?></p>
        <p><strong>Price of Purchase:</strong> <?php echo htmlspecialchars($report['price']); ?></p>
        <p><strong>Area:</strong> <?php echo htmlspecialchars($report['area']); ?></p>
    </div>
    <div class="actions">
        <form method="post" action="generateReportPDF.php">
            <input type="hidden" name="num" value="<?php echo htmlspecialchars($report['num']); ?>">
            <button type="submit">Download as PDF</button>
            <a href="../historyM.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>

