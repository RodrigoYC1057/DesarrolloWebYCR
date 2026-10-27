<?php
// Conexion a la base de datos (se incluye con require_once)
mysqli_report(MYSQLI_REPORT_OFF); // errores por connect_error, como en la guia

$con = new mysqli("localhost", "root", "", "tarea4_db");
if ($con->connect_error) {
    http_response_code(500);
    die(json_encode([
        "exito" => false,
        "error" => "Error de conexion: " . $con->connect_error
    ]));
}
$con->set_charset("utf8mb4");
?>
