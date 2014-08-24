<?php

require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
require_once 'App/LibQ/ControladorBase.php';
require_once 'App/LibQ/Config.php';
require_once DIRMODULOS . 'Honorarios/Modelo/HonorariosModelo.php';
require_once 'App/LibQ/Input.php';
require_once LibQ . 'MyFechaHora.php';
require_once LibQ . 'Google/Chart/ChartGoogle.php';
require_once LibQ . 'Zend/Json.php';
/**
 *  Clase Controladora del Modulo Honorarios
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Honorarios
 * 
 */
class HonorariosControlador extends ControladorBase
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
        'id' => 'Id',
        'cuenta' => 'Cuenta',
        'proveedor' => 'Proveedor',
        'fecha_comprobante' => 'Fecha Comp.',
        'comprobante' => 'Comprobante',
        'tipo_comprobante' => 'Tipo',
        'nro_comprobante' => 'Nro',
        'importe_gravado' => 'Imp. Grav',
        'importe_nogravado' => 'Imp. No Grav.',
        'iva_inscripto' => 'Iva Insc.',
        'iva_diferencial' => 'Iva Dif.',
        'percepciones' => 'Percep.',
        'total' => 'Total',
        'eliminado' => 'Eliminado'
    );

    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=honorarios&sub=agregar',
        'classIcono' => 'icono-nuevo32'
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
    private $_paramBotonVolver = array('href' => 'index.php?option=honorarios');

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
        'href' => 'index.php?option=honorarios&sub=listar',
        'classIcono' => 'icono-lista32'
    );

    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */

    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Honorarios/Vista');
        require_once DIRMODULOS . 'Honorarios/Modelo/HonorariosModelo.php';
        $this->_modelo = new HonorariosModelo();
    }

    /**
     * Metodo que lleva al menu de los honorarios
     * @return void
     */
    public function index()
    {
        $this->_layout->content = $this->_vista->render('HonorariosVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    /**
     * Metodo que lleva a la pag donde se cargan los ingresos
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar()
    {
        require_once DIRMODULOS . 'Honorarios/Forms/CargaHonorarios.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
//        require_once LibQ . 'MyFechaHora.php';

        /** Busco las cuentas * */
        $datos_array = $this->_modelo->listadoCuentas();
        foreach ($datos_array as $cuentaBuscada) {
            $cuentas[] = array($cuentaBuscada->id => $cuentaBuscada->cuenta);
        }
        /* Busco los empleados */
        $datos_array = $this->_modelo->listadoProfesionales(Array('id', 'apellido', 'nombre'));
        foreach ($datos_array as $empleadoBuscado) {
            $empleados[] = array($empleadoBuscado->id => $empleadoBuscado->apellido . ', ' . $empleadoBuscado->nombre);
        }
        /* Creo el Formulario */
        $this->_form = new Form_CargaHonorarios($cuentas, $empleados);
        $this->_vista->form = $this->_form->mostrar();
        /* Me fijo si se envi� el formulario */
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['cuenta'] = $values['cuentah'];
                unset($values['cuentah']);
                $values['fecha_comprobante'] = implode('-', array_reverse(explode('/', $values['fecha_comprobante'])));
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
        $this->_layout->content = $this->_vista->render('AgregarHonorarioVista.php');
        echo $this->_layout->render();
    }

    /**
     * 
     * Lista los honorarios
     * @param string $arg
     */
    public function listar($arg = '')
    {
        $filtroBoton = '';
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('HONORARIOS');
        if (isset($arg)){
            if (is_array($arg)){
                $filtroBoton = implode("&", $arg);
            }
//            print_r($filtroBoton);
            $grilla->setUrl(LIVESITE . '/index.php?option=honorarios&sub=jsonListarHonorarios&'.$filtroBoton);
        }else{
            $grilla->setUrl(LIVESITE . '/index.php?option=honorarios&sub=jsonListarHonorarios');
        }
        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'cuenta'" => "'Cuenta'",
            "'CONCAT_WS(\", \", profesionales.apellido,profesionales.nombre)'" => "'Profesional'",
            "'fecha_comprobante'" => "'Fecha Fact.'",
            "'comprobante'" => "'Comprobante'",
            "'tipo_comprobante'" => "'Tipo'",
            "'nro_comprobante'" => "'Nro'",
            "'importe_gravado'" => "'Imp.Grav.'",
            "'importe_nogravado'" => "'Imp.No Grav.'",
            "'iva_inscripto'" => "'IVA'",
            "'iva_diferencial'" => "'IVA Dif.'",
            "'percepcion'" => "'Percep.'",
            "'total'" => "'Total'"
        ));

        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name' => 'Cuenta', 'index' => 'cuenta', 'width' => 90),
            array('name' => 'CONCAT_WS(", ", profesionales.apellido,profesionales.nombre)', 'index' => 'Profesional', 'width' => 130),
            array('name' => 'Fecha Fact.', 'index' => 'fecha_comprobante', 'width' => 70, 'formatter' => 'date', 'formatoptions' => array('srcformat' => "Y-m-d", 'newformat' => "d-m-Y")),
            array('name' => 'Comprobante', 'index' => 'comprobante', 'width' => 100),
            array('name' => 'Tipo', 'index' => 'tipo_comprobante', 'width' => 50),
            array('name' => 'Nro', 'index' => 'nro_comprobante', 'width' => 80),
            array('name' => 'Imp.Grav.', 'index' => 'importe_gravado', 'width' => 70, 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
            array('name' => 'Imp.No Grav.', 'index' => 'importe_nogravado', 'width' => 70, 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
            array('name' => 'IVA', 'index' => 'iva_inscripto', 'width' => 70, 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
            array('name' => 'IVA Dif.', 'index' => 'iva_diferencial', 'width' => 70, 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
            array('name' => 'Percep.', 'index' => 'percepcion', 'width' => 70, 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
            array('name' => 'Total', 'index' => 'total', 'width' => 70, 'align' => "right", 'formatter' => 'currency', 'formatoptions' => array('prefix' => "$ ")),
        ));

        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=honorarios&sub=editar&id=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=honorarios';
        } else {
            $filtroBoton = '&lista=honorarios';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=honorarios&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoHonorariosVista.php');
        echo $this->_layout->render();
    }
    
    public function resumenHonorariosAnual ($arg='')
    {    
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $obj = '';
        $datos = '';
        $graficar = '';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=honorarios&sub=jsonListarHonorariosAnual');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
//            "'YEAR(honorarios.fecha_comprobante)'"=>"'Fecha Fact.'",
            "'SUM(honorarios.importe_gravado)'"=>"'Imp.Grav.'",
            "'SUM(honorarios.importe_nogravado)'"=>"'Imp.No Grav.'",
            "'SUM(honorarios.iva_inscripto)'"=>"'IVA'",
            "'SUM(honorarios.iva_diferencial)'"=>"'IVA Dif.'",
            "'SUM(honorarios.percepcion)'"=>"'Percep.'",
            "'SUM(honorarios.total)'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
//            array('name' => 'Fecha Fact.', 'index' => 'YEAR(honorarios.fecha_comprobante)', 'width' => 70, 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"Y",'newformat'=>"Y")),
            array('name' => 'Imp.Grav.', 'index' => 'SUM(honorarios.importe_gravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Imp.No Grav.', 'index' => 'SUM(honorarios.importe_nogravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA', 'index' => 'SUM(honorarios.iva_inscripto)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA Dif.', 'index' => 'SUM(honorarios.iva_diferencial)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Percep.', 'index' => 'SUM(honorarios.percepcion)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'SUM(honorarios.total)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=honorarios&sub=resumenHonorariosMensual&id=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=honorarios';
        } else {
            $filtroBoton = '&lista=honorarios';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=honorarios&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        /* Grafico */
        $json = new Zend_Json();
        $obj = $json->decode($this->objJsonListarHonorariosAnual($arg));
        foreach ($obj['rows'] as $valorFila) {
            foreach ($valorFila as $valorAnio) {
                if (is_array($valorAnio)){
                    foreach ($valorAnio as $valor) {
                        $valores[] =  intval($valor);
                    }
                    $fila[] = "[" . implode(',', $valores) . "]" ;
                    $valores = '';
                }
            }
            
        }
        $datos = implode(',', $fila);
        $encabezado = "['Year','Imp.Grav','Imp.NoGrav','Iva','Iva Dif.','Percep.','Total $']";
        $graficar = $encabezado . "," . $datos;
        $grafico = new ChartGoogle('Honorarios Anuales');
        $grafico->setTitutloEjeX('Año');
//        echo $graficar;
        $grafico->setDatos($graficar);
        $retorno = $grafico->incluirJs();
        $retorno .= '<div id="chart_div"></div>';
        $this->_vista->grafico = $retorno;
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoHonorariosVista.php');
        echo $this->_layout->render();
    }
    
    /**
     * 
     * @param array $arg 
     */
    public function resumenHonorariosMensual ($arg='')
    {    
        $anio = explode('=', $arg[0]);
        $filtroBoton = $filtroBoton = 'YEAR(honorarios.fecha_comprobante)=' . $anio[1];
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('HONORARIOS MENSUALES AÑO: '.$anio[1]);
        $grilla->setUrl(LIVESITE . '/index.php?option=honorarios&sub=jsonListarHonorariosMensual&'.$filtroBoton);
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'SUM(honorarios.importe_gravado)'"=>"'Imp.Grav.'",
            "'SUM(honorarios.importe_nogravado)'"=>"'Imp.No Grav.'",
            "'SUM(honorarios.iva_inscripto)'"=>"'IVA'",
            "'SUM(honorarios.iva_diferencial)'"=>"'IVA Dif.'",
            "'SUM(honorarios.percepcion)'"=>"'Percep.'",
            "'SUM(honorarios.total)'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name' => 'Imp.Grav.', 'index' => 'SUM(honorarios.importe_gravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Imp.No Grav.', 'index' => 'SUM(honorarios.importe_nogravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA', 'index' => 'SUM(honorarios.iva_inscripto)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA Dif.', 'index' => 'SUM(honorarios.iva_diferencial)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Percep.', 'index' => 'SUM(honorarios.percepcion)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'SUM(honorarios.total)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=honorarios&sub=resumenHonorariosProfesional&year='.$anio[1].'&id=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=honorarios';
        } else {
            $filtroBoton = '&lista=gastos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=honorarios&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        /* Grafico */
        $json = new Zend_Json();
        $obj = $json->decode($this->objJsonListarHonorariosMensual($arg));
        foreach ($obj['rows'] as $valorFila) {
            foreach ($valorFila as $valorAnio) {
                if (is_array($valorAnio)){
                    foreach ($valorAnio as $valor) {
                        $valores[] =  intval($valor);
                    }
                    $fila[] = "[" . implode(',', $valores) . "]" ;
                    $valores = '';
                }
            }
            
        }
        $datos = implode(',', $fila);
        $encabezado = "['Year','Imp.Grav','Imp.NoGrav','Iva','Iva Dif.','Percep.','Total $']";
        $graficar = $encabezado . "," . $datos;
        $grafico = new ChartGoogle('Honorarios Anuales');
        $grafico->setTitutloEjeX('Año');
//        echo $graficar;
        $grafico->setDatos($graficar);
        $retorno = $grafico->incluirJs();
        $retorno .= '<div id="chart_div"></div>';
        $this->_vista->grafico = $retorno;
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoHonorariosVista.php');
        echo $this->_layout->render();
    }
    
    /**
     * 
     * @param array $arg 
     */
    public function resumenHonorariosProfesional ($arg='')
    {
//        $anio = 2012;
        $anio = explode('=', $arg[0]);
        $mes = explode('=', $arg[1]);
        $filtroAnio = 'Y=' . $anio[1];
        $filtroMes = 'M=' . $mes[1];
        $filtroBoton = $filtroBoton = 'YEAR(honorarios.fecha_comprobante)=' . $anio[1];
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('HONORARIOS MENSUALES AÑO: '.$anio[1] . ' MES: ' .$mes[1]);
//        $grilla->setTitulo('HONORARIOS MENSUALES AÑO: ');
//        $grilla->setUrl(LIVESITE . '/index.php?option=honorarios&sub=jsonListarHonorariosProfesional&'.$filtroAnio.'&'.$filtroMes);
        $grilla->setUrl(LIVESITE . '/index.php?option=honorarios&sub=jsonListarHonorariosProfesional&'.$filtroAnio.'&'.$filtroMes);
        $grilla->setColNames(array(
            "'profesional'"=>"'Id'",
            "'ayn'"=>"'AyN'",
//            "'SUM(honorarios.importe_gravado)'"=>"'Imp.Grav.'",
//            "'SUM(honorarios.importe_nogravado)'"=>"'Imp.No Grav.'",
//            "'SUM(honorarios.iva_inscripto)'"=>"'IVA'",
//            "'SUM(honorarios.iva_diferencial)'"=>"'IVA Dif.'",
//            "'SUM(honorarios.percepcion)'"=>"'Percep.'",
            "'SUM(honorarios.total)'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'profesional', 'width' => 50),
            array('name' => 'AyN', 'index' => 'ayn', 'width' => 200),
//            array('name' => 'Imp.Grav.', 'index' => 'SUM(honorarios.importe_gravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
//            array('name' => 'Imp.No Grav.', 'index' => 'SUM(honorarios.importe_nogravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
//            array('name' => 'IVA', 'index' => 'SUM(honorarios.iva_inscripto)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
//            array('name' => 'IVA Dif.', 'index' => 'SUM(honorarios.iva_diferencial)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
//            array('name' => 'Percep.', 'index' => 'SUM(honorarios.percepcion)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'SUM(honorarios.total)', 'width' => 80, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=honorarios&sub=listar&idYear='.$anio[1].'&idMes='.$mes[1].'&idProf=');
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=honorarios';
        } else {
            $filtroBoton = '&lista=gastos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=honorarios&sub=exportar' . $filtroBoton ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoHonorariosVista.php');
        echo $this->_layout->render();
    }
    

    /**
     * 
     * Lista Resumen de Honorarios Por Anio
     * @param string $arg
     */
    public function objJsonListarHonorariosAnual ($arg='')
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
                $orden = 'YEAR(honorarios.fecha_comprobante )';
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
        $todos = count($this->_modelo->resumenHonorariosAnual($inicio, $orden, '' ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenHonorariosAnual($inicio, $orden, '' );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['YEAR(honorarios.fecha_comprobante)'];
            $responce->rows[$i]['cell'] = array($row['YEAR(honorarios.fecha_comprobante)'],
//                $row['YEAR(honorarios.fecha_comprobante)'],
                $row['SUM(honorarios.importe_gravado)'],
                $row['SUM(honorarios.importe_nogravado)'],
                $row['SUM(honorarios.iva_inscripto)'],
                $row['SUM(honorarios.iva_diferencial)'],
                $row['SUM(honorarios.percepcion)'],
                $row['SUM(honorarios.total)']
            );
            $i++;
        }
        // return the formated data
//        echo $json->encode($responce);
        return $json->encode($responce);
    }
    
    public function jsonListarHonorariosAnual ($arg='')
    {
        $responce = $this->objJsonListarHonorariosAnual($arg);
        echo $responce;
    }
    
    
    public function objJsonListarHonorariosMensual ($arg='')
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
                $orden = 'MONTH( honorarios.fecha_comprobante )';
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
                $filtroBoton = 'YEAR(honorarios.fecha_comprobante)=' . $anio[1];
            } else {
                $filtroBoton = '';
            }
        }
        $json = new Zend_Json();
        $todos = count($this->_modelo->resumenHonorariosMensual($inicio, $orden, $filtroBoton ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenHonorariosMensual($inicio, $orden, $filtroBoton );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['MONTH(honorarios.fecha_comprobante)'];
            $responce->rows[$i]['cell'] = array($row['MONTH(honorarios.fecha_comprobante)'],
                $row['SUM(honorarios.importe_gravado)'],
                $row['SUM(honorarios.importe_nogravado)'],
                $row['SUM(honorarios.iva_inscripto)'],
                $row['SUM(honorarios.iva_diferencial)'],
                $row['SUM(honorarios.percepcion)'],
                $row['SUM(honorarios.total)']
            );
            $i++;
        }
        // return the formated data
        return $json->encode($responce);
    }
    
    public function jsonListarHonorariosMensual ($arg='')
    {
        $responce = $this->objJsonListarHonorariosMensual($arg);
        echo $responce;
    }
    
    public function jsonListarHonorariosProfesional($arg='')
    {
        $responce = '';
        $mes = date('m',time()); //explode('=', $arg[1]);
      /** Me fijo si hay argumentos */
        if (isset($arg)) {
            if (!empty($_GET['Y'])) {
                $anio = Input::get('Y');
            } else {
                $anio = date('Y',time());
            }
            if (!empty($_GET['M'])) {
                $mes = Input::get('M');
            } else {
                $mes = date('m',time());
            }
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
                $orden = 'honorarios.profesional';
            }
            /** Me fijo si el argumento es el tipo de orden (ASC o DESC) */
            if (!empty($_GET['sord'])) {
                $orden .= ' ' . Input::get('sord');
            } else {
                $orden .= ' ASC';
            }
            /** Si el argumento es un array entonces creo el filtro */
            if (is_array($arg)) {
//                $anio = explode('=', $arg[0]);
//                $filtroBoton = 'YEAR(honorarios.fecha_comprobante)=' . $anio[1];
//                $filtroBoton .= ' AND MONTH(honorarios.fecha_comprobante)=' . $mes[1];
                $filtroBoton = 'YEAR(honorarios.fecha_comprobante)=' . $anio;
                $filtroBoton .= ' AND MONTH(honorarios.fecha_comprobante)=' . $mes;
            } else {
                $filtroBoton = '';
            }
        }
        $json = new Zend_Json();
        $todos = count($this->_modelo->resumenHonorariosProfesional($inicio, $orden, $filtroBoton ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenHonorariosProfesional($inicio, $orden, $filtroBoton );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array($row['id'],
                $row['ayn'],
//                $row['SUM(honorarios.importe_gravado)'],
//                $row['SUM(honorarios.importe_nogravado)'],
//                $row['SUM(honorarios.iva_inscripto)'],
//                $row['SUM(honorarios.iva_diferencial)'],
//                $row['SUM(honorarios.percepcion)'],
                $row['SUM(honorarios.total)']
            );
            $i++;
        }
       
//        foreach ($result as $row) {
//            $responce->rows[$i]['id'] = $row['id'];
//            $responce->rows[$i]['cell'] = array($row['id'],
//                $row['CONCAT_WS(", ", profesionales.apellido,profesionales.nombre)'],
//                $row['importe_gravado'],
//                $row['importe_nogravado'],
//                $row['iva_inscripto'],
//                $row['iva_diferencial'],
//                $row['percepcion'],
//                $row['total']
//            );
//            $i++;
//        }
        
        // return the formated data
        echo $json->encode($responce);
    }

    public function jsonListarHonorarios($arg = '')
    {
        $filtroBoton = '';
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
                $orden = 'honorarios.id';
            }
            /** Me fijo si el argumento es el tipo de orden (ASC o DESC) */
            if (!empty($_GET['sord'])) {
                $orden .= ' ' . Input::get('sord');
            } else {
                $orden .= ' ASC';
            }
            /** Si el argumento es un array entonces creo el filtro */
            if (is_array($arg)) 
                if (!empty($_GET['idYear'])) {
                    $filtroBoton .= 'YEAR(honorarios.fecha_comprobante)=' . Input::get('idYear');
                } else {
                    $filtroBoton .= '';
                }
                if (!empty($_GET['idMes'])) {
                    $filtroBoton .= ' AND MONTH(honorarios.fecha_comprobante)=' . Input::get('idMes');
                } else {
                    $filtroBoton .= '';
                }
                if (!empty($_GET['idProf'])) {
                    $filtroBoton .= ' AND honorarios.profesional=' . Input::get('idProf');
                } else {
                    $filtroBoton .= '';
                }
            } else {
                $filtroBoton = '';
            }
        $json = new Zend_Json();
        $todos = $this->_modelo->getCantidadRegistros();
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->listadoHonorarios($inicio, $orden, $filtroBoton);
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array($row['id'],
                $row['cuenta'],
                $row['CONCAT_WS(", ", profesionales.apellido,profesionales.nombre)'],
                $row['fecha_comprobante'],
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

    public function editar($arg)
    {
        require_once DIRMODULOS . 'Honorarios/Forms/CargaHonorarios.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $where = implode(',', $arg);
        $honorarioBuscado = $this->_modelo->buscarHonorario($where);
        if (is_object($honorarioBuscado)) {
            $this->_varForm['id'] = $honorarioBuscado->id;
            $this->_varForm['cuenta'] = $honorarioBuscado->cuenta;
            $this->_varForm['profesional'] = $honorarioBuscado->profesional;
            $this->_varForm['fecha_comprobante'] = implode('/', array_reverse(explode('-', $honorarioBuscado->fecha_comprobante)));
            $this->_varForm['comprobante'] = $honorarioBuscado->comprobante;
            $this->_varForm['tipo_comprobante'] = $honorarioBuscado->tipo_comprobante;
            $this->_varForm['nro_comprobante'] = $honorarioBuscado->nro_comprobante;
            $this->_varForm['importe_gravado'] = $honorarioBuscado->importe_gravado;
            $this->_varForm['importe_nogravado'] = $honorarioBuscado->importe_nogravado;
            $this->_varForm['iva_inscripto'] = $honorarioBuscado->iva_inscripto;
            $this->_varForm['iva_diferencial'] = $honorarioBuscado->iva_diferencial;
            $this->_varForm['percepciones'] = $honorarioBuscado->percepcion;
            $this->_varForm['total'] = $honorarioBuscado->total;
        }
        $datos_array = $this->_modelo->listadoCuentas();
        foreach ($datos_array as $cuentaBuscada) {
            $cuentas[] = array($cuentaBuscada->id => $cuentaBuscada->cuenta);
        }
        $datos_array = $this->_modelo->listadoProfesionales(Array('id', 'apellido'));
        foreach ($datos_array as $profesionalBuscado) {
            $profesionales[] = array($profesionalBuscado->id => $profesionalBuscado->apellido);
        }
        $this->_form = new Form_CargaHonorarios($cuentas, $profesionales, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['cuenta'] = $values['cuentah'];
                unset($values['cuentah']);
                $values['fecha_comprobante'] = implode('-', array_reverse(explode('/', $values['fecha_comprobante'])));
                $guardado = $this->_modelo->actualizar($values, $arg);
                if ($guardado >= 0) {
                    $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
                } else {
                    $this->_vista->mensajes = Mensajes::presentarMensaje($guardado, 'error');
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
        $this->_layout->content = $this->_vista->render('AgregarHonorarioVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    public function eliminar($arg = '')
    {
        $where = implode(',', $arg);
        $values['eliminado'] = '1';
        $cantReg = $this->_modelo->actualizar($values,$where);
    	if ($cantReg > 0){
            $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
        }else{
            $this->_vista->mensajes = Mensajes::presentarMensaje(ERROR_GUARDAR,'error');
        }   
        parent::_redirect(LIVESITE . '/index.php?option=honorarios&sub=listar');
    }
    
    public function ultimosHonorarios()
    {
        setlocale(LC_MONETARY, 'es_AR');
        $retorno = '<table>';
        $i = 0;
        $listaHonorarios = $this->_modelo->listadoHonorarios(0, 'id DESC');
        foreach ($listaHonorarios as $honorario) {
            $factura = $honorario['tipo_comprobante'] . $honorario['nro_comprobante'];
//            $retorno.= '<tr><td><b><a href=index.php?option=honorarios&sub=editar&id=' . $honorario['id'] .'>' . $honorario['CONCAT_WS(", ", profesionales.apellido,profesionales.nombre)'] . '</a></b></td>
//                <td>' . $factura .'</td><td align="right">' . money_format ('%i',$honorario['total']) .'</td><td>' . MyFechaHora::getFechaAr($honorario['fecha_comprobante']) .'</td></tr>';
            $retorno.= '<tr><td><b><a href=index.php?option=honorarios&sub=editar&id=' . $honorario['id'] .'>' . $honorario['CONCAT_WS(", ", profesionales.apellido,profesionales.nombre)'] . '</a></b></td>
                <td>' . $factura .'</td><td align="right">' . $honorario['total'] .'</td><td>' . MyFechaHora::getFechaAr($honorario['fecha_comprobante']) .'</td></tr>';
            $i++;
            if ($i >= 10){
                break;
            }
        }
        $retorno.='</table>';
        return $retorno;
    }
    
    public static function datosGraficoHonorariosMensuales($anio, $mes1)
    {
        require_once DIRMODULOS . 'Honorarios/Modelo/HonorariosModelo.php';
        $modelo = new HonorariosModelo();
        $dato = $modelo->totalHonorariosMensual($anio, $mes1);
        return $dato;
    }

}
