<?php

/**
 * Botón Nuevo
 * @author Walter Ruiz Diaz
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class BotonGuardar extends BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Guardar');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-guardar32');
        }
    }


}


