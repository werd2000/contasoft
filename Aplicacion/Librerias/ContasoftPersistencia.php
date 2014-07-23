<?php
require_once 'App/LibQ/Bd/Persistencia.php';

class ContasoftPersistencia extends Persistencia
{
    function __construct($tabla='')
	{
//	    if (is_string($tabla) && (string) !$tabla==''){
	    if (is_string($tabla)){
	        parent::$_table = $tabla;
	    }else{
	        require_once 'Zend/Exception.php';
	        throw new Exception ('Nombre de la tabla no asignado o erroneo');
	    }
	}
}