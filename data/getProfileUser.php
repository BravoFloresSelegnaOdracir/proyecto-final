<?php
require_once "data/connection.php";

function obtenerPerfil() {
    try {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['numEmpleado'])) {
            $db = connection();
            if (!$db) {
                throw new Exception("Error al conectar a la base de datos");
            }

            $numEmpleado = $_SESSION['numEmpleado'];
            $query = "CALL seeProfile('$numEmpleado')";
            $result = mysqli_query($db, $query);

            if (!$result) {
                throw new Exception("Error en la consulta: " . mysqli_error($db));
            }

            $profileData = mysqli_fetch_assoc($result);
            mysqli_free_result($result);
            mysqli_close($db);

            return $profileData;
        } else {
            throw new Exception("Usuario no autenticado");
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        return [
            'num' => 'N/A',
            'name' => 'N/A',
            'email' => 'N/A',
            'cellphone' => 'N/A',
            'title' => 'N/A'
        ];
    }
}
