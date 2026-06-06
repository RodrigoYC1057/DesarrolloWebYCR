<?php

session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticando...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            text-align: center;
        }

        .mensaje {
            font-size: 20px;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #333;
            background-color: #e6e6e6;
            display: inline-block;
        }

        a.boton {
            display: inline-block;
            background-color: #0000cc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario']) && isset($_POST['contrasena'])) {

        $user = $_POST['usuario'];
        $pass = $_POST['contrasena'];


        if ($user == "admin" && $pass == "admin") {
            $_SESSION['usuario'] = "admin";
        } else {
            $_SESSION['usuario'] = "usuario";
        }

        echo "<h2>Autenticación completada</h2>";
        echo "<div class='mensaje'>";

        echo "La variable de sesión guardada es: <b>" . $_SESSION['usuario'] . "</b>";
        echo "</div><br>";


        echo "<a href='acceso.php' class='boton'>Continuar a la página de Acceso</a>";
    } else {
        echo "<h3>Faltan credenciales. Usa el formulario.</h3>";
        echo "<a href='ejercicio18.html'>Volver</a>";
    }
    ?>

</body>

</html>