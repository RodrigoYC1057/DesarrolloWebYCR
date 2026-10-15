<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
require_once "proteger.php";
require_once "conexion.php";

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM pacientes");
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$total_pacientes = $fila["total"];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM medicos");
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$total_medicos = $fila["total"];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM citas WHERE estado = 'pendiente'");
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$total_citas = $fila["total"];
$stmt->close();

$subtitulo = "Panel principal";
$activo = "panel";
include "menu.php";
?>

    <h2>Bienvenido/a, <?= htmlspecialchars($_SESSION["nombre"]) ?></h2>

    <div class="panel">
        <p>
            Correo: <strong><?= htmlspecialchars($_SESSION["correo"]) ?></strong>
            &nbsp;|&nbsp; Rol:
            <span class="etiqueta et-<?= $_SESSION["rol"] ?>"><?= $_SESSION["rol"] ?></span>
        </p>
        <p style="font-size:12px; color:#5a6a7e;">
            Identificador de la sesion: <?= session_id() ?>
        </p>
    </div>

    <h2>Resumen del consultorio</h2>

    <div class="tarjetas">
        <div class="tarjeta">
            <div class="numero"><?= $total_pacientes ?></div>
            <div class="texto">Pacientes registrados</div>
        </div>
        <div class="tarjeta">
            <div class="numero"><?= $total_medicos ?></div>
            <div class="texto">Medicos</div>
        </div>
        <div class="tarjeta">
            <div class="numero"><?= $total_citas ?></div>
            <div class="texto">Citas pendientes</div>
        </div>
    </div>

</div>

<div class="pie">SIS 256 - Tecnologia y Desarrollo Web - Ing. Carlos David Montellano Barriga</div>

</body>
</html>
