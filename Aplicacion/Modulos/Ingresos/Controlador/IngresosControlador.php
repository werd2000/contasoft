<?php
require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
require_once LibQ . 'Zend/Json.php';
require_once 'App/LibQ/ControladorBase.php';

require_once 'App/LibQ/Config.php';
require_once DIRMODULOS . 'Ingresos/Modelo/IngresosModelo.php';
require_once 'App/LibQ/Input.php';

/**
 *  Clase Controladora del Modulo Ingresos
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Ingresos
 * 
 */
class IngresosControlador extends ControladorBase
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
        'cliente'=>'Proveedor',
        'fecha_comprobante'=>'Fecha Comp.',
        'comprobante'=>'Comprobante',
        'tipo_comprobante'=>'Tipo',
        'nro_comprobante'=>'Nro',
//        'importe_gravado'=>'Imp. Grav',
//        'importe_nogravado'=>'Imp. No Grav.',
//        'iva_inscripto'=>'Iva Insc.',
//        'iva_diferencial'=>'Iva Dif.',
//        'percepciones'=>'Percep.',
        'total'=>'Total',
        'eliminado'=>'Eliminado'
    );
/**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=ingresos&sub=agregar',
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
    private $_paramBotonVolver = array('href'=>'index.php?option=ingresos');
    
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
        'href' => 'index.php?option=ingresos&sub=listar',
        'classIcono' => 'icono-lista32'
        );


    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */
    function __construct ()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Ingresos/Vista');
        require_once DIRMODULOS . 'Ingresos/Modelo/IngresosModelo.php';
        $this->_modelo = new IngresosModelo();
    }
    
    /**
     * Metodo que lleva al menu de los ingresos
     * @return void
     */
    public function index ()
    {
        $this->_layout->content = $this->_vista->render('IngresosVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }
    
    /**
     * Metodo que lleva a la pag donde se cargan los ingresos
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar ()
    {
        require_once DIRMODULOS . 'Ingresos/Forms/CargaIngresos.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $cuentas = array();
        $clientes = array();
        $datos_array = $this->_modelo->listadoCuentas();
        foreach ($datos_array as $cuentaBuscada) {
            $cuentas[] = array($cuentaBuscada->id => $cuentaBuscada->cuenta);
        }
        $datos_array = $this->_modelo->listadoProveedores(Array('id' , 'razon_social'));
        foreach ($datos_array as $clienteBuscado) {
            $proveedores[] = array($clienteBuscado->id => $clienteBuscado->razon_social);
        }
        $this->_form = new Form_CargaIngresos($cuentas, $proveedores, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                if ($this->_controlar_nro_factura($values['nro_comprobante'], $values['cliente'], $values['comprobante'], $values['tipo_comprobante'])=='ok'){
                    $values['fecha_comprobante']=implode('/', array_reverse(explode('/', $values['fecha_comprobante'])));
                    $ultimoId = $this->_modelo->guardar($values);
                    $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
                }else{
                    $this->_vista->mensajes = Mensajes::presentarMensaje(FACTURAEXISTE, 'error');
                }
 
//                $values['fecha_comprobante'] = MyFechaHora::getFechaBd($values['fecha_comprobante']);
//                $this->_modelo->guardar($values);
//                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarIngresoVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    public function editar ($arg)
    {
        require_once DIRMODULOS . 'Ingresos/Forms/CargaIngresos.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $where = implode(',', $arg);
        $ingresoBuscado = $this->_modelo->buscarIngreso($where);
        if (is_object($ingresoBuscado)){
            $this->_varForm['id'] = $ingresoBuscado->id;
            $this->_varForm['cuenta'] = $ingresoBuscado->cuenta;
            $this->_varForm['cliente'] = $ingresoBuscado->cliente;
            $this->_varForm['fecha_comprobante'] = implode('/', array_reverse(explode('-', $ingresoBuscado->fecha_comprobante)));
            $this->_varForm['comprobante'] = $ingresoBuscado->comprobante;
            $this->_varForm['tipo_comprobante'] = $ingresoBuscado->tipo_comprobante;
            $this->_varForm['nro_comprobante'] = $ingresoBuscado->nro_comprobante;
            $this->_varForm['condicion_venta'] = $ingresoBuscado->condicion_venta;
            $this->_varForm['total'] = $ingresoBuscado->total;
            $this->_varForm['fecha_cobro'] = MyFechaHora::getFechaAr($ingresoBuscado->fecha_cobro);
            $this->_varForm['recibo_nro'] = $ingresoBuscado->recibo_nro;
            $this->_varForm['eliminado'] = $ingresoBuscado->eliminado;
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['cuenta'] = '';
            $this->_varForm['cliente'] = '';
            $this->_varForm['fecha_comprobante'] = '';
            $this->_varForm['comprobante'] = '';
            $this->_varForm['tipo_comprobante'] = '';
            $this->_varForm['nro_comprobante'] = '';
            $this->_varForm['condicion_venta'] = $ingresoBuscado->condicion_venta;
            $this->_varForm['total'] = '';
            $this->_varForm['fecha_cobro'] = '';
            $this->_varForm['recibo_nro'] = '';
            $this->_varForm['eliminado'] = '';
        }
        $datos_array = $this->_modelo->listadoCuentas();
        foreach ($datos_array as $cuentaBuscada) {
            $cuentas[] = array($cuentaBuscada->id => $cuentaBuscada->cuenta);
        }
        $datos_array = $this->_modelo->listadoProveedores(Array('id' , 'razon_social'));
        foreach ($datos_array as $clienteBuscado) {
            $clientees[] = array($clienteBuscado->id => $clienteBuscado->razon_social);
        }
        $this->_form = new Form_CargaIngresos($cuentas, $clientees, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            $json = $this->_form->processAjax(array($this->_form->getElement('fecha_comprobante')));
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fecha_comprobante']=implode('/', array_reverse(explode('/', $values['fecha_comprobante'])));
                $values['fecha_cobro'] = MyFechaHora::getFechaBd($values['fecha_cobro']);
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
        $this->_layout->content = $this->_vista->render('AgregarIngresoVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    /**
     * 
     * Lista los ingresos
     * @param string $arg
     */
    public function listar ($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('INGRESOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=ingresos&sub=jsonListarIngresos');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'cuenta'"=>"'Cuenta'",
            "'razon_social'"=>"'Proveedor'",
            "'fecha_comprobante'"=>"'Fecha Fact.'",
            "'comprobante'"=>"'Comprobante'",
            "'tipo_comprobante'"=>"'Tipo'",
            "'nro_comprobante'"=>"'Nro'",
            "'total'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name'=>'Cuenta', 'index'=>'cuenta', 'width'=>100),
            array('name'=>'Proveedor', 'index'=>'razon_social', 'width'=>140),
            array('name' => 'Fecha Fact.', 'index' => 'fecha_comprobante', 'width' => 70, 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"Y-m-d",'newformat'=>"d-m-Y")),
            array('name' => 'Comprobante', 'index' => 'comprobante', 'width' =>100),
            array('name' => 'Tipo', 'index' => 'tipo_comprobante', 'width' => 50),
            array('name' => 'Nro', 'index' => 'nro_comprobante', 'width' => 100),
            array('name' => 'Total', 'index' => 'total', 'width' => 120, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=ingresos&sub=editar&id=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=ingresos';
        } else {
            $filtroBoton = '&lista=ingresos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=ingresos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoIngresosVista.php');
        echo $this->_layout->render();
    }
    
    public static function datosGraficoIngresosMensuales($anio, $mes1)
    {
        require_once DIRMODULOS . 'Ingresos/Modelo/IngresosModelo.php';
        $modelo = new IngresosModelo();
        $dato = $modelo->totalIngresosMensual($anio, $mes1);
        return $dato;
    }
    
    public function jsonListarIngresos($arg='')
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
                $orden = 'ingresos.id';
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
        $result = $this->_modelo->listadoIngresos($inicio, $orden, '' );
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
                $row['fecha_comprobante'],
                $row['comprobante'],
                $row['tipo_comprobante'],
                $row['nro_comprobante'],
//                $row['importe_gravado'],
//                $row['importe_nogravado'],
//                $row['iva_inscripto'],
//                $row['iva_diferencial'],
//                $row['percepcion'],
                $row['total']
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    public function ultimosIngresos()
    {
//        setlocale(LC_MONETARY, 'es_AR');
        $retorno = '<table>';
        $i = 0;
        $listaIngresos = $this->_modelo->listadoIngresos(0, 'id DESC', '');
        foreach ($listaIngresos as $ingreso) {
            $factura = $ingreso['tipo_comprobante'] . $ingreso['nro_comprobante'];
//            money_format ('%i',$ingreso['total'])
            $total = new Zend_Currency(array('value' => $ingreso['total'],'symbol' => '$', ));
            $retorno.= '<tr><td><b><a href=index.php?option=ingresos&sub=editar&id=' . $ingreso['id'] .'>' . $ingreso['razon_social'] . '</a></b></td><td>' . $factura .'</td><td align="right">' . $total .'</td></tr>';
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
     * Lista los resumenIngresosProveedor
     * @param string $arg
     */
    public function resumenIngresosProveedor ($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS POR PROVEEDOR');
        $grilla->setUrl(LIVESITE . '/index.php?option=ingresos&sub=jsonListarIngresos');
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
        $grilla->setActionOnDblClickRow('/index.php?option=ingresos&sub=editar&id=');
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
            $filtroBoton = '&' . implode("&", $arg) . '&lista=ingresos';
        } else {
            $filtroBoton = '&lista=ingresos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=ingresos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoIngresoVista.php');
        echo $this->_layout->render();
    }
    
    public function resumenIngresosMensual ($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS POR PROVEEDOR');
        $grilla->setUrl(LIVESITE . '/index.php?option=ingresos&sub=jsonListarIngresosMensual');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'cuenta'"=>"'Cuenta'",
            "'razon_social'"=>"'Proveedor'",            
            "'CONCAT_WS("-", MONTH(ingresos.fecha_comprobante),YEAR(ingresos.fecha_comprobante))'"=>"'Fecha Fact.'",
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
            array('name' => 'Fecha Fact.', 'index' => 'CONCAT_WS("-", MONTH(ingresos.fecha_comprobante),YEAR(ingresos.fecha_comprobante))', 'width' => 70, 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"m-Y",'newformat'=>"m-Y")),
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
        $grilla->setActionOnDblClickRow('/index.php?option=ingresos&sub=editar&id=');
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
            $filtroBoton = '&' . implode("&", $arg) . '&lista=ingresos';
        } else {
            $filtroBoton = '&lista=ingresos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=ingresos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoIngresosVista.php');
        echo $this->_layout->render();
    }
    
    /**
     * 
     * Lista Resumen de Ingresos Por Anio
     * @param string $arg
     */
    public function jsonListarIngresosMensual ($arg='')
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
                $orden = 'ingresos.fecha_comprobante';
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
        $result = $this->_modelo->resumenIngresosMensual($inicio, $orden, '' );
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
                $row['CONCAT_WS("-", MONTH(ingresos.fecha_comprobante),YEAR(ingresos.fecha_comprobante))'],
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
                    $campo = 'clientees.id';
                    $datos_array = $this->_modelo->listadoProveedores(Array('id', 'razon_social'));
                    foreach ($datos_array as $clienteBuscado) {
                        $clientees[$clienteBuscado->razon_social] = $clienteBuscado->id;
                    }
                    $valor = $clientees[$valorRecibido];
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
//                $this->_redirect('index.php?option=ingresos&sub=listar');
            }
        }
        return $filtro;
    }


    private function _controlar_nro_factura($nro_factura='', $cliente='', $comprobante='', $tipo_comprobante='') 
    {
        $consulta = sprintf("nro_comprobante = '%s' && cliente = %d && comprobante = '%s' && tipo_comprobante = '%s'", $nro_factura, $cliente, $comprobante, $tipo_comprobante);
        $ingresoBuscado = $this->_modelo->buscarIngreso($consulta);
        if (empty($ingresoBuscado)) {
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
        parent::_redirect(LIVESITE .'/index.php?option=ingresos&sub=listar');
    }
    
    public function exportar()
    {
        
    }


}
