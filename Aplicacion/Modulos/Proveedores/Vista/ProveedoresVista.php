<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=proveedores&sub=agregar\" target=\"_self\" title=\"Carga de ingresos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Proveedores/Vista/proveedores_add.png\" alt=\"Carga de proveedores\" class=\"toolbar2\"/>Carga de Proveedores\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=proveedores&sub=listar\" target=\"_self\" title=\"Planilla de Gastos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Proveedores/Vista/tabla_proveedores.png\" alt=\"Planilla de proveedores\" class=\"toolbar2\"/>Planilla de Proveedores\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "/iconos/backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";

echo $retorno;
/** Fin del contenido del gastos **/
