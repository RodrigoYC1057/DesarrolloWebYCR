<?php
// Integrante 1: ____________________   |   Integrante 2: ____________________
session_start();
require_once "inc/funciones.php";

$_SESSION = [];
session_destroy();
borrarCookiesSesion();

header("Location: tienda.php");
exit();
