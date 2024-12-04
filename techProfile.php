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
    <title>Technician</title>
    <link rel="stylesheet" href="includes/techProfile.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <p>Menu Profile</p>
            <button onclick="showContent('profile')">Your Profile</button>
            <button onclick="showContent('change')">Change Password</button>
            <button onclick="window.location.href='technician.php'">Return</button>
        </div>  
        
        <div class="content">
        <img id="welcomeImage" src="img/ORION2.png" alt="Welcome" class="welcome">
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
            </div>
                <script src="includes/script.js"></script>
            </div>
    </div>
</body>
</html>