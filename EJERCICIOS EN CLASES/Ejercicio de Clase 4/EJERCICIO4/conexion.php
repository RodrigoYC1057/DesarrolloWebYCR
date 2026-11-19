<?php
// Conexion a la base de datos
mysqli_report(MYSQLI_REPORT_OFF);

$con = new mysqli("localhost", "root", "", "ejercicio4_db");
if ($con->connect_error) {
    http_response_code(500);
    die(json_encode([
        "exito" => false,
        "error" => "Error de conexion: " . $con->connect_error
    ]));
}
$con->set_charset("utf8mb4");
?>
