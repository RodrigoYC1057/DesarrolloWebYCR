<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado - Ejercicio 5</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            text-align: center;
        }

        .resultado {
            font-size: 24px;
            font-weight: bold;
            margin: 20px;
            padding: 15px;
            border: 2px solid #333;
            display: inline-block;
        }

        .par {
            color: #0000cc;
            background-color: #e6e6ff;
            border-color: #0000cc;
        }

        .impar {
            color: #cc0000;
            background-color: #ffe6e6;
            border-color: #cc0000;
        }

        a.volver {
            display: block;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numero'])) {


        $numero = $_POST['numero'];


        if ($numero > 0) {


            if ($numero % 2 == 0) {
                echo "<h2>Resultado:</h2>";
                echo "<div class='resultado par'>El número $numero es PAR.</div>";
            } else {
                echo "<h2>Resultado:</h2>";
                echo "<div class='resultado impar'>El número $numero es IMPAR.</div>";
            }
        } else {
            echo "<h3>Error: Por favor, ingrese un número entero positivo.</h3>";
        }
    } else {

        echo "<h3>Acceso denegado. Utilice el formulario para ingresar un número.</h3>";
    }
    ?>

    <a href="ejercicio5.html" class="volver">Probar con otro número</a><br>
    <a href="index.html" class="volver">Volver al Menú Principal</a>

</body>

</html>