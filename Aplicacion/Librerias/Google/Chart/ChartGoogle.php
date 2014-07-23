<?php

/**
 * Description of cahrtGoogle
 *
 * @author WERD
 */
class ChartGoogle {
    
    private $_titulo = '';
    private $_datos = '';
    private $_tituloEjeX = '';

    function __construct($titulo)
    {
        $this->_titulo = $titulo;
    }
    
    public function setDatos($datos){
        $this->_datos = $datos;
    }
    
    public function setTitutloEjeX($titulo){
        $this->_tituloEjeX = $titulo;
    }
    
    public function incluirJs()
    {
        /* Inicio del Script */
        $retorno = "<script type=\"text/javascript\">\n";
        $retorno.="google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});";
        $retorno.="google.setOnLoadCallback(drawChart);\n";
        $retorno.="function drawChart() {\n";        
        $retorno .= "var data = google.visualization.arrayToDataTable([\n";
        $retorno .= $this->_datos;
        $retorno .= " ]);";
        $retorno .= "var options = {\n";    
        $retorno .= "title: '" . $this->_titulo . "',"; // 
        $retorno .= "hAxis: {title: '" . $this->_tituloEjeX . "', titleTextStyle: {color: 'red'}}"; 
        $retorno .= "};"; 
        $retorno .= "var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));"; 
        $retorno .= "chart.draw(data, options);\n";    
        $retorno.="}\n";
        $retorno.="</script>\n";
        return $retorno;
    }
}

?>
