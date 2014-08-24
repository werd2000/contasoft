<?php
require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
require_once LibQ . 'Zend/Json.php';
require_once 'App/LibQ/ControladorBase.php';

require_once 'App/LibQ/Config.php';
require_once DIRMODULOS . 'Proveedores/Modelo/ProveedoresModelo.php';
require_once 'App/LibQ/Input.php';

/**
 *  Clase Controladora del Modulo Proveedores
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Proveedores
 * 
 */
class ProveedoresControlador extends ControladorBase
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
        'razon_social'=>'Cuenta',
        'domicilio'=>'Proveedor',
        'condicion_iva'=>'Fecha Comp.',
        'cuit'=>'Comprobante',
        'tel'=>'Tipo',
        'cel'=>'Nro',
        'email'=>'Imp. Grav',
        'eliminado'=>'Eliminado'
    );
/**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=proveedores&sub=agregar',
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
    private $_paramBotonVolver = array('href'=>'index.php?option=proveedores');
    
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
        'href' => 'index.php?option=proveedores&sub=listar',
        'classIcono' => 'icono-lista32'
        );


    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */
    function __construct ()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Proveedores/Vista');
        require_once DIRMODULOS . 'Proveedores/Modelo/ProveedoresModelo.php';
        $this->_modelo = new ProveedoresModelo();
    }
    
    /**
     * Metodo que lleva al menu de los proveedores
     * @return void
     */
    public function index ()
    {
        $this->_layout->content = $this->_vista->render('ProveedoresVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }
    
    /**
     * Metodo que lleva a la pag donde se cargan los gastos
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar ()
    {
        require_once DIRMODULOS . 'Proveedores/Forms/CargaProveedores.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
//        require_once LibQ . 'MyFechaHora.php';
        $this->_form = new Form_CargaProveedores($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $ultimoId = $this->_modelo->guardar($values);
                if ($ultimoId > 0) {
                    $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
                } else {
                    $this->_vista->mensajes = Mensajes::presentarMensaje(ERROR_GUARDAR, 'error');
                }
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarProveedorVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    public function editar ($arg)
    {
        require_once DIRMODULOS . 'Proveedores/Forms/CargaProveedores.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $where = implode(',', $arg);
        $gastoBuscado = $this->_modelo->buscarProveedor($where);
        if (is_object($gastoBuscado)){
            $this->_varForm['id'] = $gastoBuscado->id;
            $this->_varForm['cuenta'] = $gastoBuscado->cuenta;
            $this->_varForm['proveedor'] = $gastoBuscado->proveedor;
            $this->_varForm['fecha_comprobante'] = implode('/', array_reverse(explode('-', $gastoBuscado->fecha_comprobante)));
            $this->_varForm['comprobante'] = $gastoBuscado->comprobante;
            $this->_varForm['tipo_comprobante'] = $gastoBuscado->tipo_comprobante;
            $this->_varForm['nro_comprobante'] = $gastoBuscado->nro_comprobante;
            $this->_varForm['importe_gravado'] = $gastoBuscado->importe_gravado;
            $this->_varForm['importe_nogravado'] = $gastoBuscado->importe_nogravado;
            $this->_varForm['iva_inscripto'] = $gastoBuscado->iva_inscripto;
            $this->_varForm['iva_diferencial'] = $gastoBuscado->iva_diferencial;
            $this->_varForm['percepciones'] = $gastoBuscado->percepcion;
            $this->_varForm['total'] = $gastoBuscado->total;
            $this->_varForm['eliminado'] = $gastoBuscado->eliminado;
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['cuenta'] = '';
            $this->_varForm['proveedor'] = '';
            $this->_varForm['fecha_comprobante'] = '';
            $this->_varForm['comprobante'] = '';
            $this->_varForm['tipo_comprobante'] = '';
            $this->_varForm['nro_comprobante'] = '';
            $this->_varForm['importe_gravado'] = '';
            $this->_varForm['importe_nogravado'] = '';
            $this->_varForm['iva_inscripto'] = '';
            $this->_varForm['iva_diferencial'] = '';
            $this->_varForm['percepciones'] = '';
            $this->_varForm['total'] = '';
            $this->_varForm['eliminado'] = '';
        }
        $datos_array = $this->_modelo->listadoCuentas();
        foreach ($datos_array as $cuentaBuscada) {
            $cuentas[] = array($cuentaBuscada->id => $cuentaBuscada->cuenta);
        }
        $datos_array = $this->_modelo->listadoProveedores(Array('id' , 'razon_social'));
        foreach ($datos_array as $proveedorBuscado) {
            $proveedores[] = array($proveedorBuscado->id => $proveedorBuscado->razon_social);
        }
        $this->_form = new Form_CargaProveedores($cuentas, $proveedores, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            $json = $this->_form->processAjax(array($this->_form->getElement('fecha_comprobante')));
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
        $this->_layout->content = $this->_vista->render('AgregarProveedorVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    /**
     * 
     * Lista los gastos
     * @param string $arg
     */
    public function listar ($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('PROVEEDORES');
        $grilla->setUrl(LIVESITE . '/index.php?option=proveedores&sub=jsonListarProveedores');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'razon_social'"=>"'Proveedor'",
            "'domicilio'"=>"'Domicilio'",
            "'condicion_iva'"=>"'IVA'",
            "'cuit'"=>"'CUIT'",
            "'tel'"=>"'Tel'",
            "'cel'"=>"'Cel'",
            "'email'"=>"'Email'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name'=>'Proveedor', 'index'=>'razon_social', 'width'=>160),
            array('name' => 'Domicilio', 'index' => 'domicilio', 'width' => 190),
            array('name' => 'IVA', 'index' => 'condicion_iva', 'width' =>100),
            array('name' => 'CUIT', 'index' => 'cuit', 'width' => 90),
            array('name' => 'Tel.', 'index' => 'tel', 'width' => 80),
            array('name' => 'Cel.', 'index' => 'cel', 'width' => 70, 'align'=>"right"),
            array('name' => 'Email', 'index' => 'email', 'width' => 190, 'align'=>"right",'formatter'=>'email')
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=proveedores&sub=editar&id=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=proveedores';
        } else {
            $filtroBoton = '&lista=gastos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=proveedores&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoProveedorVista.php');
        echo $this->_layout->render();
    }
    
    public function jsonListarProveedores($arg='')
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
                $orden = 'proveedores.id';
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
        $result = $this->_modelo->listadoProveedores($inicio, $orden, '' );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array($row['id'],
                $row['razon_social'],
                $row['domicilio'],
                $row['condicion_iva'],
                $row['cuit'],
                $row['tel'],
                $row['cel'],
                $row['email']
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    /**
     * 
     * Lista los resumenProveedoresProveedor
     * @param string $arg
     */
    public function resumenProveedoresProveedor ($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS POR PROVEEDOR');
        $grilla->setUrl(LIVESITE . '/index.php?option=gastos&sub=jsonListarProveedores');
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
        $grilla->setActionOnDblClickRow('/index.php?option=gastos&sub=editar&id=');
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
            $filtroBoton = '&' . implode("&", $arg) . '&lista=gastos';
        } else {
            $filtroBoton = '&lista=gastos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=gastos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoProveedorVista.php');
        echo $this->_layout->render();
    }
    
    public function resumenProveedoresMensual ($arg='')
    {
        require_once 'App/LibQ/Grilla.php';
        require_once 'App/vistas/html/LibQ_BarraHerramientas.php';
        $campoRecibido = '';
        $valorRecibido = '';
        if (isset ($arg)){
            if (! empty($_GET['pg'])) {
                $pag = Input::get('pg');
            } else {
                $pag = 1;
            }
            $inicio = 0 + ($pag - 1) * 30;
            if (! empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'id DESC';
            }
        }
        $filtro = $this->_crearFiltro($pag);
        $fuenteDatos = $this->_modelo->resumenProveedoresMensual($inicio, $orden, $filtro);
        $grilla = new Grilla($fuenteDatos);
        $grilla->setTotalPaginas(ceil($this->_modelo->getCantidadRegistros($filtro)/$this->_config->get('limiteGrilla')));
        $grilla->setPagina($pag);
        $grilla->setCamposFiltro(array('fecha_comprobante'=>'Fecha'));
        $grilla->setEncabezadoGrilla('RESUMEN GASTOS MENSUAL');
        $grilla->setFiltrar('SI');
        $grilla->setColNames(array(
                    'id'=>'Id',
                    'CONCAT_WS("-", MONTH(gastos.fecha_comprobante),YEAR(gastos.fecha_comprobante))'=>'Mes-Año',
                    'MONTH(gastos.fecha_comprobante)'=>'Mes',
                    'YEAR(gastos.fecha_comprobante)'=>'Año',
                    'SUM(gastos.importe_gravado)'=>'Imp.Grav.',
                    'SUM(gastos.importe_nogravado)'=>'Imp.No Grav.',
                    'SUM(gastos.iva_inscripto)'=>'IVA',
                    'SUM(gastos.iva_diferencial)'=>'IVA Dif.',
                    'SUM(gastos.percepcion)'=>'Percep.',
                    'SUM(gastos.total)'=>'Total',
        ));

        $grilla->setCampos(array(
                    'razon_social'=>'Proveedor',
                    'SUM(gastos.importe_gravado)'=>'Imp.Grav.',
                    'SUM(gastos.importe_nogravado)'=>'Imp.No Grav.',
                    'SUM(gastos.iva_inscripto)'=>'IVA',
                    'SUM(gastos.iva_diferencial)'=>'IVA Dif.',
                    'SUM(gastos.percepcion)'=>'Percep.',
                    'SUM(gastos.total)'=>'Total',
            ));
        $grilla->setFormatoCol(array(
                     'id'=>'entero',
                     'SUM(gastos.importe_gravado)'=>'moneda',
                     'SUM(gastos.importe_nogravado)'=>'moneda',
                     'SUM(gastos.iva_inscripto)'=>'moneda',
                     'SUM(gastos.iva_diferencial)'=>'moneda',
                     'SUM(gastos.percepcion)'=>'moneda',
                     'SUM(gastos.total)'=>'moneda',
            ));
        $grilla->setLink('index.php?option=gastos&sub=listar');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', 'gastos');
        $bh->addBoton('Filtrar', 'gastos'); 
        $filtroExportar = $campoRecibido . '=' . $valorRecibido;
        $bh->addBoton('Exportar', 'gastos',$filtroExportar); 
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->render();
        $this->_layout->content = $this->_vista->render('ListadoProveedorVista.php');
        echo $this->_layout->render();
    }
    
    /**
     * 
     * Lista los resumenProveedoresProveedorAnio
     * @param string $arg
     */
    public function resumenProveedoresProveedorAnio ($arg='')
    {
        require_once 'App/LibQ/Grilla.php';
        require_once 'App/vistas/html/LibQ_BarraHerramientas.php';
        $campoRecibido = '';
        $valorRecibido = '';
        if (isset ($arg)){
            if (! empty($_GET['pg'])) {
                $pag = Input::get('pg');
            } else {
                $pag = 1;
            }
            $inicio = 0 + ($pag - 1) * 30;
            if (! empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'id DESC';
            }
        }
        $filtro = $this->_crearFiltro($arg);
        $fuenteDatos = $this->_modelo->resumenProveedoresProveedorAnio($inicio, $orden, $filtro);
        $grilla = new Grilla($fuenteDatos);
        $grilla->setTotalPaginas(ceil($this->_modelo->getCantidadRegistros($filtro)/$this->_config->get('limiteGrilla')));
        $grilla->setPagina($pag);
        $grilla->setEncabezadoGrilla('GASTOS');
        $grilla->setFiltrar('SI');
        $grilla->setColNames(array(
                    'id'=>'Id',
                    'razon_social'=>'Proveedor',
                    'Anio'=>'Año',
                    'SUM(gastos.importe_gravado)'=>'Imp.Grav.',
                    'SUM(gastos.importe_nogravado)'=>'Imp.No Grav.',
                    'SUM(gastos.iva_inscripto)'=>'IVA',
                    'SUM(gastos.iva_diferencial)'=>'IVA Dif.',
                    'SUM(gastos.percepcion)'=>'Percep.',
                    'SUM(gastos.total)'=>'Total',
        ));

        $grilla->setCampos(array(
                    'razon_social'=>'Proveedor',
                    'SUM(gastos.importe_gravado)'=>'Imp.Grav.',
                    'SUM(gastos.importe_nogravado)'=>'Imp.No Grav.',
                    'SUM(gastos.iva_inscripto)'=>'IVA',
                    'SUM(gastos.iva_diferencial)'=>'IVA Dif.',
                    'SUM(gastos.percepcion)'=>'Percep.',
                    'SUM(gastos.total)'=>'Total',
            ));
        $grilla->setFormatoCol(array(
                     'id'=>'entero',
                     'SUM(gastos.importe_gravado)'=>'moneda',
                     'SUM(gastos.importe_nogravado)'=>'moneda',
                     'SUM(gastos.iva_inscripto)'=>'moneda',
                     'SUM(gastos.iva_diferencial)'=>'moneda',
                     'SUM(gastos.percepcion)'=>'moneda',
                     'SUM(gastos.total)'=>'moneda',
            ));
        $grilla->setLink('index.php?option=gastos&sub=listar');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', 'gastos');
        $bh->addBoton('Filtrar', 'gastos'); 
        $filtroExportar = $campoRecibido . '=' . $valorRecibido;
        $bh->addBoton('Exportar', 'gastos',$filtroExportar); 
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->render();
        $this->_layout->content = $this->_vista->render('ListadoProveedorVista.php');
        echo $this->_layout->render();
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
//                $this->_redirect('index.php?option=gastos&sub=listar');
            }
        }
        return $filtro;
    }


    private function _controlar_nro_factura($nro_factura='', $proveedor='', $comprobante='', $tipo_comprobante='') 
    {
        $consulta = sprintf("nro_comprobante = '%s' && proveedor = %d && comprobante = '%s' && tipo_comprobante = '%s'", $nro_factura, $proveedor, $comprobante, $tipo_comprobante);
        $gastoBuscado = $this->_modelo->buscarProveedor($consulta);
        if (empty($gastoBuscado)) {
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
    	$this->_vista->mensajes = Mensajes::presentarMensaje($this->_config->get('datoseliminados'),'info');
        parent::_redirect(LIVESITE .'/index.php?option=gastos&sub=listar');
    }
    
    public function exportar()
    {
        
    }


}

