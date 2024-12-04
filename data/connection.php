<?php
function connection() : mysqli {
    $db = mysqli_connect("localhost", "root", "", "mantenimientoEquipo");
    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $db;
}
?>