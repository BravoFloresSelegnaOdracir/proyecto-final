<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['numEmpleado'])) {
        if (isset($_POST['report']) && isset($_POST['equipment']) && isset($_POST['type'])) {
            $report = $_POST['report'];
            $equipment = $_POST['equipment'];
            $type = $_POST['type'];
            $empleado = $_SESSION['numEmpleado'];

            $db = connection();

            $query = "CALL insertarReporte('$report', '$equipment', '$type', '$empleado');";
            
            $response = mysqli_query($db, $query);
            if ($response) {
                echo "<script>alert('Report added successfully.');
                window.location.href = '../operator.php';</script>";
                exit();
            } else {
                echo "<script>alert('Report Not Created: " . mysqli_error($db) . "');
                window.location.href = '../operator.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Error: Missing form fields!');
            window.location.href = '../operator.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error: User not logged in!');
        window.location.href = '../operator.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Error: Form not submitted correctly!');
    window.location.href = '../operator.php';</script>";
    exit();
}
?>