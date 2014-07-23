<?php
/*require_once 'Zend/Currency.php';
require_once 'Zend/Date.php';
require_once 'configuration.php';
require_once 'App/LibQ/Grilla/Celda.php';
require_once 'App/LibQ/Grilla/Fila.php';
require_once 'App/LibQ/Grilla/Abstract.php';*/
class Grilla extends Grilla_Abstract
{
    private $_retorno;
    private $_encabezadoGrilla = '';
    private $_filtrar;
    private $_source = array();
    private $_nombreColumnas = array();
    private $_campos = array();
    private $_camposFiltro = array();
    private $_url; //la url para la paginación
    private $_link; //la url para que vaya cuando se haga clic
    /** Formato a la columna **/
    private $_formatoCol = array();
    private $_pagina;
    private $_totalPaginas;
    private $_limit;
    private $_ordenar_por;
    private $_marcar_fila;
    private $_condicion = array();
    private $_corteControl;
    private $_campoCorte1;
    private $_valoresPosiblesFiltro;
    
    /**
     * Constructor de la clase Grilla
     * @param resource $dataSource
     * @param array $params 
     */
    function __construct ($dataSource = null, $params = array())
    {
        $this->_source = $dataSource;
        $this->_url = "http://" . $_SERVER['HTTP_HOST'] . htmlspecialchars($_SERVER['REQUEST_URI']);
        $filters = array('orderBy' => 'StripTags' , 'direction' => 'alpha' , 'page' => 'digits');
        $valids = array('orderBy' => array('allowEmpty' => true) , 'direction' => array('Alpha' , 'allowEmpty' => true) , 'page' => array('int' , 'default' => 0));
        $input = new Zend_Filter_Input($filters, $valids, $params);
        if (! $input->isValid()) {
            $errors = '';
            foreach ($input->getMessages() as $messageId => $messages) {
                $message = current($messages);
                $errors .= "'$messageId': $message\n";
            }
            require_once 'Zend/Exception.php';
            throw new Zend_Exception('Invalid Parmas for DataGrid: ' . $errors);
        }
    }
    
    /**
     * Establece el total de páginas que tendrá la grilla
     * @param imt $paginas 
     */
    public function setTotalPaginas($paginas)
    {
        $this->_totalPaginas = $paginas;
    }
    
    /**
     * Establece el limite de registros que lee por cada pantalla
     * @param type $limiteGrilla 
     */
    public function setLimit($limiteGrilla)
    {
        $this->_limit = $limiteGrilla;
    }
    
    /**
     * Establece la página actual
     * @param int $pagina
     */
    public function setPagina($pagina)
    {
        $this->_pagina = $pagina;
    }
    
    public function crearFila ($celdas)
    {
        $fila = new Fila($celdas);
        return $fila;
    }
    public function crearCelda ($contenido)
    {
        $celda = new Celda($contenido);
        return $celda;
    }
    public function setLink ($link)
    {
        $this->_link = $link;
    }
    public function setMarcarFila ($marcar, $condicion)
    {
        $this->_marcar_fila = $marcar;
        $this->_condicion = $condicion;
    }
    
    /**
     * Establece el título de cada una de las columnas de la grilla
     * @param array $nombreColumnas
     * @access public
     */
    public function setColNames ($nombreColumnas)
    {
        $this->_nombreColumnas = $nombreColumnas;
    }
    
    public function setCampos ($campos)
    {
        if (is_array($campos)) {
            $this->_campos = $campos;
        } else {
            throw new Zend_Exception('Se esperaba un array');
        }
    }
    
    public function setCamposFiltro($campos)
    {
        if (is_array($campos)){
            $this->_camposFiltro = $campos;
        }else{
            $this->_camposFiltro = explode(',', $campos);
        }
    }


    /**
     * Indica por que columna deben ordenarse los datos
     * @param string $ordenar_por 
     */
    public function setOrdenarPor ($ordenar_por)
    {
        $this->_ordenar_por = $ordenar_por;
    }
    
