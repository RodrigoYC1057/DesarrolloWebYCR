<?php
$op = $_POST['operacion'];
$n = (int)$_POST['n'];
?>
<h2>Tabla de <?php echo $op; ?></h2>
<table border="1" style="border-collapse:collapse">
<tr><td></td>
<?php for ($c = 1; $c <= $n; $c++) echo "<td>$c</td>"; ?>
</tr>
<?php for ($f = 1; $f <= $n; $f++): ?>
<tr><td><?php echo $f; ?></td>
<?php for ($c = 1; $c <= $n; $c++):
    if ($op == 'Suma') $r = $f + $c;
    elseif ($op == 'Resta') $r = $f - $c;
    elseif ($op == 'Multiplicacion') $r = $f * $c;
    else $r = $c ? round($f / $c, 2) : '-';
    echo "<td>$r</td>";
endfor; ?>
</tr>
<?php endfor; ?>
</table>
