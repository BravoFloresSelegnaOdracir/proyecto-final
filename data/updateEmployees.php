<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employees</title>
    <link rel="stylesheet" href="../includes/stylesUpdate.css">
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num = $_POST['num'];
    $name = $_POST['name'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $cellphone = $_POST['cellphone'];

    $db = connection();
    $stmt = $db->prepare("CALL updateEmployee(?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $num, $name, $middleName, $lastName, $email, $cellphone);

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
    $num = $_GET['num'];
    $db = connection();
    $stmt = $db->prepare("SELECT * FROM vw_employees WHERE num = ?");
    $stmt->bind_param("i", $num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $middleName = $row['middleName'];
        $lastName = $row['lastName'];
        $email = $row['email'];
        $cellphone = $row['cellphone'];
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
            <label for="num">No Employee:</label>
            <input type="text" id="num" name="num" value="<?php echo htmlspecialchars($num); ?>" readonly>
            <br>
        </div>

        <div class="form-group">
            <label for="name">First Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            <br>
        </div>

        <div class="form-group">
            <label for="middleName">Middle Name:</label>
            <input type="text" id="middleName" name="middleName" value="<?php echo htmlspecialchars($middleName); ?>">
            <br>
        </div>

        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>">
            <br>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <br>
        </div>

        <div class="form-group">
            <label for="cellphone">Cellphone:</label>
            <input type="text" id="cellphone" name="cellphone" value="<?php echo htmlspecialchars($cellphone); ?>">
            <br>
        </div>

        <div class="form-group buttons">
            <input type="submit" value="Update" class="btn btn-primary">
            <a href="../employee.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</main>
</body>
</html>