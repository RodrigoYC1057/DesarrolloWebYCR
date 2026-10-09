<?php
require_once __DIR__ . "/conexion.php";
$resultado = mysqli_query($con, "SELECT * FROM libros ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca - Listar libros</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f4f7; margin: 0; padding: 25px; color: #333; }
        .contenedor { max-width: 950px; margin: auto; background: white; padding: 25px; border-radius: 8px; }
        h1 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        th, td { border: 1px solid #333; padding: 10px; text-align: left; }
        th { background: #2C3E50; color: white; text-align: center; }
        tbody tr:nth-child(even) { background: #F2F2F2; }
        tbody tr:nth-child(odd) { background: white; }
        a.boton { display: inline-block; padding: 10px 14px; background: #2c3e50; color: white; text-decoration: none; border-radius: 4px; }
        a.eliminar { color: #333; }
        .tabla-contenedor { overflow-x: auto; }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Biblioteca: listado de libros</h1>
    <p><a class="boton" href="form-insertar.html">Nuevo Libro</a></p>
    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Año</th>
                    <th>Editorial</th>
                    <th>Operación</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($resultado && mysqli_num_rows($resultado) > 0) { ?>
                <?php while ($fila = mysqli_fetch_array($resultado)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fila["titulo"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($fila["autor"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo intval($fila["anio"]); ?></td>
                        <td><?php echo htmlspecialchars($fila["editorial"], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><a class="eliminar" href="eliminar.php?id=<?php echo intval($fila["id"]); ?>" onclick="return confirm('¿Deseas eliminar este libro?');">Eliminar</a></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr><td colspan="5">No hay libros registrados.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <p><a href="../index.html">Volver al menú principal</a></p>
</div>
</body>
</html>
<?php mysqli_close($con); ?>
