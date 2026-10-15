<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo 
function subirFotografia($archivo) {

    if ($archivo["error"] === UPLOAD_ERR_NO_FILE) {
        return "";
    }

    if ($archivo["error"] === UPLOAD_ERR_INI_SIZE || $archivo["error"] === UPLOAD_ERR_FORM_SIZE) {
        return "Error: la fotografia pesa mas de 2 MB.";
    }

    if ($archivo["error"] !== UPLOAD_ERR_OK) {
        return "Error: no se pudo recibir el archivo.";
    }

    if ($archivo["size"] > 2 * 1024 * 1024) {
        return "Error: la fotografia pesa mas de 2 MB.";
    }

    $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));
    $permitidas = ["jpg", "jpeg", "png", "webp"];
    if (!in_array($extension, $permitidas)) {
        return "Error: solo se permiten fotografias jpg, jpeg, png o webp.";
    }

    $nuevo = uniqid() . "." . $extension;

    if (!move_uploaded_file($archivo["tmp_name"], "images/" . $nuevo)) {
        return "Error: no se pudo guardar la fotografia en el servidor.";
    }

    return $nuevo;
}
?>
