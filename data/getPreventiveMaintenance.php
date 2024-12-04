<?php
require_once "connection.php";

function obtenerMantenimientoPreventivo() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['numEmpleado'])) {
        $db = connection();
        $numEmpleado = $_SESSION['numEmpleado'];

        $query = "CALL manteProcesoPrev('$numEmpleado')";
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