    /**
     * Establece el formato de la columna
     * @param array $formatoColumnas 
     * @access public
     */
    public function setFormatoCol ($formatoColumnas)
    {
        if (is_array($formatoColumnas)) {
            $this->_formatoCol = $formatoColumnas;
        } else {
            throw new Zend_Exception('Se esperaba un array');
        }
    }
    
    /**
     * Establece el título de la grilla
     * @param string $encabezado
     * @access public
     */
    public function setEncabezadoGrilla ($encabezado = '')
    {
        $this->_encabezadoGrilla = $encabezado;
    }
    
    /**
     * Establece si se muestra o no el area de filtro
     * @example 'SI', 'NO'
     * @param string $filtrar
     * @access public
     */
    public function setFiltrar($filtrar = '')
    {
        $this->_filtrar = $filtrar;
    }
    
    private function _setCorteControl($corte)
    {
        $this->_corteControl = $corte;
    }
    
    public function setCampoCorte1($campo)
    {
        $this->_campoCorte1 = $campo;
    }
    
    private function _mostrarAreaFiltro ()
    {
        require_once 'Zend/Form.php';
        require_once 'App/LibQ/Config.php';
        $elementDecorators = array(
            'ViewHelper',
            array('Description', 	array('tag' => 'span', 'class' => 'element-description')),
            array('Errors'),
            array('Label', array('separator' => ' ')),
            array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        );
        if(count($this->_camposFiltro)==0){
            $this->_camposFiltro = $this->_campos;
        }
        if (in_array('Fecha', $this->_camposFiltro)){
            $this->_valoresPosiblesFiltro = Array('0'=>"Seleccione",'1'=>"Enero",'2'=>"Febrero",'3'=>"Marzo",'4'=>"Abril",'5'=> "Mayo", '6'=>"Junio",'7'=> "Julio",'8'=> "Agosto",'9'=> "Septiembre", '10'=>"Octubre", '11'=>"Noviembre", '12'=> "Diciembre");
            $valor = new Zend_Form_Element_Select('valor',array(
                'decorators' => $elementDecorators,
                'label'=>"Seleccione el valor a buscar",'value'=>''
            ));
            $valor->setOptions(array('multiOptions'=>$this->_valoresPosiblesFiltro));
        }else{
            $this->_valoresPosiblesFiltro = '';
            $valor = new Zend_Form_Element_Text('valor',array(
                'decorators' => $elementDecorators,
                'label'=>"Ingrese el valor a buscar",'value'=>$this->_valoresPosiblesFiltro
            ));
        }
        $retorno = "<div id=\"LibQ_BarraHerramientas\">";
        $campo = new Zend_Form_Element_Select('campo',array(
                'decorators' => $elementDecorators,
                'label' => "Seleccione un campo de la lista",'value'=> ''
            ));
        $campo->setOptions(array('multiOptions' => $this->_camposFiltro));
        $retorno .= $campo;
        
        $retorno .= $valor;
        $retorno .= "</div>\n";
        return $retorno;
        
    }
    
    /**
     * Arma el encabezado de la grilla
     * @return string
     * @access public
     */
    private function _mostrarEncabezadoGrilla ()
    {
        $retorno = "<div class=\"ui-jqgrid-titlebar ui-widget-header ui-corner-top ui-helper-clearfix\">";
        $retorno .= "<a href=\"javascript:void(0);\" role=\"link\" class=\"ui-jqgrid-titlebar-close HeaderButton\" style=\"right: 0px;\">\n";
        $retorno .= "<span class=\"ui-icon ui-icon-circle-triangle-n\"></span></a>\n";
        $retorno .= "<span class=\"ui-jqgrid-title\">\n";
        $retorno .= $this->_encabezadoGrilla . "</span>\n";
        $retorno .= "</div>\n";
        return $retorno;
    }
    
    /**
     * Establece la fuente de los datos
     * @param array $datos
     * @access public
     * @todo aceptar datos json y xml
     */
    public function setSource ($datos)
    {
        $this->_source = $datos;
    }
    
