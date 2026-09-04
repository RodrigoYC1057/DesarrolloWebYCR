<?php
// Integrante 1: ____________________   |   Integrante 2: ____________________

class Producto {
    private $codigo;
    private $nombre;
    private $categoria;
    private $precio;
    private $stock;

    public function __construct($codigo, $nombre, $categoria, $precio, $stock) {
        $this->codigo    = $codigo;
        $this->nombre    = $nombre;
        $this->categoria = $categoria;
        $this->precio    = $precio;
        $this->stock     = $stock;
    }

    public function getCodigo()    { return $this->codigo; }
    public function getNombre()    { return $this->nombre; }
    public function getCategoria() { return $this->categoria; }
    public function getPrecio()    { return $this->precio; }
    public function getStock()     { return $this->stock; }

    // Parametro con valor por defecto
    public function getPrecioConDescuento($porcentaje = 10) {
        return round($this->precio * (1 - $porcentaje / 100), 2);
    }

    public function hayStock() {
        return $this->stock > 0;
    }

    // Devuelve el HTML de la tarjeta (no imprime, retorna la cadena)
    public function mostrarTarjeta() {
        $html  = '<div class="tarjeta">';
        $html .= '<div class="tarjeta-codigo">' . htmlspecialchars($this->codigo) . '</div>';
        $html .= '<div class="tarjeta-cuerpo">';
        $html .= '<span class="etiqueta-categoria">' . htmlspecialchars($this->categoria) . '</span>';
        $html .= '<h3>' . htmlspecialchars($this->nombre) . '</h3>';
        $html .= '<p class="precio">Bs ' . number_format($this->precio, 2) . '</p>';
        $html .= '<p class="descuento">Con 10% de descuento: Bs ' . number_format($this->getPrecioConDescuento(), 2) . '</p>';
        if ($this->hayStock()) {
            $html .= '<p class="stock">Stock disponible: ' . (int) $this->stock . ' unidades</p>';
        } else {
            $html .= '<span class="etiqueta-agotado">AGOTADO</span>';
        }
        $html .= '</div></div>';
        return $html;
    }
}
