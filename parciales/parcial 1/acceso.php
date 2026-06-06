<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ejercicio18.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Acceso</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
        .panel { width: 400px; margin: 0 auto; border: 1px solid #333; padding: 20px; background-color: #f4f4f4; }
        .menu-roles { list-style: none; padding: 0; }
        .menu-roles li { margin: 10px 0; }
        .menu-roles a { display: block; padding: 10px; background-color: #fff; border: 1px solid #ccc; text-decoration: none; color: #333; font-weight: bold;}
        .menu-roles a:hover { background-color: #ddd; }
        a.salir { color: red; }
    </style>
</head>
<body>

    <div class="panel">
        <h2>Panel de Acceso</h2>
        
        <?php
        echo "<p>Bienvenido, tu nivel de acceso es: <b>" . $_SESSION['usuario'] . "</b></p>";
        echo "<hr>";

        echo "<ul class='menu-roles'>";
        if ($_SESSION['usuario'] == "admin") {
            echo "<li><a href='#'>Crear</a></li>";
            echo "<li><a href='#'>Listar</a></li>";
            echo "<li><a href='#'>Borrar</a></li>";
            echo "<li><a href='index.html' class='salir'>Salir al Menú Principal</a></li>";
        } else {
            echo "<li><a href='#'>Listar</a></li>";
            echo "<li><a href='index.html' class='salir'>Salir al Menú Principal</a></li>";
        }
        echo "</ul>";
        ?>
    </div>

</body>
</html>