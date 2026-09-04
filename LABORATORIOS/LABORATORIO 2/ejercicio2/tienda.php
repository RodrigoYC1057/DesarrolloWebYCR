<?php
// Integrante 1: ____________________   |   Integrante 2: ____________________
session_start();
require_once "inc/Producto.php";
require_once "inc/Carrito.php";
require_once "inc/catalogo.php";
require_once "inc/funciones.php";

// --- Procesar: guardar nombre del cliente (POST -> redirigir) ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"]) && trim($_POST["nombre"]) !== "") {
    guardarNombreCliente(trim($_POST["nombre"]));
    header("Location: tienda.php");
    exit();
}

// --- Procesar: agregar producto al carrito (POST -> redirigir) ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["codigo"])) {
    $catalogo = obtenerCatalogo();
    $producto = buscarProducto($catalogo, $_POST["codigo"]);
    if ($producto !== null) {
        $carrito = obtenerCarrito();
        $cantidad = isset($_POST["cantidad"]) ? (int) $_POST["cantidad"] : 1;
        $carrito->agregar($producto->getCodigo(), $cantidad);
        guardarCarrito($carrito);
        $_SESSION["mensaje"] = 'Se agregó "' . $producto->getNombre() . '" al carrito.';
    }
    header("Location: tienda.php");
    exit();
}

// --- Procesar: cambiar tema (GET -> redirigir) ---
if (isset($_GET["cambiarTema"])) {
    cambiarTema(obtenerTema());
    header("Location: tienda.php");
    exit();
}

$nombreCliente = obtenerNombreCliente();
$tema = obtenerTema();
$claseTema = $tema === "oscuro" ? "tema-oscuro" : "tema-claro";

// --- Vista: identificacion (todavia no hay cookie "cliente") ---
if ($nombreCliente === "") { ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>TecnoStore USFX - Tienda</title>
<link rel="stylesheet" href="../estilos.css">
</head>
<body class="<?php echo $claseTema; ?>">
<header class="cabecera">
    <div>
        <h1>TecnoStore USFX - Tienda</h1>
        <p>Ejercicio 2 - Cookies y sesiones en PHP</p>
    </div>
</header>
<main class="contenedor">
    <section>
        <h2>Identificación del cliente</h2>
        <form method="post" class="formulario-identificacion">
            <p>Escriba su nombre. Se guarda en una <strong>cookie</strong> durante 7 días, de modo que el navegador lo recuerde aunque cierre la página.</p>
            <label for="nombre">Nombre del cliente</label><br>
            <input type="text" id="nombre" name="nombre" placeholder="Ej: Ana Perez" required>
            <button type="submit">Ingresar a la tienda</button>
        </form>
    </section>
</main>
<footer class="pie">SIS 256 - Tecnología y Desarrollo Web · Ing. Carlos David Montellano Barriga</footer>
</body>
</html>
<?php
    exit();
}

// --- Cliente identificado ---
$visitas = registrarVisita();
$carrito = obtenerCarrito();
$catalogoCompleto = obtenerCatalogo();
$disponibles = array_filter($catalogoCompleto, function ($p) { return $p->hayStock(); });

$mensaje = $_SESSION["mensaje"] ?? "";
unset($_SESSION["mensaje"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>TecnoStore USFX - Tienda</title>
<link rel="stylesheet" href="../estilos.css">
</head>
<body class="<?php echo $claseTema; ?>">
<header class="cabecera">
    <div>
        <h1>TecnoStore USFX - Tienda</h1>
        <p>Ejercicio 2 - Cookies y sesiones en PHP</p>
    </div>
    <nav>
        <a href="carrito.php" class="boton">Carrito (<?php echo $carrito->cantidadTotal(); ?>)</a>
        <a href="tienda.php?cambiarTema=1" class="boton">Tema <?php echo $tema === "oscuro" ? "claro" : "oscuro"; ?></a>
        <a href="salir.php" class="boton">Salir</a>
    </nav>
</header>

<main class="contenedor">
    <?php if ($mensaje !== ""): ?>
        <p class="mensaje-confirmacion"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <h2>Hola <?php echo htmlspecialchars($nombreCliente); ?></h2>

    <section class="caja-info">
        <p>Esta es su <strong>visita número <?php echo (int) $visitas; ?></strong> (dato guardado en la cookie <code>visitas</code>).</p>
        <p>Su carrito tiene <strong><?php echo $carrito->cantidadTotal(); ?></strong> artículo(s) y vive en la sesión <?php echo htmlspecialchars(session_id()); ?>.</p>
    </section>

    <section>
        <h2>Productos disponibles</h2>
        <div class="galeria">
            <?php foreach ($disponibles as $p): ?>
            <div class="tarjeta">
                <div class="tarjeta-codigo"><?php echo htmlspecialchars($p->getCodigo()); ?></div>
                <div class="tarjeta-cuerpo">
                    <span class="etiqueta-categoria"><?php echo htmlspecialchars($p->getCategoria()); ?></span>
                    <h3><?php echo htmlspecialchars($p->getNombre()); ?></h3>
                    <p class="precio">Bs <?php echo number_format($p->getPrecio(), 2); ?></p>
                    <form method="post" class="formulario-agregar">
                        <input type="hidden" name="codigo" value="<?php echo htmlspecialchars($p->getCodigo()); ?>">
                        <input type="number" name="cantidad" value="1" min="1">
                        <button type="submit">Agregar</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<footer class="pie">SIS 256 - Tecnología y Desarrollo Web · Ing. Carlos David Montellano Barriga</footer>
</body>
</html>
