<?php
require_once "connection.php";

function getReports($search = null) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['numEmpleado'])) {
        $db = connection();

        if ($search) {
            $query = "CALL reportSearch('$search');";
        } else {
            $query = "CALL seeReports();";
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