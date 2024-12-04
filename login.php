<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login </title>
    <link rel="stylesheet" href="includes/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="header-bar">
            <h1>M.E.I</h1>
            <p>Maintenance. Equipment. Industrial.</p>
            <br>
            <h1>W E L C O M E</h1>
        </div>
    <div class="wrapper">
        <form action="app/log.php" method="POST">
            <h1>LOGIN</h1>
            <div class="input-box">
                <input type="number" name="numEmp" placeholder="No. Employee" required>
            </div>
            <div class="input-box">
                <input type="password" name="txtpass" placeholder="Password" required>
            </div>
            <br>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
    <div class="footer-bar">
        <p>© 2024 OrionTech & Co. Todos los derechos reservados.</p>
    </div>
</body>
</html>