<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo 
session_start();
require_once "conexion.php";

if (!isset($_POST["correo"]) || !isset($_POST["clave"])) {
    header("Location: form_login.php");
    exit();
}

$correo = trim($_POST["correo"]);
$clave = sha1($_POST["clave"]);   

$sql = "SELECT id, correo, nombre, rol FROM usuarios WHERE correo = ? AND clave = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $correo, $clave);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $fila = $resultado->fetch_assoc();

    $_SESSION["id"] = $fila["id"];
    $_SESSION["correo"] = $fila["correo"];
    $_SESSION["nombre"] = $fila["nombre"];
    $_SESSION["rol"] = $fila["rol"];

    $stmt->close();
    header("Location: panel.php");
    exit();
} else {
    $stmt->close();
    header("Location: form_login.php?error=1");
    exit();
}
?>
