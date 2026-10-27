<?php
// API REST: mensajes (GET, POST, DELETE)
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

switch ($method) {
    case "GET":
        $origen = $_GET["origen"] ?? "";
        $stmt = $con->prepare("SELECT id, mensaje, fecha FROM mensajes WHERE origen = ? ORDER BY id DESC");
        $stmt->bind_param("s", $origen);
        $stmt->execute();
        $result = $stmt->get_result();
        $mensajes = [];
        while ($f = $result->fetch_assoc()) $mensajes[] = $f;
        echo json_encode($mensajes);
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $mensaje = trim($data["mensaje"] ?? "");
        $origen = trim($data["origen"] ?? "");

        if ($mensaje === "" || $origen === "") {
            http_response_code(400);
            echo json_encode(["exito" => false, "error" => "Escribe un mensaje"]);
            break;
        }
        if (mb_strlen($mensaje) > 500) {
            http_response_code(400);
            echo json_encode(["exito" => false, "error" => "El mensaje es muy largo (maximo 500 caracteres)"]);
            break;
        }

        $stmt = $con->prepare("INSERT INTO mensajes (origen, mensaje) VALUES (?, ?)");
        $stmt->bind_param("ss", $origen, $mensaje);
        $stmt->execute();
        echo json_encode(["exito" => true, "mensaje" => "Mensaje enviado, gracias!", "id" => $con->insert_id]);
        break;

    case "DELETE":
        $id = (int) ($_GET["id"] ?? 0);
        $stmt = $con->prepare("DELETE FROM mensajes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["exito" => $stmt->affected_rows > 0]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["exito" => false, "error" => "Metodo no permitido"]);
}
?>
