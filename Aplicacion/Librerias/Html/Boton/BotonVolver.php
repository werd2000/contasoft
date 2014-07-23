<?php

/**
 * Botón Volver
 * @author Walter Ruiz Diaz
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class BotonVolver extends BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Volver');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-volver32');
        }
    }


}


