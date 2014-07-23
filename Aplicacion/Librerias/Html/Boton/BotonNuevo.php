<?php

/**
 * Botón Nuevo
 * @author Walter Ruiz Diaz
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class BotonNuevo extends BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Nuevo');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-nuevo32');
        }
    }


}


