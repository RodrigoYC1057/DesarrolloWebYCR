<?php
// API REST: contactos / correos registrados (GET, POST, PUT, DELETE)
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require_once "conexion.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "OPTIONS") { // preflight CORS
    http_response_code(204);
    exit;
}

// Valida correo y edad. Devuelve un texto de error o null si todo esta bien
function validar($correo, $edad) {
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        return "El correo no es valido";
    }
    if (!is_numeric($edad) || $edad < 1 || $edad > 120) {
        return "La edad debe estar entre 1 y 120";
    }
    return null;
}

switch ($method) {
    case "GET":
        $result = $con->query("SELECT id, correo, edad FROM contactos ORDER BY id DESC");
        $contactos = [];
        while ($f = $result->fetch_assoc()) $contactos[] = $f;
        echo json_encode($contactos);
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $correo = trim($data["correo"] ?? "");
        $edad = $data["edad"] ?? "";

        $error = validar($correo, $edad);
        if ($error) {
            http_response_code(400);
            echo json_encode(["exito" => false, "error" => $error]);
            break;
        }

        $edad = (int) $edad;
        $stmt = $con->prepare("INSERT INTO contactos (correo, edad) VALUES (?, ?)");
        $stmt->bind_param("si", $correo, $edad);
        if (!$stmt->execute()) {
            if ($stmt->errno === 1062) { // correo duplicado
                http_response_code(409);
                echo json_encode(["exito" => false, "error" => "Ese correo ya esta registrado"]);
            } else {
                http_response_code(500);
                echo json_encode(["exito" => false, "error" => "No se pudo guardar"]);
            }
            break;
        }
        echo json_encode(["exito" => true, "mensaje" => "Correo registrado correctamente", "id" => $con->insert_id]);
        break;

    case "PUT":
        $data = json_decode(file_get_contents("php://input"), true);
        $id = (int) ($data["id"] ?? 0);
        $correo = trim($data["correo"] ?? "");
        $edad = $data["edad"] ?? "";

        $error = validar($correo, $edad);
        if ($error) {
            http_response_code(400);
            echo json_encode(["exito" => false, "error" => $error]);
            break;
        }

        $edad = (int) $edad;
        $stmt = $con->prepare("UPDATE contactos SET correo = ?, edad = ? WHERE id = ?");
        $stmt->bind_param("sii", $correo, $edad, $id);
        if (!$stmt->execute()) {
            if ($stmt->errno === 1062) {
                http_response_code(409);
                echo json_encode(["exito" => false, "error" => "Ese correo ya esta registrado"]);
            } else {
                http_response_code(500);
                echo json_encode(["exito" => false, "error" => "No se pudo actualizar"]);
            }
            break;
        }
        echo json_encode(["exito" => true, "mensaje" => "Actualizado correctamente"]);
        break;

    case "DELETE":
        $id = (int) ($_GET["id"] ?? 0);
        $stmt = $con->prepare("DELETE FROM contactos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["exito" => $stmt->affected_rows > 0]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["exito" => false, "error" => "Metodo no permitido"]);
}
?>
