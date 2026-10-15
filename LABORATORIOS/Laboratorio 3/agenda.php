<?php
// Condori Carmona Fernando Favian
// Yucra Caballero Rodrigo 
require_once "proteger.php";
require_once "conexion.php";

$estados = ["pendiente", "atendida", "cancelada"];
$estado = "";
if (isset($_GET["estado"]) && in_array($_GET["estado"], $estados)) {
    $estado = $_GET["estado"];
}

$donde = "";
if ($estado != "") {
    $donde = "WHERE c.estado = ?";
}

$sql = "SELECT c.fecha, c.hora, c.motivo, c.estado,
               p.nombres AS pac_nombres, p.apellidos AS pac_apellidos,
               m.nombres AS med_nombres, m.apellidos AS med_apellidos,
               e.nombre AS especialidad
        FROM citas c
        INNER JOIN pacientes p ON c.paciente_id = p.id
        INNER JOIN medicos m ON c.medico_id = m.id
        INNER JOIN especialidades e ON m.especialidad_id = e.id
        " . $donde . "
        ORDER BY c.fecha, c.hora";
$stmt = $conn->prepare($sql);
if ($estado != "") {
    $stmt->bind_param("s", $estado);
}
$stmt->execute();
$resultado = $stmt->get_result();
$cantidad = $resultado->num_rows;

$subtitulo = "Agenda de citas";
$activo = "agenda";
include "menu.php";
?>

    <h2>Agenda de citas (<?= $cantidad ?>)</h2>

    <div class="panel">
        <form action="agenda.php" method="GET" class="barra">
            <div class="campo" style="margin-bottom:0;">
                <label>Filtrar por estado</label>
                <select name="estado">
                    <option value="">-- Todos --</option>
                    <?php foreach ($estados as $e) { ?>
                        <option value="<?= $e ?>" <?= $estado == $e ? "selected" : "" ?>><?= ucwords($e) ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="boton">Filtrar</button>
        </form>
    </div>

    <h3>Datos tomados de cuatro tablas relacionadas con INNER JOIN</h3>

    <?php if ($cantidad == 0) { ?>

        <div class="aviso aviso-error">No hay citas con ese estado.</div>

    <?php } else { ?>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Medico</th>
                    <th>Especialidad</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td><?= date("d/m/Y", strtotime($fila["fecha"])) ?></td>
                    <td><?= substr($fila["hora"], 0, 5) ?></td>
                    <td><?= htmlspecialchars($fila["pac_nombres"] . " " . $fila["pac_apellidos"]) ?></td>
                    <td><?= htmlspecialchars($fila["med_nombres"] . " " . $fila["med_apellidos"]) ?></td>
                    <td><?= htmlspecialchars($fila["especialidad"]) ?></td>
                    <td><?= htmlspecialchars($fila["motivo"]) ?></td>
                    <td><span class="etiqueta et-<?= $fila["estado"] ?>"><?= $fila["estado"] ?></span></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } ?>

</div>

<div class="pie">SIS 256 - Tecnologia y Desarrollo Web - Ing. Carlos David Montellano Barriga</div>

</body>
</html>
