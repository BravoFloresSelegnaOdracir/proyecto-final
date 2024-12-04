<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['nombreCompleto'])) {
    header("Location: login.php");
    exit();
}

require_once _DIR_ . "/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $incident = $_POST['incident'];
    $description = $_POST['description'];
    $technicianId = $_SESSION['numEmpleado'];

    $conn = connection();
    $query = "CALL insertarMante(?, ?)";

    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("si", $description, $incident);
        if ($stmt->execute()) {
            header("Location: ../repair.php");
            exit();
        } else {
            echo "<script>alert('Error creating maintenance: " . htmlspecialchars($stmt->error) . "'); 
            window.history.back();</script>";
            exit();
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . htmlspecialchars($conn->error) . "'); 
        window.history.back();</script>";
        exit();
    }
    $conn->close();
} else if (isset($_GET['incident'])) {
    $incident = $_GET['incident'];
} else {
    echo "<script>alert('No incident specified.'); 
        window.history.back();</script>";
        exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Maintenance</title>
    <link rel="stylesheet" href="../includes/stylesUpdate.css">
</head>
<body>
    <main class="container-update">
        <h2>Start Maintenance</h2>
        <form method="post" class="form-update">
            <input type="hidden" name="incident" value="<?php echo htmlspecialchars($incident); ?>">

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" rows="4" required></textarea>
            </div>
            
            <div class="buttons">
                <button type="submit" class="btn btn-primary">Start Maintenance</button>
                <a href="../technician.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>












