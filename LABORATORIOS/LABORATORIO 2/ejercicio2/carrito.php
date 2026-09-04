<?php
// Integrante 1: ____________________   |   Integrante 2: ____________________
session_start();
require_once "inc/Producto.php";
require_once "inc/Carrito.php";
require_once "inc/catalogo.php";
require_once "inc/funciones.php";

if (obtenerNombreCliente() === "") {
    header("Location: tienda.php");
    exit();
}

$catalogo = obtenerCatalogo();
$carrito = obtenerCarrito();

// --- Procesar: quitar una linea (POST -> redirigir) ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["quitar"])) {
    $carrito->quitar($_POST["quitar"]);
    guardarCarrito($carrito);
    $_SESSION["mensaje"] = "Se quitó el producto del carrito.";
    header("Location: carrito.php");
    exit();
}

// --- Procesar: vaciar carrito (POST -> redirigir) ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["vaciar"])) {
    $carrito->vaciar();
    guardarCarrito($carrito);
    $_SESSION["mensaje"] = "El carrito quedó vacío.";
    header("Location: carrito.php");
    exit();
}

$nombreCliente = obtenerNombreCliente();
$tema = obtenerTema();
$claseTema = $tema === "oscuro" ? "tema-oscuro" : "tema-claro";
$mensaje = $_SESSION["mensaje"] ?? "";
unset($_SESSION["mensaje"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>TecnoStore USFX - Carrito</title>
<link rel="stylesheet" href="../estilos.css">
</head>
<body class="<?php echo $claseTema; ?>">
<header class="cabecera">
    <div>
        <h1>TecnoStore USFX - Carrito</h1>
        <p>Contenido guardado en $_SESSION</p>
    </div>
    <nav>
        <a href="tienda.php" class="boton">Seguir comprando</a>
        <a href="salir.php" class="boton">Salir</a>
    </nav>
</header>

<main class="contenedor">
    <?php if ($mensaje !== ""): ?>
        <p class="mensaje-confirmacion"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <h2>Compra de <?php echo htmlspecialchars($nombreCliente); ?></h2>

    <section>
        <h3>Detalle del carrito</h3>
        <?php if ($carrito->estaVacio()): ?>
            <p class="aviso">El carrito está vacío.</p>
        <?php else: ?>
            <table>
                <tr><th>Código</th><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th>Acción</th></tr>
                <?php foreach ($carrito->getItems() as $codigo => $cantidad):
                    $producto = buscarProducto($catalogo, $codigo);
                    if ($producto === null) continue;
                    $subtotal = $producto->getPrecio() * $cantidad;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($producto->getCodigo()); ?></td>
                    <td><?php echo htmlspecialchars($producto->getNombre()); ?></td>
                    <td>Bs <?php echo number_format($producto->getPrecio(), 2); ?></td>
                    <td><?php echo (int) $cantidad; ?></td>
                    <td>Bs <?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="quitar" value="<?php echo htmlspecialchars($codigo); ?>">
                            <button type="submit">Quitar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="fila-destacada">
                    <td colspan="4">TOTAL A PAGAR (<?php echo $carrito->cantidadTotal(); ?> artículos)</td>
                    <td colspan="2">Bs <?php echo number_format($carrito->total($catalogo), 2); ?></td>
                </tr>
            </table>
            <form method="post" class="linea-acciones">
                <button type="submit" name="vaciar" value="1">Vaciar carrito</button>
            </form>
        <?php endif; ?>
        <a href="tienda.php" class="boton">Seguir comprando</a>
    </section>

    <section>
        <h2>Comprobación de sesiones y cookies</h2>
        <h3>Identificador de la sesión</h3>
        <p class="caja-codigo"><?php echo htmlspecialchars(session_id()); ?></p>
        <h3>Contenido de $_SESSION</h3>
        <pre class="caja-codigo"><?php echo htmlspecialchars(print_r($_SESSION, true)); ?></pre>
        <h3>Contenido de $_COOKIE</h3>
        <pre class="caja-codigo"><?php echo htmlspecialchars(print_r($_COOKIE, true)); ?></pre>
    </section>
</main>
<footer class="pie">SIS 256 - Tecnología y Desarrollo Web · Ing. Carlos David Montellano Barriga</footer>
</body>
</html>
