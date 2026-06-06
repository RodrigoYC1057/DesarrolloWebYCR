<?php
$host     = "localhost";
$usuario  = "root";
$password = "";
$base = "db_citas_medicas";

$conn = mysqli_connect($host, $usuario, $password, $base);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>
