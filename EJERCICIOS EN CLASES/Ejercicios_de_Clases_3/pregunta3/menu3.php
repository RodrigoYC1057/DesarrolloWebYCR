<?php
require_once __DIR__ . "/estante.php";
session_start();

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = isset($_POST["accion"]) ? $_POST["accion"] : "";

    if ($accion == "crear") {
        $capacidad = isset($_POST["capacidad"]) ? intval($_POST["capacidad"]) : 0;
        if ($capacidad >= 1 && $capacidad <= 10) {
            $_SESSION["estante"] = new Estante($capacidad);
            $mensaje = "Estante creado con capacidad de " . $capacidad . " elementos por nivel.";
        } else {
            $mensaje = "La capacidad debe estar entre 1 y 10.";
        }
    } elseif (!isset($_SESSION["estante"])) {
        $mensaje = "Primero debe crear el estante.";
    } elseif ($accion == "insertar") {
        $nivel = isset($_POST["nivel"]) ? intval($_POST["nivel"]) : 0;
        $elemento = isset($_POST["elemento"]) ? trim($_POST["elemento"]) : "";
        if ($elemento == "") {
            $mensaje = "Escriba el nombre del elemento.";
        } else {
            $estante = $_SESSION["estante"];
            $mensaje = $estante->insertar($nivel, $elemento);
            $_SESSION["estante"] = $estante;
        }
    } elseif ($accion == "quitar") {
        $nivel = isset($_POST["nivel"]) ? intval($_POST["nivel"]) : 0;
        $estante = $_SESSION["estante"];
        $mensaje = $estante->quitar($nivel);
        $_SESSION["estante"] = $estante;
    } elseif ($accion == "mostrar") {
        $mensaje = "Contenido actual del estante.";
    }
}

$estante = isset($_SESSION["estante"]) ? $_SESSION["estante"] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregunta 3 - Estante</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f4f7; margin: 0; padding: 25px; color: #333; }
        .contenedor { max-width: 850px; margin: auto; background: white; padding: 25px; border-radius: 8px; }
        h1, h2 { color: #2c3e50; }
        .formularios { display: flex; flex-wrap: wrap; gap: 15px; }
        .formulario { flex: 1 1 220px; border: 1px solid #ddd; padding: 15px; }
        label { display: block; margin-top: 10px; }
        input, select { box-sizing: border-box; width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 12px; padding: 9px 14px; background: #2c3e50; color: white; border: 0; cursor: pointer; }
        .mensaje { padding: 10px; background: #eaf2f8; }
        .tabla-estante { border-collapse: collapse; width: 100%; margin-top: 15px; }
        .tabla-estante th, .tabla-estante td { border: 1px solid #333; padding: 10px; text-align: center; }
        .tabla-estante th { background: #e2e2e2; font-weight: bold; min-width: 85px; }
        .nivel3 { background: #8bb9db; }
        .nivel2 { background: #ffc928; }
        .nivel1 { background: #16a765; color: white; }
        a { color: #2c3e50; }
    </style>
</head>
<body>
<div class="contenedor">
    <h1>Estante de tres niveles</h1>
    <?php if ($mensaje != "") { ?>
        <p class="mensaje"><?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php } ?>

    <div class="formularios">
        <form class="formulario" method="POST" action="menu3.php">
            <h2>Crear Estante</h2>
            <input type="hidden" name="accion" value="crear">
            <label for="capacidad">Capacidad máxima por nivel:</label>
            <input type="number" id="capacidad" name="capacidad" min="1" max="10" required>
            <button type="submit">Crear</button>
        </form>

        <form class="formulario" method="POST" action="menu3.php">
            <h2>Insertar</h2>
            <input type="hidden" name="accion" value="insertar">
            <label for="nivel-insertar">Nivel:</label>
            <select id="nivel-insertar" name="nivel">
                <option value="1">Nivel 1</option>
                <option value="2">Nivel 2</option>
                <option value="3">Nivel 3</option>
            </select>
            <label for="elemento">Nombre del elemento:</label>
            <input type="text" id="elemento" name="elemento" required>
            <button type="submit">Insertar elemento</button>
        </form>

        <form class="formulario" method="POST" action="menu3.php">
            <h2>Quitar</h2>
            <input type="hidden" name="accion" value="quitar">
            <label for="nivel-quitar">Nivel del que quitar:</label>
            <select id="nivel-quitar" name="nivel">
                <option value="1">Nivel 1</option>
                <option value="2">Nivel 2</option>
                <option value="3">Nivel 3</option>
            </select>
            <button type="submit">Quitar último elemento</button>
        </form>

        <form class="formulario" method="POST" action="menu3.php">
            <h2>Mostrar Estante</h2>
            <p>Ver el contenido de los tres niveles.</p>
            <input type="hidden" name="accion" value="mostrar">
            <button type="submit">Mostrar</button>
        </form>
    </div>

    <h2>Contenido actual</h2>
    <?php
    if ($estante != null) {
        $estante->mostrarEstante();
        echo '<p>Capacidad por nivel: ' . intval($estante->capacidad) . '</p>';
    } else {
        echo '<p>Primero crea el estante para ver su contenido.</p>';
    }
    ?>
    <p><a href="../index.html">Volver al menú principal</a></p>
</div>
</body>
</html>
