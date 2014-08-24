<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=profesionales&sub=agregar\" target=\"_self\" title=\"Carga de Profesionales\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Profesionales/Vista/Profesionales_Add.png\" alt=\"Nuevo Usuario\" class=\"toolbar2\"/>Nuevo Profesionales\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=profesionales&sub=listar\" target=\"_self\" title=\"Lista de Profesionales\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Profesionales/Vista/Lista_Profesionales.png\" alt=\"Lista Profesionales\" class=\"toolbar2\"/>Lista de Profesionales\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";

$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
/** Fin del contenido de profesionales **/
