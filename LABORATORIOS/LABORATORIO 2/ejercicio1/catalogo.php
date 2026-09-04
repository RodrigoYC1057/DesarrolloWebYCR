<?php
// Integrante 1: ____________________   |   Integrante 2: ____________________
require_once "Producto.php";

// Arreglo indexado con 8 objetos Producto de 3 categorias (2 con stock 0)
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

// Arreglo asociativo clave => texto visible, para el <select>
function obtenerCategorias() {
    return [
        "Periféricos"    => "Periféricos",
        "Pantallas"      => "Pantallas",
        "Almacenamiento" => "Almacenamiento",
    ];
}

function filtrarPorCategoria(array $productos, $categoria) {
    if ($categoria === "" || $categoria === null) {
        return $productos;
    }
    $resultado = [];
    foreach ($productos as $p) {
        if ($p->getCategoria() === $categoria) {
            $resultado[] = $p;
        }
    }
    return $resultado;
}

// Usa count(), array_map(), array_sum(), max() y min()
function estadisticas(array $productos) {
    $total = count($productos);
    if ($total === 0) {
        return ["total" => 0, "stock" => 0, "promedio" => 0, "mayor" => 0, "menor" => 0];
    }
    $precios = array_map(function ($p) { return $p->getPrecio(); }, $productos);
    $stocks  = array_map(function ($p) { return $p->getStock(); }, $productos);
    return [
        "total"    => $total,
        "stock"    => array_sum($stocks),
        "promedio" => round(array_sum($precios) / $total, 2),
        "mayor"    => max($precios),
        "menor"    => min($precios),
    ];
}

function contarPorCategoria(array $productos) {
    $conteo = [];
    foreach ($productos as $p) {
        $cat = $p->getCategoria();
        $conteo[$cat] = ($conteo[$cat] ?? 0) + 1;
    }
    return $conteo;
}
