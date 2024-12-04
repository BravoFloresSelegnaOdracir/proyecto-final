<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../data/employee.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $myuser = new Employee();
    $myuser->setNoEmpleado($_POST['numEmp']);
    $myuser->setPassword($_POST['txtpass']);

    $userData = $myuser->getUserData();

    if ($userData !== false) {
        session_regenerate_id(true);
        $registro = $userData;
        $_SESSION['numEmpleado'] = $_POST['numEmp'];
        $_SESSION['nombreCompleto'] = $registro['nombreCompleto'];
        $_SESSION['tipoEmpleado'] = $registro['tipoEmpleado'];
    
        switch ($_SESSION['tipoEmpleado']) {
            case 'OPE':
                header("Location: ../operator.php");
                exit();
            case 'TEC':
                header("Location: ../technician.php");
                exit();
            case 'GER':
                header("Location: ../manager.php");
                exit();
            default:
                echo "<script>alert('No. Employee or password incorrect, try again.'); window.location.href = '../login.php?error=2';</script>";
                exit();
        }
    } else {
        echo "<script>alert('No. Employee or password incorrect, try again.'); window.location.href = '../login.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request method. Redirecting to login.'); window.location.href = '../login.php?error=1';</script>";
    exit();
}
