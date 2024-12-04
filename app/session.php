<?php
session_start();

if (isset($_SESSION['nickRH'])) {
    $menuadmin = true;
    $userRH = 'Bienvenid@ ' . $_SESSION['nickRH'] . "<br>";
    $noEmpleado = $_SESSION['noEmpleado'];
    $codigoMaquiladora = $_SESSION['codigoMaquiladora'];
} else {
    $menuadmin = false;
    $userRH = '';
}

if (isset($_SESSION['nickE'])) {
    $menuE = true;
    $userE = 'Bienvenid@ ' . $_SESSION['nickE'] . "<br>";
    $noEmpleado = $_SESSION['noEmpleado'];
    $codigoMaquiladora = $_SESSION['codigoMaquiladora'];
} else {
    $menuE = false;
    $userE = '';
}
?>