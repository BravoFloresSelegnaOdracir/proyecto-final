<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "connection.php";

header('Content-Type: application/json');

function obtenerMantenimientoPreventivo() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['numEmpleado'])) {
        $db = connection();
        $numEmpleado = $_SESSION['numEmpleado'];

        $query = "CALL preventiveMaintenance()";
        $result = mysqli_query($db, $query);

        if (!$result) {
            http_response_code(500);
            echo json_encode(["error" => "Error al ejecutar la consulta: " . mysqli_error($db)]);
            mysqli_close($db);
            exit;
        }

        $mantenimientos = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $mantenimientos[] = [
                "equipo" => $row['equipo'],
                "nombre" => $row['nombre'],
                "fechaProgramada" => $row['fechaProgramada'],
                "estadoMantenimiento" => $row['estadoMantenimiento'],
                "tipoMantenimiento" => "Preventivo",
                "tecnico" => "No asignado",
            ];
        }

        mysqli_close($db);
        echo json_encode($mantenimientos);
        exit;
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Usuario no autenticado."]);
        exit;
    }
}

obtenerMantenimientoPreventivo();
?>

