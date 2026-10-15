<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
session_start();

if (!isset($_SESSION["correo"])) {
    header("Location: form_login.php?error=2");
    exit();
}
?>
