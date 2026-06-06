<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado - Guiones</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
        .resultado { font-size: 24px; font-weight: bold; letter-spacing: 2px; margin: 20px auto; padding: 20px; border: 2px dashed #333; display: inline-block; background-color: #e6f7ff; }
        a.volver { display: block; margin-top: 20px; color: #333; text-decoration: none; }
    </style>
</head>
<body>

    <?php
    include 'utiles.php';

    if (isset($_GET['cadena']) && isset($_GET['guiones'])) {
        
        $palabra = $_GET['cadena'];
        $n_guiones = $_GET['guiones'];

        $miUtil = new utiles($palabra);

        echo "<h2>Resultado de la Operación:</h2>";
        echo "<div class='resultado'>";
        echo htmlspecialchars($miUtil->aumentarguiones($n_guiones));
        echo "</div>";

    } else {
        echo "<h3>Faltan parámetros. Usa el formulario.</h3>";
    }
    ?>

    <a href="pregunta4.html" class="volver">Intentar con otra palabra</a>
    <a href="index.html" class="volver">Volver al Menú Principal</a>

</body>
</html>