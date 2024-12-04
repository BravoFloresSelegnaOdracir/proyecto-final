<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['numEmpleado'])) {
        if (isset($_POST['name']) && isset($_POST['middle']) && isset($_POST['last']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['cell']) && isset($_POST['type'])) {
            $name = $_POST['name'];
            $middle = $_POST['middle'];
            $last = $_POST['last'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $cell = $_POST['cell'];
            $type = $_POST['type'];

            $db = connection();

            $query = "CALL insertarEmpleado('$name', '$middle', '$last', '$password', '$email', '$cell', '$type');";

            $response = mysqli_query($db, $query);
            if ($response) {
                echo "<script>alert('Employee added successfully.'); window.location.href = '../employee.php';</script>";
            } else {
                echo "<script>alert('Employee Not Created: " . mysqli_error($db) . "'); 
                window.history.back();</script>";
                exit();
            }
        } else {
                echo "<script>alert('Missing form fields!'); 
                window.history.back();</script>";
                exit();
        }
    } else {
        echo "<script>alert('User not logged in!'); 
        window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('Form not submitted correctly!'); 
    window.history.back();</script>";
    exit();
}
?>