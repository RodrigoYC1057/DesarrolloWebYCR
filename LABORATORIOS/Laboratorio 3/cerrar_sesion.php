<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo 
session_start();

$_SESSION = [];
session_destroy();

header("Location: form_login.php");
exit();
?>
