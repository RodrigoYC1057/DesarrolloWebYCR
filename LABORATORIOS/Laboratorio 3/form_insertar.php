<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
require_once "proteger.php";
require_once "permiso.php";

$subtitulo = "Nuevo paciente";
$activo = "pacientes";
include "menu.php";
?>

    <?php
    if (isset($_SESSION["error"])) {
        echo '<div class="aviso aviso-error">' . htmlspecialchars($_SESSION["error"]) . '</div>';
        unset($_SESSION["error"]);
    }
    ?>

    <h2>Registrar un paciente</h2>

    <div class="panel">
        <!-- sin enctype="multipart/form-data" $_FILES llega vacio -->
        <form action="insert.php" method="POST" enctype="multipart/form-data">
            <div class="fila">
                <div class="campo">
                    <label>Nombres</label>
                    <input type="text" name="nombres" required>
                </div>
                <div class="campo">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" required>
                </div>
            </div>
            <div class="fila">
                <div class="campo">
                    <label>Carnet de identidad</label>
                    <input type="text" name="ci" required>
                </div>
                <div class="campo">
                    <label>Telefono</label>
                    <input type="text" name="telefono" required>
                </div>
                <div class="campo">
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" required>
                </div>
            </div>
            <div class="campo">
                <label>Fotografia (jpg, jpeg, png o webp - maximo 2 MB)</label>
                <input type="file" name="fotografia">
            </div>
            <button type="submit" class="boton boton-ok">Guardar paciente</button>
            <a href="listar.php" class="boton boton-sec">Cancelar</a>
        </form>
    </div>

</div>

<div class="pie">SIS 256 - Tecnologia y Desarrollo Web - Ing. Carlos David Montellano Barriga</div>

</body>
</html>
