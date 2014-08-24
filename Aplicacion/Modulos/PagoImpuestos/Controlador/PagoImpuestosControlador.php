<?php
require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
require_once 'App/LibQ/ControladorBase.php';
require_once 'App/LibQ/Config.php';
require_once DIRMODULOS . 'PagoImpuestos/Modelo/PagoImpuestosModelo.php';
require_once 'App/LibQ/Input.php';
require_once LibQ . 'Google/Chart/ChartGoogle.php';
require_once LibQ . 'Zend/Json.php';

/**
 *  Clase Controladora del Modulo PagoImpuestos
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package PagoImpuestos
 * 
 */
class PagoImpuestosControlador extends ControladorBase
{
    /**
     * Propiedad usada para enviar los elementos del formulario
     * @var type Array
     */
    private $_varForm = array();
    /**
     * Propiedad usada para establecer los campos de la BD
     * @var type Array
     */
    private $_campos = array(
        'id'=>'Id',
        'cuenta'=>'Cuenta',
        'proveedor'=>'Proveedor',
        'fecha_comprobante'=>'Fecha Comp.',
        'comprobante'=>'Comprobante',
        'tipo_comprobante'=>'Tipo',
        'nro_comprobante'=>'Nro',
        'importe_gravado'=>'Imp. Grav',
        'importe_nogravado'=>'Imp. No Grav.',
        'iva_inscripto'=>'Iva Insc.',
        'iva_diferencial'=>'Iva Dif.',
        'percepciones'=>'Percep.',
        'total'=>'Total',
        'eliminado'=>'Eliminado'
    );
/**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=PagoImpuestos&sub=agregar',
        'classIcono' => 'icono-nuevo32'
        );
    
    /**
     * Propiedad usada para configurar el boton FILTRAR
     * @var type array
     */
    private $_paramBotonFiltrar = array(
        'class' => 'btn_filtrar' ,
        'evento' => "onclick=\"javascript: submitbutton('filtrar')\"" ,
        'href'=>"\"javascript:void(0);\""
    );
    
