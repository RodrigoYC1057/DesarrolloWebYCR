<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo

if ($_SESSION["rol"] !== "admin") {
    $_SESSION["error"] = "Su rol (" . $_SESSION["rol"] . ") no tiene permiso para realizar esa accion.";
    header("Location: listar.php");
    exit();
}
?>
