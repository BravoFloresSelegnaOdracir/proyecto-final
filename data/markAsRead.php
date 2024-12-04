<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/connection.php";

$conn = connection();

if (isset($_GET['notificationId'])) {
    $notificationId = intval($_GET['notificationId']);

    $query = "UPDATE notificacionIncidencia SET leida = TRUE WHERE noNotificacion = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $notificationId);

        if ($stmt->execute()) {
            header("Location: ../technician.php");
            exit();
        } else {
            echo "Error updating notification: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>
