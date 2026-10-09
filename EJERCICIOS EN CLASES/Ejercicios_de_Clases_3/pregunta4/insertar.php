<?php
require_once __DIR__ . "/conexion.php";

$titulo = isset($_POST["titulo"]) ? trim($_POST["titulo"]) : "";
$autor = isset($_POST["autor"]) ? trim($_POST["autor"]) : "";
$anio = isset($_POST["anio"]) ? intval($_POST["anio"]) : 0;
$editorial = isset($_POST["editorial"]) ? trim($_POST["editorial"]) : "";

if ($titulo == "" || $autor == "" || $editorial == "") {
    die("Todos los campos son obligatorios. <a href='form-insertar.html'>Volver</a>");
}

$stmt = $con->prepare("INSERT INTO libros (titulo, autor, anio, editorial) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $titulo, $autor, $anio, $editorial);

if ($stmt->execute()) {
    $stmt->close();
    mysqli_close($con);
    header("Location: listar.php");
    exit;
} else {
    echo "Error al insertar el libro: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8');
}

$stmt->close();
mysqli_close($con);
?>
