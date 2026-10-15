<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>document</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="cabecera">
    <div>
        <h1>Consultorio USFX</h1>
        <p><?= $subtitulo ?></p>
    </div>
    <nav>
        <a href="panel.php" class="<?= $activo == 'panel' ? 'activo' : '' ?>">Panel</a>
        <a href="listar.php" class="<?= $activo == 'pacientes' ? 'activo' : '' ?>">Pacientes</a>
        <a href="agenda.php" class="<?= $activo == 'agenda' ? 'activo' : '' ?>">Agenda</a>
        <a href="cerrar_sesion.php">Salir (<?= $_SESSION["nombre"] ?>)</a>
    </nav>
</div>

<div class="contenedor">
