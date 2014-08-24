<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=ingresos&sub=agregar\" target=\"_self\" title=\"Carga de ingresos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Ingresos/Vista/Ingresos_add.png\" alt=\"Nueva compra\" class=\"toolbar2\"/>Nueva compra\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=ingresos&sub=listar\" target=\"_self\" title=\"Planilla de Ingresos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Ingresos/Vista/lista_ingresos.png\" alt=\"Lista de Ingresos\" class=\"toolbar2\"/>Planilla de Ingresos\n";
$retorno .= "</a></div>\n";

//$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
//$retorno .= "<a href=\"index.php?option=ingresos&sub=resumenIngresosProveedor\" target=\"_self\" title=\"Resumen Ingresos Proveedor\">\n";
//$retorno .= "<img src=\"" . DIRMODULOS . "Ingresos/Vista/tabla_proveedores.png\" alt=\"Resumen Ingresos Proveedor\" class=\"toolbar2\"/>Resumen Ingresos por Proveedor\n";
//$retorno .= "</a></div>\n";

//$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
//$retorno .= "<a href=\"index.php?option=ingresos&sub=resumenIngresosMensual\" target=\"_self\" title=\"Mensual\">\n";
//$retorno .= "<img src=\"" . DIRMODULOS . "Ingresos/Vista/resumenIngresosProveedoresAnual.png\" alt=\"Resumen Ingresos Mensual\" class=\"toolbar2\"/>Resumen Ingresos Mensual\n";
//$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "/iconos/backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
/** Fin del contenido del ingresos **/
