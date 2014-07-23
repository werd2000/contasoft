<?php
/** Error reporting */
error_reporting(E_ALL);
/** PHPExcel */
require_once LibQ . 'PHPExcel.php';
/** PHPExcel_IOFactory */
require_once LibQ . 'PHPExcel/IOFactory.php';
class ExportToExcel
{
    private $_titulo;
    private $_encabezados;
    private $_encBD;
    private $_objPHPExcel;
    private $_consulta;
    private $_columnas = array("A" , "B" , "C" , "D" , "E" , "F" , "G" , "H" , "I" , "J" , "K" , "L" , "M" , "N" , "O" , "P" , "Q" , "R" , "S" , "T" , "U" , "V" , "W" , "X" , "Y" , "Z");
    private $_ifTotales;
    private $_ultimaColumna;
    private $_nombreArchivo;
    private $_formatoCol;
    /**
     * Establece si se calculan los totales
     * @param $ifTotales
     * @return void
     */
    public function setIfTotales ($ifTotales)
    {
        $this->_ifTotales = $ifTotales;
    }
    
    /**
     * Establece el formato de columnas
     * @param type $formato Array
     * @return void
     */
    public function setFormatoCol($formato)
    {
        $this->_formatoCol = $formato;
    }

    /**
     * Establece el t�tulo de la planilla en la primera fila
     * @param $titulo
     * @return void
     */
    public function setTitulo ($titulo)
    {
        $this->_titulo = $titulo;
    }
    /**
     * Coloca los encabezados de la planilla de excel
     * @param $encabezado
     * @return void
     */
    public function setEncabezados ($encabezado)
    {
        if (is_array($encabezado)) {
            $this->_encabezados = $encabezado;
        } else {
            $this->_encabezados = explode(",", $encabezado);
        }
    }
    /**
     * Coloca los encabezados de la bd
     * @param $encabezado
     * @return void
     */
    public function setEncBD ($encBD)
    {
        if (is_array($encBD)) {
            $this->_encBD = $encBD;
        }else{
            $this->_encBD = explode(",", $encBD);
        }
    }
    /**
     * Establece los par�metros de la consulta para la BD
     * @param $opciones
     * @return void
     */
    public function setConsulta ($opciones)
    {
        $this->_consulta = $opciones;
    }
    /**
     * Combina las celdas del t�tulo
     * @return void
     */
    private function _combinarCeldasTitulo ()
    {
        $this->_objPHPExcel->getActiveSheet()->mergeCells('A1:' . $this->_ultimaColumna . '1');
    }
    /**
     * Establece el ancho de columna autom�tico
     * @return void
     */
    private function _autosize ()
    {
        $cant = count($this->_encabezados);
        for ($j = 0; $j < $cant; $j ++) {
            $this->_objPHPExcel->getActiveSheet()->getColumnDimension($this->_columnas[$j])->setAutoSize(true);
        }
    }
    public function setEncabezadoPagina ($encabezado)
    {
        $this->_objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader($encabezado);
    }
    public function setPiePagina ($pie)
    {
        $this->_objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $this->_objPHPExcel->getProperties()->getTitle() . $pie);
    }
    function __construct ()
    {
        // Create new PHPExcel object
        $this->_objPHPExcel = new PHPExcel();
        // Set properties
        $this->_objPHPExcel->getProperties()->setCreator("Contasoft")->setLastModifiedBy("Walter Enrique Ruiz Diaz")->setTitle("Office 2003 XLS Document")->setSubject("Office 2003 XLS Document")->setDescription("Document for Office 2003 XLS, generated using PHP classes.")->setKeywords("office 2003 openxml php")->setCategory("Export result file");
        $this->_objPHPExcel->setActiveSheetIndex(0)->getPageSetup()->setPaperSize(5);
        $this->_objPHPExcel->setActiveSheetIndex(0)->getPageSetup()->setOrientation('landscape');
    }
    
    /**
     * Exporta los datos
     * @param Array $datos 
     */
    public function exportar ($datos)
    {
        /* Colocar titulo */
        $this->_ponerTitulo();
        /* cuento la cantidad de encabezados */
        $cant = count($this->_encabezados);
        /* establezco cual es la ultima columna utilizando la cant de encabezados 
           el array de columnas */
        $this->_ultimaColumna = $this->_columnas[$cant - 1];
        /* coloco los encabezado de datos */
        for ($i = 0; $i < $cant; $i ++) {
            $fila = 2;
            $this->_objPHPExcel->setActiveSheetIndex(0)->setCellValue($this->_columnas[$i] . $fila, $this->_encabezados[$i]);
        }
        /* coloco el nombre a la hoja */
        $this->_objPHPExcel->getActiveSheet()->setTitle($this->_titulo); 
        /* coloco el nombre del archivo a exportar */
        $this->_nombreArchivo = $this->_titulo . '.xls';
        /* cargo los datos */
        self::_grilla($datos, $cant);
        // Redirect output to a clientes web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $this->_nombreArchivo . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->_objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit();
    }
    
    private function _grilla ($datos, $cant)
    {
        /* Cantidad de datos */
        $count = count($datos);
        /* comienzo los datos en la fila 3 */
        $fila = 3;
        /* recorro las filas del array $datos */
        for ($i = 0; $i < $count; $i ++) {
            /* Me fijo si hay que calcular totales */
            if ($this->_ifTotales) {
                /** @todo colocar acá el calculo de totales */
            }
            /* recorro las columnas */
            for ($j = 0; $j < $cant; $j ++) {
                /* guardo en $enc el dato del array encBD */
                $enc = $this->_encBD[$j];
                /* escribo el dato en la celda */
                $this->_objPHPExcel->setActiveSheetIndex(0)->setCellValue($this->_columnas[$j] . $fila, $datos[$i][$enc]);
            }
            /* voy a la fila siguiente */
            $fila ++;
        }
        /* Si se calcularon los totales escribo debajo de los datos */
        if ($this->_ifTotales) {
//            $fila ++;
//            $this->_objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $fila, 'TOTAL')->setCellValue('I' . $fila, $tig)->setCellValue('K' . $fila, $tivai)->setCellValue('N' . $fila, $ttotal);
        }
        /* Combino las celdas del titulo */
        self::_combinarCeldasTitulo();       
        /* recorro el array donde estan los formatos */
        foreach ($this->_formatoCol as $id=>$val){
            /* busco en el Array encBD el $id del array de los formatos */
            $darFormato = array_keys($this->_encBD, $id);
            /* busco el indice del array de formato */
            $doyFormato = $darFormato[0];
            /* creo el rango de datos a formatear */
            $columnaI = $this->_columnas[$doyFormato].'3';
            $columnaF = $this->_columnas[$doyFormato].$fila;
            /* me fijo el tipo de datos */
            switch ($val){
                /* formato a los numeros enteros */
                case 'entero':
                    $this->_objPHPExcel->getActiveSheet()->getStyle($columnaI.':'.$columnaF)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
                    break;
                /* formato a las fechas */
                case 'fecha':
                    $this->_objPHPExcel->getActiveSheet()->getStyle($columnaI.':'.$columnaF)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
                    break;
                /* formato de moneda */
                case 'moneda':
                    $this->_objPHPExcel->getActiveSheet()->getStyle('I3:N' . $fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
                    break;
            }
        }
        
        /* Formato de tamaño al Titulo */
        $this->_ponerFormatoTitulo();
        /* Formato a los encabezados de datos */
        $this->_ponerFormatoEncabezadoDatos();
//        $this->_objPHPExcel->getActiveSheet()->getStyle('A1:N2')->getFont()->setBold(true);
//        $objPHPExcel->getActiveSheet()->getStyle('A:N2')->getFont()->setBold(true);
//        $this->_objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        //$objPHPExcel->getActiveSheet()->getStyle('F3:F'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
//        $this->_objPHPExcel->getActiveSheet()->getStyle('H3:H' . $fila)->getNumberFormat()->setFormatCode('0000-00000000');
        /* ancho automatico */
        self::_autosize();
        /* Establezco la primer hoja como la activa. Esa será la que se mostrará cuando
           abra excel  */
        $this->_objPHPExcel->setActiveSheetIndex(0);
    }
    
    /**
     * Poner el titulo de planilla en la celda A1
     */
    private function _ponerTitulo()
    {
        $this->_objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $this->_titulo);
    }
    
    /**
     * Poner formato al titulo que está en la celda A1
     */
    private function _ponerFormatoTitulo()
    {
        $cant = count($this->_encabezados);
        $celdaI = 'A1';
        $celdaF = $this->_ultimaColumna.'1';
        $rango = $celdaI . ':' . $celdaF;
        $this->_objPHPExcel->getActiveSheet()->getStyle($rango)->getFont()->setSize(20);       
        $this->_objPHPExcel->getActiveSheet()->getStyle('A1:N2')->getFont()->setBold(true);
  }
  
  /**
   * Poner formato a los encabezados de los datos
   */
  private function _ponerFormatoEncabezadoDatos()
  {
      $cant = count($this->_encabezados);
      $celdaI = 'A2';
      $celdaF = $this->_ultimaColumna.'2';
      $rango = $celdaI . ':' . $celdaF;
      $this->_objPHPExcel->getActiveSheet()->getStyle($rango)->getFont()->setSize(12);       
      $this->_objPHPExcel->getActiveSheet()->getStyle($rango)->getFont()->setBold(true);
  }
   

}