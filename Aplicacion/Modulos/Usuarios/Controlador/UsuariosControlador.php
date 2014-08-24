<?php

require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
require_once LibQ . 'Zend/Json.php';
require_once 'App/LibQ/ControladorBase.php';

/**
 *  Clase Controladora del Modulo Usuarios
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Usuarios
 * 
 */
class UsuariosControlador extends ControladorBase
{

    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=usuarios&sub=agregar',
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
    private $_paramBotonVolver = array('href' => 'index.php?option=usuarios');
    private $_paramBotonVerHistorial = array(
        'class' => 'btn_Ver',
        'evento' => "onclick=\"javascript: submitbutton('verHistorialDocente')\"",
        'href' => "\"javascript:void(0);\"",
        'titulo' => 'Historial'
    );

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
        'href' => 'index.php?option=usuarios&sub=listar',
        'classIcono' => 'icono-lista32'
    );

    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */

    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Usuarios/Vista');
        require_once DIRMODULOS . 'Usuarios/Modelo/UsuariosModelo.php';
        $this->_modelo = new UsuariosModelo();

        $this->_varForm['id'] = '0';
        $this->_varForm['nombre'] = '';
        $this->_varForm['userName'] = '';
        $this->_varForm['password'] = '';
        $this->_varForm['categoria'] = '';
        $this->_varForm['bloqueado'] = false;
        $this->_varForm['enviarMail'] = false;
        $this->_varForm['activo'] = false;
        $this->_varForm['email'] = '';
    }

    /**
     * M�todo que lleva al men� de los gastos
     * @return void
     */
    public function index()
    {
        // seteo la variable content:
        $this->_layout->content = $this->_vista->render('UsuariosVista.php');
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
        require_once DIRMODULOS . 'Usuarios/Forms/CargaUsuarios.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $this->_form = new Form_CargaUsuarios($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['password']=  md5($values['password']);
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
        $this->_layout->content = $this->_vista->render('AgregarUsuariosVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    public function editar($arg)
    {
        require_once DIRMODULOS . 'Usuarios/Forms/CargaUsuarios.php';
        include_once LibQ . 'MyFechaHora.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'Zend/File/Transfer.php';
        $where = implode(',', $arg);
        $usuarioBuscado = $this->_modelo->buscarUsuario($where);
        if (is_object($usuarioBuscado)) {
            $this->_varForm['id'] = $usuarioBuscado->id;
            $this->_varForm['nombre'] = $usuarioBuscado->nombre;
            $this->_varForm['userName'] = $usuarioBuscado->userName;
            $this->_varForm['password'] = $usuarioBuscado->password;
            $this->_varForm['categoria'] = $usuarioBuscado->categoria;
            $this->_varForm['bloqueado'] = $usuarioBuscado->bloqueado;
            $this->_varForm['enviarMail'] = $usuarioBuscado->enviarMail;
            $this->_varForm['activo'] = $usuarioBuscado->activo;
            $this->_varForm['email'] = $usuarioBuscado->email;
        }
        $this->_form = new Form_CargaUsuarios($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $cantReg = $this->_modelo->actualizar($values, $arg);
                if ($cantReg > 0) {
                    $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
                } else {
                    $this->_vista->mensajes = Mensajes::presentarMensaje(ERROR_GUARDAR, 'error');
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
        $this->_layout->content = $this->_vista->render('AgregarUsuariosVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    public function listar($arg = '')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('USUARIOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=usuarios&sub=jsonListarUsuarios');
        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'nombre'" => "'Nombre'",
            "'userName'" => "'Nombre Usuario'",
            "'categoria'" => "'Categoría'",
            "'bloqueado'" => "'Bloqueado'",
            "'enviarMail'" => "'Enviar EMail'",
            "'activo'" => "'Activo'",
            "'email'" => "'E-Mail'",
        ));

        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '55'),
            array('name' => 'Nombre', 'index' => 'nombre', 'width' => '180'),
            array('name' => 'Nombre Usuario', 'index' => 'userName', 'width' => '180'),
            array('name' => 'Categoría', 'index' => 'categoria', 'width' => '100'),
            array('name' => 'Bloqueado', 'index' => 'bloqueado', 'width' => '55', 'align'=>'center','formatter' => 'checkbox'),
            array('name' => 'Enviar EMail', 'index' => 'enviarMail', 'width' => '55', 'align'=>'center','formatter' => 'checkbox'),
            array('name' => 'Activo', 'index' => 'activo', 'width' => '55', 'align'=>'center','formatter' => 'checkbox'),
            array('name' => 'E-Mail', 'index' => 'email', 'width' => '200')
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(TRUE);
        $grilla->setActionOnDblClickRow('/index.php?option=usuarios&sub=editar&id=');

        $bh = new LibQ_BarraHerramientas($this->_vista);
//        $bh->addBoton('Exportar', array('href' => 'index.php?option=alumnos&sub=exportar' . $filtroBoton,
//        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoUsuariosVista.php');
        echo $this->_layout->render();
    }

    public function jsonListarUsuarios($arg = '')
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
        $campos = array('id', 'nombre', 'userName', 'categoria', 'bloqueado', 'enviarMail', 'activo', 'email');
        $todos = $this->_modelo->getCantidadRegistros();
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->ListaUsuarios($inicio, $orden, $campos);
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array(
                $row['id'],
                $row['nombre'],
                $row['userName'],
                $row['categoria'],
                $row['bloqueado'],
                $row['enviarMail'],
                $row['activo'],
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
        parent::_redirect(LIVESITE . '/index.php?option=usuarios&sub=listar');
    }

}
