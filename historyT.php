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
    <title>History</title>
    <link rel="stylesheet" href="includes/historyT.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
        <p>History</p>
            <button onclick="showContent('incidents')">All Incidents</button>
            <button onclick="showContent('maintenance')">All Maintenance</button>
            <button onclick="showContent('repairs')">All Repairs</button>
            <button onclick="window.location.href='techReports.php'">Return</button>
        </div>  
        
        <div class="content">
        <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
        <?php
                require("data/getIncidentsTec.php");
                $result = obtenerIncidenciasTecnico();
        ?>
        <div id="incidents" class="section">
                <h2>Incidents Assignated</h2>
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Equipment</th>
                                <th>Problem</th>
                                <th>Status</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['problem']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['priority']); ?></td>
                                    <td><?php echo htmlspecialchars($row['startDate']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <?php
                    require("data/getMaintenanceTec.php");
                    $result = obtenerMantenimientosTecnico();
                ?>
                <div id="maintenance" class="section">
                        <h2>Maintenance</h2>
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
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                <?php
                    require("data/getRepaisTec.php");
                    $result = obtenerReparacionesTec();
                ?>
                <div id="repairs" class="section">
                        <h2>Repairs</h2>
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th>Start Time</th>
                                        <th>Finished</th>
                                        <th>Time Down</th>
                                        <th>Description</th>
                                        <th>Cost</th>
                                        <th>Serial Number</th>
                                        <th>Equipment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['startTime']); ?></td>
                                            <td><?php echo htmlspecialchars($row['finishedTime']); ?></td>
                                            <td><?php echo htmlspecialchars($row['downTime']); ?></td>
                                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                                            <td><?php echo htmlspecialchars($row['cost']); ?></td>
                                            <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                            <td><?php echo htmlspecialchars($row['equipment']); ?></td>
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