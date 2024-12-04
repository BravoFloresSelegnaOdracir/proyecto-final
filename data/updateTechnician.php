<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Technician</title>
    <link rel="stylesheet" href="../includes/stylesUpdate.css">
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $noEmployee = $_POST['noEmployee'];
    $name = $_POST['name'];
    $field = $_POST['field'];
    $scheduale = $_POST['scheduale'];

    $db = connection();
    $stmt = $db->prepare("CALL modificarTecnico(?, ?, ?)");
    $stmt->bind_param("iss", $noEmployee, $field, $scheduale);

    if ($stmt->execute()) {
        header("Location: ../employee.php");
    } else {
        echo "<script>alert('Error updating employee: " . htmlspecialchars($stmt->error) . "');
        window.history.back();</script>";
        exit();
    }

    $stmt->close();
    $db->close();
} else {
    $noEmployee = $_GET['noEmployee'];
    $db = connection();
    $stmt = $db->prepare("SELECT * FROM vw_technician WHERE noEmployee = ?");
    $stmt->bind_param("i", $noEmployee);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $noEmployee = $row['noEmployee'];
        $name = $row['name'];
        $field = $row['field'];
        $scheduale = $row['scheduale'];
    } else {
        echo "<script>alert('Employee not found.');
        window.history.back();</script>";
        exit();
    }

    $stmt->close();
    $db->close();
}
?>

<main class="container-update"> 
    <h2>Update Employee</h2>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" class="form-update">
        <div class="form-group">
            <label for="noEmployee">No Employee:</label>
            <input type="text" id="noEmployee" name="noEmployee" value="<?php echo htmlspecialchars($noEmployee); ?>" readonly>
        </div>
        
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" readonly>
        </div>
        
        <div class="form-group">
            <label for="field">Field:</label>
            <input type="text" id="field" name="field" value="<?php echo htmlspecialchars($field); ?>">
        </div>
        
        <div class="form-group">
            <label for="scheduale">Schedule:</label>
            <input type="text" id="scheduale" name="scheduale" value="<?php echo htmlspecialchars($scheduale); ?>">
        </div>
        
        <div class="form-group buttons">
            <input type="submit" value="Update" class="btn btn-primary">
            <a href="../employee.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</main>

</body>
</html>