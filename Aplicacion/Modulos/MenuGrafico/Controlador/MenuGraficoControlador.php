<?php
require_once 'Zend/View.php';
require_once 'App/LibQ/ControladorBase.php';
require_once LibQ . 'ControlarSesion.php';
require_once DIRMODULOS . 'Widgets/Controlador/WidgetsControlador.php';

class MenuGraficoControlador extends ControladorBase
{

    protected $_vista;
    protected $_sesion;

    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'MenuGrafico/Vista');
        $this->_vista->addScriptPath(DIRMODULOS .'Widgets/Vista');
        ControlarSesion::iniciarSesion();
//        if (!Zend_Session::namespaceIsset('contasoft')) {
//            $this->_sesion = new Zend_Session_Namespace('contasoft');
//        }
    }

    public function index()
    {
        require_once LibQ . 'archivosYcarpetas.php';
        $listaWidgets = array();
        $listaWidgets[] = new WidgetsControlador('GastosControlador', 'ultimosGastos');
        $grafEgresos = new WidgetsControlador('GastosControlador', 'graficoTotalGastosMensuales');
        $listaWidgets[] = $grafEgresos;
        $listaWidgets[] = new WidgetsControlador('HonorariosControlador', 'ultimosHonorarios');
//        $grafIngresos = new WidgetsControlador('IngresosControlador', 'graficoIngresosMensuales');
//        $listaWidgets[] = $grafIngresos;
        $listaWidgets[] = new WidgetsControlador('IngresosControlador', 'ultimosIngresos');
        $sesion = new Zend_Session_Namespace('contasoft');
        $modulos = archivosYcarpetas::listar_directorios_ruta('App/Modulos/');
//        print_r($sesion);
        foreach ($modulos as $modulo) {
            if ($modulo != 'Login' && $modulo != 'MenuGrafico' && $modulo != 'Widgets'){
                if ($modulo == 'Usuarios' && $sesion->MM_UserGroup == 'ADMINISTRADOR'){
                    $listaModulos[]=$modulo;
                }
                $listaModulos[]=$modulo;
            }
        }
        $this->_vista->modulos = $listaModulos;
        $this->_layout->content = $this->_vista->render('MenuGraficoVista.php');
        
        if (is_array($listaWidgets)){
            $this->_vista->widgets = $listaWidgets;        
        }else{
            throw new Exception('Lista de Widgets no válida');
        }
        $this->_layout->widgets = $this->_vista->render('WidgetsVista.php');
        
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    public function logout()
    {
        Usuario::actualizarUltimaVisita($sesion->MM_UserId, 'NO');
        Zend_Session::stop();
        // Finalmente, destruye la sesi�n
        Zend_Session::destroy();
    }


}
