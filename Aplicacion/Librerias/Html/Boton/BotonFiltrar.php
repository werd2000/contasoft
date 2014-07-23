<?php

/**
 * Botón Filtrar
 * @author Walter Ruiz Diaz
 * @see BotonAbstract.php
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class BotonFiltrar extends BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Filtrar');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-filtrar32');
        }

    }


}


