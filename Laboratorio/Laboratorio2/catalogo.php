<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo Tecnológico</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; color: #333; }
        h1, h2 { color: #0056b3; }
        .resumen { background-color: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .grid-productos { display: flex; flex-wrap: wrap; gap: 20px; }
        .tarjeta { background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px; width: 250px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .tarjeta h3 { margin-top: 0; color: #333; }
        .precio { font-size: 1.2em; font-weight: bold; color: #28a745; }
        .categoria { font-size: 0.9em; color: #666; font-style: italic; }
        .disponible { color: #28a745; font-weight: bold; }
        .agotado { color: #dc3545; font-weight: bold; }
        ul { padding-left: 20px; margin-bottom: 0; }
        .seccion-filtrada { margin-top: 40px; padding-top: 20px; border-top: 2px dashed #ccc; }
    </style>
</head>
<body>

    <h1>Catálogo de Productos Tecnológicos</h1>

    <?php
    $catalogo = [
        [
            "nombre" => "Laptop Pro 15",
            "categoria" => "Computadoras",
            "precio" => 1250.00,
            "disponible" => true,
            "caracteristicas" => ["16GB RAM", "512GB SSD", "Procesador i7"]
        ],
        [
            "nombre" => "Mouse Gamer RGB",
            "categoria" => "Periféricos",
            "precio" => 45.50,
            "disponible" => true,
            "caracteristicas" => ["Sensor óptico 10000 DPI", "Iluminación RGB", "6 botones programables"]
        ],
        [
            "nombre" => "Monitor UltraWide 29\"",
            "categoria" => "Monitores",
            "precio" => 280.00,
            "disponible" => false,
            "caracteristicas" => ["Panel IPS", "75Hz", "AMD FreeSync"]
        ],
        [
            "nombre" => "Teclado Mecánico",
            "categoria" => "Periféricos",
            "precio" => 85.00,
            "disponible" => true,
            "caracteristicas" => ["Switches Blue", "Teclado numérico", "Cuerpo de aluminio"]
        ],
        [
            "nombre" => "Mini PC Básico",
            "categoria" => "Computadoras",
            "precio" => 320.00,
            "disponible" => true,
            "caracteristicas" => ["8GB RAM", "256GB SSD", "Wi-Fi Integrado"]
        ]
    ];

    usort($catalogo, function($a, $b) {
        return $a['precio'] <=> $b['precio'];
    });

    $valor_total_inventario = 0;
    $cantidad_disponibles = 0;
    $cantidad_agotados = 0;

    foreach ($catalogo as $producto) {
        if ($producto['disponible']) {
            $cantidad_disponibles++;
            $valor_total_inventario += $producto['precio']; 
        } else {
            $cantidad_agotados++;
        }
    }
    ?>

    <div class="resumen">
        <h2>Resumen del Inventario</h2>
        <p><strong>Valor total del inventario disponible:</strong> $<?php echo number_format($valor_total_inventario, 2); ?></p>
        <p><strong>Productos disponibles:</strong> <?php echo $cantidad_disponibles; ?></p>
        <p><strong>Productos agotados:</strong> <?php echo $cantidad_agotados; ?></p>
    </div>

    <h2>Todos los Productos (Ordenados por Precio)</h2>
    <div class="grid-productos">
        <?php foreach ($catalogo as $producto): ?>
            <div class="tarjeta">
                <h3><?php echo $producto['nombre']; ?></h3>
                <p class="categoria"><?php echo $producto['categoria']; ?></p>
                <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
                
                <?php if ($producto['disponible']): ?>
                    <p class="disponible">✓ En Stock</p>
                <?php else: ?>
                    <p class="agotado">✗ Agotado</p>
                <?php endif; ?>

                <p><strong>Características:</strong></p>
                <ul>
                    <?php 
                    foreach ($producto['caracteristicas'] as $caracteristica) {
                        echo "<li>" . $caracteristica . "</li>";
                    }
                    ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="seccion-filtrada">
        <?php $categoria_buscada = "Periféricos"; ?>
        <h2>Filtrado: Solo <?php echo $categoria_buscada; ?></h2>
        <div class="grid-productos">
            <?php 
            foreach ($catalogo as $producto) {
                if ($producto['categoria'] === $categoria_buscada) {
                    ?>
                    <div class="tarjeta">
                        <h3><?php echo $producto['nombre']; ?></h3>
                        <p class="precio">$<?php echo number_format($producto['precio'], 2); ?></p>
                        <?php if ($producto['disponible']): ?>
                            <p class="disponible">✓ En Stock</p>
                        <?php else: ?>
                            <p class="agotado">✗ Agotado</p>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

</body>
</html>