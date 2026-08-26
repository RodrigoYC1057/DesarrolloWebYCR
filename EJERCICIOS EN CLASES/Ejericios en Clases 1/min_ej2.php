<?php
$n = $_POST['n'] ?? 8;
$m = $_POST['m'] ?? 8;
?>
<form method="POST">
    Filas: <input name="n" value="<?php echo $n; ?>">
    Columnas: <input name="m" value="<?php echo $m; ?>">
    <button>Generar</button>
</form>
<table style="border-collapse:collapse">
<?php for ($f = 0; $f < $n; $f++): ?>
<tr>
<?php for ($c = 0; $c < $m; $c++):
    $color = ($f + $c) % 2 == 0 ? '#f0d9b5' : '#b58863';
?>
    <td style="width:35px;height:35px;background:<?php echo $color; ?>"></td>
<?php endfor; ?>
</tr>
<?php endfor; ?>
</table>
