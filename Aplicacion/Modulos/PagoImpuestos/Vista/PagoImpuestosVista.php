<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=PagoImpuestos&sub=agregar\" target=\"_self\" title=\"Carga de impuestos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "PagoImpuestos/Vista/pagoimpuestos_add.png\" alt=\"Nuevo Pago de Impuesto\" class=\"toolbar2\"/>Nuevo Pago de Impuestos\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=PagoImpuestos&sub=listar\" target=\"_self\" title=\"Planilla de PagoImpuestos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "PagoImpuestos/Vista/lista_pagoimpuestos.png\" alt=\"Lista de Pago de Impuestos\" class=\"toolbar2\"/>Planilla de Pago de Impuestos\n";
$retorno .= "</a></div>\n";

//$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
//$retorno .= "<a href=\"index.php?option=pagoimpuestos&sub=resumenPagoImpuestosProveedor\" target=\"_self\" title=\"Resumen PagoImpuestos Proveedor\">\n";
//$retorno .= "<img src=\"" . DIRMODULOS . "PagoImpuestos/Vista/tabla_proveedores.png\" alt=\"Resumen PagoImpuestos Proveedor\" class=\"toolbar2\"/>Resumen PagoImpuestos por Proveedor\n";
//$retorno .= "</a></div>\n";
//
//$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
//$retorno .= "<a href=\"index.php?option=pagoimpuestos&sub=resumenPagoImpuestosMensual\" target=\"_self\" title=\"Mensual\">\n";
//$retorno .= "<img src=\"" . DIRMODULOS . "PagoImpuestos/Vista/resumenPagoImpuestosProveedoresAnual.png\" alt=\"Resumen PagoImpuestos Mensual\" class=\"toolbar2\"/>Resumen PagoImpuestos Mensual\n";
//$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "/iconos/backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
/** Fin del contenido del pagoimpuestos **/
