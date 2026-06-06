<?php
include '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

// ---- Devuelve listas de médicos y pacientes en JSON ----
if ($accion == 'selects') {

    $medicos   = [];
    $pacientes = [];

    $rm = mysqli_query($conn, "SELECT id, nombre FROM medicos ORDER BY nombre");
    while ($f = mysqli_fetch_assoc($rm)) $medicos[] = $f;

    $rp = mysqli_query($conn, "SELECT id, nombre FROM pacientes ORDER BY nombre");
    while ($f = mysqli_fetch_assoc($rp)) $pacientes[] = $f;

    header('Content-Type: application/json');
    echo json_encode(['medicos' => $medicos, 'pacientes' => $pacientes]);

// ---- Lista todas las citas con filtros opcionales ----
} elseif ($accion == 'listar') {

    $where = "WHERE 1=1";
    if (!empty($_GET['estado']))   $where .= " AND c.estado='"   . mysqli_real_escape_string($conn, $_GET['estado'])   . "'";
    if (!empty($_GET['medico']))   $where .= " AND c.id_medico=" . intval($_GET['medico']);
    if (!empty($_GET['paciente'])) $where .= " AND c.id_paciente=" . intval($_GET['paciente']);

    $sql = "SELECT c.id, p.nombre AS paciente, m.nombre AS medico,
                   c.fecha_cita, c.hora_cita, c.estado, c.motivo
            FROM citas c
            JOIN pacientes p ON c.id_paciente = p.id
            JOIN medicos   m ON c.id_medico   = m.id
            $where
            ORDER BY c.fecha_cita DESC, c.hora_cita DESC";

    $r = mysqli_query($conn, $sql);

    if (mysqli_num_rows($r) == 0) {
        echo '<div class="alert alert-info">No hay citas registradas.</div>';
        exit;
    }

    echo '<table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-primary">
            <tr><th>#</th><th>Paciente</th><th>Médico</th><th>Fecha</th><th>Hora</th><th>Motivo</th><th>Estado</th><th>Acción</th></tr>
        </thead><tbody>';

    while ($f = mysqli_fetch_assoc($r)) {
        // Color según estado
        $color = 'secondary';
        if ($f['estado'] == 'Pendiente') $color = 'warning';
        if ($f['estado'] == 'Atendida')  $color = 'success';
        if ($f['estado'] == 'Cancelada') $color = 'danger';

        echo "<tr>
            <td>{$f['id']}</td>
            <td>{$f['paciente']}</td>
            <td>{$f['medico']}</td>
            <td>{$f['fecha_cita']}</td>
            <td>{$f['hora_cita']}</td>
            <td>{$f['motivo']}</td>
            <td><span class='badge bg-{$color}'>{$f['estado']}</span></td>
            <td>
                <button class='btn btn-sm btn-outline-primary'
                    onclick=\"cambiarEstado({$f['id']},'{$f['estado']}')\">
                    🔄 Estado
                </button>
            </td>
        </tr>";
    }
    echo '</tbody></table>';

// ---- Insertar nueva cita ----
} elseif ($accion == 'insertar') {

    $id_medico   = intval($_POST['id_medico']);
    $id_paciente = intval($_POST['id_paciente']);
    $fecha       = mysqli_real_escape_string($conn, $_POST['fecha_cita']);
    $hora        = mysqli_real_escape_string($conn, $_POST['hora_cita']);
    $motivo      = mysqli_real_escape_string($conn, $_POST['motivo']);

    // Validar que no haya otro turno del mismo médico a la misma hora y fecha
    $check = mysqli_fetch_row(mysqli_query($conn,
        "SELECT COUNT(*) FROM citas WHERE id_medico=$id_medico AND fecha_cita='$fecha' AND hora_cita='$hora'"))[0];

    if ($check > 0) {
        echo '⚠️ El médico ya tiene una cita a esa hora. Elige otra hora.';
    } else {
        $sql = "INSERT INTO citas (id_paciente, id_medico, fecha_cita, hora_cita, motivo, estado)
                VALUES ($id_paciente, $id_medico, '$fecha', '$hora', '$motivo', 'Pendiente')";
        if (mysqli_query($conn, $sql))
            echo '✅ Cita registrada correctamente.';
        else
            echo '❌ Error: ' . mysqli_error($conn);
    }

// ---- Cambiar estado de una cita ----
} elseif ($accion == 'cambiarEstado') {

    $id     = intval($_POST['id']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);

    if (mysqli_query($conn, "UPDATE citas SET estado='$estado' WHERE id=$id"))
        echo '✅ Estado actualizado.';
    else
        echo '❌ Error: ' . mysqli_error($conn);
}
?>
