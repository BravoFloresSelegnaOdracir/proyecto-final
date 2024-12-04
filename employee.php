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
    <title>Employees</title>
    <link rel="stylesheet" href="includes/employee.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>Manage Employees</p>
            <button onclick="showContent('register')">Register Employee</button>
            <button onclick="showContent('employees')">Employees Information</button>
            <button onclick="showContent('updateTec')">Technician Information</button>
            <button onclick="showContent('profile')">Your Profile</button>
            <button onclick="showContent('change')">Change Your Password</button>
            <button onclick="window.location.href='manager.php'">Return To Main Menu</button>
        </div>  
        
        <div class="content">
        <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
            <div id="register" class="section">
            <h2>Add a new employee</h2>
                <div>
                    <form action="data/addEmployee.php" method="post">
                        <fieldset>
                            <legend>Fill all form fields</legend>
                            <div>
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name">
                            </div>
                            <br>
                            <div>
                                <label for="middle">Middle Name</label>
                                <input type="text" id="middle" name="middle">
                            </div>
                            <br>
                            <div>
                                <label for="last">Last Name</label>
                                <input type="text" id="last" name="last">
                            </div>
                            <br>
                            <div>
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password">
                            </div>
                            <br>
                            <div>
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email">
                            </div>
                            <br>
                            <div>
                                <label for="cell">Cellphone</label>
                                <input type="text" id="cell" name="cell" maxlength="13" pattern="\d{3}-\d{3}-\d{2}-\d{2}" title="Formato: XXX-XXX-XX-XX" oninput="formatPhoneNumber(this)" required>
                            </div>
                            <br>
                            <div>
                                <label for="type">Type:</label>
                                <select id="type" name="type" required>
                                    <option value="" disabled selected>Select a type</option>
                                    <option value="OPE">Operator</option>
                                    <option value="TEC">Technician</option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <button type="submit">Add New Employee</button>
                            </div>
                            <br>
                            <script>
                                function formatPhoneNumber(input) {
                                    let value = input.value.replace(/\D/g, '');

                                    if (value.length <= 3) {
                                        input.value = value;
                                    } else if (value.length <= 6) {
                                        input.value = value.slice(0, 3) + '-' + value.slice(3);
                                    } else if (value.length <= 8) {
                                        input.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6);
                                    } else {
                                        input.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 8) + '-' + value.slice(8, 10);
                                    }
                                }
                            </script>
                        </fieldset>
                    </form>
                </div>
            </div>

            <div id="employees" class="section styled-table">
                <h2>See Employees</h2>
                
                <form method="post" action="">
                    <input type="text" name="search" placeholder="Search employees..." value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
                    <button type="submit">Search</button>
                </form>

                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>No Employee</th>
                            <th>Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Cellphone</th>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once "data/getAllEmployees.php";

                        $search = isset($_POST['search']) ? $_POST['search'] : null;
                        $result = obtenerEmpleados($search);

                        if ($result && mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)):
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['num']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['middleName']); ?></td>
                                <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['cellphone']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><a href="data/updateEmployees.php?num=<?php echo htmlspecialchars($row['num']); ?>" class="btn-update">Update</a></td>
                            </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="8">No results found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php
                require("data/getTechnicians.php");
                $result = obtenerTecnicos();
            ?>
            <div id="updateTec" class="section styled-table">
                <h2>See All Technicians</h2>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>No. Employee</th>
                            <th>Name</th>
                            <th>No. Incidents</th>
                            <th>Field</th>
                            <th>Scheduale</th>
                            <th>Availability</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['noEmployee']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['assignedIncidents']); ?></td>
                                <td><?php echo htmlspecialchars($row['field']); ?></td>
                                <td><?php echo htmlspecialchars($row['scheduale']); ?></td>
                                <td><?php echo htmlspecialchars($row['availability']); ?></td>
                                <td><a href="data/updateTechnician.php?noEmployee=<?php echo htmlspecialchars($row['noEmployee']); ?>" class="btn-update">Update</a></td>
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
                        <input type="text" id="num" value="<?php echo htmlspecialchars($profileData['num']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" value="<?php echo htmlspecialchars($profileData['name']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" value="<?php echo htmlspecialchars($profileData['email']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="cellphone">Cellphone:</label>
                        <input type="text" id="cellphone" value="<?php echo htmlspecialchars($profileData['cellphone']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" value="<?php echo htmlspecialchars($profileData['title']); ?>" readonly>
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
                <script src="includes/script.js"></script>
            </div>
    </div>
</body>
</html>