<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";
require_once "funciones.php";

if (!isset($_POST["id"])) {
    header("Location: listar.php");
    exit();
}

$id = (int) $_POST["id"];
$nombres = trim($_POST["nombres"]);
$apellidos = trim($_POST["apellidos"]);
$ci = trim($_POST["ci"]);
$telefono = trim($_POST["telefono"]);
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$foto_actual = $_POST["foto_actual"];

if ($nombres == "" || $apellidos == "" || $ci == "" || $telefono == "" || $fecha_nacimiento == "") {
    $_SESSION["error"] = "Debe llenar todos los campos del paciente.";
    header("Location: form_editar.php?id=" . $id);
    exit();
}

$fotografia = subirFotografia($_FILES["fotografia"]);

if (substr($fotografia, 0, 6) == "Error:") {
    $_SESSION["error"] = $fotografia;
    header("Location: form_editar.php?id=" . $id);
    exit();
}

if ($fotografia == "") {
    $fotografia = $foto_actual;
}

$sql = "UPDATE pacientes
        SET fotografia = ?, nombres = ?, apellidos = ?, ci = ?, telefono = ?, fecha_nacimiento = ?
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $fotografia, $nombres, $apellidos, $ci, $telefono, $fecha_nacimiento, $id);
$stmt->execute();
$stmt->close();

$_SESSION["mensaje"] = "Los datos del paciente " . $nombres . " " . $apellidos . " se actualizaron correctamente.";
header("Location: listar.php");
exit();
?>
