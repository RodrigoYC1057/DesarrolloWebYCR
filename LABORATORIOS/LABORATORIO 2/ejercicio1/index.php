<?php
// Integrante 1: ____________________   |   Integrante 2: ____________________
require_once "catalogo.php";

$categorias       = obtenerCategorias();
$catalogoCompleto = obtenerCatalogo();
$conteoCategorias = contarPorCategoria($catalogoCompleto);

$categoriaSeleccionada = $_GET["categoria"] ?? "";
if ($categoriaSeleccionada !== "" && !array_key_exists($categoriaSeleccionada, $categorias)) {
    $categoriaSeleccionada = ""; // categoria inexistente -> catalogo completo
}

$productos = filtrarPorCategoria($catalogoCompleto, $categoriaSeleccionada);
$stats     = estadisticas($productos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>TecnoStore USFX - Catálogo</title>
<link rel="stylesheet" href="../estilos.css">
</head>
<body>
<header class="cabecera">
    <div>
        <h1>TecnoStore USFX - Catálogo</h1>
        <p>Ejercicio 1 - Clases, objetos y arreglos en PHP</p>
    </div>
    <nav>
        <a href="index.php" class="boton">Ver todo</a>
        <a href="../ejercicio2/tienda.php" class="boton">Ir a la tienda</a>
    </nav>
</header>

<main class="contenedor">
    <section>
        <h2>1. Filtro de productos</h2>
        <form method="get" class="formulario-filtro">
            <label for="categoria">Categoría</label><br>
            <select name="categoria" id="categoria">
                <option value="" <?php echo $categoriaSeleccionada === "" ? "selected" : ""; ?>>-- Todas las categorías --</option>
                <?php foreach ($categorias as $clave => $texto): ?>
                <option value="<?php echo htmlspecialchars($clave); ?>" <?php echo $categoriaSeleccionada === $clave ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($texto) . " (" . ($conteoCategorias[$clave] ?? 0) . ")"; ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrar</button>
            <p class="url-generada">URL generada: <?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?></p>
        </form>
    </section>

    <section>
        <h2>2. Productos (<?php echo count($productos); ?> encontrados)</h2>
        <?php if (count($productos) === 0): ?>
            <p class="aviso">No se encontraron productos para esta categoría.</p>
        <?php else: ?>
            <div class="galeria">
                <?php foreach ($productos as $p) { echo $p->mostrarTarjeta(); } ?>
            </div>
        <?php endif; ?>
    </section>

    <section>
        <h2>3. Resumen calculado con funciones de arreglo</h2>
        <h3>Estadísticas de los productos mostrados</h3>
        <table>
            <tr><th>Indicador</th><th>Valor</th></tr>
            <tr><td>Productos listados</td><td><?php echo $stats["total"]; ?></td></tr>
            <tr><td>Unidades en stock</td><td><?php echo $stats["stock"]; ?></td></tr>
            <tr><td>Precio promedio</td><td>Bs <?php echo number_format($stats["promedio"], 2); ?></td></tr>
            <tr><td>Precio más alto</td><td>Bs <?php echo number_format($stats["mayor"], 2); ?></td></tr>
            <tr><td>Precio más bajo</td><td>Bs <?php echo number_format($stats["menor"], 2); ?></td></tr>
            <tr class="fila-destacada"><td>Categorías del catálogo completo</td><td><?php echo count($categorias); ?></td></tr>
        </table>

        <h3>Contenido del arreglo $_GET</h3>
        <pre class="caja-codigo"><?php echo htmlspecialchars(print_r($_GET, true)); ?></pre>
    </section>
</main>

<footer class="pie">SIS 256 - Tecnología y Desarrollo Web · Ing. Carlos David Montellano Barriga</footer>
</body>
</html>
