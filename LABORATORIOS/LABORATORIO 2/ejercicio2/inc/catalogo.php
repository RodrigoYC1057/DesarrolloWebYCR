<?php
// Integrante 1: ____________________   |   Integrante 2: ____________________
require_once "Producto.php";

// Mismo catalogo del Ejercicio 1
function obtenerCatalogo() {
    return [
        new Producto("P01", "Teclado mecánico RGB",   "Periféricos",    320.00, 12),
        new Producto("P02", "Mouse inalámbrico",       "Periféricos",    145.50, 8),
        new Producto("P03", "Monitor 24 pulgadas",     "Pantallas",     1250.00, 5),
        new Producto("P04", "Monitor curvo 27 pulg.",  "Pantallas",     2100.00, 0),
        new Producto("P05", "Disco sólido 480 GB",     "Almacenamiento", 380.00, 20),
        new Producto("P06", "Memoria USB 64 GB",       "Almacenamiento", 75.00, 35),
        new Producto("P07", "Audífonos con micrófono", "Periféricos",    210.00, 0),
        new Producto("P08", "Disco externo 1 TB",      "Almacenamiento", 640.00, 6),
    ];
}

function buscarProducto(array $productos, $codigo) {
    foreach ($productos as $p) {
        if ($p->getCodigo() === $codigo) {
            return $p;
        }
    }
    return null;
}
