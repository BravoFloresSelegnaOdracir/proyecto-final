<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finish Maintenance</title>
    <link rel="stylesheet" href="../includes/stylesUpdate.css">
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numMantenimiento = $_POST['numM'];
    $tiempoInactivo = $_POST['tiempoInc'];

    $db = connection();

    $stmt = $db->prepare("CALL finalizarMantenimiento(?, ?)");
    $stmt->bind_param("id", $numMantenimiento, $tiempoInactivo);

    if ($stmt->execute()) {
        echo "<script>alert('Maintenance finished successfully.');
        window.location.href = '../maintenances.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error finishing maintenance: " . htmlspecialchars($stmt->error) . "');
        window.location.href = '../maintenances.php';</script>";
        exit();
    }

    $stmt->close();
    $db->close();
} else {
    $numMantenimiento = $_GET['numM'] ?? null;

    if (!$numMantenimiento) {
        echo "<script>alert('Error: No maintenance ID provided.');
        window.location.href = '../maintenances.php';</script>";
        exit();
    }

    $db = connection();

    $stmt = $db->prepare("SELECT * FROM mantenimiento WHERE noMantenimiento = ?");
    $stmt->bind_param("i", $numMantenimiento);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $numMantenimiento = $row['noMantenimiento'];
        $tiempoInicio = $row['horaInicio'];
        $estado = $row['estado'];
    } else {
        echo "<script>alert('Error: Maintenance not found.');
        window.location.href = '../maintenances.php';</script>";
        exit();
    }
    $stmt->close();
    $db->close();
}
?>

<main>
    <h2>Finish Maintenance</h2>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label>Maintenance ID:</label>
        <input type="text" name="numM" value="<?= htmlspecialchars($numMantenimiento); ?>" readonly>
        <br>
        <label>Start Time:</label>
        <input type="text" value="<?= htmlspecialchars($tiempoInicio); ?>" readonly>
        <br>
        <label>Inactive Time (in hours):</label>
        <input type="number" name="tiempoInc" step="0.01" required>
        <br>
        <input type="submit" value="Finish Maintenance">
        <a href="../maintenances.php">Back</a>
    </form>
</main>
</body>
</html>

