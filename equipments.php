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
    <title>Operator</title>
    <link rel="stylesheet" href="includes/equipments.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>Manage Equipments</p>
            <button onclick="showContent('register')">Register Equipment</button>
            <button onclick="showContent('see')">Equipments</button>
            <button onclick="window.location.href='manager.php'">Return To Main Menu</button>
        </div>  
        
        <div class="content">
        <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
            <div id="register" class="section">
            <h2>Register Equipment</h2>
                        <div>
                            <form action="data/addEquipment.php" method="post" class="styled-form">
                                <fieldset>
                                    <legend>Fill all form fields</legend>
                                    <div>
                                        <label for="number">Serial Number</label>
                                        <input type="text" id="number" name="number">
                                    </div>
                                    <br>
                                    <div>
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name">
                                    </div>
                                    <br>
                                    <div>
                                        <label for="datep">Purchase date</label>
                                        <input type="date" id="datep" name="datep">
                                    </div>
                                    <br>
                                    <div>
                                        <label for="price">Purchase price</label>
                                        <input type="number" id="price" name="price">
                                    </div>
                                    <br>
                                    <div>
                                        <label for="model">Model</label>
                                        <select name="model" id="model">
                                        <option value="" disabled selected>Select a model</option>
                                            <?php while($modelo = mysqli_fetch_assoc($modelos)): ?>
                                                <option value="<?php echo $modelo['codModelo']; ?>"><?php echo $modelo['modelo']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="brand">Brand</label>
                                        <select name="brand" id="brand">
                                        <option value="" disabled selected>Select a brand</option>
                                            <?php while($brand = mysqli_fetch_assoc($brands)): ?>
                                                <option value="<?php echo $brand['codigo']; ?>"><?php echo $brand['nombre']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <br>
                                    <div>
                                        <label for="status">Status</label>
                                        <select id="status" name="status" required>
                                            <option value="" disabled selected>Select a status</option>
                                            <option value="ACTV">Active</option>
                                            <option value="INAC">Inactive</option>
                                            <option value="BAJA">Decommissioned</option>
                                        </select>
                                    <div>
                                        <br>
                                    <div>
                                        <label for="area">Area</label>
                                        <select name="area" id="area">
                                        <option value="" disabled selected>Select an area</option>
                                            <?php while($area = mysqli_fetch_assoc($areas)): ?>
                                                <option value="<?php echo $area['clave']; ?>"><?php echo $area['nombre']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <br>
                                        <button type="submit">Add New Equipment</button>
                                </fieldset>
                            </form>
                        </div>
            </div>

            <div id="see" class="section styled-table">
                <h2>Equipments</h2>
                
                <form method="post" action="">
                    <input type="text" name="search" placeholder="Search equipments..." value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                    <button type="submit">Search</button>
                </form>
                <br>
                <br>

                <table class="styled-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Area</th>
                            <th>Serial Number</th>
                            <th>Equipment</th>
                            <th>Funcionality %</th>
                            <th>Purchesed</th>
                            <th>Cost</th>
                            <th>Model</th>
                            <th>Brand</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once "data/getEquipment.php";

                        $search = isset($_POST['search']) ? $_POST['search'] : null;
                        $result = getEquipments($search);

                        if ($result && mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                        ?>
                            <tr>
                                <td><a href="data/updateEquipment.php?NumeroSerieEquipo=<?php echo htmlspecialchars($row['NumeroSerieEquipo']); ?>" class="btn-update">Update</a></td>                                
                                <td><?php echo htmlspecialchars($row['nombreArea']); ?></td>
                                <td><?php echo htmlspecialchars($row['NumeroSerieEquipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombreEquipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['funcionalidadEquipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['fechaCompraEquipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['costoActualEquipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['modeloEquipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['marcaEquipo']); ?></td>
                                <td><?php echo htmlspecialchars($row['estadoEquipo']); ?></td>                            
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
    </div>
</body>
</html>