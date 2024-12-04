<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notificationId = $_POST['notificationId'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($notificationId && $action) {
        $status = ($action === 'approve') ? 'APR' : 'DEN';
        $db = connection();

        try {
            $stmt = $db->prepare("CALL managerRequest(?, ?)");
            $stmt->bind_param("si", $status, $notificationId);

            if ($stmt->execute()) {
                echo "<script> alert('Request successfully " . 
                ($action === 'approve' ? "approved" : "denied") . ".');
                window.history.back();</script>";
                exit();
            } else {
                echo "<script> alert('Error executing procedure: " . htmlspecialchars($stmt->error) . "');
                window.history.back();</script>";
                exit();
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "<script> alert('Unexpected error: " . htmlspecialchars($e->getMessage()) . "');
            window.history.back();</script>";
            exit();
        }

        $db->close();
    } else {
        echo "<script>alert('Error: Missing data.');window.history.back();</script>";
        exit();
    }
}
?>
<a href="../manager.php">Back to Notifications</a>
