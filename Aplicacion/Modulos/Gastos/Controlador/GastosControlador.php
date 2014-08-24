<?php
require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
require_once 'App/LibQ/ControladorBase.php';
require_once 'App/LibQ/Config.php';
require_once DIRMODULOS . 'Gastos/Modelo/GastosModelo.php';
require_once 'App/LibQ/Input.php';
require_once LibQ . 'Google/Chart/ChartGoogle.php';
require_once DIRMODULOS . 'Sueldos/Controlador/SueldosControlador.php';
require_once LibQ . 'Zend/Currency.php';
require_once LibQ . 'Zend/Json.php';
require_once LibQ . 'JQGrid.php';

/**
 *  Clase Controladora del Modulo Gastos
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Gastos
 * 
 */
class GastosControlador extends ControladorBase
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
        'href' => 'index.php?option=gastos&sub=agregar',
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
    private $_paramBotonVolver = array('href'=>'index.php?option=gastos');
    
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
        'href' => 'index.php?option=gastos&sub=listar',
        'classIcono' => 'icono-lista32'
        );


    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */
    function __construct ()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Gastos/Vista');
        require_once DIRMODULOS . 'Gastos/Modelo/GastosModelo.php';
        $this->_modelo = new GastosModelo();
    }
    
    /**
     * Metodo que lleva al menu de los gastos
     * @return void
     */
    public function index ()
    {
        $this->_layout->content = $this->_vista->render('GastosVista.php');
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
        require_once DIRMODULOS . 'Gastos/Forms/CargaGastos.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $cuentas = array();
        $proveedores = array();
        $datos_array = $this->_modelo->listadoCuentas();
        foreach ($datos_array as $cuentaBuscada) {
            $cuentas[] = array($cuentaBuscada->id => $cuentaBuscada->cuenta);
        }
        $datos_array = $this->_modelo->listadoProveedores(Array('id' , 'razon_social'));
        foreach ($datos_array as $proveedorBuscado) {
            $proveedores[] = array($proveedorBuscado->id => $proveedorBuscado->razon_social);
        }
        $this->_form = new Form_CargaGastos($cuentas, $proveedores, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                if ($this->_controlar_nro_factura($values['nro_comprobante'], $values['proveedor'], $values['comprobante'], $values['tipo_comprobante'])=='ok'){
                    $values['fecha_comprobante']=implode('/', array_reverse(explode('/', $values['fecha_comprobante'])));
                    $ultimoId = $this->_modelo->guardar($values);
                    $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
                }else{
                    $this->_vista->mensajes = Mensajes::presentarMensaje(FACTURAEXISTE, 'error');
                }
 
//                $values['fecha_comprobante'] = MyFechaHora::getFechaBd($values['fecha_comprobante']);
//                $this->_modelo->guardar($values);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarGastoVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    public function editar ($arg)
    {
        require_once DIRMODULOS . 'Gastos/Forms/CargaGastos.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $where = implode(',', $arg);
        $gastoBuscado = $this->_modelo->buscarGasto($where);
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
        $this->_form = new Form_CargaGastos($cuentas, $proveedores, $this->_varForm);
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
        $this->_layout->content = $this->_vista->render('AgregarGastoVista.php');
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
        $filtroBoton = '';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS');
        if (isset($arg)){
            if (is_array($arg)){
                $filtroBoton = implode("&", $arg);
            }
            $grilla->setUrl(LIVESITE . '/index.php?option=gastos&sub=jsonListarGastos&'.$filtroBoton);
        }else{
            $grilla->setUrl(LIVESITE . '/index.php?option=gastos&sub=jsonListarGastos');
        }
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
            array('name' => 'Total', 'index' => 'total', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=gastos&sub=editar&id=');
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
        $this->_layout->content = $this->_vista->render('ListadoGastoVista.php');
        echo $this->_layout->render();
    }
    
    public function jsonListarGastos($arg='')
    {
        $filtroBoton = '';
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
                $orden = 'gastos.id';
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
                    $filtroBoton .= 'YEAR(gastos.fecha_comprobante)=' . Input::get('idYear');
                } else {
                    $filtroBoton .= '';
                }
                if (!empty($_GET['idMes'])) {
                    $filtroBoton .= ' AND MONTH(gastos.fecha_comprobante)=' . Input::get('idMes');
                } else {
                    $filtroBoton .= '';
                }
                if (!empty($_GET['idProv'])) {
                    $filtroBoton .= ' AND gastos.proveedor=' . Input::get('idProv');
                } else {
                    $filtroBoton .= '';
                }
            } else {
                $filtroBoton = '';
            }

        
        $json = new Zend_Json();
        $todos = $this->_modelo->getCantidadRegistros();
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->listadoGastos($inicio, $orden, $filtroBoton );
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
    
    public function ultimosGastos()
    {
//        setlocale(LC_MONETARY, 'es_AR');
        $retorno = '<table>';
        $i = 0;
        $listaGastos = $this->_modelo->listadoGastos(0, 'id DESC', '');
        foreach ($listaGastos as $gasto) {
            $factura = $gasto['tipo_comprobante'] . $gasto['nro_comprobante'];
//             money_format ('%i',$gasto['total'])
            $total = new Zend_Currency(array('value' => $gasto['total'], 'symbol' => '$',));
            $retorno.= '<tr><td><b><a href=index.php?option=gastos&sub=editar&id=' . $gasto['id'] .'>' . $gasto['razon_social'] . '</a></b></td><td>' . $factura .'</td><td align="right">' . $total .'</td></tr>';
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
     * Lista los resumenGastosProveedor
     * @param string $arg
     */
    public function resumenGastosProveedor ($arg='')
    {
        $anio = explode('=', $arg[0]);
        $mes = explode('=', $arg[1]);
        $filtroAnio = 'Y=' . $anio[1];
        $filtroMes = 'M=' . $mes[1];
        $filtroBoton = $filtroBoton = 'YEAR(honorarios.fecha_comprobante)=' . $anio[1];
        
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
//        $grilla->setTitulo('GASTOS POR PROVEEDOR');
        $grilla->setTitulo('GASTOS MENSUALES AÑO: '.$anio[1] . ' MES: ' .$mes[1]);
        $grilla->setUrl(LIVESITE . '/index.php?option=gastos&sub=jsonListarGastosProveedor&'.$filtroAnio.'&'.$filtroMes);
        $grilla->setColNames(array(
            "'id'"=>"'Proveedor'",
            "'ayn'"=>"'Razon Social'",
//            "'razon_social'"=>"'Proveedor'",
//            "'fecha_comprobante'"=>"'Fecha Fact.'",
//            "'comprobante'"=>"'Comprobante'",
//            "'tipo_comprobante'"=>"'Tipo'",
//            "'nro_comprobante'"=>"'Nro'",
//            "'importe_gravado'"=>"'Imp.Grav.'",
//            "'importe_nogravado'"=>"'Imp.No Grav.'",
//            "'iva_inscripto'"=>"'IVA'",
//            "'iva_diferencial'"=>"'IVA Dif.'",
//            "'percepcion'"=>"'Percep.'",
            "'total'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
//            array('name'=>'Cuenta', 'index'=>'cuenta', 'width'=>90),
            array('name'=>'Proveedor', 'index'=>'ayn', 'width'=>180),
//            array('name' => 'Fecha Fact.', 'index' => 'fecha_comprobante', 'width' => 70, 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"Y-m-d",'newformat'=>"d-m-Y")),
//            array('name' => 'Comprobante', 'index' => 'comprobante', 'width' =>100),
//            array('name' => 'Tipo', 'index' => 'tipo_comprobante', 'width' => 50),
//            array('name' => 'Nro', 'index' => 'nro_comprobante', 'width' => 80),
//            array('name' => 'Imp.Grav.', 'index' => 'importe_gravado', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
//            array('name' => 'Imp.No Grav.', 'index' => 'importe_nogravado', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
//            array('name' => 'IVA', 'index' => 'iva_inscripto', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
//            array('name' => 'IVA Dif.', 'index' => 'iva_diferencial', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
//            array('name' => 'Percep.', 'index' => 'percepcion', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'total', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ "), 'summaryType'=>'sum'),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=gastos&sub=listar&idYear='.$anio[1].'&idMes='.$mes[1].'&idProv=');
                
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
        $this->_layout->content = $this->_vista->render('ListadoGastoVista.php');
        echo $this->_layout->render();
    }
    
    public function resumenGastosAnual ($arg='')
    {    
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=gastos&sub=jsonListarGastosAnual');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
//            "'YEAR(gastos.fecha_comprobante)'"=>"'Fecha Fact.'",
            "'SUM(gastos.importe_gravado)'"=>"'Imp.Grav.'",
            "'SUM(gastos.importe_nogravado)'"=>"'Imp.No Grav.'",
            "'SUM(gastos.iva_inscripto)'"=>"'IVA'",
            "'SUM(gastos.iva_diferencial)'"=>"'IVA Dif.'",
            "'SUM(gastos.percepcion)'"=>"'Percep.'",
            "'SUM(gastos.total)'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
//            array('name' => 'Fecha Fact.', 'index' => 'YEAR(gastos.fecha_comprobante)', 'width' => 70, 'formatter'=>'date', 'formatoptions'=>array('srcformat'=>"Y",'newformat'=>"Y")),
            array('name' => 'Imp.Grav.', 'index' => 'SUM(gastos.importe_gravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Imp.No Grav.', 'index' => 'SUM(gastos.importe_nogravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA', 'index' => 'SUM(gastos.iva_inscripto)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA Dif.', 'index' => 'SUM(gastos.iva_diferencial)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Percep.', 'index' => 'SUM(gastos.percepcion)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'SUM(gastos.total)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=gastos&sub=resumenGastosMensual&id=');
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
        
        /*
         * Gráfico
         */
        $json = new Zend_Json();
        $obj = $json->decode($this->objJsonListarGastosAnual($arg));
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
        $grafico = new ChartGoogle('Gastos Anuales');
        $grafico->setTitutloEjeX('Año');
//        echo $graficar;
        $grafico->setDatos($graficar);
        $retorno = $grafico->incluirJs();
        $retorno .= '<div id="chart_div"></div>';
        $this->_vista->grafico = $retorno;
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoGastoVista.php');
        echo $this->_layout->render();
    }
    
    /**
     * 
     * @param array $arg 
     */
    public function resumenGastosMensual ($arg='')
    {    
        $anio = explode('=', $arg[0]);
        $filtroBoton = $filtroBoton = 'YEAR(gastos.fecha_comprobante)=' . $anio[1];
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('GASTOS MENSUALES AÑO: '.$anio[1]);
        $grilla->setUrl(LIVESITE . '/index.php?option=gastos&sub=jsonListarGastosMensual&'.$filtroBoton);
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'SUM(gastos.importe_gravado)'"=>"'Imp.Grav.'",
            "'SUM(gastos.importe_nogravado)'"=>"'Imp.No Grav.'",
            "'SUM(gastos.iva_inscripto)'"=>"'IVA'",
            "'SUM(gastos.iva_diferencial)'"=>"'IVA Dif.'",
            "'SUM(gastos.percepcion)'"=>"'Percep.'",
            "'SUM(gastos.total)'"=>"'Total'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => 40),
            array('name' => 'Imp.Grav.', 'index' => 'SUM(gastos.importe_gravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Imp.No Grav.', 'index' => 'SUM(gastos.importe_nogravado)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA', 'index' => 'SUM(gastos.iva_inscripto)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'IVA Dif.', 'index' => 'SUM(gastos.iva_diferencial)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Percep.', 'index' => 'SUM(gastos.percepcion)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Total', 'index' => 'SUM(gastos.total)', 'width' => 70, 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(true);
        $grilla->setActionOnDblClickRow('/index.php?option=gastos&sub=resumenGastosProveedor&year='.$anio[1].'&id=');        $bh = new LibQ_BarraHerramientas($this->_vista);
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
        
                /*
         * Gráfico
         */
        $json = new Zend_Json();
        $obj = $json->decode($this->objJsonListarGastosMensual($arg));
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
        $grafico = new ChartGoogle('Gastos Mensuales');
        $grafico->setTitutloEjeX('Mes');
//        echo $graficar;
        $grafico->setDatos($graficar);
        $retorno = $grafico->incluirJs();
        $retorno .= '<div id="chart_div"></div>';
        $this->_vista->grafico = $retorno;
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoGastoVista.php');
        echo $this->_layout->render();
    }
    
    /**
     * 
     * Lista Resumen de Gastos Por Anio
     * @param string $arg
     */
    public function objJsonListarGastosAnual ($arg='')
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
                $orden = 'YEAR( gastos.fecha_comprobante )';
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
        $todos = count($this->_modelo->resumenGastosAnual($inicio, $orden, '' ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenGastosAnual($inicio, $orden, '' );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['YEAR(gastos.fecha_comprobante)'];
            $responce->rows[$i]['cell'] = array($row['YEAR(gastos.fecha_comprobante)'],
//                $row['YEAR(gastos.fecha_comprobante)'],
                $row['SUM(gastos.importe_gravado)'],
                $row['SUM(gastos.importe_nogravado)'],
                $row['SUM(gastos.iva_inscripto)'],
                $row['SUM(gastos.iva_diferencial)'],
                $row['SUM(gastos.percepcion)'],
                $row['SUM(gastos.total)']
            );
            $i++;
        }
        // return the formated data
        return $json->encode($responce);
    }
    
    public function jsonListarGastosAnual ($arg='')
    {
        $responce = $this->objJsonListarGastosAnual($arg);
        echo $responce;
    }
    
    public function jsonListarGastosProveedor($arg='')
    {
        $responce = '';
        $mes = date('m',time());
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
                $orden = 'gastos.proveedor';
            }
            /** Me fijo si el argumento es el tipo de orden (ASC o DESC) */
            if (!empty($_GET['sord'])) {
                $orden .= ' ' . Input::get('sord');
            } else {
                $orden .= ' ASC';
            }
            /** Si el argumento es un array entonces creo el filtro */
            if (is_array($arg)) {
                $filtroBoton = 'YEAR(gastos.fecha_comprobante)=' . $anio;
                $filtroBoton .= ' AND MONTH(gastos.fecha_comprobante)=' . $mes;
            } else {
                $filtroBoton = '';
            }
        }
        $json = new Zend_Json();
        $todos = count($this->_modelo->resumenGastosProveedor($inicio, $orden, $filtroBoton ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenGastosProveedor($inicio, $orden, $filtroBoton );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['proveedor'];
            $responce->rows[$i]['cell'] = array($row['proveedor'],
                $row['ayn'],
//                $row['SUM(honorarios.importe_gravado)'],
//                $row['SUM(honorarios.importe_nogravado)'],
//                $row['SUM(honorarios.iva_inscripto)'],
//                $row['SUM(honorarios.iva_diferencial)'],
//                $row['SUM(honorarios.percepcion)'],
                $row['SUM(gastos.total)']
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

    
    public function objJsonListarGastosMensual ($arg='')
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
                $orden = 'MONTH( gastos.fecha_comprobante )';
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
                $filtroBoton = 'YEAR(gastos.fecha_comprobante)=' . $anio[1];
            } else {
                $filtroBoton = '';
            }
//            print_r($filtroBoton);
        }
        $json = new Zend_Json();
        $todos = count($this->_modelo->resumenGastosMensual($inicio, $orden, $filtroBoton ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->resumenGastosMensual($inicio, $orden, $filtroBoton );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['MONTH(gastos.fecha_comprobante)'];
            $responce->rows[$i]['cell'] = array($row['MONTH(gastos.fecha_comprobante)'],
//                $row['YEAR(gastos.fecha_comprobante)'],
                $row['SUM(gastos.importe_gravado)'],
                $row['SUM(gastos.importe_nogravado)'],
                $row['SUM(gastos.iva_inscripto)'],
                $row['SUM(gastos.iva_diferencial)'],
                $row['SUM(gastos.percepcion)'],
                $row['SUM(gastos.total)']
            );
            $i++;
        }
        // return the formated data
        return $json->encode($responce);
    }
    
    public function jsonListarGastosMensual ($arg='')
    {
        $responce = $this->objJsonListarGastosMensual($arg);
        echo $responce;
    }
    
    public static function datosGraficoHonorariosMensuales($anio, $mes1)
    {
        require_once DIRMODULOS . 'Honorarios/Modelo/HonorariosModelo.php';
        $modelo = new HonorariosModelo();
        $dato = $modelo->totalHonorariosMensual($anio, $mes1);
        return $dato;
    }
    
    public function graficoTotalGastosMensuales(){
        $anio = date('Y');
        $mes1 = date('m')-1;
        $mes2 = $mes1 - 1;
        $mes3 = $mes2 - 1;
        $encabezado = "['Mes','Egresos $','Ingresos $']";
        $dato1Gastos = $this->_modelo->totalGastosMensual($anio, $mes1);
        $dato1Ingresos = IngresosControlador::datosGraficoIngresosMensuales($anio, $mes1);
        $dato1Honorarios = HonorariosControlador::datosGraficoHonorariosMensuales($anio, $mes1);
        $dato1Sueldos = SueldosControlador::datosGraficoSueldosMensuales($anio, $mes1);
        
        $dato2Gastos = $this->_modelo->totalGastosMensual($anio, $mes2);
        $dato2Ingresos = IngresosControlador::datosGraficoIngresosMensuales($anio, $mes2);
        $dato2Honorarios = HonorariosControlador::datosGraficoHonorariosMensuales($anio, $mes2);
        $dato2Sueldos = SueldosControlador::datosGraficoSueldosMensuales($anio, $mes2);
        
        $dato3Gastos = $this->_modelo->totalGastosMensual($anio, $mes3);
        $dato3Ingresos = IngresosControlador::datosGraficoIngresosMensuales($anio, $mes3);
        $dato3Honorarios = HonorariosControlador::datosGraficoHonorariosMensuales($anio, $mes3);
        $dato3Sueldos = SueldosControlador::datosGraficoSueldosMensuales($anio, $mes3);
        
        $mesT1 = date('F',mktime(0,0,0,$mes1,1,$anio));
        $mesT2 = date('F',mktime(0,0,0,$mes2,1,$anio));
        $mesT3 = date('F',mktime(0,0,0,$mes3,1,$anio));
        
        $gasto1 = $dato1Gastos[0]['gtotal'] + $dato1Honorarios[0]['gtotal'] + $dato1Sueldos[0]['gtotal'];
        $gasto2 = $dato2Gastos[0]['gtotal'] + $dato2Honorarios[0]['gtotal'] + $dato2Sueldos[0]['gtotal'];
        $gasto3 = $dato3Gastos[0]['gtotal'] + $dato3Honorarios[0]['gtotal'] + $dato3Sueldos[0]['gtotal'];
//        $retorno .= "['2007',  1030]";
        $datos = $encabezado . "," . "['" . $mesT3 . "'," . $gasto1 . ',' . $dato1Ingresos[0]['gtotal'] . "]";
        $datos .= ",['" . $mesT2 . "'," . $gasto2 . ',' . $dato2Ingresos[0]['gtotal'] . "]";
        $datos .= ",['" . $mesT1 . "'," . $gasto3 . ',' . $dato3Ingresos[0]['gtotal'] . "]";
        $grafico = new ChartGoogle('Gastos Mensuales');
        $grafico->setTitutloEjeX('Mes');
        $grafico->setDatos($datos);
//        echo $datos;
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
//                $this->_redirect('index.php?option=gastos&sub=listar');
            }
        }
        return $filtro;
    }


    private function _controlar_nro_factura($nro_factura='', $proveedor='', $comprobante='', $tipo_comprobante='') 
    {
        $consulta = sprintf("nro_comprobante = '%s' && proveedor = %d && comprobante = '%s' && tipo_comprobante = '%s'", $nro_factura, $proveedor, $comprobante, $tipo_comprobante);
        $gastoBuscado = $this->_modelo->buscarGasto($consulta);
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
    	$this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS,'info');
        parent::_redirect(LIVESITE .'/index.php?option=gastos&sub=listar');
    }
    
    public function exportar()
    {
        
    }


}
