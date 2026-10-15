<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";

$id = 0;
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
}

$stmt = $conn->prepare("SELECT * FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    $_SESSION["error"] = "El paciente que quiere editar no existe.";
    header("Location: listar.php");
    exit();
}

$paciente = $resultado->fetch_assoc();
$stmt->close();

$subtitulo = "Editar paciente";
$activo = "pacientes";
include "menu.php";
?>

    <?php
    if (isset($_SESSION["error"])) {
        echo '<div class="aviso aviso-error">' . htmlspecialchars($_SESSION["error"]) . '</div>';
        unset($_SESSION["error"]);
    }
    ?>

    <h2>Editar paciente</h2>

    <div class="panel">
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $paciente["id"] ?>">
            <input type="hidden" name="foto_actual" value="<?= htmlspecialchars($paciente["fotografia"]) ?>">

            <div class="fila">
                <div class="campo">
                    <label>Nombres</label>
                    <input type="text" name="nombres" value="<?= htmlspecialchars($paciente["nombres"]) ?>" required>
                </div>
                <div class="campo">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" value="<?= htmlspecialchars($paciente["apellidos"]) ?>" required>
                </div>
            </div>
            <div class="fila">
                <div class="campo">
                    <label>Carnet de identidad</label>
                    <input type="text" name="ci" value="<?= htmlspecialchars($paciente["ci"]) ?>" required>
                </div>
                <div class="campo">
                    <label>Telefono</label>
                    <input type="text" name="telefono" value="<?= htmlspecialchars($paciente["telefono"]) ?>" required>
                </div>
                <div class="campo">
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="fecha_nacimiento" value="<?= $paciente["fecha_nacimiento"] ?>" required>
                </div>
            </div>

            <div class="campo">
                <label>Fotografia actual</label>
                <?php if ($paciente["fotografia"] != "") { ?>
                    <img class="foto" src="images/<?= htmlspecialchars($paciente["fotografia"]) ?>" alt="foto">
                <?php } else { ?>
                    <div class="sinfoto">S/F</div>
                <?php } ?>
            </div>
            <div class="campo">
                <label>Cambiar fotografia (si no elige una nueva se conserva la actual)</label>
                <input type="file" name="fotografia">
            </div>

            <button type="submit" class="boton boton-ok">Actualizar paciente</button>
            <a href="listar.php" class="boton boton-sec">Cancelar</a>
        </form>
    </div>

</div>

<div class="pie">SIS 256 - Tecnologia y Desarrollo Web - Ing. Carlos David Montellano Barriga</div>

</body>
</html>
