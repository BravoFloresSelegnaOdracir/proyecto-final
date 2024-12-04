<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['numEmpleado'])) {
        if (isset($_POST['delivery']) && isset($_POST['equipments'])) {
            $delivery = $_POST['delivery'];
            $equipments = $_POST['equipments'];
            $empleado = $_SESSION['numEmpleado'];

            $db = connection();

            $query = "CALL startRequest('$delivery', '$empleado', '$equipments');";
            
            $response = mysqli_query($db, $query);
            if ($response) {
                echo "<script>alert('Request added successfully.'); window.location.href = '../materials.php';</script>";
            } else {
                echo "<p style='color: red;'>Incident Not Created: " . mysqli_error($db) . "</p>";
            }
        } else {
            echo "<p style='color: red;'>Missing form fields!</p>";
        }
    } else {
        echo "<p style='color: red;'>User not logged in!</p>";
    }
} else {
    echo "<p style='color: red;'>Form not submitted correctly!</p>";
}
?>