<?php
require_once "data/connection.php";

function obtenerSolicitudes($search = null) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['numEmpleado'])) {
        $db = connection();

        if ($search) {
            $query = "CALL seeRequestsMan('$search');";
        } else {
            $query = "CALL seeAllRequests();";
        }

        $result = mysqli_query($db, $query);

        if (!$result) {
            die("Error al ejecutar la consulta: " . mysqli_error($db));
        }
        return $result;
    }
    return null;
}
?>