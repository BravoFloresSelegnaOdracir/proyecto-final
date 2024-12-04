<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['nombreCompleto'])) {
    header("Location: login.php");
    exit();
}
$message = $_SESSION['change_password_message'] ?? null;

unset($_SESSION['change_password_message']);

require("data/connection.php");

$conn = connection();

$query_equipments = "SELECT NumeroSerieEquipo, nombreEquipo FROM vw_equiposPorArea;";
$equipment = mysqli_query($conn, $query_equipments);
if (!$equipment) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator</title>
    <link rel="stylesheet" href="includes/stylesO.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['nombreCompleto']); ?></p>
            <button onclick="showContent('report')">Generate Incident Report</button>
            <button onclick="showContent('history')">See Reported Incidents</button>
            <button onclick="showContent('profile')">Profile</button>
            <button onclick="showContent('change')">Change Password</button>
            <button id="logoutButton">Log Out</button>
        </div>  
        
        <div class="content">
            <div class="welcome">
                <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
            </div>
            <div id="report" class="section">
                <h2>Add a New Incident</h2>
                <form action="data/addIncident.php" method="post">
                    <fieldset>
                        <legend>Fill All Form Fields</legend>
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description">
                        <br>
                        <br>

                        <label for="problem">Problem</label>
                        <select id="problem" name="problem" required>
                            <option value="" disabled selected>Select a problem</option>
                            <option value="ELE">Electric</option>
                            <option value="MEC">Mechanical</option>
                            <option value="MTO">others...</option>
                        </select>
                        <br>
                        <br>

                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" required>
                            <option value="" disabled selected>Select a priority</option>
                            <option value="ALT">High</option>
                            <option value="BAJ">Low</option>
                            <option value="MED">Medium</option>
                        </select>
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
                        <button type="submit">Create a New Incident</button>
                    </fieldset>
                </form>
            </div>

            <?php
                require("data/getIncidentsOpe.php");
                $result = obtenerIncidenciasOperador();
            ?>
            <div id="history" class="section styled-table">
                <h2>Reported Incidents</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Equipment</th>
                            <th>Problem</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Priority</th>
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
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php
            require("data/getProfileUser.php");
            $profileData = obtenerPerfil();
            ?>
            <div id="profile" class="section">
                <h2>Your Profile Information</h2>
                <form class="profile-form">
                    <div class="form-group">
                        <label for="num">No. Employee:</label>
                        <input type="text" id="num" name="num" value="<?php echo htmlspecialchars($profileData['num']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($profileData['name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($profileData['email']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="cellphone">Cellphone:</label>
                        <input type="text" id="cellphone" name="cellphone" value="<?php echo htmlspecialchars($profileData['cellphone']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($profileData['title']); ?>" readonly>
                    </div>
                </form>
            </div>

            <div id="change" class="section">
                <h2>Change Password</h2>
                <form action="data/changePassword.php" method="post" onsubmit="return validatePasswords()">
                    <fieldset>
                        <legend>Change Password</legend>
                        <label for="newPass">New Password:</label>
                        <input type="password" id="newPass" name="newPass" required minlength="8" maxlength="12">
                        <br>
                        <br>
                        <label for="confirmPass">Confirm Password:</label>
                        <input type="password" id="confirmPass" name="confirmPass" required minlength="8" maxlength="12">
                        <br>
                        <br>
                        <button type="submit">Save New Password</button>
                    </fieldset>
                </form>
                <?php if ($message): ?>
                    <div class="alert">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function showContent(section) {
            document.querySelectorAll('.section').forEach(sec => sec.style.display = 'none');
            document.getElementById(section).style.display = 'block';
        }

        function validatePasswords() {
            const newPass = document.getElementById("newPass").value;
            const confirmPass = document.getElementById("confirmPass").value;

            if (newPass !== confirmPass) {
                alert("Passwords do not match. Please try again.");
                return false;
            }
            return true;
        }
    </script>

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
