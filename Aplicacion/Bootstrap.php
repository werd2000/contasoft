<?php

require_once 'Controlador.php';
require_once 'Aplicacion/config/configuration.php';

class Bootstrap
{

    protected $_appNamespace = 'Application';

    protected function __construct()
    {
        
    }

    protected function _initSession()
    {
        ControlarSesion::iniciarSesion();
//         if (!Zend_Session::isStarted()) {
//             Zend_Session::start();
//         }
    }

    static function main()
    {
        require_once LibQ . 'Zend/Loader.php';
//        Zend_Loader::registerAutoload();
//        $autoloader = Zend_Loader_Autoloader::getInstance();
        Controlador::despachador();
    }

}
