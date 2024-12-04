<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require "connection.php";
$db = connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['description']) && isset($_POST['fecha']) && isset($_POST['equipment'])) {
        $description = $_POST["description"];
        $fecha = $_POST["fecha"];
        $equipment = $_POST["equipment"];
        $numEmpleado = $_SESSION['numEmpleado'];

        $query = "CALL insertarMantePrev('$description', '$fecha', '$equipment', '$numEmpleado');";
        
        $response = mysqli_query($db, $query);
        if ($response) {
            echo "<script>alert('Maintenance added successfully.');
            window.location.href = '../preventiveMaintenance.php';</script>";
            exit();
        } else {
            echo "<script>alert('Maintenance Not Created: " . mysqli_error($db) . "');
            window.location.href = '../preventiveMaintenance.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error: Missing form fields!');
        window.location.href = '../preventiveMaintenance.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Error: Form not submitted correctly!');
    window.location.href = '../preventiveMaintenance.php';</script>";
    exit();
}
?>