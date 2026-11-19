<?php 
require_once "conexion.php";

$id = (int) ($_GET["id"] ?? 0);

$stmt = $conn->prepare(
    "SELECT p.id, p.nombre, p.precio, p.descripcion, p.imagen, p.disponible, c.nombre AS categoria
     FROM productos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     WHERE p.id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$fila) {
    responder(404, ["exito" => false, "mensaje" => "El producto no existe"]);
}

responder(200, [
    "id" => (int) $fila["id"],
    "nombre" => $fila["nombre"],
    "precio" => (float) $fila["precio"],
    "descripcion" => $fila["descripcion"],
    "imagen" => $fila["imagen"],
    "disponible" => (bool) $fila["disponible"],
    "categoria" => $fila["categoria"]
]);
