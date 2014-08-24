<?php
ob_start();
// Reportar E_NOTICE puede ser bueno tambien (para reportar variables
// no inicializadas o capturar equivocaciones en nombres de variables ...)
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

abstract class Index
{
    public function ejecutar ()
    {
        require_once 'Aplicacion/Librerias/Zend/Loader.php';
        require_once 'Aplicacion/Bootstrap.php';
        /* Se define el path de la App incluyendo el directorio App */
        defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/App'));
        /* Se incluye en el path a la carpeta LibQ y a la carpeta config */
        set_include_path(implode(PATH_SEPARATOR,
              array(realpath(APPLICATION_PATH . '/LibQ') ,
                    realpath(APPLICATION_PATH . '/Config'),
                    realpath(APPLICATION_PATH . '/LibQ/Zend'),
                    get_include_path()))
        );
        Bootstrap::main();
    }
}

Index::ejecutar();
ob_end_flush();

