<?php
require_once 'configuration.php';

class Celda
{
    private $_retorno;
    
    function __construct ($contenido, $colspan=1)
    {
        if ($colspan > 1){
            $this->_retorno = "<td colspan=\"$colspan\">\n";
        }else{
            $this->_retorno = "<td>\n";
        }
        $this->_retorno .= $contenido;
        $this->_retorno .= "</td>\n";
    }
    public function render ()
    {
        return $this->_retorno;
    }
    
}
?>