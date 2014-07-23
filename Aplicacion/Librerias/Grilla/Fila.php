<?php
require_once 'configuration.php';
require_once 'App/LibQ/Grilla/Celda.php';

class Fila
{
    protected $_celda;
    private $_retorno;
    
    function __construct($celdas)
    {
        $this->_retorno .= "<tr>\n";
        if (is_array($celdas)){
            $cantCol = count($celdas)-1;
            for ($i=0;$i<=$cantCol;$i++){
                $this->_retorno .= $celdas[$i]->render();
            }
        }else{
            $this->_retorno .= $celdas->render();
        }
        $this->_retorno .= "</tr>\n";
//        echo htmlentities($this->_retorno);
    }
    
    public function render ()
    {
        return $this->_retorno;
    }
}
?>