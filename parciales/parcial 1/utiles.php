<?php
class utiles {
    private $cadena;

    public function __construct($cadena) {
        $this->cadena = $cadena;
    }

    public function aumentarguiones($n) {
        $guiones = str_repeat('-', $n);
        
        $letras = str_split($this->cadena);
        
        $resultado = implode($guiones, $letras);
        
        return $resultado;
    }
}
?>