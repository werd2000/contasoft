<?php

/**
 * Botón Eliminar
 * @author Walter Ruiz Diaz
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class BotonEeditar extends BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Editar');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-editar32');
        }
    }


}


