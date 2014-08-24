<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=honorarios&sub=agregar\" target=\"_self\" title=\"Carga de ingresos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Honorarios/Vista/honorario_add.png\" alt=\"Carga de honorarios\" class=\"toolbar2\"/>Carga de Honorarios\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=honorarios&sub=listar\" target=\"_self\" title=\"Planilla de Gastos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Honorarios/Vista/tabla_honorario.png\" alt=\"Planilla de honorarios\" class=\"toolbar2\"/>Planilla de Honorarios\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=honorarios&sub=resumenHonorariosAnual\" target=\"_self\" title=\"Planilla de Honorarios Anual\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Honorarios/Vista/tabla_honorario_anual.png\" alt=\"Planilla de honorarios anual\" class=\"toolbar2\"/>Planilla de Honorarios Anual\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "/iconos/backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";

echo $retorno;
/** Fin del contenido del gastos **/
