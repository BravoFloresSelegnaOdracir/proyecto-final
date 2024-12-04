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

$tecnicoId = $_SESSION['numEmpleado'];

$queryIncidents = "CALL notificationsIncidents(?)";
$stmtIncidents = $conn->prepare($queryIncidents);
if (!$stmtIncidents) {
    die("Error preparing statement: " . $conn->error);
}

$stmtIncidents->bind_param("i", $tecnicoId);
$stmtIncidents->execute();
$resultIncidents = $stmtIncidents->get_result();

$stmtIncidents->close();

$queryRequests = "CALL notificationRequestFinish(?)";
$stmtRequests = $conn->prepare($queryRequests);
if (!$stmtRequests) {
    die("Error preparing statement: " . $conn->error);
}

$stmtRequests->bind_param("i", $tecnicoId);
$stmtRequests->execute();
$resultRequests = $stmtRequests->get_result();

$stmtRequests->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician</title>
    <link rel="stylesheet" href="includes/stylesT.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['nombreCompleto']); ?></p>
            <button onclick="showContent('notifications')">Notifications</button>
            <button onclick="window.location.href='techReports.php'">Menu Reports...</button>
            <button onclick="window.location.href='materials.php'">Menu for Request Materials...</button>
            <button onclick="window.location.href='techProfile.php'">Go to Profile...</button>
            <button id="logoutButton">Log Out</button>
        </div>  
        
        <div class="content">
            <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
            <div id="notifications" class="section">
                <h2>Notifications</h2>
                <ul>
                    <?php if ($resultIncidents->num_rows > 0): ?>
                        <?php while ($row = $resultIncidents->fetch_assoc()): ?>
                            <li class="<?php echo $row['leida'] ? 'read' : 'unread'; ?>">
                                <strong><?php echo htmlspecialchars($row['descripcion']); ?></strong><br>
                                <span>Date: <?php echo htmlspecialchars($row['fecha']); ?></span><br>
                                <a href="data/markAsRead.php?notificationId=<?php echo intval($row['noNotificacion']); ?>">Mark as Read</a>
                                <?php if (isset($row['incident'])): ?>
                                    <a href="data/addCorrectiveM.php?incident=<?php echo intval($row['incident']); ?>">Start Maintenance. Go to repairs menu: open a repair, in the reports seccion</a>
                                <?php endif; ?>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No incident notifications available.</p>
                    <?php endif; ?>
                    
                    <?php if ($resultRequests->num_rows > 0): ?>
                        <?php while ($row = $resultRequests->fetch_assoc()): ?>
                            <li class="<?php echo $row['leida'] ? 'read' : 'unread'; ?>">
                                <strong>Material Request: <?php echo htmlspecialchars($row['estadoAprobacion']); ?></strong><br>
                                <span>Date: <?php echo htmlspecialchars($row['fecha']); ?></span><br>
                                <span>Equipment: <?php echo htmlspecialchars($row['equipo']); ?></span><br>
                                <span>Delivery Date: <?php echo htmlspecialchars($row['fechaEntrega']); ?></span><br>
                                <span>Total Cost: <?php echo htmlspecialchars($row['costoTotal']); ?></span><br>
                                <a href="data/markAsRead.php?notificationId=<?php echo intval($row['noNotificacion']); ?>" class="btn-mark-read">Mark as Read</a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No material request notifications available.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="logoutModal" class="logout-confirm" style="display: none;">
            <div class="logout-modal-content">
                <center><h2>Are you sure you want to log out?</h2></center>
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
</body>
</html>

