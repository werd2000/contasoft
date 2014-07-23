<?php

/**
 * Botón Inscribir
 * @author Walter Ruiz Diaz
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class BotonInscribir extends BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Inscribir');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-inscribir32');
        }
    }


}


