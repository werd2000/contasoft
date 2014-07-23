<?php

///require_once 'App/LibQ/Bd/BaseDeDatos.php';
//require_once LibQ . 'Bd/MySQL.php';
require_once 'App/LibQ/Bd/Sql.php';
//require_once 'App/LibQ/Config.php';
require_once 'Zend/Db/Table/Abstract.php';

/**
 *  Clase @abstract usada como base para las clases modelos
 *  @author Walter Ruiz Diaz
 *  @category Modelo
 *  @package LibQ
 *  @see Bd_MySql, Bd_Sql, Config
 */
abstract class ModelBase extends Zend_Db_Table_Abstract
{

    /**
     * Propiedad utilizada para definir la BD
     */
    protected $_db;

    /**
     *  Propiedad utilizada para definir si se muestran los registros eliminados 
     */
    protected $_verEliminados;

    /**
     * Indica cuantos registros por vez puede traer la lista
     * @var type Int
     */
    protected $_limite = 29;

    /**
     *  Metodo constructor
     *  Se crea la BD con los parametros necesarios
     */
    public function __construct()
    {
        require_once LibQ . 'Zend/Db/Adapter/Pdo/Mysql.php';
//            $this->_db = new Zend_Db_Adapter_Pdo_Mysql(array(
//                'host'             => 'localhost',
//                'username'         => 'ig000179',
//                'password'         => 'ti32FOfuwa',
//                'dbname'           => 'ig000179_contasoft'
//            ));

        $this->_db = new Zend_Db_Adapter_Pdo_Mysql(array(
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => '',
                    'dbname' => 'ig000179_contasoft'
                ));
//            $this->_config = Config::singleton();
        $this->_verEliminados = 'false'; //$this->_config->get('verEliminados');
    }

    public function setLimite($limite)
    {
        $this->_limite = intval($limite);
    }

}
