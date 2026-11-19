<?php
// eliminar.php?id=X : elimina una receta (se llama con fetch DELETE)
header("Content-Type: application/json; charset=UTF-8");
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);
    echo json_encode(["exito" => false, "error" => "Metodo no permitido"]);
    exit;
}

$id = (int) ($_GET["id"] ?? 0);
$stmt = $con->prepare("DELETE FROM recetas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["exito" => true]);
} else {
    http_response_code(404);
    echo json_encode(["exito" => false, "error" => "La receta no existe"]);
}
?>
