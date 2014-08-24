<?php
/** Desde aquí contenido propio del menú **/
$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=sueldos&sub=agregar\" target=\"_self\" title=\"Carga de sueldos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Sueldos/Vista/sueldos_add.png\" alt=\"Nueva sueldo\" class=\"toolbar2\"/>Nueva Sueldo\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=sueldos&sub=listar\" target=\"_self\" title=\"Planilla de Sueldos\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Sueldos/Vista/lista_sueldos.png\" alt=\"Lista de Sueldos\" class=\"toolbar2\"/>Planilla de Sueldos\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=sueldos&sub=resumenSueldosAnual\" target=\"_self\" title=\"Resumen Sueldos Anual\">\n";
$retorno .= "<img src=\"" . DIRMODULOS . "Sueldos/Vista/lista_sueldos_anual.png\" alt=\"Resumen Sueldos Anual\" class=\"toolbar2\"/>Resumen Sueldos Anual\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "/iconos/backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
/** Fin del contenido del sueldos **/
