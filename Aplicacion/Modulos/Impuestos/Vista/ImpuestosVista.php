<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=impuestos&sub=agregar\" target=\"_self\" title=\"Carga de impuestos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Impuestos/Vista/impuestos_add.png\" alt=\"Nuevo Impuesto\" class=\"toolbar2\"/>Nuevo Impuesto\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=impuestos&sub=listar\" target=\"_self\" title=\"Planilla de Impuestos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Impuestos/Vista/lista_impuestos.png\" alt=\"Lista de Impuestos\" class=\"toolbar2\"/>Planilla de Impuestos\n";
$retorno .= "</a></div>\n";

//$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
//$retorno .= "<a href=\"index.php?option=impuestos&sub=resumenImpuestosProveedor\" target=\"_self\" title=\"Resumen Impuestos Proveedor\">\n";
//$retorno .= "<img src=\"" . DIRMODULOS . "Impuestos/Vista/tabla_proveedores.png\" alt=\"Resumen Impuestos Proveedor\" class=\"toolbar2\"/>Resumen Impuestos por Proveedor\n";
//$retorno .= "</a></div>\n";
//
//$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
//$retorno .= "<a href=\"index.php?option=impuestos&sub=resumenImpuestosMensual\" target=\"_self\" title=\"Mensual\">\n";
//$retorno .= "<img src=\"" . DIRMODULOS . "Impuestos/Vista/resumenImpuestosProveedoresAnual.png\" alt=\"Resumen Impuestos Mensual\" class=\"toolbar2\"/>Resumen Impuestos Mensual\n";
//$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "/iconos/backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
/** Fin del contenido del impuestos **/
