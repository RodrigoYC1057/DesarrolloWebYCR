<?php
$con = new mysqli("localhost", "root", "", "bd_biblioteca");

if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}

$con->set_charset("utf8mb4");
?>
