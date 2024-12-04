<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finish Repair</title>
    <link rel="stylesheet" href="../includes/stylesM.css">
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numRepair = $_POST['numR'];
    $description = $_POST['description'];
    $endTime = $_POST['endTime'];

    $endTimeFormatted = date("Y-m-d H:i:s", strtotime($endTime));

    $db = connection();

    $stmt = $db->prepare("CALL finReparacion(?, ?, ?)");
    $stmt->bind_param("ssi", $endTimeFormatted, $description, $numRepair);

    if ($stmt->execute()) {
        echo "<script>alert('Repair finished successfully.');
        window.location.href = '../repair.php';</script>";
        exit();
    } else {
        echo "<script> alert('Error finishing repair: " . htmlspecialchars($stmt->error) . "');
        window.location.href = '../repair.php';</script>";
        exit();
    }

    $stmt->close();
    $db->close();
} else {
    $numRepair = $_GET['numR'] ?? null;

    if (!$numRepair) {
        echo "<script>alert('Error: No repair ID provided.');
        window.location.href = '../repair.php';</script>";
        exit();
    }

    $db = connection();

    $stmt = $db->prepare("SELECT * FROM reparacion WHERE noRepar = ?");
    $stmt->bind_param("i", $numRepair);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $numRepair = $row['noRepar'];
        $description = $row['descripcion'];
        $startTime = $row['horaInicio'];
    } else {
        echo "<script>alert('Error: Repair not found.');
        window.location.href = '../repair.php';</script>";
        exit();
    }

    $stmt->close();
    $db->close();
}
?>

<main>
    <h2>Finish Repair</h2>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <label>Repair ID:</label>
        <input type="text" name="numR" value="<?php echo htmlspecialchars($numRepair); ?>" readonly>
        <br>
        <label>Start Time:</label>
        <input type="text" value="<?php echo htmlspecialchars($startTime); ?>" readonly>
        <br>
        <label>End Time:</label>
        <input type="datetime-local" name="endTime" required>
        <br>
        <label>Description:</label>
        <textarea name="description" rows="4" cols="50" required><?php echo htmlspecialchars($description); ?></textarea>
        <br>
        <input type="submit" value="Finish Repair">
        <a href="../repair.php">Back</a>
    </form>
</main>
</body>
</html>

