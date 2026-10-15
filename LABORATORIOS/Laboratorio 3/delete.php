<?php 
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
require_once "proteger.php";
require_once "permiso.php";
require_once "conexion.php";

$id = 0;
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
}

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM citas WHERE paciente_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();
$total_citas = $fila["total"];
$stmt->close();

if ($total_citas > 0) {
    $_SESSION["error"] = "No se puede eliminar: el paciente tiene " . $total_citas . " cita(s) registrada(s).";
    header("Location: listar.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM pacientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $_SESSION["mensaje"] = "El paciente se elimino correctamente.";
} else {
    $_SESSION["error"] = "El paciente que quiere eliminar no existe.";
}
$stmt->close();

header("Location: listar.php");
exit();
?>
