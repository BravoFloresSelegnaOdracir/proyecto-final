<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['nombreCompleto'])) {
    header("Location: login.php");
    exit();
}

require("data/connection.php");

$conn = connection();

$query_equipments = "SELECT NumeroSerieEquipo, nombreEquipo FROM vw_equiposPorArea;";
$equipment = mysqli_query($conn, $query_equipments);
if (!$equipment) {
    die("Query failed: " . mysqli_error($conn));
}

if (!isset($_SESSION['numEmpleado'])) {
    die("Technician ID not found in session.");
}

$tecId = $_SESSION['numEmpleado'];

$stmt = $conn->prepare("CALL manteProcesoPrev(?)");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("i", $tecId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error retrieving maintenance data: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalize'])) {
    $maintenanceId = intval($_POST['maintenanceId']);
    $inactiveTime = floatval($_POST['inactiveTime']);

    $result->free(); 
    $stmt->close();

    $stmt = $conn->prepare("CALL finalizarMantenimiento(?, ?)");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("id", $maintenanceId, $inactiveTime);
    
    if ($stmt->execute()) {
        echo "<script>alert('Maintenance finalized successfully!');
        window.history.back();</script>";
        exit();
    } else {
        echo "<script>alert('Error finalizing maintenance: " . $stmt->error . "');
        window.history.back();</script>";
        exit();
    }
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician</title>
    <link rel="stylesheet" href="includes/preventiveMaintenance.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
        <p>Preventive Maintenance</p>
            <button onclick="showContent('preventive')">Start a Preventive Maintenance</button>
            <button onclick="showContent('add')">Add Materials</button>
            <button onclick="showContent('finish')">Finish Preventive Maintenance</button>
            <button onclick="window.location.href='techReports.php'">Return</button>
        </div>  
        
        <div class="content">
        <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
        
            <div id="preventive" class="section">
                <h2>Add Preventive Maintenance</h2>
                <form action="data/addPreventiveMaintenance.php" method="post">
                    <fieldset>
                        <legend>Fill all form fields</legend>
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description">
                        <br>
                        <br>

                        <label for="fecha">Scheduale date</label>
                        <input type="date" id="fecha" name="fecha">
                        <br>
                        <br>

                        <label for="equipment">Equipment</label>
                            <select name="equipment" id="equipment" required>
                                <option value="" disabled selected>Select an equipment</option>
                                <?php while ($row = mysqli_fetch_assoc($equipment)): ?>
                                    <option value="<?php echo htmlspecialchars($row['NumeroSerieEquipo']); ?>">
                                        <?php echo htmlspecialchars($row['NumeroSerieEquipo']).' - '.($row['nombreEquipo']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <br>
                            <br>
                        <button type="submit">Open a new preventive maintenance</button>
                    </fieldset>
                </form>
            </div>

            <div id="finish" class="section">
                <h2>Maintenance in Progress</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Serial Number</th>
                            <th>Equipment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['cost']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                <td><?php echo htmlspecialchars($row['equipment']); ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="maintenanceId" value="<?php echo intval($row['num']); ?>">
                                        <input type="number" step="0.01" name="inactiveTime" placeholder="Inactive time" required>
                                        <button type="submit" name="finalize">Finalize</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <?php
                require("data/getMaintenanceP.php");
                $result = getMaintenanceP();
            ?>
            <div id="add" class="section">
                <h2>Maintenance in Progress</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Serial Number</th>
                            <th>Equipment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['date']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['cost']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                <td><?php echo htmlspecialchars($row['equipment']); ?></td>
                                <td><a href="data/addMaterialsMaint.php?num=<?php echo htmlspecialchars($row['num']); ?>">Add Materials</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <script src="includes/script.js"></script>
        </div>
    </div>
</body>
</html>