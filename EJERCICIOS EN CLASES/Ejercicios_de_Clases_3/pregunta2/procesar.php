<?php
$cadena = isset($_GET["cadena"]) ? trim($_GET["cadena"]) : "";
$n = isset($_GET["n"]) ? intval($_GET["n"]) : 0;

if ($n < 0) {
    $n = 0;
}
if ($n > 20) {
    $n = 20;
}

$letras = str_split($cadena);
$guiones = str_repeat("-", $n);
$separada = implode($guiones, $letras);
$invertida = strrev($cadena);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de la cadena</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f4f7; margin: 0; padding: 25px; }
        .caja { max-width: 750px; margin: auto; background: white; padding: 25px; border-radius: 8px; }
        h1, h2 { color: #2c3e50; }
        .resultado { padding: 12px; background: #eeeeee; overflow-wrap: anywhere; }
        table { border-collapse: collapse; margin-top: 10px; }
        td { min-width: 36px; padding: 10px; text-align: center; border: 1px solid black; color: white; }
        .rojo { background: red; }
        .amarillo { background: #ffc107; color: black; }
        .verde { background: green; }
        a { color: #2c3e50; }
    </style>
</head>
<body>
    <div class="caja">
        <h1>Resultado del procesamiento</h1>
        <h2>a) Cadena original y separada</h2>
        <p class="resultado">Cadena original: <?php echo htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="resultado">Cadena separada: <?php echo htmlspecialchars($separada, ENT_QUOTES, 'UTF-8'); ?></p>

        <h2>b) Tabla horizontal con colores intercalados</h2>
        <?php if (count($letras) > 0) { ?>
            <table>
                <tr>
                    <?php
                    $colores = array("rojo", "amarillo", "verde");
                    foreach ($letras as $i => $letra) {
                        $clase = $colores[$i % 3];
                        echo '<td class="' . $clase . '">' . htmlspecialchars($letra, ENT_QUOTES, 'UTF-8') . '</td>';
                    }
                    ?>
                </tr>
            </table>
        <?php } else { ?>
            <p>No se ingresó una cadena.</p>
        <?php } ?>

        <h2>c) Cadena invertida</h2>
        <p class="resultado"><?php echo htmlspecialchars($invertida, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><a href="pregunta2.html">Volver al formulario</a> | <a href="../index.html">Menú principal</a></p>
    </div>
</body>
</html>
