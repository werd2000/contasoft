<?php
require_once 'Zend/View.php';
//require_once 'App/LibQ/Input.php';
require_once 'Zend/Session/Namespace.php';
require_once 'App/LibQ/ControladorBase.php';
//require_once 'App/modelos/LoginModelo.php';

/**
 * Description of RegistroControlador
 *
 * @author WERD
 */
class RegistroControlador {


    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */
    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Login/Vista');
        include_once DIRMODULOS . 'Login/Modelo/LoginModelo.php';
        $this->_modelo = new LoginModelo();
    }

    public function index()
    {
        //include_once DIRMODULOS . 'Login/Vista/LoginVista.php';
        $this->_layout->content = $this->_vista->render('LoginVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    /* Valida el ingreso al sitio */

    public function validarLogin()
    {
        $retorno = false;
//        if (Zend_Session::isStarted()) {
        if (!Zend_Session::namespaceIsset('didaskalos')) {
            $sesion = new Zend_Session_Namespace('didaskalos');
        }
//        }
        print_r($sesion);

        if (Input::post('login')) {
            $username = Input::post('username');
            $password = Input::post('password');
        }
        if ($username !== '' && $password !== '') {
            $password = md5($password);
            $usuario = self::_validarUsuario($username, $password);
            if (is_object($usuario) && $usuario->nombre != '') {
                $sesion->MM_activo = 'SI';
                //actualizo fecha de ultima visita
                self::_actualizarUltimaVisita($usuario->id);
                //declara las variables de sesión
                $sesion->MM_Username = $username;
                $sesion->MM_Nombre = $usuario->nombre;
                var_dump($sesion);

                $sesion->MM_UserGroup = $usuario->categoria;
                $sesion->MM_UserId = $usuario->id;
                $sesion->Mensaje = '';
                $retorno = true;
                $this->_redirect('index.php');
            } else {
                $retorno = false;
                $this->_redirect('index.php');
            }
        }
//        var_dump($retorno);
        return $retorno;
    }

    private function _actualizarUltimaVisita($usuario)
    {
        $datos = array(
            'ultimaVisita' => date("Y-m-d H:i:s"),
            'activo' => 'SI',
            'ultima_ip' => $_SERVER['REMOTE_ADDR']
        );
        $where = array("id = '$usuario'");
        if (!$this->_modelo->actualizar($datos, $where)) {
            require_once 'Zend/Exception.php';
            throw new Exception('No se pudo actualizar los datos del usuario');
        }
        return $retorno;
    }

    private function _validarUsuario($userName, $password)
    {
        $where = array("username = '$userName'", "password = '$password'");
        $usuarioBuscado = $this->_modelo->buscarUsuario($where);
        return $usuarioBuscado;
    }

    public function logout()
    {
        Zend_Session::namespaceIsset('didaskalos');
        $datos = array(
            'ultimaVisita' => date("Y-m-d H:i:s"),
            'activo' => 'NO',
        );
        $where = array("id = '$sesion->MM_UserId'");
        $this->_modelo->actualizar($datos, $where);
        Zend_Session::namespaceUnset('didaskalos');
        parent::_redirect(LIVESITE);
    }



}

