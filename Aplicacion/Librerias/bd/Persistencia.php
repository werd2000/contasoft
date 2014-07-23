<?php
//require_once 'configuration.php';
require_once 'App/LibQ/bd/BaseDeDatos.php';
require_once 'App/LibQ/bd/MySQL.php';
require_once 'App/LibQ/bd/Sql.php';
require_once 'App/LibQ/bd/Decoradores/ObjetoResultDecorator.php';
//require_once 'persistencia/Decoradores/ArrayResultDecorator.php';
//require_once 'persistencia/Decoradores/JSONResultDecorator.php';
require_once 'App/LibQ/bd/ResultDecorator.php';

class Persistencia
{
    private $_tipoDatos='Objeto';
    protected static $_table;

    function __construct ($tabla='')
    {
        self::$_table = $tabla;
    }
    public function setTipoDatos($tipoDatos)
    {
        $this->_tipoDatos = $tipoDatos;
    }
    
    public function getTipoDatos()
    {
        return $this->_tipoDatos;
    }
    
    /**
     * Dependiendo del tipo de datos buscado
     * devuelve un Objeto, un Array o un JSON
     * @param String $order
     * @return ObjetoResultDecorator
     */
    public function getAll ($order = '',$filtro='')
    {
        $bd = new BaseDeDatos(unMySQL::getInstance());
        $sql = new Sql();
        $sql->addTable(self::$_table);
        if ($order != ''){
            $sql->addOrder($order);
        }
        if ($filtro != ''){
            $sql->addWhere($filtro);
        }
//        echo $sql.'<br>';
        $result = $bd->ejecutar($sql);
        switch ($this->_tipoDatos){
            case 'Objeto':
                $resultDecorator=new ResultDecorator($result);
                $objetoResultDecorator=new ObjetoResultDecorator ($resultDecorator);
                $retorno = $objetoResultDecorator->getArray();
                break;
            case 'Array':
                $resultDecorator=new ResultDecorator($result);
                $arrayResultDecorator=new ArrayResultDecorator ($resultDecorator);
                $retorno = $arrayResultDecorator->getArray();
                break;
            case 'JSON':
                $resultDecorator=new ResultDecorator($result);
                $jsonResultDecorator=new JSONResultDecorator ($resultDecorator);
                $retorno = $jsonResultDecorator->getArray();
                break;
        }
        return $retorno;
    }
    
    /**
     * Devuelve los datos como un array
     * @param String $sql
     * @return ObjetoResultDecorator Array
     */
    public function getAllArray ($sql = '')
    {
        $bd = new BaseDeDatos(unMySQL::getInstance());
        $result = $bd->ejecutar($sql);
        $resultDecorator=new ResultDecorator($result);
        $arrayResultDecorator=new ArrayResultDecorator ($resultDecorator);
        return $arrayResultDecorator->getArray();
    }
    
    /**
     * Devuelve un objeto encontrado
     * @param Mixed $clave
     * @return ObjetoResultDecorator
     */
    public function load ($clave)
    {
        $bd = new BaseDeDatos(unMySQL::getInstance());
        $sql = new Sql();
        $sql->addTable(self::$_table);
        if (is_array($clave)){
            foreach ($clave as $key => $value) {
                $sql->addWhere("$key=" . $value);
            }
        }else{
            $sql->addWhere($clave);
        }
//        echo $sql.'<br>';
        $result = $bd->ejecutar($sql);
        $resultDecorator=new ResultDecorator($result);
        $objetoResultDecorator=new ObjetoResultDecorator ($resultDecorator);
        // get result set as array
        return $objetoResultDecorator->getObjeto();
        
    }
    /**
     * Ejecuta una consulta MySql
     * @param SQL $sql
     * @return ObjetoResultDecorator
     */
    public function execute( $sql )
    {
        $bd = new BaseDeDatos(MySQL::getInstance());
//        $sql = new Sql($query);
//        $this->lastQuery = $query;
        $result = $bd->ejecutar($sql);
        $resultDecorator=new ResultDecorator($result);
        $objetoResultDecorator=new ObjetoResultDecorator ($resultDecorator);
        return $objetoResultDecorator;
   }

    public function guardarDatos ($matriz)
    {
        $bd = new BaseDeDatos(MySQL::getInstance());
        $sql = new Sql();
        $sql->addFuncion("insert");
        $sql->addTable(self::$_table);
        foreach ($matriz as $key => $value) {
            $sql->addSelect($key);
            $sql->addValue($value);
//            echo $key . "=" . $value . "</br>";
        }
//        echo $sql;
        return $bd->ejecutar($sql);
    }
    /**
     * Con esta funci�n modificamos los datos del usuario
     * Recibe el Id del usuario a modificar y la matriz con los datos
     * Devuelve el n�mero de registros afectados
     */
    public function modificarDatos ($matriz, $where)
    {
        $bd = new BaseDeDatos(MySQL::getInstance());
        $sql = new SQL();
        $sql->addFuncion("update");
        $sql->addTable(self::$_table);
        foreach ($matriz as $key => $value) {
            $sql->addSelect("$key=  $value  ");
        }
        foreach ($where as $key => $value) {
            $sql->addWhere("$key=" . $value);
        }
        echo $sql;
        return $bd->ejecutar($sql);
    }
    
    public function eliminarUsuario ($where)
    {
        $bd = new BaseDeDatos(new MySQL());
        $sql = new SQL();
        $sql->addFuncion("delete");
        $sql->addTable(self::$_table);
        foreach ($where as $key => $value) {
            $sql->addWhere("$key=" . $value);
        }
                
        return $bd->ejecutar($sql);
    }
}