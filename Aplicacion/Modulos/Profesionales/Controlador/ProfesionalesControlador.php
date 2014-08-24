<?php

require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
require_once LibQ . 'Zend/Json.php';
require_once 'App/LibQ/ControladorBase.php';

/**
 *  Clase Controladora del Modulo Profesionales
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Profesionales
 * 
 */
class ProfesionalesControlador extends ControladorBase
{

    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=profesionales&sub=agregar',
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
    private $_paramBotonVolver = array('href' => 'index.php?option=profesionales');
   

    /**
     * Propiedad usada para enviar los elementos del formulario
     * @var type Array
     */
    private $_varForm = array();

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
        'href' => 'index.php?option=profesionales&sub=listar',
        'classIcono' => 'icono-lista32'
    );

    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */

    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Profesionales/Vista');
        require_once DIRMODULOS . 'Profesionales/Modelo/ProfesionalesModelo.php';
        $this->_modelo = new ProfesionalesModelo();

        $this->_varForm['id'] = '0';
        $this->_varForm['nombre'] = '';
        $this->_varForm['profesion'] = '';
        $this->_varForm['apellido'] = '';
        $this->_varForm['cel'] = '';
        $this->_varForm['cuit'] = false;
        $this->_varForm['domicilio'] = false;
        $this->_varForm['email'] = false;
        $this->_varForm['nro_doc'] = '';
        $this->_varForm['tel'] = '';
        $this->_varForm['condicion_iva'] = '';
    }

    /**
     * M�todo que lleva al men� de los gastos
     * @return void
     */
    public function index()
    {
        // seteo la variable content:
        $this->_layout->content = $this->_vista->render('ProfesionalesVista.php');
        // establezco el layout:
        $this->_layout->setLayout('layout');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * M�todo que lleva a la pag donde se cargan los proveedores
     * @return void
     */
    public function agregar()
    {
        require_once DIRMODULOS . 'Profesionales/Forms/CargaProfesionales.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $this->_form = new Form_CargaProfesionales($this->_varForm);
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
        $this->_layout->content = $this->_vista->render('AgregarProfesionalesVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    public function editar($arg)
    {
        require_once DIRMODULOS . 'Profesionales/Forms/CargaProfesionales.php';
        include_once LibQ . 'MyFechaHora.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'Zend/File/Transfer.php';
        $where = implode(',', $arg);
        $profesionalBuscado = $this->_modelo->buscarProfesional($where);
        if (is_object($profesionalBuscado)) {
            $this->_varForm['id'] = $profesionalBuscado->id;
            $this->_varForm['nombre'] = $profesionalBuscado->nombre;
            $this->_varForm['apellido'] = $profesionalBuscado->apellido;
            $this->_varForm['profesion'] = $profesionalBuscado->profesion;
            $this->_varForm['nro_doc'] = $profesionalBuscado->nro_doc;
            $this->_varForm['condicion_iva'] = $profesionalBuscado->condicion_iva;
            $this->_varForm['cuit'] = $profesionalBuscado->cuit;
            $this->_varForm['tel'] = $profesionalBuscado->tel;
            $this->_varForm['cel'] = $profesionalBuscado->cel;
            $this->_varForm['email'] = $profesionalBuscado->email;
        }
        $this->_form = new Form_CargaProfesionales($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $cantReg = $this->_modelo->actualizar($values, $arg);
                if ($cantReg > 0) {
                    $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
                } else {
                    $this->_vista->mensajes = Mensajes::presentarMensaje($cantReg, 'error');
                }
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Eliminar', $this->_paramBotonEliminar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Lista', $this->_paramBotonLista);
//        $bh->addBoton('Ver', $this->_paramBotonVerHistorial);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $foto = '<div id=mostrarFoto><img src="' . IMG . 'fotos/id' . $this->_varForm['id'] . '.png" class="mostrarFoto"/></div>';
        $this->_vista->foto = $foto;
        $this->_layout->content = $this->_vista->render('AgregarProfesionalesVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    public function listar($arg = '')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('PROFESIONALES');
        $grilla->setUrl(LIVESITE . '/index.php?option=profesionales&sub=jsonListarProfesionales');
        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'apellido'" => "'Apellido'",
            "'nombre'" => "'Nombre'",
            "'profesion'" => "'Profesión'",
            "'nro_doc'" => "'Nro.Doc.'",
            "'condicion_iva'" => "'Condición Iva'",
            "'cuit'" => "'Cuit'",
            "'domicilio'" => "'Domicilio'",
            "'tel'" => "'Teléfono'",
            "'cel'" => "'Celular'",
            "'email'" => "'Email'",
        ));

        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '30'),
            array('name' => 'Apellido', 'index' => 'apellido', 'width' => '95'),
            array('name' => 'Nombre', 'index' => 'nombre', 'width' => '115'),
            array('name' => 'Profesión', 'index' => 'profesion', 'width' => '115'),
            array('name' => 'Nro.Doc.', 'index' => 'nro_doc', 'width' => '70', 'align'=>'right'),
            array('name' => 'Condición Iva', 'index' => 'condicion_iva', 'width' => '90', 'align'=>'center'),
            array('name' => 'Cuit', 'index' => 'cuit', 'width' => '80', 'align'=>'center'),
            array('name' => 'Domicilio', 'index' => 'domicilio', 'width' => '170'),
            array('name' => 'Teléfono', 'index' => 'tel', 'width' => '65', 'align'=>'right'),
            array('name' => 'Celular', 'index' => 'cel', 'width' => '70', 'align'=>'right'),
            array('name' => 'Email', 'index' => 'email', 'width' => '115')
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(TRUE);
        $grilla->setActionOnDblClickRow('/index.php?option=profesionales&sub=editar&id=');

        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoProfesionalesVista.php');
        echo $this->_layout->render();
    }

    public function jsonListarProfesionales($arg = '')
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
                $orden = 'nombre';
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
        $campos = array('id', 'apellido', 'nombre', 'profesion', 'nro_doc', 'condicion_iva', 'cuit', 'domicilio', 'tel', 'cel', 'email');
        $todos = $this->_modelo->getCantidadRegistros();
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->ListaProfesionales($inicio, $orden, $campos);
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array(
                $row['id'],
                $row['apellido'],
                $row['nombre'],
                $row['profesion'],
                $row['nro_doc'],
                $row['condicion_iva'],
                $row['cuit'],
                $row['domicilio'],
                $row['tel'],
                $row['cel'],
                $row['email'],
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }

    public function eliminar($arg = '')
    {
        $where = implode(',', $arg);
        $values['eliminado'] = true;
        $cantReg = $this->_modelo->actualizar($values, $where);
        if ($cantReg > 0) {
            $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
        } else {
            $this->_vista->mensajes = Mensajes::presentarMensaje(ERROR_GUARDAR, 'error');
        }
        parent::_redirect(LIVESITE . '/index.php?option=profesionales&sub=listar');
    }

}
