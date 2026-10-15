<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo 
require_once "proteger.php";
require_once "conexion.php";

$buscar = "";
if (isset($_GET["buscar"])) {
    $buscar = trim($_GET["buscar"]);
}


$columnas_permitidas = ["nombres", "apellidos", "ci", "fecha_nacimiento"];
$orden = "apellidos";
if (isset($_GET["orden"]) && in_array($_GET["orden"], $columnas_permitidas)) {
    $orden = $_GET["orden"];
}

$sql = "SELECT * FROM pacientes
        WHERE nombres LIKE ? OR apellidos LIKE ? OR ci LIKE ?
        ORDER BY " . $orden;
$stmt = $conn->prepare($sql);
$patron = "%" . $buscar . "%";
$stmt->bind_param("sss", $patron, $patron, $patron);
$stmt->execute();
$resultado = $stmt->get_result();
$cantidad = $resultado->num_rows;

$es_admin = ($_SESSION["rol"] === "admin");

$subtitulo = "Registro de pacientes";
$activo = "pacientes";
include "menu.php";
?>

    <?php
    if (isset($_SESSION["mensaje"])) {
        echo '<div class="aviso">' . htmlspecialchars($_SESSION["mensaje"]) . '</div>';
        unset($_SESSION["mensaje"]);
    }
    if (isset($_SESSION["error"])) {
        echo '<div class="aviso aviso-error">' . htmlspecialchars($_SESSION["error"]) . '</div>';
        unset($_SESSION["error"]);
    }
    ?>

    <h2>Pacientes (<?= $cantidad ?> encontrados)</h2>

    <div class="panel">
        <form action="listar.php" method="GET" class="barra">
            <div class="campo" style="flex:1; margin-bottom:0;">
                <label>Buscar por nombre, apellido o CI</label>
                <input type="text" name="buscar" value="<?= htmlspecialchars($buscar) ?>">
            </div>
            <input type="hidden" name="orden" value="<?= $orden ?>">
            <button type="submit" class="boton">Buscar</button>
            <a href="listar.php" class="boton boton-sec">Limpiar</a>
            <?php if ($es_admin) { ?>
                <a href="form_insertar.php" class="boton boton-ok">Nuevo paciente</a>
            <?php } ?>
        </form>
    </div>

    <?php if ($cantidad == 0) { ?>

        <div class="aviso aviso-error">No se encontraron pacientes con ese dato.</div>

    <?php } else { ?>

        <h3>Haga clic en el titulo de una columna para ordenar</h3>

        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th><a href="listar.php?orden=nombres&buscar=<?= urlencode($buscar) ?>">Nombres</a></th>
                    <th><a href="listar.php?orden=apellidos&buscar=<?= urlencode($buscar) ?>">Apellidos</a></th>
                    <th><a href="listar.php?orden=ci&buscar=<?= urlencode($buscar) ?>">CI</a></th>
                    <th>Telefono</th>
                    <th><a href="listar.php?orden=fecha_nacimiento&buscar=<?= urlencode($buscar) ?>">Nacimiento</a></th>
                    <?php if ($es_admin) { ?>
                        <th>Operaciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <?php if ($fila["fotografia"] != "") { ?>
                            <img class="foto" src="images/<?= htmlspecialchars($fila["fotografia"]) ?>" alt="foto">
                        <?php } else { ?>
                            <div class="sinfoto">S/F</div>
                        <?php } ?>
                    </td>
                    <td><?= htmlspecialchars($fila["nombres"]) ?></td>
                    <td><?= htmlspecialchars($fila["apellidos"]) ?></td>
                    <td><?= htmlspecialchars($fila["ci"]) ?></td>
                    <td><?= htmlspecialchars($fila["telefono"]) ?></td>
                    <td><?= date("d/m/Y", strtotime($fila["fecha_nacimiento"])) ?></td>
                    <?php if ($es_admin) { ?>
                        <td>
                            <a href="form_editar.php?id=<?= $fila["id"] ?>" class="boton mini">Editar</a>
                            <a href="delete.php?id=<?= $fila["id"] ?>" class="boton boton-no mini"
                               onclick="return confirm('Seguro que desea eliminar este paciente?')">Eliminar</a>
                        </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } ?>

</div>

<div class="pie">SIS 256 - Tecnologia y Desarrollo Web - Ing. Carlos David Montellano Barriga</div>

</body>
</html>
