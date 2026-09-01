<?php
class Pizarra {
    public $palabra;
    public $color;
    public $color_fondo;

    public function __construct($palabra, $color, $color_fondo) {
        $this->palabra = $palabra;
        $this->color = $color;
        $this->color_fondo = $color_fondo;
    }

    public function triangulo() {
        $longitud = strlen($this->palabra);

        echo '<h2>Triángulo</h2>';
        echo '<table class="pizarra">';

        for ($fila = 1; $fila <= $longitud; $fila++) {
            echo '<tr>';

            for ($columna = 0; $columna < $longitud; $columna++) {
                if ($columna < $fila) {
                    $caracter = htmlspecialchars($this->palabra[$columna]);
                    echo '<td style="color:' . htmlspecialchars($this->color) .
                         '; background-color:' . htmlspecialchars($this->color_fondo) .
                         ';">' . $caracter . '</td>';
                } else {
                    echo '<td class="vacia"></td>';
                }
            }

            echo '</tr>';
        }

        echo '</table>';
    }
}
?>
