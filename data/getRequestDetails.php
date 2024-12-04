<?php
require_once "connection.php";

function obtenerDetallesSolicitud($num) {
    $db = connection();

    $sql = "CALL viewReport(?)";
    
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $db->error);
    }

    $stmt->bind_param("i", $num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $report = $result->fetch_assoc();
        $stmt->close();
        $db->close();
        return $report;
    } else {
        $stmt->close();
        $db->close();
        return false;
    }
}
?>
