<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materials Repair</title>
    <link rel="stylesheet" href="../includes/stylesUpdate.css">
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "connection.php";

$db = connection();

$query_materials = "SELECT code, name FROM materials";
$materials_result = mysqli_query($db, $query_materials);

if (!$materials_result) {
    die("<p>Error fetching materials: " . htmlspecialchars(mysqli_error($db)) . "</p>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $material = $_POST['material'] ?? null;
    $numR = $_POST['numR'] ?? null;
    $amount = $_POST['amount'] ?? null;

    if (!$material || !$numR || !$amount) {
        echo "<script>alert('Error: All fields are required.');
        window.location.href = '../repair.php';</script>";
        exit();
    } else {
        try {
            $stmt = $db->prepare("CALL materialsRepair(?, ?, ?)");
            $stmt->bind_param("sii", $material, $numR, $amount);

            if ($stmt->execute()) {
                echo "<script>alert('Materials added successfully. Click OK to return to Repairs.');
                window.location.href = '../repair.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error executing procedure: " . htmlspecialchars($stmt->error) . "');
                window.location.href = '../repair.php';</script>";
                exit();
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "<script>alert('Unexpected error: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = '../repair.php';</script>";
            exit();
        }
    }
}

$material = $_GET['material'] ?? '';
$numR = $_GET['numR'] ?? '';
$amount = $_GET['amount'] ?? '';
?>

<main class="container-update">
    <h2>Materials Repair</h2>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="form-update">
        <label for="material">Material:</label>
        <select name="material" id="material" required>
            <option value="" disabled selected>Select a material</option>
            <?php while ($row = mysqli_fetch_assoc($materials_result)): ?>
                <option value="<?= htmlspecialchars($row['code']); ?>">
                    <?= htmlspecialchars($row['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br>

        <label for="numR">Repair ID:</label>
        <input type="number" id="numR" name="numR" value="<?= htmlspecialchars($numR); ?>" readonly>
        <br>

        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($amount); ?>" required>
        <br>

        <div class="buttons">
            <input type="submit" value="Add Material" class="btn btn-primary">
            <a href="../repair.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</main>
</body>
</html>
