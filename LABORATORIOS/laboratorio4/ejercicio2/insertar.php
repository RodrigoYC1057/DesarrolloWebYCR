<?php 
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responder(405, ["exito" => false, "mensaje" => "Metodo no permitido: use POST"]);
}

$errores = [];

$nombre = trim($_POST["nombre"] ?? "");
if (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 60) {
    $errores[] = "El nombre debe tener entre 3 y 60 caracteres.";
}

$categoria_id = (int) ($_POST["categoria_id"] ?? 0);
$stmt = $conn->prepare("SELECT id FROM categorias WHERE id = ?");
$stmt->bind_param("i", $categoria_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    $errores[] = "Seleccione una categoría válida.";
}
$stmt->close();

$precioTexto = str_replace(",", ".", trim($_POST["precio"] ?? ""));
if (!is_numeric($precioTexto) || (float) $precioTexto <= 0 || (float) $precioTexto > 9999.99) {
    $errores[] = "El precio debe ser mayor a cero (máximo 9999.99).";
}
$precio = (float) $precioTexto;

$descripcion = trim($_POST["descripcion"] ?? "");
if (mb_strlen($descripcion) > 255) {
    $errores[] = "La descripción no puede superar los 255 caracteres.";
}

$disponible = (($_POST["disponible"] ?? "1") === "0") ? 0 : 1;

$nombreImagen = "";
$tieneFoto = isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE;

if ($tieneFoto) {
    $foto = $_FILES["imagen"];
    $extension = strtolower(pathinfo($foto["name"], PATHINFO_EXTENSION));
    $permitidas = ["jpg", "jpeg", "png", "webp"];

    if ($foto["error"] !== UPLOAD_ERR_OK) {
        $errores[] = "No se pudo recibir la fotografía (error " . $foto["error"] . ").";
    } elseif (!in_array($extension, $permitidas, true)) {
        $errores[] = "La fotografía debe ser jpg, jpeg, png o webp.";
    } elseif ($foto["size"] > 2 * 1024 * 1024) {
        $errores[] = "La fotografía no puede superar los 2 MB.";
    } elseif (@getimagesize($foto["tmp_name"]) === false) {
        $errores[] = "El archivo enviado no es una imagen válida.";
    } else {
        $nombreImagen = uniqid("prod_") . "." . $extension; 
    }
}

if (count($errores) > 0) {
    responder(422, ["exito" => false, "errores" => $errores]);
}

if ($nombreImagen !== "") {
    $destino = __DIR__ . "/../images/" . $nombreImagen;
    if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $destino)) {
        responder(500, ["exito" => false, "mensaje" => "No se pudo guardar la fotografía"]);
    }
}

$stmt = $conn->prepare(
    "INSERT INTO productos (nombre, categoria_id, precio, descripcion, imagen, disponible)
     VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("sidssi", $nombre, $categoria_id, $precio, $descripcion, $nombreImagen, $disponible);

if (!$stmt->execute()) {
    if ($nombreImagen !== "") {
        @unlink(__DIR__ . "/../images/" . $nombreImagen); 
    }
    responder(500, ["exito" => false, "mensaje" => "No se pudo guardar el producto"]);
}

responder(201, [
    "exito" => true,
    "id" => $conn->insert_id,
    "mensaje" => "Producto guardado correctamente."
]);
