<?php

require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
require_once LibQ . 'Zend/Json.php';
require_once 'App/LibQ/ControladorBase.php';

require_once 'App/LibQ/Config.php';
require_once DIRMODULOS . 'Sueldos/Modelo/SueldosModelo.php';
require_once 'App/LibQ/Input.php';

/**
 *  Clase Controladora del Modulo Sueldos
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Sueldos
 * 
 */
class SueldosControlador extends ControladorBase {

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
        "'id'" => "'Id'",
        "'empleado'" => "'Empleado'",
        "'periodo_pago'" => "'Período Pago'",
        "'nro_recibo'" => "'Recibo Número'",
        "'remuneracion_gravada'" => "'Remuneración Gravada'",
        "'remuneracion_nogravada'" => "'Remuneración No Gravada'",
        "'descuentos'" => "'Descuentos'",
        "'total'" => "'Total'",
        "'eliminado'" => "'Eliminado'"
    );

    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=sueldos&sub=agregar',
        'classIcono' => 'icono-nuevo32'
    );

    /**
     * Propiedad usada para configurar el boton FILTRAR
     * @var type array
     */
    private $_paramBotonFiltrar = array(
        'class' => 'btn_filtrar',
        'evento' => "onclick=\"javascript: submitbutton('filtrar')\"",
        'href' => "\"javascript:void(0);\""
    );

    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href' => 'index.php?option=sueldos');

    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
    private $_paramBotonGuardar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"",
    );

    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=sueldos&sub=listar',
        'classIcono' => 'icono-lista32'
    );

    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */

    function __construct() {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Sueldos/Vista');
        require_once DIRMODULOS . 'Sueldos/Modelo/SueldosModelo.php';
        $this->_modelo = new SueldosModelo();
    }

    /**
     * Metodo que lleva al menu de los sueldos
     * @return void
     */
    public function index() {
        $this->_layout->content = $this->_vista->render('SueldosVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    /**
     * Metodo que lleva a la pag donde se cargan los sueldos
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar() {
        require_once DIRMODULOS . 'Sueldos/Forms/CargaSueldos.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        /* Busco los empleados */
        $empleados = array();
        $datos_array = $this->_modelo->listadoEmpleados(Array('id', 'apellidos', 'nombres'));
        foreach ($datos_array as $empleadoBuscado) {
            $empleados[] = array($empleadoBuscado->id => $empleadoBuscado->apellidos . ', ' . $empleadoBuscado->nombres);
        }

        $this->_form = new Form_CargaSueldos($empleados, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['periodo_pago'] = implode('/', array_reverse(explode('/', $values['periodo_pago'])));
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
        $this->_layout->content = $this->_vista->render('AgregarSueldoVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    public function editar($arg) {
        require_once DIRMODULOS . 'Sueldos/Forms/CargaSueldos.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $where = implode(',', $arg);
        $sueldoBuscado = $this->_modelo->buscarSueldo($where);
        if (is_object($sueldoBuscado)) {
            $this->_varForm['id'] = $sueldoBuscado->id;
            $this->_varForm['empleado'] = $sueldoBuscado->empleado;
            $this->_varForm['periodo_pago'] = implode('/', array_reverse(explode('-', $sueldoBuscado->periodo_pago)));
            $this->_varForm['nro_recibo'] = $sueldoBuscado->nro_recibo;
            $this->_varForm['remuneracion_gravada'] = $sueldoBuscado->remuneracion_gravada;
            $this->_varForm['remuneracion_nogravada'] = $sueldoBuscado->remuneracion_nogravada;
            $this->_varForm['descuentos'] = $sueldoBuscado->descuentos;
            $this->_varForm['total'] = $sueldoBuscado->total;
            $this->_varForm['eliminado'] = $sueldoBuscado->eliminado;
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['empleado'] = '';
            $this->_varForm['periodo_pago'] = '';
            $this->_varForm['nro_recibo'] = '';
            $this->_varForm['remuneracion_gravada'] = '';
            $this->_varForm['remuneracion_nogravada'] = '';
            $this->_varForm['descuentos'] = '';
            $this->_varForm['total'] = '';
            $this->_varForm['eliminado'] = '';
        }
        /* Busco los empleados */
        $empleados = array();
        $datos_array = $this->_modelo->listadoEmpleados(Array('id', 'apellidos', 'nombres'));
        foreach ($datos_array as $empleadoBuscado) {
            $empleados[] = array($empleadoBuscado->id => $empleadoBuscado->apellidos . ', ' . $empleadoBuscado->nombres);
        }

        $this->_form = new Form_CargaSueldos($empleados, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            $json = $this->_form->processAjax(array($this->_form->getElement('fecha_comprobante')));
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['periodo_pago'] = implode('/', array_reverse(explode('/', $values['periodo_pago'])));
                $guardado = $this->_modelo->actualizar($values, $arg);
                if ($guardado > 0) {
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
        $this->_layout->content = $this->_vista->render('AgregarSueldoVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * 
     * Lista los sueldos
     * @param string $arg
     */
    public function listar($arg = '') {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('Sueldos');
        $grilla->setUrl(LIVESITE . '/index.php?option=sueldos&sub=jsonListarSueldos');
        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'empleado'" => "'Empleado'",
            "'periodo_pago'" => "'Período Pago'",
            "'nro_recibo'" => "'Recibo Número'",
            "'remuneracion_gravada'" => "'Remu. Gravada'",
            "'remuneracion_nogravada'" => "'Rem. No Gravada'",
            "'descuentos'" => "'Descuentos'",
            "'total'" => "'Total'",
        ));

        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '50', 'align' => "rigth"),
            array('name' => 'Empleado', 'index' => 'empleado', 'width' => '190'),
            array('name' => 'Período Pago', 'index' => 'periodo_pago', 'width' => '100', 'formatter' => 'date', 'formatoptions' => array('srcformat' => "Y-m-d", 'newformat' => "d-M-Y")),
            array('name' => 'Recibo Número', 'index' => 'nro_recibo', 'width' => '70', 'align' => "right"),
            array('name' => 'Rem. Gravada', 'index' => 'remuneracion_gravada', 'width' => '80', 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
            array('name' => 'Rem. No Gravada', 'index' => 'remuneracion_nogravada', 'width' => '80', 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
            array('name' => 'Descuentos', 'index' => 'descuentos', 'width' => '80', 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
            array('name' => 'Total', 'index' => 'total', 'width' => '80', 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ "))
        ));
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('index.php?option=sueldos&sub=editar&id=');
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);

        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=sueldos';
        } else {
            $filtroBoton = '&lista=sueldos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=sueldos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoSueldoVista.php');
        echo $this->_layout->render();
    }

    public function jsonListarSueldos($arg = '') {
        $responce = new stdClass();
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
                $orden = 'sueldos.id';
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
        $result = $this->_modelo->listadoSueldos($inicio, $orden, '');
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array($row['id'],
                $row['CONCAT_WS(",", empleados.apellidos,empleados.nombres)'],
                $row['periodo_pago'],
                $row['nro_recibo'],
                $row['remuneracion_gravada'],
                $row['remuneracion_nogravada'],
                $row['descuentos'],
                $row['total']
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }

    /**
     * Resumen Anual de los Sueldos Pagados
     * @param array $arg 
     */
    public function resumenSueldosAnual ($arg='')
    {    
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=sueldos&sub=jsonListarSueldosAnual');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
//            "'YEAR(gastos.fecha_comprobante)'"=>"'Fecha Fact.'",
            "'SUM(gastos.remuneracion_gravada)'"=>"'Rem.Grav.'",
            "'SUM(gastos.remuneracion_nogravada)'"=>"'Rem.No Grav.'",
            "'SUM(gastos.descuentos)'"=>"'Desc.'",
            "'SUM(gastos.total)'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
//            array('name' => 'Fecha Fact.', 'index' => 'YEAR(gastos.fecha_comprobante)', 'width' => 70, 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"Y",'newformat'=>"Y")),
            array('name' => 'Rem.Grav.', 'index' => 'SUM(sueldos.importe_gravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Rem.No Grav.', 'index' => 'SUM(sueldos.importe_nogravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Desc', 'index' => 'SUM(sueldos.iva_inscripto)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'SUM(sueldos.total)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=sueldos&sub=resumenSueldosMensual&id=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=sueldos';
        } else {
            $filtroBoton = '&lista=sueldos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=sueldos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoSueldoVista.php');
        echo $this->_layout->render();
    }
    
    
    
    /**
     * Lista Resumen de Sueldos Por Anio
     * @param string $arg
     */
    public function jsonListarSueldosAnual ($arg='')
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
                $orden = 'YEAR( sueldos.periodo_pago )';
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
        $todos = count($this->_modelo->resumenSueldosAnual($inicio, $orden, '' ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenSueldosAnual($inicio, $orden, '' );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['YEAR(sueldos.periodo_pago)'];
            $responce->rows[$i]['cell'] = array($row['YEAR(sueldos.periodo_pago)'],
//                $row['YEAR(gastos.fecha_comprobante)'],
                $row['SUM(sueldos.remuneracion_gravada)'],
                $row['SUM(sueldos.remuneracion_nogravada)'],
                $row['SUM(sueldos.descuentos)'],
                $row['SUM(sueldos.total)']
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    
    public function resumenSueldosMensual($arg = '') 
    {
        $anio = explode('=', $arg[0]);
        $filtroBoton = $filtroBoton = 'YEAR(sueldos.periodo_pago)=' . $anio[1];
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('SUELDOS MENSUALES AÑO: '.$anio[1]);
        $grilla->setUrl(LIVESITE . '/index.php?option=sueldos&sub=jsonListarSueldosMensual&'.$filtroBoton);
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'SUM(sueldos.remuneracion_gravada)'"=>"'Imp.Grav.'",
            "'SUM(sueldos.remuneracion_nogravada)'"=>"'Imp.No Grav.'",
            "'SUM(sueldos.descuentos)'"=>"'IVA'",
            "'SUM(sueldos.total)'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name' => 'Rem.Grav.', 'index' => 'SUM(sueldos.remuneracion_gravada)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Rem.No Grav.', 'index' => 'SUM(sueldos.remuneracion_nogravada)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Desc.', 'index' => 'SUM(sueldos.descuentos)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'SUM(sueldos.total)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
//        $grilla->setActionOnDblClickRow('/index.php?option=gastos&sub=resumenGastosMensual&id=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=sueldos';
        } else {
            $filtroBoton = '&lista=sueldos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=sueldos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoSueldoVista.php');
        echo $this->_layout->render();
    }

    public function jsonListarSueldosMensual ($arg='')
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
                $orden = 'MONTH( sueldos.periodo_pago )';
            }
            /** Me fijo si el argumento es el tipo de orden (ASC o DESC) */
            if (!empty($_GET['sord'])) {
                $orden .= ' ' . Input::get('sord');
            } else {
                $orden .= ' ASC';
            }
            /** Si el argumento es un array entonces creo el filtro */
            if (is_array($arg)) {
                $anio = explode('=', $arg[0]);
//                print_r($anio);
//                $filtroBoton = '&' . implode("&", $arg);
                $filtroBoton = 'YEAR(sueldos.periodo_pago)=' . $anio[1];
            } else {
                $filtroBoton = '';
            }
//            print_r($filtroBoton);
        }
        $json = new Zend_Json();
        $todos = count($this->_modelo->resumenSueldosMensual($inicio, $orden, $filtroBoton ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenSueldosMensual($inicio, $orden, $filtroBoton );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['MONTH(sueldos.periodo_pago)'];
            $responce->rows[$i]['cell'] = array($row['MONTH(sueldos.periodo_pago)'],
//                $row['YEAR(gastos.fecha_comprobante)'],
                $row['SUM(sueldos.remuneracion_gravada)'],
                $row['SUM(sueldos.remuneracion_nogravada)'],
                $row['SUM(sueldos.descuentos)'],
                $row['SUM(sueldos.total)']
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    
    /**
     * 
     * Lista los resumenSueldosProveedorAnio
     * @param string $arg
     */
    public function resumenSueldosProveedorAnio($arg = '') {
        require_once 'App/LibQ/Grilla.php';
        require_once 'App/vistas/html/LibQ_BarraHerramientas.php';
        $campoRecibido = '';
        $valorRecibido = '';
        if (isset($arg)) {
            if (!empty($_GET['pg'])) {
                $pag = Input::get('pg');
            } else {
                $pag = 1;
            }
            $inicio = 0 + ($pag - 1) * 30;
            if (!empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'id DESC';
            }
        }
        $filtro = $this->_crearFiltro($arg);
        $fuenteDatos = $this->_modelo->resumenSueldosProveedorAnio($inicio, $orden, $filtro);
        $grilla = new Grilla($fuenteDatos);
        $grilla->setTotalPaginas(ceil($this->_modelo->getCantidadRegistros($filtro) / $this->_config->get('limiteGrilla')));
        $grilla->setPagina($pag);
        $grilla->setEncabezadoGrilla('GASTOS');
        $grilla->setFiltrar('SI');
        $grilla->setColNames(array(
            'id' => 'Id',
            'razon_social' => 'Proveedor',
            'Anio' => 'Año',
            'SUM(sueldos.importe_gravado)' => 'Imp.Grav.',
            'SUM(sueldos.importe_nogravado)' => 'Imp.No Grav.',
            'SUM(sueldos.iva_inscripto)' => 'IVA',
            'SUM(sueldos.iva_diferencial)' => 'IVA Dif.',
            'SUM(sueldos.percepcion)' => 'Percep.',
            'SUM(sueldos.total)' => 'Total',
        ));

        $grilla->setCampos(array(
            'razon_social' => 'Proveedor',
            'SUM(sueldos.importe_gravado)' => 'Imp.Grav.',
            'SUM(sueldos.importe_nogravado)' => 'Imp.No Grav.',
            'SUM(sueldos.iva_inscripto)' => 'IVA',
            'SUM(sueldos.iva_diferencial)' => 'IVA Dif.',
            'SUM(sueldos.percepcion)' => 'Percep.',
            'SUM(sueldos.total)' => 'Total',
        ));
        $grilla->setFormatoCol(array(
            'id' => 'entero',
            'SUM(sueldos.importe_gravado)' => 'moneda',
            'SUM(sueldos.importe_nogravado)' => 'moneda',
            'SUM(sueldos.iva_inscripto)' => 'moneda',
            'SUM(sueldos.iva_diferencial)' => 'moneda',
            'SUM(sueldos.percepcion)' => 'moneda',
            'SUM(sueldos.total)' => 'moneda',
        ));
        $grilla->setLink('index.php?option=sueldos&sub=listar');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', 'sueldos');
        $bh->addBoton('Filtrar', 'sueldos');
        $filtroExportar = $campoRecibido . '=' . $valorRecibido;
        $bh->addBoton('Exportar', 'sueldos', $filtroExportar);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->render();
        $this->_layout->content = $this->_vista->render('ListadoSueldoVista.php');
        echo $this->_layout->render();
    }

    private function _crearFiltro($pag) {
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
                //                $this->_redirect('index.php?option=sueldos&sub=listar');
            }
        }
        return $filtro;
    }

    private function _controlar_nro_factura($nro_factura = '', $proveedor = '', $comprobante = '', $tipo_comprobante = '') {
        $consulta = sprintf("nro_comprobante = '%s' && proveedor = %d && comprobante = '%s' && tipo_comprobante = '%s'", $nro_factura, $proveedor, $comprobante, $tipo_comprobante);
        $sueldoBuscado = $this->_modelo->buscarSueldo($consulta);
        if (empty($sueldoBuscado)) {
            $retorno = 'ok';
        } else {
            $retorno = 'Ya existe un comprobante con ese Número. Verifique por favor';
        }
        return $retorno;
    }

    public function eliminar($arg = '') {
        $where = implode(',', $arg);
        $values['eliminado'] = '1';
        $this->_modelo->actualizar($values, $arg);
        $this->_vista->mensajes = Mensajes::presentarMensaje($this->_config->get('datoseliminados'), 'info');
        parent::_redirect(LIVESITE . '/index.php?option=sueldos&sub=listar');
    }

    public function exportar() {
        
    }

    public static function datosGraficoSueldosMensuales($anio, $mes1) 
    {
        require_once DIRMODULOS . 'Sueldos/Modelo/SueldosModelo.php';
        $modelo = new SueldosModelo();
        $dato = $modelo->totalSueldosMensual($anio, $mes1);
        return $dato;
    }

}
