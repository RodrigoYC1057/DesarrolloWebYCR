<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado - Ejercicio 6</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
        .resultado { font-size: 20px; margin: 20px auto; padding: 20px; border: 2px solid #333; display: inline-block; background-color: #f4f4f4;}
        .texto-rojo { color: red; }
        a.volver { display: block; margin-top: 20px; color: #333; text-decoration: none; }
    </style>
</head>
<body>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre']) && isset($_POST['ciudad'])) {
        
        $nombre = $_POST['nombre'];
        $ciudad = $_POST['ciudad'];

        echo "<h2>Tus Datos:</h2>";
        echo "<div class='resultado'>";
        echo "Hola <b>" . htmlspecialchars($nombre) . "</b>, veo que vives en <span class='texto-rojo'>" . htmlspecialchars($ciudad) . "</span>.";
        echo "</div>";

    } else {
        echo "<h3>No se recibieron datos. Por favor, vuelve al formulario.</h3>";
    }
    ?>

    <a href="ejercicio6.html" class="volver">Volver al formulario</a>
    <a href="index.html" class="volver">Volver al Menú Principal</a>

</body>
</html>