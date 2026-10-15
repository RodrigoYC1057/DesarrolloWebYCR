<?php
//Condori Carmona Fernando Favian
//Yucra Caballero Rodrigo
$conn = new mysqli("localhost", "root", "", "bd_consultorio26", 3306);

if ($conn->connect_error) {
    die("Error de conexion: " . $conn->connect_error);
}

// para que los acentos se vean bien
$conn->set_charset("utf8mb4");
?>
