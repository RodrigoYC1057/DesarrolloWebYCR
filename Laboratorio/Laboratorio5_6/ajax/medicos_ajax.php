<?php
include '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

if ($accion == 'listar') {

    $r = mysqli_query($conn, "SELECT * FROM medicos ORDER BY id DESC");

    if (mysqli_num_rows($r) == 0) {
        echo '<div class="alert alert-info">No hay médicos registrados.</div>';
        exit;
    }

    echo '<table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-primary">
            <tr><th>#</th><th>Nombre</th><th>Especialidad</th><th>Teléfono</th><th>Correo</th><th>Acciones</th></tr>
        </thead><tbody>';

    while ($f = mysqli_fetch_assoc($r)) {
        $id = $f['id'];
        $n  = htmlspecialchars($f['nombre'],       ENT_QUOTES);
        $e  = htmlspecialchars($f['especialidad'],  ENT_QUOTES);
        $t  = htmlspecialchars($f['telefono'],      ENT_QUOTES);
        $c  = htmlspecialchars($f['correo'],        ENT_QUOTES);
        echo "<tr>
            <td>$id</td><td>$n</td><td>$e</td><td>$t</td><td>$c</td>
            <td>
                <button class='btn btn-warning btn-sm'
                    onclick=\"editarMedico($id,'$n','$e','$t','$c')\">✏️ Editar</button>
                <button class='btn btn-danger btn-sm ms-1'
                    onclick=\"eliminarMedico($id)\">🗑️ Eliminar</button>
            </td>
        </tr>";
    }
    echo '</tbody></table>';

} elseif ($accion == 'insertar') {

    $n = mysqli_real_escape_string($conn, $_POST['nombre']);
    $e = mysqli_real_escape_string($conn, $_POST['especialidad']);
    $t = mysqli_real_escape_string($conn, $_POST['telefono']);
    $c = mysqli_real_escape_string($conn, $_POST['correo']);

    if (mysqli_query($conn, "INSERT INTO medicos (nombre,especialidad,telefono,correo) VALUES ('$n','$e','$t','$c')"))
        echo '✅ Médico registrado correctamente.';
    else
        echo '❌ Error: ' . mysqli_error($conn);

} elseif ($accion == 'editar') {

    $id = intval($_POST['id']);
    $n  = mysqli_real_escape_string($conn, $_POST['nombre']);
    $e  = mysqli_real_escape_string($conn, $_POST['especialidad']);
    $t  = mysqli_real_escape_string($conn, $_POST['telefono']);
    $c  = mysqli_real_escape_string($conn, $_POST['correo']);

    if (mysqli_query($conn, "UPDATE medicos SET nombre='$n',especialidad='$e',telefono='$t',correo='$c' WHERE id=$id"))
        echo '✅ Médico actualizado correctamente.';
    else
        echo '❌ Error: ' . mysqli_error($conn);

} elseif ($accion == 'eliminar') {

    $id    = intval($_POST['id']);
    $check = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM citas WHERE id_medico=$id"))[0];

    if ($check > 0)
        echo '⚠️ No se puede eliminar: tiene citas registradas.';
    elseif (mysqli_query($conn, "DELETE FROM medicos WHERE id=$id"))
        echo '✅ Médico eliminado correctamente.';
    else
        echo '❌ Error: ' . mysqli_error($conn);
}
?>
