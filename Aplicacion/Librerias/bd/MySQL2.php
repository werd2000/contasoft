<?php
require_once 'App/LibQ/bd/ManejadorBaseDeDatosInterface.php';
require_once 'App/LibQ/bd/Result.php';

class contasoft_App_LibQ_bd_MySQL extends Zend_Db_Adapter_Abstract implements ManejadorBaseDeDatosInterface
{
    const USUARIO = 'root';
    const CLAVE = '';
    const BASE = 'contasoft';
    //        const USUARIO = 'ig000179';
    //        const CLAVE = 'ti32FOfuwa';
    //        const BASE = 'ig000179_contasoft';
    const SERVIDOR = 'localhost';
    private $_conexion;
    private static $_instance = NULL;
    
    /**
     * M�todo constructor privado
     * @access private
     */
//    private function __construct ($config)
//    {
//        $this->conectar();
//    }
    
    /**
     * Retorna una instancia de la clase MySQL
     * @access public
     */
    public static function getInstance ()
    {
        if (self::$_instance === NULL) {
            self::$_instance = new MySQL();
        }
        return self::$_instance;
    }
    
    /**
     * Conectar a la BD
     * @access public
     */
    public function conectar ()
    {
        $this->_conexion = mysql_connect(self::SERVIDOR, self::USUARIO, self::CLAVE);
        mysql_select_db(self::BASE, $this->_conexion);
    }
    
    /**
     * (non-PHPdoc)
     * @see App/LibQ/bd/ManejadorBaseDeDatosInterface#desconectar()
     */
    public function desconectar ()
    {
        mysql_close($this->_conexion);
    }
    
//    public function traerDatos (Sql $sql)
//    {
//        $todo = array();
//        $resultado = mysql_query($sql, $this->_conexion) or die("Consulta fallida: " . mysql_error() . " Actual query: " . $sql . " en " . debug_print_backtrace());;
//        $retorno = new Result($this, $resultado);
//        return $retorno;
//    }
    
    /**
     * Ejecuta una consulta sql
     * @see persistencia/ManejadorBaseDeDatosInterface#ejecutar($sql)
     */
    public function ejecutar (Sql $sql)
    {
        $result = mysql_query($sql, $this->_conexion) or die("Consulta fallida: " . mysql_error() . " Actual query: " . $sql . " en " . debug_print_backtrace());;
        if (!$result)
            throw new Zend_Exception("Query failed: " . mysql_error() . " Actual query: " . $sql);
            //      $this->queryCount++;
        $retorno = new Result($this, $result);
        return $retorno;
    }
    
	/**
     * Obtiene multiples registros
     * @param string $sql Consulta SQL
     * @param string $campoLlave Campo que desea como llaves de arreglo
     * @return array Retorna un arreglo con o sin resultados
     */
//    public function obtener($sql = null)
//    {
////        if (!$this->_conectado) {
////            $this->conectar();
////        }
//
//        if (!$sql && !$this->_sql) {
//            throw new Exception('MyNP | La consulta SQL a ejecutar esta vacia.');
//        } elseif (!$sql && $this->_sql) {
//            $sql = $this->_sql;
//        }
//        
//        if ($resultado = mysql_query($sql,$this->_conexion)) {
//            $final = array();
//            $fila = mysql_fetch_assoc($resultado);
//            mysql_free_result($resultado);
//            return $final;
//        } else {
//            throw new Exception('La consulta [ ' . $sql . ' ] di� el siguiente error: ' . mysqli_error($this->_conexion) . '.', 102);
//        }
//    }
}