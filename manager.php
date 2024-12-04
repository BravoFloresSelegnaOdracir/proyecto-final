<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['nombreCompleto'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . "/data/connection.php";

$conn = connection();

$query_equipments = "SELECT NumeroSerieEquipo, nombreEquipo FROM vw_equiposPorArea;";
$equipments = mysqli_query($conn, $query_equipments);
if (!$equipments) {
    die("Query failed: " . mysqli_error($conn));
}

$manager = $_SESSION['numEmpleado'];

$queryRequest = "CALL notificationRequestMade(?)";
$stmtRequest = $conn->prepare($queryRequest);
if (!$stmtRequest) {
    die("Error preparing statement: " . $conn->error);
}

$stmtRequest->bind_param("i", $manager);
$stmtRequest->execute();
$resultRequest = $stmtRequest->get_result();

$stmtRequest->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager</title>
    <link rel="stylesheet" href="includes/stylesM.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['nombreCompleto']); ?></p>
            <button onclick="showContent('notifications')">Notifications</button>
            <button onclick="showContent('report')">Generate Report</button>
            <button onclick="window.location.href='historyM.php'">All Archives</button>
            <button onclick="window.location.href='equipments.php'">Manage Equipments</button>
            <button onclick="window.location.href='employee.php'">Manage Employees</button>
            <button id="logoutButton">Log Out</button>
        </div>  
        
        <div class="content">
            <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
            <div id="notifications" class="section">
                <h2>Notifications</h2>
                <ul>
                    <?php if ($resultRequest && $resultRequest->num_rows > 0): ?>
                        <?php while ($row = $resultRequest->fetch_assoc()): ?>
                            <li class="<?php echo $row['leida'] ? 'read' : 'unread'; ?>">
                                <strong>Request for Equipment: <?php echo htmlspecialchars($row['equipo']); ?></strong><br>
                                <span>Notification Date: <?php echo htmlspecialchars($row['fecha']); ?></span><br>
                                <span>Delivery Date: <?php echo htmlspecialchars($row['fechaEntrega']); ?></span><br>
                                <span>Total Cost: $<?php echo htmlspecialchars($row['costoTotal']); ?></span><br>

                                <form action="data/processRequest.php" method="post" style="display:inline;">
                                    <input type="hidden" name="notificationId" value="<?php echo intval($row['noNotificacion']); ?>">
                                    <button type="submit" name="action" value="approve">Approve</button>
                                </form>
                                <form action="data/processRequest.php" method="post" style="display:inline;">
                                    <input type="hidden" name="notificationId" value="<?php echo intval($row['noNotificacion']); ?>">
                                    <button type="submit" name="action" value="deny">Deny</button>
                                </form>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No notifications available.</p>
                    <?php endif; ?>
                </ul>
            </div>

            <div id="report" class="section">
                <h2>Add a new report</h2>
                <div>
                    <form action="data/addReport.php" method="post" class="styled-form">
                        <fieldset>
                            <legend>Fill all form fields</legend>
                            <div>
                                <label for="report">Report</label>
                                <input type="text" id="report" name="report">
                            </div>
                            <br>
                            <div>
                            <label for="priority">Equipment</label>
                                <select name="equipments" id="equipments" required>
                                        <option value="" disabled selected>Select an equipment</option>
                                        <?php while ($row = mysqli_fetch_assoc($equipments)): ?>
                                            <option value="<?php echo htmlspecialchars($row['NumeroSerieEquipo']); ?>">
                                                <?php echo htmlspecialchars($row['nombreEquipo']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                </select>
                            </div>
                            <br>
                            <div>
                                <label for="type">Type:</label>
                                <select id="type" name="type" required>
                                    <option value="" disabled selected>Select a type</option>
                                    <option value="GNRL">General</option>
                                    <option value="INCI">Incident</option>
                                    <option value="MANT">Maintenance</option>
                                    <option value="REPR">Repair</option>
                                </select>
                            </div>
                            <br>
                            <br>
                            <div>
                                <button type="submit">Create New Report</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

    <div id="logoutModal" class="logout-confirm" style="display: none;">
            <div class="logout-modal-content">
                <center><p>Are you sure you want to log out?</p></center>
                <button id="confirmLogout">Yes</button>
                <button id="cancelLogout">No</button>
                </div>
            </div>
        </div>

            <script>
                document.getElementById("logoutButton").addEventListener("click", function() {
                    document.getElementById("logoutModal").style.display = "block";
                });

                document.getElementById("confirmLogout").addEventListener("click", function() {
                    window.location.href = "logout.php"; 
                });

                document.getElementById("cancelLogout").addEventListener("click", function() {
                    document.getElementById("logoutModal").style.display = "none";
                });
            </script>

            <script src="includes/logout.js"></script>
            <script src="includes/script.js"></script>
        </div>
    </div>
</body>
</html>
