<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materials Request</title>
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
    $numReq = $_POST['numReq'] ?? null;
    $amount = $_POST['amount'] ?? null;

    if (!$material || !$numReq || !$amount) {
        echo "<script>
        alert('Error: All fields are required.');
        window.history.back(); </script>";
        exit();
exit();
    } else {
        try {
            $stmt = $db->prepare("CALL materialsRequest(?, ?, ?)");
            $stmt->bind_param("sii", $material, $numReq, $amount);

            if ($stmt->execute()) {
                echo "<script>
                alert('Materials added successfully to the request.');
                window.location.href = '../materials.php'; 
                </script>";
                exit();
            } else {
                echo "<script>
                alert('Error executing procedure: " . htmlspecialchars($stmt->error) . "');
                window.history.back(); </script>";
                exit();
            }

            $stmt->close();
        } catch (Exception $e) {
            echo "<script>
            alert('Unexpected error: " . htmlspecialchars($e->getMessage()) . "');
            window.history.back();
            </script>";
            exit();
        }
    }
}

$material = $_GET['material'] ?? '';
$numReq = $_GET['numReq'] ?? '';
$amount = $_GET['amount'] ?? '';
?>

<main class="container-update">
    <h2>Materials Request</h2>
    <form class="form-update" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
            <label for="material">Material:</label>
            <select name="material" id="material" required>
                <option value="" disabled selected>Select a material</option>
                <?php while ($row = mysqli_fetch_assoc($materials_result)): ?>
                    <option value="<?= htmlspecialchars($row['code']); ?>">
                        <?= htmlspecialchars($row['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="numReq">Request ID:</label>
            <input type="number" id="numReq" name="numReq" value="<?= htmlspecialchars($numReq); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($amount); ?>" required>
        </div>

        <div class="buttons">
            <button type="submit" class="btn btn-primary">Add Material</button>
            <a href="../materials.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</main>
</body>
</html>

