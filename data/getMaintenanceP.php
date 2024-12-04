<?php
require_once "data/connection.php";

function getMaintenanceP() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['numEmpleado'])) {
        $db = connection();
        $numEmpleado = $_SESSION['numEmpleado'];

        $query = "CALL maintenanceInProcess('$numEmpleado')";
        $result = mysqli_query($db, $query);

        if (!$result) {
            die("Error al ejecutar la consulta: " . mysqli_error($db));
        }

        return $result;
    } else {
        die("Error: Usuario no autenticado.");
    }
}
?>