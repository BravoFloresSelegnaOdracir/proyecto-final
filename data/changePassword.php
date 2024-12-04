<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("connection.php");

$newPass = $_POST['newPass'] ?? null;
$noEmpl = $_SESSION['numEmpleado'] ?? null;

if (!$newPass || !$noEmpl) {
    $_SESSION['change_password_message'] = "Error: Missing required fields.";
    header("Location: ../operator.php");
    exit();
}

$db = connection();

try {
    $stmt = $db->prepare("CALL changePassword(?, ?)");
    $stmt->bind_param("si", $newPass, $noEmpl);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute stored procedure: " . $stmt->error);
    }

    $_SESSION['change_password_message'] = "Password updated successfully.";
} catch (mysqli_sql_exception $e) {
    $_SESSION['change_password_message'] = "Database error: " . htmlspecialchars($e->getMessage());
} catch (Exception $e) {
    $_SESSION['change_password_message'] = "Unexpected error: " . htmlspecialchars($e->getMessage());
}

header("Location: ../operator.php");
exit();
?>
