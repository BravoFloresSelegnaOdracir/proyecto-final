<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['numEmpleado'])) {
        if (isset($_POST['description']) && isset($_POST['problem']) && isset($_POST['priority']) && isset($_POST['equipment'])) {
            $description = $_POST['description'];
            $problem = $_POST['problem'];
            $priority = $_POST['priority'];
            $equipment = $_POST['equipment'];
            $empleado = $_SESSION['numEmpleado'];

            $db = connection();

            $query = "CALL insertarIncidencias('$description', '$problem', '$priority', '$equipment', '$empleado');";

            $response = mysqli_query($db, $query);
            if ($response) {
                echo "<script>alert('Incident added successfully.');
                window.location.href = '../operator.php';</script>";
                exit();
            } else {
                echo "<script>
                    alert('Incident Not Created: " . mysqli_error($db) . "');
                    window.location.href = '../operator.php';
                </script>";
                exit();
            }
        } else {
            echo "<script>alert('Missing form fields!');
            window.location.href = '../operator.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('User not logged in!');
        window.location.href = '../login.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Form not submitted correctly!');
    window.location.href = '../operator.php';</script>";
    exit();
}
?>
