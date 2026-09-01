<?php
session_start();
require_once "pizarra.php";

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
    <title>Triángulo - Pizarra</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .pizarra { border-collapse: collapse; margin-top: 20px; }
        .pizarra td {
            width: 45px;
            height: 45px;
            border: 1px solid #555;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }
        .pizarra td.vacia {
            border: none;
            background: transparent;
        }
        a { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <?php $pizarra->triangulo(); ?>

    <a href="menu3.php">← Volver al menú</a>
</body>
</html>
