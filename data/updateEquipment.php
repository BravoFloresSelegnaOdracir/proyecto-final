<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Equipment</title>
    <link rel="stylesheet" href="../includes/stylesUpdate.css">
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NumeroSerieEquipo = $_POST['NumeroSerieEquipo'];
    $nombreEquipo = $_POST['nombreEquipo'];
    $fechaCompraEquipo = $_POST['fechaCompraEquipo'];
    $modeloEquipo = $_POST['modeloEquipo'];
    $marcaEquipo = $_POST['marcaEquipo'];
    $estadoEquipo = $_POST['estadoEquipo'];
    $nombreArea = $_POST['nombreArea'];

    $db = connection();
    $stmt = $db->prepare("CALL modificarEquipo(?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $NumeroSerieEquipo, $nombreEquipo, $fechaCompraEquipo, $modeloEquipo, $marcaEquipo, $estadoEquipo, $nombreArea);

    if ($stmt->execute()) {
        header("Location: ../equipments.php");
    } else {
        echo "<script>alert('Error updating equipment: " . htmlspecialchars($stmt->error) . "');
        window.history.back();</script>";
        exit();

    }

    $stmt->close();
    $db->close();
} else {

    if (isset($_GET['NumeroSerieEquipo'])) {
        $NumeroSerieEquipo = $_GET['NumeroSerieEquipo'];
    } else {
        echo "<script>alert('Error: num not set in URL.');
        window.history.back();</script>";
        exit();
    }
    

    $NumeroSerieEquipo = $_GET['NumeroSerieEquipo'];
    $db = connection();
    $stmt = $db->prepare("SELECT * FROM vw_equiposPorArea WHERE NumeroSerieEquipo = ?");
    $stmt->bind_param("s", $NumeroSerieEquipo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $NumeroSerieEquipo = $row['NumeroSerieEquipo'];
        $nombreEquipo = $row['nombreEquipo'];
        $fechaCompraEquipo = $row['fechaCompraEquipo'];
        $modeloEquipo = $row['modeloEquipo'];
        $marcaEquipo = $row['marcaEquipo'];
        $estadoEquipo = $row['estadoEquipo'];
        $nombreArea = $row['nombreArea'];
    } else {
        echo "<script>alert('Equipment not found.');
        window.history.back();</script>";
        exit();
    }

    $stmt->close();
    $db->close();
}
?>

<main class="container-update">
    <h2>Update Equipment</h2>
    <form class="form-update" action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <div class="form-group">
            <label>Serial Number:</label>
            <input type="text" name="NumeroSerieEquipo" value="<?php echo htmlspecialchars($NumeroSerieEquipo); ?>" readonly>
            <br>
        </div>

        <div class="form-group">
            <label>Equipment:</label>
            <input type="text" name="nombreEquipo" value="<?php echo htmlspecialchars($nombreEquipo); ?>">
            <br>
        </div>

        <div class="form-group">
            <label>Purchased:</label>
            <input type="date" name="fechaCompraEquipo" value="<?php echo htmlspecialchars($fechaCompraEquipo); ?>">
            <br>
        </div>

        <div class="form-group">
            <label>Model:</label>
            <input type="text" name="modeloEquipo" value="<?php echo htmlspecialchars($modeloEquipo); ?>">
            <br>
        </div>

        <div class="form-group">
            <label>Brand:</label>
            <input type="text" name="marcaEquipo" value="<?php echo htmlspecialchars($marcaEquipo); ?>">
            <br>
        </div>

        <div class="form-group">
            <label>Status:</label>
            <input type="text" name="estadoEquipo" value="<?php echo htmlspecialchars($estadoEquipo); ?>">
            <br>
        </div>

        <div class="form-group">
            <label>Area:</label>
            <input type="text" name="nombreArea" value="<?php echo htmlspecialchars($nombreArea); ?>">
            <br>
        </div>

        <div class="form-group buttons">
            <input type="submit" value="Update" class="btn btn-primary">
            <a href="../employee.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</main>
</body>
</html>