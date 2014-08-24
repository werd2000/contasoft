<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=usuarios&sub=agregar\" target=\"_self\" title=\"Carga de Usuarios\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Usuarios/Vista/Usuarios_Add.png\" alt=\"Nuevo Usuario\" class=\"toolbar2\"/>Nuevo Usuarios\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=usuarios&sub=listar\" target=\"_self\" title=\"Lista de Usuarios\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Usuarios/Vista/Lista_Usuarios.png\" alt=\"Lista Usuarios\" class=\"toolbar2\"/>Lista de Usuarios\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";

$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
/** Fin del contenido de usuarios **/
