<?php
session_start();
require_once "pizarra.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $palabra = trim($_POST["palabra"] ?? "");
    $color = $_POST["color"] ?? "black";
    $color_fondo = $_POST["color_fondo"] ?? "white";

    if ($palabra === "") {
        die("La palabra es obligatoria. <a href='formulario3.html'>Volver</a>");
    }

    $pizarra = new Pizarra($palabra, $color, $color_fondo);
    $_SESSION["pizarra"] = $pizarra;
}

if (!isset($_SESSION["pizarra"])) {
    header("Location: formulario3.html");
    exit;
}

$pizarra = $_SESSION["pizarra"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Pizarra</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        a { display: inline-block; padding: 10px 16px; background: #eee; text-decoration: none; color: #000; }
    </style>
</head>
<body>
    <h1>Menú de Pizarra</h1>
    <p>Palabra: <strong><?= htmlspecialchars($pizarra->palabra) ?></strong></p>
    <p>Seleccione una opción:</p>

    <a href="triangulo.php">Triángulo</a>
    <a href="formulario3.html">Nueva configuración</a>
</body>
</html>
