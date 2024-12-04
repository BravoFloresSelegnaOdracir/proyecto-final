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
$db = connection();
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

$query_equipments = "SELECT NumeroSerieEquipo, nombreEquipo FROM vw_equiposPorArea;";
$equipments = mysqli_query($db, $query_equipments);
if (!$equipments) {
    die("Query failed: " . mysqli_error($db));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materials Requests</title>
    <link rel="stylesheet" href="includes/materials.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>MENU MATERIALS</p>
            <button onclick="showContent('start')">Start a Materials Request</button>
            <button onclick="showContent('add')">Add Materials to a Request</button>
            <button onclick="showContent('materials')">Materials Stock</button>
            <button onclick="window.location.href='technician.php'">Return To Main Menu</button>
        </div>  
        <div class="content">
            <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
            <div id="start" class="section">
                <h2>Start a Materials Request</h2>
                <form action="data/addRequest.php" method="post">
                    <fieldset>
                        <legend>Fill all form fields</legend>
                        <div>
                            <label for="equipments">Equipment</label>
                            <select name="equipments" id="equipments" required>
                                <option value="" disabled selected>Select an equipment</option>
                                <?php while ($row = mysqli_fetch_assoc($equipments)): ?>
                                    <option value="<?php echo htmlspecialchars($row['NumeroSerieEquipo']); ?>">
                                    <?php echo htmlspecialchars($row['NumeroSerieEquipo']).' - '.($row['nombreEquipo']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <br>
                        <div>
                            <label for="delivery">Expected Delivery Date</label>
                            <input type="date" id="delivery" name="delivery" required>
                        </div>
                        <br>
                        <div>
                            <button type="submit">Create New Request</button>
                        </div>
                    </fieldset>
                </form>
            </div>

            <?php
            require("data/getRequestTech.php");
            $resultRequests = obtenerSolicitudesTec();
            ?>
            <div id="add" class="section">
                <h2>Add Materials to a Request</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Request Date</th>
                            <th>Delivery</th>
                            <th>Cost</th>
                            <th>Equipment</th>
                            <th>Serial Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultRequests && mysqli_num_rows($resultRequests) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($resultRequests)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['requestDate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['delivery']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cost']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['noEquipment']); ?></td>
                                    <td><a href="data/addMaterialsReq.php?numReq=<?php echo htmlspecialchars($row['numReq']); ?>" class="btn-add-materials">Add Materials</a></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No requests found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

+            <?php
            require("data/getMaterials.php");
            $resultMaterials = getMaterials();
            ?>
            <div id="materials" class="section">
                <h2>Materials Stock</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Stock</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultMaterials && mysqli_num_rows($resultMaterials) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($resultMaterials)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No materials found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="includes/script.js"></script>
</body>
</html>
<?php
if ($equipments) {
    mysqli_free_result($equipments);
}
if (isset($resultRequests)) {
    mysqli_free_result($resultRequests);
}
if (isset($resultMaterials)) {
    mysqli_free_result($resultMaterials);
}
mysqli_close($db);
?>