    /**
     * Propiedad usada para configurar el boton ELIMINAR
     * @var type Array
     */
    private $_paramBotonEliminar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Eliminar')\"",
    );
    
    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href'=>'index.php?option=PagoImpuestos');
    
    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
    private $_paramBotonGuardar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"" ,
        );
    
   
    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=PagoImpuestos&sub=listar',
        'classIcono' => 'icono-lista32'
        );


    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */
    function __construct ()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'PagoImpuestos/Vista');
        require_once DIRMODULOS . 'PagoImpuestos/Modelo/PagoImpuestosModelo.php';
        $this->_modelo = new PagoImpuestosModelo();
    }
    
    /**
     * Metodo que lleva al menu de los impuestos
     * @return void
     */
    public function index ()
    {
        $this->_layout->content = $this->_vista->render('PagoImpuestosVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }
    
    /**
     * Metodo que lleva a la pag donde se cargan los impuestos
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar ()
    {
        require_once DIRMODULOS . 'PagoImpuestos/Forms/CargaPagoImpuestos.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $listaImpuestos = array();
        $datos_array = $this->_modelo->listadoImpuestos();
        foreach ($datos_array as $impuestoBuscado) {
            $listaImpuestos[] = array($impuestoBuscado->impuesto=>$impuestoBuscado->impuesto);
        }

        $this->_form = new Form_CargaPagoImpuestos($listaImpuestos, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fecha_comprobante']=implode('/', array_reverse(explode('/', $values['fecha_comprobante'])));
                $ultimoId = $this->_modelo->guardar($values);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarPagoImpuestoVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    public function editar ($arg)
    {
        require_once DIRMODULOS . 'PagoImpuestos/Forms/CargaPagoImpuestos.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $where = implode(',', $arg);
        $pagoImpuestoBuscado = $this->_modelo->buscarPagoImpuesto($where);
        $datos_array = $this->_modelo->listadoImpuestos();
        foreach ($datos_array as $impuestoBuscado) {
            $listaImpuestos[] = array($impuestoBuscado->impuesto=>$impuestoBuscado->impuesto);
        }
        if (is_object($impuestoBuscado)){
            $this->_varForm['id'] = $pagoImpuestoBuscado->id;
            $this->_varForm['impuesto'] = $pagoImpuestoBuscado->impuesto;
            $this->_varForm['fecha_comprobante'] = implode('/', array_reverse(explode('-', $pagoImpuestoBuscado->fecha_comprobante)));
            $this->_varForm['total'] = $pagoImpuestoBuscado->total;
            $this->_varForm['observaciones'] = $pagoImpuestoBuscado->observaciones;
            $this->_varForm['eliminado'] = $pagoImpuestoBuscado->eliminado;
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['impuesto'] = '';
            $this->_varForm['fecha_comprobante'] = '';
            $this->_varForm['total'] = '';
            $this->_varForm['observaciones'] = '';
            $this->_varForm['eliminado'] = '';
        }
        $this->_form = new Form_CargaPagoImpuestos($listaImpuestos, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fecha_comprobante']=implode('/', array_reverse(explode('/', $values['fecha_comprobante'])));
                $guardado = $this->_modelo->actualizar($values,$arg);
                if ($guardado > 0 ){
                    $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
                }else{
                    $this->_vista->mensajes = Mensajes::presentarMensaje(ERROR_GUARDAR, 'error');
                }
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Eliminar', $this->_paramBotonEliminar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarPagoImpuestoVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    /**
     * 
     * Lista los impuestos
     * @param string $arg
     */
    public function listar ($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('LISTA DE IMPUESTOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=PagoImpuestos&sub=jsonListarPagoImpuestos');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'impuesto'"=>"'Impuesto'",
            "'caracter'"=>"'Fecha Pago'",
            "'tipo_vencimiento'"=>"'Importe'",
            "'observaciones'"=>"'Observaciones'",
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name'=>'Impuesto', 'index'=>'impuesto', 'width'=>90),
            array('name'=>'Fecha Pago', 'index'=>'fecha_comprobante', 'width'=>130, 'align'=>"right", 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"Y-m-d",'newformat'=>"d-m-Y")),
            array('name' => 'Importe', 'index' => 'total', 'width' => 130, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Observaciones', 'index' => 'observaciones', 'width' =>300),
        ));
        
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=PagoImpuestos&sub=editar&id=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=impuestos';
        } else {
            $filtroBoton = '&lista=impuestos';
        }
        $bh->addBoton('Exportar', array('href' => '/index.php?option=impuestos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoPagoImpuestosVista.php');
        echo $this->_layout->render();
    }
    
    public function jsonListarPagoImpuestos($arg='')
    {
//        print_r($arg);
        /** Me fijo si hay argumentos */
        if (isset($arg)) {
            /** Me fijo si existe el argumento page */
            if (!empty($_GET['page'])) {
                $pag = Input::get('page');
            } else {
                $pag = 1;
            }
            $inicio = ($pag - 1) * 30;
            /** Me fijo si existe el argumento de orden */
            if (!empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'impuestos.id';
            }
            /** Me fijo si el argumento es el tipo de orden (ASC o DESC) */
            if (!empty($_GET['sord'])) {
                $orden .= ' ' . Input::get('sord');
            } else {
                $orden .= ' ASC';
            }
            /** Si el argumento es un array entonces creo el filtro */
            if (is_array($arg)) {
                $filtroBoton = '&' . implode("&", $arg);
            } else {
                $filtroBoton = '';
            }
        }
        $json = new Zend_Json();
        $todos = $this->_modelo->getCantidadRegistros();
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->listadoPagoImpuestos($inicio, $orden, '' );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array($row['id'],
                $row['impuesto'],
                $row['fecha_comprobante'],
                $row['total'],
                $row['observaciones']
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    public function ultimosPagoImpuestos()
    {
//        setlocale(LC_MONETARY, 'es_AR');
        $retorno = '<table>';
        $i = 0;
        $listaPagoImpuestos = $this->_modelo->listadoPagoImpuestos(0, 'id DESC', '');
        foreach ($listaPagoImpuestos as $impuesto) {
            $factura = $impuesto['tipo_comprobante'] . $impuesto['nro_comprobante'];
//             money_format ('%i',$impuesto['total'])
            $total = new Zend_Currency(array('value' => $impuesto['total'], 'symbol' => '$',));
            $retorno.= '<tr><td><b><a href=index.php?option=impuestos&sub=editar&id=' . $impuesto['id'] .'>' . $impuesto['razon_social'] . '</a></b></td><td>' . $factura .'</td><td align="right">' . $total .'</td></tr>';
            $i++;
            if ($i >= 10){
                break;
            }
        }
        $retorno.='</table>';
        return $retorno;
    }

        /**
     * 
     * Lista los resumenPagoImpuestosProveedor
     * @param string $arg
     */
    public function resumenPagoImpuestosProveedor ($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS POR PROVEEDOR');
        $grilla->setUrl(LIVESITE . '/index.php?option=impuestos&sub=jsonListarPagoImpuestos');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'cuenta'"=>"'Cuenta'",
            "'razon_social'"=>"'Proveedor'",
            "'fecha_comprobante'"=>"'Fecha Fact.'",
            "'comprobante'"=>"'Comprobante'",
            "'tipo_comprobante'"=>"'Tipo'",
            "'nro_comprobante'"=>"'Nro'",
            "'importe_gravado'"=>"'Imp.Grav.'",
            "'importe_nogravado'"=>"'Imp.No Grav.'",
            "'iva_inscripto'"=>"'IVA'",
            "'iva_diferencial'"=>"'IVA Dif.'",
            "'percepcion'"=>"'Percep.'",
            "'total'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name'=>'Cuenta', 'index'=>'cuenta', 'width'=>90),
            array('name'=>'Proveedor', 'index'=>'razon_social', 'width'=>130),
            array('name' => 'Fecha Fact.', 'index' => 'fecha_comprobante', 'width' => 70, 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"Y-m-d",'newformat'=>"d-m-Y")),
            array('name' => 'Comprobante', 'index' => 'comprobante', 'width' =>100),
            array('name' => 'Tipo', 'index' => 'tipo_comprobante', 'width' => 50),
            array('name' => 'Nro', 'index' => 'nro_comprobante', 'width' => 80),
            array('name' => 'Imp.Grav.', 'index' => 'importe_gravado', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Imp.No Grav.', 'index' => 'importe_nogravado', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA', 'index' => 'iva_inscripto', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA Dif.', 'index' => 'iva_diferencial', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Percep.', 'index' => 'percepcion', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'total', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ "), 'summaryType'=>'sum'),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=impuestos&sub=editar&id=');
        $grilla->setSortname('razon_social');
        $grilla->setGrouping(array(
            "grouping"=>true,
            "groupSummary"=>'true',
            "campoGrouping"=>'Proveedor',
            "mostrarCampoGrupo"=>'false',
            "textoGrupo"=>'<b>{0} - {1} Factura(s)</b>'
            ));
                
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=impuestos';
        } else {
            $filtroBoton = '&lista=impuestos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=impuestos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoImpuestoVista.php');
        echo $this->_layout->render();
    }
    
    public function resumenPagoImpuestosMensual ($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS POR PROVEEDOR');
        $grilla->setUrl(LIVESITE . '/index.php?option=impuestos&sub=jsonListarPagoImpuestosMensual');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'cuenta'"=>"'Cuenta'",
            "'razon_social'"=>"'Proveedor'",            
            "'CONCAT_WS("-", MONTH(impuestos.fecha_comprobante),YEAR(impuestos.fecha_comprobante))'"=>"'Fecha Fact.'",
            "'comprobante'"=>"'Comprobante'",
            "'tipo_comprobante'"=>"'Tipo'",
            "'nro_comprobante'"=>"'Nro'",
            "'importe_gravado'"=>"'Imp.Grav.'",
            "'importe_nogravado'"=>"'Imp.No Grav.'",
            "'iva_inscripto'"=>"'IVA'",
            "'iva_diferencial'"=>"'IVA Dif.'",
            "'percepcion'"=>"'Percep.'",
            "'total'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name'=>'Cuenta', 'index'=>'cuenta', 'width'=>90),
            array('name'=>'Proveedor', 'index'=>'razon_social', 'width'=>130),
            array('name' => 'Fecha Fact.', 'index' => 'CONCAT_WS("-", MONTH(impuestos.fecha_comprobante),YEAR(impuestos.fecha_comprobante))', 'width' => 70, 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"m-Y",'newformat'=>"m-Y")),
            array('name' => 'Comprobante', 'index' => 'comprobante', 'width' =>100),
            array('name' => 'Tipo', 'index' => 'tipo_comprobante', 'width' => 50),
            array('name' => 'Nro', 'index' => 'nro_comprobante', 'width' => 80),
            array('name' => 'Imp.Grav.', 'index' => 'importe_gravado', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Imp.No Grav.', 'index' => 'importe_nogravado', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA', 'index' => 'iva_inscripto', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA Dif.', 'index' => 'iva_diferencial', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Percep.', 'index' => 'percepcion', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'total', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ "), 'summaryType'=>'sum'),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=impuestos&sub=editar&id=');
        $grilla->setSortname('fecha_comprobante');
        $grilla->setGrouping(array(
            "grouping"=>true,
            "groupSummary"=>'true',
            "campoGrouping"=>'Fecha Fact.',
            "mostrarCampoGrupo"=>'true',
            "textoGrupo"=>'<b>{0} - {1} Factura(s)</b>'
            ));
                
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=impuestos';
        } else {
            $filtroBoton = '&lista=impuestos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=impuestos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoImpuestoVista.php');
        echo $this->_layout->render();
    }
    
    /**
     * 
     * Lista Resumen de PagoImpuestos Por Anio
     * @param string $arg
     */
    public function jsonListarPagoImpuestosMensual ($arg='')
    {
        /** Me fijo si hay argumentos */
        if (isset($arg)) {
            /** Me fijo si existe el argumento page */
            if (!empty($_GET['page'])) {
                $pag = Input::get('page');
            } else {
                $pag = 1;
            }
            $inicio = ($pag - 1) * 30;
            /** Me fijo si existe el argumento de orden */
            if (!empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'impuestos.fecha_comprobante';
            }
            /** Me fijo si el argumento es el tipo de orden (ASC o DESC) */
            if (!empty($_GET['sord'])) {
                $orden .= ' ' . Input::get('sord');
            } else {
                $orden .= ' DESC';
            }
            /** Si el argumento es un array entonces creo el filtro */
            if (is_array($arg)) {
                $filtroBoton = '&' . implode("&", $arg);
            } else {
                $filtroBoton = '';
            }
        }
        $json = new Zend_Json();
        $todos = $this->_modelo->getCantidadRegistros();
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenPagoImpuestosMensual($inicio, $orden, '' );
//        print_r($result);
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array($row['id'],
                $row['cuenta'],
                $row['razon_social'],
                $row['CONCAT_WS("-", MONTH(impuestos.fecha_comprobante),YEAR(impuestos.fecha_comprobante))'],
                $row['comprobante'],
                $row['tipo_comprobante'],
                $row['nro_comprobante'],
                $row['importe_gravado'],
                $row['importe_nogravado'],
                $row['iva_inscripto'],
                $row['iva_diferencial'],
                $row['percepcion'],
                $row['total']
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    public function graficoPagoImpuestosMensuales(){
        $anio = date('Y');
        $mes1 = date('m')-1;
        $mes2 = $mes1 - 1;
        $mes3 = $mes2 - 1;
        $encabezado = "['Mes','Importe $']";
        $dato1 = $this->_modelo->totalPagoImpuestosMensual($anio, $mes1);
        $dato2 = $this->_modelo->totalPagoImpuestosMensual($anio, $mes2);
        $dato3 = $this->_modelo->totalPagoImpuestosMensual($anio, $mes3);
        $mesT1 = date('F',mktime(0,0,0,$mes1,1,$anio));
        $mesT2 = date('F',mktime(0,0,0,$mes2,1,$anio));
        $mesT3 = date('F',mktime(0,0,0,$mes3,1,$anio));
//        $retorno .= "['2007',  1030]";
        $datos = $encabezado . "," . "['" . $mesT3 . "'," . $dato3[0]['gtotal'] . "]";
        $datos .= ",['" . $mesT2 . "'," . $dato2[0]['gtotal'] . "]";
        $datos .= ",['" . $mesT1 . "'," . $dato1[0]['gtotal'] . "]";
        $grafico = new ChartGoogle('PagoImpuestos Mensuales');
        $grafico->setTitutloEjeX('Mes');
        $grafico->setDatos($datos);
        $retorno = $grafico->incluirJs();
        $retorno .= '<div id="chart_div"></div>';
        return $retorno;
    }


    private function _crearFiltro($pag)
    {
        $filtro = '';
        $valorRecibido = Input::get('valor');
        if ($valorRecibido != 'valor' && $valorRecibido != '') {
            $campoRecibido = Input::get('campo');
            switch ($campoRecibido) {
                case 'fecha_comprobante':
                    $campo = $campoRecibido;
                    $valor = "'" . implode('/', array_reverse(explode('/', $valorRecibido))) . "'";
                    $valor = implode('/', array_reverse(explode('/', $valorRecibido)));                    
                    break;
                case 'cuenta':
                    $campo = 'cuentas.id';
                    $cuentas = array();
                    $datos_array = $this->_modelo->listadoCuentas();
                    foreach ($datos_array as $cuentaBuscada) {
                        $cuentas[$cuentaBuscada->cuenta] = $cuentaBuscada->id;
                    }
                    $valor = $cuentas[$valorRecibido];
                    break;
                case 'razon_social':
                    $campo = 'proveedores.id';
                    $datos_array = $this->_modelo->listadoProveedores(Array('id', 'razon_social'));
                    foreach ($datos_array as $proveedorBuscado) {
                        $proveedores[$proveedorBuscado->razon_social] = $proveedorBuscado->id;
                    }
                    $valor = $proveedores[$valorRecibido];
                    break;
                default:
                    $valor = Input::get('valor');
                    $campo = Input::get('campo');
                    break;
            }
            $filtro = $campo . '=' . $valor;
        } else {
            $filtro = '';
            $url = trim($_SERVER['QUERY_STRING']);
            if ($pag <= 1) {
//                $this->_redirect('index.php?option=impuestos&sub=listar');
            }
        }
        return $filtro;
    }


    private function _controlar_nro_factura($nro_factura='', $proveedor='', $comprobante='', $tipo_comprobante='') 
    {
        $consulta = sprintf("nro_comprobante = '%s' && proveedor = %d && comprobante = '%s' && tipo_comprobante = '%s'", $nro_factura, $proveedor, $comprobante, $tipo_comprobante);
        $impuestoBuscado = $this->_modelo->buscarImpuesto($consulta);
        if (empty($impuestoBuscado)) {
            $retorno = 'ok';
        } else {
            $retorno = 'Ya existe un comprobante con ese Número. Verifique por favor';
        }
        return $retorno;
    }
    
    public function eliminar ($arg='')
    {
	$where = implode(',', $arg);
    	$values['eliminado']='1';
    	$this->_modelo->actualizar($values,$arg);
    	$this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS,'info');
        parent::_redirect(LIVESITE .'/index.php?option=impuestos&sub=listar');
    }
    
    public function exportar()
    {
        
    }


}
