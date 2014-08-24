<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=gastos&sub=agregar\" target=\"_self\" title=\"Carga de gastos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Gastos/Vista/gastos_add.png\" alt=\"Nueva compra\" class=\"toolbar2\"/>Nueva compra\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=gastos&sub=listar\" target=\"_self\" title=\"Planilla de Gastos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Gastos/Vista/lista_gastos.png\" alt=\"Lista de Gastos\" class=\"toolbar2\"/>Planilla de Gastos\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=gastos&sub=resumenGastosProveedor\" target=\"_self\" title=\"Resumen Gastos Proveedor\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Gastos/Vista/tabla_proveedores.png\" alt=\"Resumen Gastos Proveedor\" class=\"toolbar2\"/>Resumen Gastos por Proveedor\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=gastos&sub=resumenGastosAnual\" target=\"_self\" title=\"Anual\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Gastos/Vista/resumenGastosProveedoresAnual.png\" alt=\"Resumen Gastos Anual\" class=\"toolbar2\"/>Resumen Gastos Anual\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "/iconos/backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
/** Fin del contenido del gastos **/
