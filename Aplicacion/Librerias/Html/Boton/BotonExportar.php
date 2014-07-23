<?php

/**
 * Botón Exportar
 * @author Walter Ruiz Diaz
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class BotonExportar extends BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Exportar');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-exportar32');
        }
    }


}


