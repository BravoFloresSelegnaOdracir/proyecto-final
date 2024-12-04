<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['nombreCompleto'])) {
    header("Location: login.php");
    exit();
}
require "data/connection.php";
$db = connection();

$query_brands = "SELECT codigo, nombre FROM vw_Marcas";
$query_areas = "SELECT clave, nombre FROM vw_Areas";
$query_modelo = "SELECT codModelo, modelo FROM vw_marcaModelo";

$brands = mysqli_query($db, $query_brands);
$areas = mysqli_query($db, $query_areas);
$modelos = mysqli_query($db, $query_modelo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="includes/historyM.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>All Archives</p>
            <button onclick="showContent('incidents')">All Incidents</button>
            <button onclick="showContent('maintenance')">All Maintenances</button>
            <button onclick="showContent('repair')">All Repairs</button>
            <button onclick="showContent('reports')">All Reports</button>
            <button onclick="showContent('request')">All Materials Requests</button>
            <button onclick="window.location.href='manager.php'">Return To Main Menu</button>
        </div>  
        
        <div class="content">
        <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
            <div id="incidents" class="section">
                <h2>See Incidents</h2>
                    
                    <form method="post" action="">
                        <input type="text" name="search" placeholder="Search incidents..." value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                        <button type="submit">Search</button>
                    </form>

                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Serial Number</th>
                                <th>Equipment</th>
                                <th>Area</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once "data/getIncidentsMan.php";

                            $search = isset($_POST['search']) ? $_POST['search'] : null;
                            $result = getIncidentsManager($search);

                            if ($result && mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['equipmentName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['area']); ?></td>
                                    <td>
                                        <form method="post" action="data/viewIncident.php">
                                            <input type="hidden" name="num" value="<?php echo $row['num']; ?>">
                                            <button type="submit" class="view-button">See Report</button>
                                        </form>
                                    </td>  
                                </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="7">No results found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
            </div>

            <div id="maintenance" class="section">
                <h2>See maintenance</h2>

                    <form method="post" action="">
                        <input type="text" name="search" placeholder="Search maintenance..." value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                        <button type="submit">Search</button>
                    </form>

                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Time Down</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>No Equipment</th>
                                <th>Equipment</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once "data/getMaintenanceMan.php";

                            $search = isset($_POST['search']) ? $_POST['search'] : null;
                            $result = obtenerMantenimientosManager($search);

                            if ($result && mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['timeDown']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cost']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['equipment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="7">No results found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
            </div>

            <div id="repair" class="section">
                <h2>See repairs</h2>

                    <form method="post" action="">
                        <input type="text" name="search" placeholder="Search repairs..." value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                        <button type="submit">Search</button>
                    </form>

                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Started</th>
                                <th>Finished</th>
                                <th>Time Down</th>
                                <th>Cost</th>
                                <th>No Equipment</th>
                                <th>Equipment</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once "data/getRepairsMan.php";

                            $search = isset($_POST['search']) ? $_POST['search'] : null;
                            $result = obtenerReparaciones($search);

                            if ($result && mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['startTime']); ?></td>
                                    <td><?php echo htmlspecialchars($row['finishedTime']); ?></td>
                                    <td><?php echo htmlspecialchars($row['downTime']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cost']); ?></td>
                                    <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['equipment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="7">No results found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
            </div>

            <div id="reports" class="section">
                <h2>See Reports</h2>
                    
                    <form method="post" action="">
                        <input type="text" name="search" placeholder="Search reports..." value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                        <button type="submit">Search</button>
                    </form>

                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Type</th>
                                <th>Serial Number</th>
                                <th>Equipment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once "data/getReports.php";

                            $search = isset($_POST['search']) ? $_POST['search'] : null;
                            $result = getReports($search);

                            if ($result && mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['equipment']); ?></td>
                                    <td>
                                        <form method="post" action="data/viewReport.php">
                                            <input type="hidden" name="num" value="<?php echo $row['num']; ?>">
                                            <button type="submit" class="view-button">See Report</button>
                                        </form>
                                    </td>                                
                                </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="9">No results found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
            </div>
            
            <div id="request" class="section">
                <h2>See Requests</h2>
                    
                    <form method="post" action="">
                        <input type="text" name="search" placeholder="Search reports..." value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                        <button type="submit">Search</button>
                    </form>

                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Requested</th>
                                <th>Delivery</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Technician</th>
                                <th>Name</th>
                                <th>Equipment</th>
                                <th>Serial Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once "data/getRequestMan.php";

                            $search = isset($_POST['search']) ? $_POST['search'] : null;
                            $result = obtenerSolicitudes($search);

                            if ($result && mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['requestDate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['delivery']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cost']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['technician']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="7">No results found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
            </div>
                <script src="includes/script.js"></script>
        </div>
</body>
</html>