    /**
     * Arma el contenido de la grilla
     * Recibe el número de la pág. que debe mostrar
     * @param int $page
     * @return string
     */
    private function _contenidoConST ()
    {
        if (Zend_Session::isStarted()) {
            $conta_sesion = new Zend_Session_Namespace('Usuario');
        }
        $subtotal = $conta_sesion->Transporte;
        $retorno = "<div id=\"contenido\">\n";
        if ($this->_filtrar != ''){
            $retorno .= $this->_mostrarAreaFiltro();
        }
        $retorno .= "<div class=\"ui-jqgrid ui-widget ui-widget-content ui-corner-all\" id=\"ventana\">\n";
        $retorno .= "<div class=\"ui-jqgrid-view\">";
        
        if ($this->_encabezadoGrilla != '') {
            $retorno .= $this->_mostrarEncabezadoGrilla();
        }
        $retorno .=  "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"grilla\" class=\"ui-jqgrid\">\n";
        $retorno .= "<tr class=\"ui-state-default ui-jqgrid-hdiv\">";
        $retorno .= implode('', self::_armarTH($this->_source));
        $retorno .= "</tr>";
        $this->_setCorteControl($this->_source[0][$this->_campoCorte1]);
        foreach ($this->_source as $fila) {
            if ($this->_corteControl == $fila[$this->_campoCorte1]){
                $subtotal += $fila['total'];
            }else{
                $retorno .= "<tr id=$num class=\"ui-widget-content jqgrow ui-row-ltr\" role=\"row\" aria-selected=\"false\">";
                $retorno .= implode('', self::_armarSubTotal($subtotal));
                $retorno .= "</tr>";
                $subtotal = 0;
                $conta_sesion->Transporte = 0;
                $subtotal += $fila['total'];
                $this->_setCorteControl($fila[$this->_campoCorte1]);
            }
            $num = $fila["id"];
            $retorno .= "<tr id=$num class=\"ui-widget-content jqgrow ui-row-ltr\" role=\"row\" aria-selected=\"false\">";
            $retorno .= implode('', self::_armarCeldas($fila));
            $retorno .= "</tr>";
        }
        //mostrar Total de Totales
        $retorno .= '</table>';
        $conta_sesion->Transporte = $subtotal;
        $retorno .= "</div>";
        $retorno .= $this->_pie();
        $retorno .= "</div>";
        $retorno .= "</div>";
        return $retorno;
    }
    
    private function _contenido ()
    {
        $retorno = "<div id=\"contenido\">\n";
        if ($this->_filtrar != ''){
            $retorno .= $this->_mostrarAreaFiltro();
        }
        $retorno .= "<div class=\"ui-jqgrid ui-widget ui-widget-content ui-corner-all\" id=\"ventana\">\n";
        $retorno .= "<div class=\"ui-jqgrid-view\">";
        
        if ($this->_encabezadoGrilla != '') {
            $retorno .= $this->_mostrarEncabezadoGrilla();
        }
        $retorno .=  "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"grilla\" class=\"ui-jqgrid\">\n";
        $retorno .= "<tr class=\"ui-state-default ui-jqgrid-hdiv\">";
        $retorno .= implode('', self::_armarTH($this->_source));
        $retorno .= "</tr>";
        foreach ($this->_source as $fila) {
            $num = $fila["id"];
            $retorno .= "<tr id=$num class=\"ui-widget-content jqgrow ui-row-ltr\" role=\"row\" aria-selected=\"false\">";
            $retorno .= implode('', self::_armarCeldas($fila));
            $retorno .= "</tr>";
        }
        //mostrar Total de Totales
        $retorno .= '</table>';
        $retorno .= "</div>";
        $retorno .= $this->_pie();
        $retorno .= "</div>";
        $retorno .= "</div>";
        return $retorno;
    }
    
    /**
     * Arma una celda de la grilla
     * Recibe una fila de la fuente de datos
     * @param array $fila
     * @return string
     */
    private function _armarSubTotal($subtotal)
    {
        $celdas = '';
        $celdas[]= '<td colSpan="5"><strong>Subtotal</strong></td>';
        //self::_darFormatoCelda ($this->_formatoCol[$campo], $dato) .'</td>';
        $celdas[]= '<td style="text-align:right" colSpan="4"><strong>'.self::_darFormatoCelda ('moneda',$subtotal).'</strong></td>';
        $celdas[]= '<td colSpan="3"></td>';
        return $celdas;
    }
    
