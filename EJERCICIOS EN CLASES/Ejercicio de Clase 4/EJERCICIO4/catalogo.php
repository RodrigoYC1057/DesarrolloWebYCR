<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "conexion.php";

$result = $con->query("SELECT id, titulo, tipo, imagen, preparacion FROM recetas ORDER BY id");
$recetas = [];
while ($fila = $result->fetch_assoc()) {
    $recetas[] = $fila;
}
echo json_encode($recetas);
?>
