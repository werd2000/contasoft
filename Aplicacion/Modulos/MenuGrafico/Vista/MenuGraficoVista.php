<?php

/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$caption_modulo = '';
$ubicacion = 0;
foreach ($this->modulos as $mod){
    $modulo = $mod;
    $busqueda = preg_match_all("/[A-Z]/", $modulo, $coincidencias, PREG_OFFSET_CAPTURE);
    if ($busqueda > 0){
        if (isset ($coincidencias[0][1][1])){
            $ubicacion = $coincidencias[0][1][1];
        }
        $caption_modulo = substr($modulo, 0, $ubicacion) . ' ' . substr($modulo, $ubicacion); 
    }
    $retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
    $retorno .= "<a href=\"index.php?option=" . $modulo . "\" target=\"_self\" title=\"Gesti&oacute;n de " . ucfirst($modulo) . "\">\n";
    $retorno .= "<img src=\"" . DIR_MODULOS . ucfirst($modulo) . "/Vista/Mod_" . $modulo . ".png\" alt=\"$modulo\" class=\"toolbar2\"/>" . ucfirst($caption_modulo) . "\n";
    $retorno .= "</a></div>\n";
}

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=login&sub=logout\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "exit.png\" alt=\"Salir\" class=\"toolbar2\"/>Salir\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
/** Fin del contenido del men� **/