    /**
     * Arma la fila de subtotal
     * @param array $fila
     * @return string
     */
    private function _armarCeldas($fila)
    {
        $celdas = '';
        foreach ($fila as $campo=>$dato){
            if (isset ($this->_formatoCol[$campo])){
                $celdas[] = '<td style="text-align:'.self::_darAlineacionCelda($this->_formatoCol[$campo]).';">'. self::_darFormatoCelda ($this->_formatoCol[$campo], $dato) .'</td>';
            }else{
                $celdas[] = '<td>'.$dato.'</td>';
            }
        }    
        return $celdas;
    }
    
    /**
     * Establece alineación a la celda que se está creando
     * @param string $formato
     * @return string 
     */
    private function _darAlineacionCelda($formato)
    {
        $retorno = 'left';
        switch ($formato){
            case 'moneda':
            case 'fecha':
            case 'entero':
                $retorno = 'right';
                break;
        }
        return $retorno;
    }
    /**
     * Da formato al valor de la celda
     * @param string $formato
     * @param $dato
     * @return currency | date 
     */
    private function _darFormatoCelda($formato, $dato = '0')
    {
    	//require_once 'Zend/Currency.php';
        $retorno=$dato;
        switch ($formato){
            case 'moneda':
                $currency = new Zend_Currency(
                        array(
                            'value' => doubleval($dato),
                            'currency' => '$'
                        ),'es_AR'
                );
                $retorno = $currency->__toString();
                break;
            case 'fecha':
                $date = new Zend_Date($dato, 'dd MM y');
                $retorno = $date->get(Zend_Date::DATES);
                break;
        }
        return $retorno;
    }
    
    /**
     * Arma la celda de encabezado de la grilla
     * Recibe una fila de la fuente de datos y compara el nombre de cada campo
     * con el array _nombreColumnas para establecer el nombre de la columna
     * @param array $datos
     * @return string
     */
    private function _armarTH($datos)
    {
        $encabezado = '';
        foreach ($datos[0] as $campo=>$dato){
            if (isset($this->_nombreColumnas[$campo])){
                $encabezado[] = '<td class="ui-state-default ui-th-column ui-th-ltr">'
                                . '<a href="' . $this->_link . '&sidx='.$campo.'" >' 
                                . $this->_nombreColumnas[$campo]
                                . '</a></td>';
            }else{
                $encabezado[] = '<td class="ui-state-default ui-th-column ui-th-ltr">'.$campo.'</td>';
            }
        }    
        return $encabezado;
    }
    
    /**
     * Arma el pie de la grilla
     * @return string
     * @access private
     */
    private function _pie ()
    {
        if (isset($this->_pagina) && $this->_pagina > 1){
            $url = explode('amp;', $this->_url, -1);
            $url = htmlspecialchars(implode($url));
        } else {
            $url = $this->_url . '&';
        }

        $retorno = "<div id=\"pager\" class=\"ui-state-default ui-jqgrid-pager ui-corner-bottom\" style=\"width: 100%;\">\n";
        $retorno .= "<div id=\"pg_pager\" class=\"ui-pager-control\">\n";
        $retorno .= "<table style=\"width: 100%;\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" id=\"grilla\" class=\"ui-pg-table\" >\n";
        $retorno .= "<tr>";
        $retorno .= "<td align=\"left\" id=\"pager_left\">";
//        //$jqgrid->setExport(LIVESITE . '/exportar.php?option=iva_compras');
////        if ($_POST['fecha_desde']) {
//        if (isset($this->_fechadesde)){
//            $fecha_desde = "fd=" . $_POST['fecha_desde'];
//        }
////        if ($_POST['fecha_hasta']) {
//        if (isset($this->_fechahasta)){
//            $fecha_hasta = "fh=" . $_POST['fecha_hasta'];
//        }
//        if ($fecha_desde != "" && $fecha_hasta != "") {
//            $retorno .= "<a href=\"exportar.php?option=iva_compras&$fecha_desde&$fecha_hasta\" target=\"_blank\"><span class=\"ui-icon ui-icon-disk\"></span></a>\n";
//        } else {
//            $retorno .= "<a href=\"exportar.php?option=iva_compras\" target=\"_blank\"><span class=\"ui-icon ui-icon-disk\"></span></a>\n";
//        }

        $retorno .= '</td>' . "\n";
        $retorno .= "<td align=\"center\" id=\"pager_center\" style=\"width: 282px;\">\n";
        $retorno .= $this->_paginacion($url);
       
        $retorno .= '</td>' . "\n";
        $retorno .= "<td align=\"right\" id=\"pager_right\">";
        $retorno .= '</td>' . "\n";
        $retorno .= '</tr>';
        $retorno .= '</table>';
        $retorno .= "</div>\n";
        $retorno .= "</div>\n";
        return $retorno;
    }
    
