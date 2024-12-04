<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require "connection.php";
$db = connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['numEmpleado'])) {
        if (isset($_POST['number']) && isset($_POST['name']) && isset($_POST['datep']) && isset($_POST['price']) && isset($_POST['model']) && isset($_POST['brand']) && isset($_POST['status']) && isset($_POST['area'])) {
            $number = $_POST['number'];
            $name = $_POST['name'];
            $datep = $_POST['datep'];
            $price = $_POST['price'];
            $model = $_POST['model'];
            $brand = $_POST['brand'];
            $status = $_POST['status'];
            $area = $_POST['area'];

            $db = connection();

            $query = "CALL insertarEquipo('$number', '$name', '$datep', '$price', '$model', '$brand', '$status', '$area');";

            $response = mysqli_query($db, $query);
            if ($response) {
                echo "<script>alert('Equipment added successfully.'); 
                window.location.href = '../manager.php';</script>";
                exit();
            } else {
                echo "<script>alert('Equipment Not Created: " . mysqli_error($db) . "'); 
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