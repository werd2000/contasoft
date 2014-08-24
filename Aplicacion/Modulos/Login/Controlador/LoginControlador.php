<?php

require_once 'Zend/View.php';
require_once 'Zend/Session/Namespace.php';
require_once 'App/LibQ/ControladorBase.php';
require_once 'App/LibQ/ControlarSesion.php';
require_once 'App/LibQ/Hash.php';
/**
 *  Clase Controladora del Modulo Login
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Login
 */
class LoginControlador extends ControladorBase
{
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
        $this->_layout->content = $this->_vista->render('LoginVista.phtml');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    /* Valida el ingreso al sitio */
    public function validarLogin()
    {
        ControlarSesion::iniciarSesion();
        $retorno = false;
//        if (!Zend_Session::namespaceIsset('contasoft')) {
            $sesion = new Zend_Session_Namespace('contasoft');
//        }
//        print_r($sesion);

        if (Input::post('login')) {
            $username = Input::post('username');
            $password = Input::post('password');
        }
        if ($username !== '' && $password !== '') {
            $password = Hash::getHash('sha1',$password,HASH_KEY);
            $usuario = self::_validarUsuario($username, $password);
            if (is_object($usuario) && $usuario->nombre != '') {
                $sesion->MM_activo = 'SI';
                //actualizo fecha de ultima visita
                self::_actualizarUltimaVisita($usuario->id);
                //declara las variables de sesión
                $sesion->MM_Username = $username;
                $sesion->MM_Nombre = $usuario->nombre;
//                var_dump($sesion);

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
//        return $retorno;
    }

    private function _validarUsuario($userName, $password)
    {
        $where = array("username = '$userName'", "password = '$password'");
        $usuarioBuscado = $this->_modelo->buscarUsuario($where);
        return $usuarioBuscado;
    }

    public function logout()
    {
        Zend_Session::namespaceIsset('contasoft');
        $datos = array(
            'ultimaVisita' => date("Y-m-d H:i:s"),
            'activo' => 'NO',
        );
        $where = array("id = '$sesion->MM_UserId'");
        $this->_modelo->actualizar($datos, $where);
        Zend_Session::namespaceUnset('contasoft');
        parent::_redirect(LIVESITE);
    }

}
