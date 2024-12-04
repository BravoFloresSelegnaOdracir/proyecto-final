<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "getIncidentsDetails.php";

if (!isset($_POST['num'])) {
    echo "No Incident selected.";
    exit;
}

$num = $_POST['num'];
$incident = obtenerDetallesIncidencias($num);

if (!$incident) {
    echo "Incident not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/stylesReport.css">
    <title>Incident Details</title>
</head>
<body>
    <div class="report-card">
        <h3>Incident <?php echo htmlspecialchars($incident['num']).' Details'?></h3>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($incident['description']); ?></p>
        <p><strong>Type of problem:</strong> <?php echo htmlspecialchars($incident['problem']); ?></p>
        <p><strong>Date of the report:</strong> <?php echo htmlspecialchars($incident['startDate']); ?></p>
        <p><strong>Finished on:</strong> <?php echo htmlspecialchars($incident['finishedDate']); ?></p>
        <p><strong>Current status:</strong> <?php echo htmlspecialchars($incident['status']); ?></p>
        <p><strong>Priority:</strong> <?php echo htmlspecialchars($incident['priority']); ?></p>
        <p><strong>Assigned Technician:</strong> <?php echo htmlspecialchars($incident['technicianName']); ?></p>
        <p><strong>Operator that reported:</strong> <?php echo htmlspecialchars($incident['operatorName']); ?></p>
        <p><strong>Equipment:</strong> <?php echo htmlspecialchars($incident['equipment']); ?></p>
        <p><strong>Serial Number:</strong> <?php echo htmlspecialchars($incident['noEquipment']); ?></p>
    </div>
    <div class="actions">
        <form method="post" action="generateIncidentPDF.php">
            <input type="hidden" name="num" value="<?php echo htmlspecialchars($incident['num']); ?>">
            <button type="submit">Download as PDF</button>
            <a href="../historyM.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>