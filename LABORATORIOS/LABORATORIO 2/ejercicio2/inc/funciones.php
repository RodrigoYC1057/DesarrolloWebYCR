<?php
// Integrante 1: ____________________   |   Integrante 2: ____________________

// ---------- Cookie del cliente ----------
function obtenerNombreCliente() {
    return $_COOKIE["cliente"] ?? "";
}

function guardarNombreCliente($nombre) {
    setcookie("cliente", $nombre, time() + 7 * 24 * 60 * 60, "/");
}

// ---------- Cookie del tema ----------
function obtenerTema() {
    return $_COOKIE["tema"] ?? "claro";
}

function cambiarTema($temaActual) {
    $nuevo = ($temaActual === "claro") ? "oscuro" : "claro";
    setcookie("tema", $nuevo, time() + 30 * 24 * 60 * 60, "/");
    return $nuevo;
}

// ---------- Cookie de visitas (sobrevive al cierre de sesion) ----------
function registrarVisita() {
    if (!isset($_SESSION["visita_contada"])) {
        $anterior = isset($_COOKIE["visitas"]) ? (int) $_COOKIE["visitas"] : 0;
        $nuevo = $anterior + 1;
        setcookie("visitas", $nuevo, time() + 365 * 24 * 60 * 60, "/");
        $_SESSION["visitas"] = $nuevo;
        $_SESSION["visita_contada"] = true;
    }
    return $_SESSION["visitas"];
}

// ---------- Carrito guardado en la sesion ----------
function obtenerCarrito() {
    if (isset($_SESSION["carrito"])) {
        return unserialize($_SESSION["carrito"]);
    }
    return new Carrito();
}

function guardarCarrito(Carrito $carrito) {
    $_SESSION["carrito"] = serialize($carrito);
}

// ---------- Cierre de sesion ----------
function borrarCookiesSesion() {
    setcookie("cliente", "", time() - 3600, "/");
    setcookie("tema", "", time() - 3600, "/");
    // "visitas" NO se borra: debe sobrevivir al cierre de la sesion
}