    private function _paginacion($url)
    {
        $retorno  = "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" class=\"ui-pg-table\" style=\"table-layout: auto; width: 100%;\">\n";
        $retorno .= "<tr>\n";
        $retorno .= "<td class=\"ui-pg-button ui-corner-all\" id=\"first\" style=\"cursor: default;\">\n";
        $retorno .= "<a href=\"$url" . "pg=1\"><span class=\"ui-icon ui-icon-seek-first\"></span></a>\n";
        $retorno .= "</td>\n";
        $retorno .= "<td class=\"ui-pg-button ui-corner-all\" id=\"prev\">\n";
        if ($this->_pagina > 1) {
            $prevPag = $this->_pagina - 1;
        } else {
            $prevPag = 1;
        }
        $retorno .= "<a href=\"$url" . "pg=$prevPag\"><span class=\"ui-icon ui-icon-seek-prev\"></span></a>\n";
        $retorno .= '</td>' . "\n";
        $retorno .= "<td style=\"width: 4px;\" class=\"ui-pg-button ui-state-disabled\">\n";
        $retorno .= "<span class=\"ui-separator\"></span>\n";
        $retorno .= '</td>' . "\n";
        $retorno .= "<td dir=\"ltr\">\n";
        $retorno .= "P&aacute;g $this->_pagina de $this->_totalPaginas\n";
        $retorno .= '</td>' . "\n";
        $retorno .= "<td style=\"width: 4px;\" class=\"ui-pg-button ui-state-disabled\">\n";
        $retorno .= "<span class=\"ui-separator\"></span>\n";
        $retorno .= '</td>' . "\n";
        $retorno .= "<td class=\"ui-pg-button ui-corner-all\" id=\"next\" style=\"cursor: default;\">\n";
        if ($this->_pagina != $this->_totalPaginas) {
            $nextPag = $this->_pagina + 1;
        } else {
            $nextPag = $this->_totalPaginas;
        }
        $retorno .= "<a href=\"$url" . "pg=$nextPag\"><span class=\"ui-icon ui-icon-seek-next\"></span></a>\n";
        $retorno .= "<td class=\"ui-pg-button ui-corner-all\" id=\"last\">\n";
        $retorno .= "<a href=\"$url" . "pg=$this->_totalPaginas\"><span class=\"ui-icon ui-icon-seek-end\"></span></a>\n";
        $retorno .= '</td>' . "\n";
        $retorno .= "<td dir=\"ltr\">\n";
//        $retorno .= "<select role=\"listbox\" class=\"ui-pg-selbox\"><option value=\"10\" role=\"option\">10</option><option value=\"20\" role=\"option\">20</option><option selected=\"\" value=\"30\" role=\"option\">30</option></select>\n";
        //        </td></tr></tbody>
        $retorno .= '</td>' . "\n";
        $retorno .= "</tr>\n";
        $retorno .= "</table>\n";
        return $retorno;
    }
    
    /**
     * Arma la grilla y la muestra
     * @param int $pg
     * @return string
     */
    public function render ()
    {
        //print_r($this->_contenido);
    	$this->_retorno .= $this->_contenido();
        return $this->_retorno;
        //return 'Grilla';
    }
}
?>