<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['nombreCompleto'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician</title>
    <link rel="stylesheet" href="includes/techReports.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>Menu Reports</p>
            <button onclick="window.location.href='preventiveMaintenance.php'">Go To Preventive Maintenances...</button>
            <button onclick="window.location.href='repair.php'">Go To Repairs...</button>
            <button onclick="window.location.href='historyT.php'">Go To History...</button>
            <button onclick="window.location.href='technician.php'">Return to Main Menu</button>
        </div>  
        
        <div class="content">
        <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
            
            <script src="includes/script.js"></script>
        </div>
    </div>
</body>
</html>
