<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";
require_once "funciones.php";

if (!isset($_POST["nombres"])) {
    header("Location: listar.php");
    exit();
}

$nombres = trim($_POST["nombres"]);
$apellidos = trim($_POST["apellidos"]);
$ci = trim($_POST["ci"]);
$telefono = trim($_POST["telefono"]);
$fecha_nacimiento = $_POST["fecha_nacimiento"];

if ($nombres == "" || $apellidos == "" || $ci == "" || $telefono == "" || $fecha_nacimiento == "") {
    $_SESSION["error"] = "Debe llenar todos los campos del paciente.";
    header("Location: form_insertar.php");
    exit();
}

$fotografia = subirFotografia($_FILES["fotografia"]);

if (substr($fotografia, 0, 6) == "Error:") {
    $_SESSION["error"] = $fotografia;
    header("Location: form_insertar.php");
    exit();
}

$sql = "INSERT INTO pacientes (fotografia, nombres, apellidos, ci, telefono, fecha_nacimiento)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $fotografia, $nombres, $apellidos, $ci, $telefono, $fecha_nacimiento);
$stmt->execute();
$stmt->close();

$_SESSION["mensaje"] = "El paciente " . $nombres . " " . $apellidos . " se registro correctamente.";

header("Location: listar.php");
exit();
?>
