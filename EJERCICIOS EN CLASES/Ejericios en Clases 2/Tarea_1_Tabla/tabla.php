<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tabla.html");
    exit;
}

$operacion = $_POST["operacion"] ?? "";
$n = filter_input(INPUT_POST, "n", FILTER_VALIDATE_INT);

$operaciones = [
    "suma" => ["nombre" => "Suma", "simbolo" => "+"],
    "resta" => ["nombre" => "Resta", "simbolo" => "-"],
    "multiplicacion" => ["nombre" => "Multiplicación", "simbolo" => "*"],
    "division" => ["nombre" => "División", "simbolo" => "/"]
];

if (!isset($operaciones[$operacion]) || $n === false || $n < 1 || $n > 10) {
    die("Datos inválidos. <a href='tabla.html'>Volver</a>");
}

$nombre = $operaciones[$operacion]["nombre"];
$simbolo = $operaciones[$operacion]["simbolo"];

function calcular($fila, $columna, $operacion) {
    switch ($operacion) {
        case "suma":
            return $fila + $columna;
        case "resta":
            return $fila - $columna;
        case "multiplicacion":
            return $fila * $columna;
        case "division":
            return number_format($fila / $columna, 2, ".", "");
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla de <?= htmlspecialchars($nombre) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #555; padding: 10px 14px; text-align: center; }
        th { background: #D9D9D9; font-weight: bold; }
        .volver { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Tabla de <?= htmlspecialchars($nombre) ?></h1>

    <table>
        <tr>
            <th><?= htmlspecialchars($simbolo) ?></th>
            <?php for ($j = 1; $j <= $n; $j++): ?>
                <th><?= $j ?></th>
            <?php endfor; ?>
        </tr>

        <?php for ($i = 1; $i <= $n; $i++): ?>
            <tr>
                <th><?= $i ?></th>
                <?php for ($j = 1; $j <= $n; $j++): ?>
                    <td><?= calcular($i, $j, $operacion) ?></td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <a class="volver" href="tabla.html">← Volver al formulario</a>
</body>
</html>
