<?php
class Estante {
    public $nivel1;
    public $nivel2;
    public $nivel3;
    public $capacidad;

    public function __construct($capacidad) {
        $this->nivel1 = array();
        $this->nivel2 = array();
        $this->nivel3 = array();
        $this->capacidad = $capacidad;
    }

    public function insertar($nivel, $elemento) {
        if ($nivel == 1) {
            $arreglo = &$this->nivel1;
        } elseif ($nivel == 2) {
            $arreglo = &$this->nivel2;
        } elseif ($nivel == 3) {
            $arreglo = &$this->nivel3;
        } else {
            return "Nivel no válido.";
        }

        if (count($arreglo) >= $this->capacidad) {
            return "Nivel " . $nivel . " lleno.";
        }

        array_push($arreglo, $elemento);
        return "Elemento insertado en el Nivel " . $nivel . ".";
    }

    public function quitar($nivel) {
        if ($nivel == 1) {
            $arreglo = &$this->nivel1;
        } elseif ($nivel == 2) {
            $arreglo = &$this->nivel2;
        } elseif ($nivel == 3) {
            $arreglo = &$this->nivel3;
        } else {
            return "Nivel no válido.";
        }

        if (count($arreglo) == 0) {
            return "El Nivel " . $nivel . " está vacío.";
        }

        array_pop($arreglo);
        return "Se quitó el último elemento del Nivel " . $nivel . ".";
    }

    public function mostrarEstante() {
        echo '<table class="tabla-estante">';
        $niveles = array(
            3 => $this->nivel3,
            2 => $this->nivel2,
            1 => $this->nivel1
        );

        foreach ($niveles as $numero => $elementos) {
            echo '<tr><th>Nivel ' . $numero . '</th>';
            for ($i = 0; $i < $this->capacidad; $i++) {
                $clase = 'nivel' . $numero;
                if (isset($elementos[$i])) {
                    echo '<td class="' . $clase . '">' . htmlspecialchars($elementos[$i], ENT_QUOTES, 'UTF-8') . '</td>';
                } else {
                    echo '<td class="' . $clase . '">&nbsp;</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
    }
}
?>
