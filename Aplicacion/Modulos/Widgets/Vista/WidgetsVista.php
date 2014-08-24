<?php
require_once DIRMODULOS . 'Widgets/Controlador/WidgetsControlador.php';
/** Desde aquí contenido propio del menú **/
$retorno = '';
$retorno = "<div id=\"ventana\" class=\"window\">\n";
$retorno .= "<div class=\"widgets\">\n";
foreach ($this->widgets as $widget){
//        print_r($widget);
    
        $retorno .= "<div class=\"widget ui-widget-content ui-corner-all\">\n";
        $retorno .= "<div class=\"titulo-widget ui-corner-top\">\n";
        $retorno .= $widget->getTitulo();
        $retorno .= "</div>\n";
        $retorno .= "<div class=\"contenido\">\n";
        $retorno .= $widget->getContenido() ;
        $retorno .= "</div>\n";
        $retorno .= "</div>\n";
}        
$retorno .= "</div>\n";
$retorno .= "</div>\n";

echo $retorno;
/** Fin del contenido del men� **/
