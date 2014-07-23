<?php
require_once 'Zend/Form/Decorator/Abstract.php';

class App_LibQ_Form_Decorator_IconoInformacion
    extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        //var_dump($content);
        $placement = $this->getPlacement();
        $output = '<img alt="Este campo es obligatorio" title="Este campo es obligatorio" class="imgRequerido" src="site_media/imagenes/iconos/information.png">';
        switch ($placement)
        {
            case 'PREPEND':
                return $output . $content;
            case 'APPEND':
            default:
                return $content . $output;
                
        }
    }
}
?>