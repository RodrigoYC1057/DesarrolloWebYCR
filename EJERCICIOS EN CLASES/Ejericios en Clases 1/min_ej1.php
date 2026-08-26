<?php
$suma = null;
if ($_POST) {
    $suma = array_sum(explode(',', $_POST['numeros']));
}
?>
<form method="POST">
    Números separados por coma: <input name="numeros">
    <button>Sumar</button>
</form>
<?php if ($suma !== null) echo "<h3>Suma: $suma</h3>"; ?>
