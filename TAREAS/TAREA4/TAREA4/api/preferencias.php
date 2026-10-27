<?php
// API REST: preferencias (musica, sitio, hobby) -> GET (conteo de votos) y POST (votar)
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
require_once "conexion.php";

$method = $_SERVER["REQUEST_METHOD"];
$categorias = ["musica", "sitio", "hobby"]; // categorias permitidas

if ($method === "OPTIONS") { // preflight CORS
    http_response_code(204);
    exit;
}

switch ($method) {
    case "GET":
        $categoria = $_GET["categoria"] ?? "";
        $stmt = $con->prepare(
            "SELECT valor, COUNT(*) AS votos FROM preferencias
             WHERE categoria = ? GROUP BY valor ORDER BY votos DESC, valor ASC");
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultados = [];
        while ($f = $result->fetch_assoc()) $resultados[] = $f;
        echo json_encode($resultados);
        break;

    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $categoria = trim($data["categoria"] ?? "");
        $valor = trim($data["valor"] ?? "");

        if (!in_array($categoria, $categorias) || $valor === "" || mb_strlen($valor) > 60) {
            http_response_code(400);
            echo json_encode(["exito" => false, "error" => "Datos no validos"]);
            break;
        }

        $stmt = $con->prepare("INSERT INTO preferencias (categoria, valor) VALUES (?, ?)");
        $stmt->bind_param("ss", $categoria, $valor);
        $stmt->execute();
        echo json_encode(["exito" => true, "mensaje" => "Guardaste tu favorito: " . $valor]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["exito" => false, "error" => "Metodo no permitido"]);
}
?>
