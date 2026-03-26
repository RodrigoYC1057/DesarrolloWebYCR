<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados del Registro</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007BFF; color: white; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px;}
        .metodo { padding: 15px; background-color: #e9ecef; border-radius: 5px; font-weight: bold; margin-bottom: 20px; }
        .btn-volver { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; }
        .btn-volver:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <h2>Datos Procesados</h2>

    <?php
    $metodo = $_SERVER['REQUEST_METHOD'];
    echo "<div class='metodo'>Método de envío detectado: " . $metodo . "</div>";

    $datos = ($metodo === 'POST') ? $_POST : $_GET;

    $errores = [];

    $nombre = isset($datos['nombre']) ? htmlspecialchars(trim($datos['nombre'])) : '';
    $correo = isset($datos['correo']) ? htmlspecialchars(trim($datos['correo'])) : '';
    $carrera = isset($datos['carrera']) ? htmlspecialchars(trim($datos['carrera'])) : '';
    $semestre = isset($datos['semestre']) ? (int)$datos['semestre'] : 0;
    $turno = isset($datos['turno']) ? htmlspecialchars(trim($datos['turno'])) : 'No especificado';
    $comentarios = isset($datos['comentarios']) ? htmlspecialchars(trim($datos['comentarios'])) : '';

    $materias_raw = isset($datos['materias']) ? $datos['materias'] : [];
    $materias_saneadas = [];
    foreach ($materias_raw as $materia) {
        $materias_saneadas[] = htmlspecialchars(trim($materia));
    }
    $materias_str = empty($materias_saneadas) ? 'Ninguna' : implode(", ", $materias_saneadas);

    if (empty($nombre)) {
        $errores[] = "El campo de 'Nombre completo' no puede estar vacío.";
    }

    if ($semestre < 1 || $semestre > 10) {
        $errores[] = "El semestre debe ser un valor numérico entre 1 y 10.";
    }

    if (!empty($errores)) {
        echo "<div class='error'>";
        echo "<h3>Se encontraron los siguientes errores:</h3><ul>";
        foreach ($errores as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul></div>";
    } else {
        echo "<table>";
        echo "<tr><th>Campo</th><th>Valor Registrado</th></tr>";
        echo "<tr><td>Nombre Completo</td><td>" . $nombre . "</td></tr>";
        echo "<tr><td>Correo Electrónico</td><td>" . $correo . "</td></tr>";
        echo "<tr><td>Carrera</td><td>" . $carrera . "</td></tr>";
        echo "<tr><td>Semestre</td><td>" . $semestre . "</td></tr>";
        echo "<tr><td>Turno</td><td>" . $turno . "</td></tr>";
        echo "<tr><td>Materias de Interés</td><td>" . $materias_str . "</td></tr>";
        echo "<tr><td>Comentarios</td><td>" . $comentarios . "</td></tr>";
        echo "</table>";
    }
    ?>

    <a href="registro.html" class="btn-volver">Volver al formulario</a>

</body>
</html>