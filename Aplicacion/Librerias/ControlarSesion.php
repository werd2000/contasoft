<?php

include_once 'Zend/Session.php';
include_once 'Zend/Session/Namespace.php';


/**
 * Controla que la sesion este iniciada
 * en caso contrario la crea con el nombre del parametro pasado en el constructor
 * Usa la libreria Zend_Session, Zend_Session_Namespace
 * @see Zend_Session
 *
 * @author walter
 */
class ControlarSesion
{

    /**
     * Controla si el nombre de espacioSesion existe.
     * Si no existe la crea
     * @param string $nombreEspacio
     * @return Zend_Session_Namespace 
     */
    public static function controlarEspacioSesion($nombreEspacio)
    {
        if (!Zend_Session::isStarted()){
            Zend_Session::start();
        }
        if (Zend_Session::namespaceIsset($nombreEspacio)) {
            $edu_sesion = new Zend_Session_Namespace($nombreEspacio, false  );
        }
        return $edu_sesion;
    }

    /**
     * Inicia una sesión usando la libreria Zend_Session
     */
    public static function iniciarSesion()
    {
        if (!Zend_Session::isStarted()){
            Zend_Session::start();
        }
    }

    public static function destruirSesion()
    {
        Zend_Session::destroy();
    }

}

