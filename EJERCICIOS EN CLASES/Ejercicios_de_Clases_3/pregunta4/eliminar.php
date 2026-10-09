<?php
require_once __DIR__ . "/conexion.php";

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id > 0) {
    $stmt = $con->prepare("DELETE FROM libros WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

mysqli_close($con);
header("Location: listar.php");
exit;
?>
