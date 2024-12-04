<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['nombreCompleto'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repairs</title>
    <link rel="stylesheet" href="includes/repair.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>Repairs</p>
            <button onclick="showContent('start')">Open a Repair</button>
            <button onclick="showContent('close')">Repairs In Process</button>
            <button onclick="window.location.href='techReports.php'">Return</button>
        </div>  
        
        <div class="content">
        <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
        <?php
            require("data/getMaintenanceP.php");
            $result = getMaintenanceP();

            if (isset($_GET['startRepair']) && isset($_GET['mantId'])) {
                require_once "data/connection.php";
                $conn = connection();

                $mantId = intval($_GET['mantId']);

                $stmt = $conn->prepare("CALL iniciarReparacion(?)");
                if ($stmt) {
                    $stmt->bind_param("i", $mantId);
                    if ($stmt->execute()) {
                        echo "<script>alert('Repair started successfully for maintenance ID: $mantId');
                        window.history.back();</script>";
                        exit();
                    } else {
                        echo "<script>alert('Error starting repair: " . $stmt->error . "');
                        window.history.back();</script>";
                        exit();
                    }
                    $stmt->close();
                } else {
                    echo "<script>alert('Error preparing statement: " . $conn->error . "');
                    window.history.back();</script>";
                    exit();
                }
                $conn->close();
            }
        ?>
        <div id="start" class="section">
            <h2>Maintenance In Process</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Serial Number</th>
                        <th>Equipment</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['type']); ?></td>
                            <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                            <td><?php echo htmlspecialchars($row['equipment']); ?></td>
                            <td>
                                <a href="?startRepair=true&mantId=<?php echo intval($row['num']); ?>">Create repair</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php
            require("data/getRepairsP.php");
            $result = repairInProcess();
        ?>
        <div id="close" class="section">
            <h2>Repairs In Process</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>Description</th>
                        <th>Cost</th>
                        <th>Serial Number</th>
                        <th>Equipment</th>
                        <th>Actions</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['startTime']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['cost']); ?></td>
                            <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                            <td><?php echo htmlspecialchars($row['equipment']); ?></td>
                            <td>
                            <a href="data/addMaterialsR.php?numR=<?php echo intval($row['numR']); ?>" class="btn-add-materials">Add Materials</a>
                            </td>
                            <td>
                                <a href="data/finishRepair.php?numR=<?php echo intval($row['numR']); ?>" class="btn-finish-repair">Finish Repair</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <script src="includes/script.js"></script>
</body>
</html>
