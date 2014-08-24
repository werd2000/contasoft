<?php
require_once 'Zend/Form.php';

/** Desde aquí contenido propio del menú **/
echo $this->LibQ_BarraHerramientas;
//echo $this->mostrarFiltroAlumnos;
echo '<table id="grilla"></table>';
echo '<div id="pager2"></div>';
echo '<div id="filter" style="margin-left:30%;display:none">Search Invoices</div>';
echo $this->grid;
echo "<div class=\"widget ui-widget-content ui-corner-all\">\n";
echo "<div class=\"contenido\">\n";
echo $this->grafico;
echo "</div>\n"; 
echo "</div>\n";
/** Fin del contenido del gastos **/
