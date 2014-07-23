<?php
class Sql
{
    private $_colWhere = array(); 
    private $_colSelect = array(); 
    private $_limit ;
    private $_colFrom = array(); 
    private $_colOrder = array(); 
    private $_colValue = array();
    private $_funcion = "select";
    private $_groupBy;
    
    public function __construct ($sql=null)
    {
        if (is_string($sql)){
            self::_evaluarSql ($sql);
        }
    }
    
    /**
     * Evalua el string sql que vino en el constructor
     * @param String $sql
     * @return void
     */
    private function _evaluarSql($sql)
    {
        $findme = 'SELECT';
        $pos = strpos($sql, $findme);
        if ($pos === false) {
            trigger_error( "La cadena '$findme' no fue encontrada en la cadena '$sql'", E_USER_NOTICE);
        } else {
            echo "La cadena '$findme' fue encontrada en la cadena '$sql'";
            echo " y existe en la posición $pos";
        }
    }
    
    /**
     * Agrega una tabla de la bd donde se realizar� la consulta
     * @param String $table
     * @return void
     */
    public function addTable($table)
    {
        $this->_colFrom[] = $table;
    }
    
    /**
     * Agrega un condición para la consulta
     * @param String $where
     * @return void
     */
    public function addWhere($where)
    {
        $this->_colWhere[] = $where;
    }
    
    /**
     * Agrega campo de la tabla para establecer la consulta
     * @param String $select
     * @return void
     */
    public function addSelect($select)
    {
        $this->_colSelect[] = ' ' . $select;
    }
    
    /**
     * Agrega un l�mite de registros a la consulta
     * @param int $inicio
     * @param int $limit
     * @return void
     */
    public function addLimit($inicio, $limit)
    {
        $this->_limit = " LIMIT $inicio , $limit";
    }
    
    /**
     * Establece por que campos se va a ordenar el resultado de la consulta
     * @param String $order
     * @return void
     */
    public function addOrder($order)
    {
        $this->_colOrder[] = $order;
    }
    
    /**
     * Establece un valor en la matriz de valores que corresponde a cada campo
     * @param $valor
     * @return unknown_type
     */
    public function addValue($valor)
    {
//        $this->_colValue[] = "'".$valor."'";
        $this->_colValue[] = $valor;
    }
    
    /**
     * Especifica el tipo de consulta
     * @param String $funcion
     * @return void
     */
    public function addFuncion($funcion)
    {
        $this->_funcion = $funcion;
    }
    
    /**
     * Especifica la clausula GroupBy
     * @param type $group string
     */
    public function addGroupBy($group)
    {
        $this->_groupBy = $group;
    }
    
    /**
     * Genera la consulta sql
     * @return String Sql
     */
    private function _generar()
    {
        $select = implode(', ',array_unique($this->_colSelect));
        $values = implode(', ',$this->_colValue);
        $from   = implode(', ',array_unique($this->_colFrom));
        $where  = implode(' AND ',array_unique($this->_colWhere));
        $groupBy = '';
        $orden = implode(', ',array_unique($this->_colOrder));
        $limit = $this->_limit;
        
        if($this->_groupBy != ''){
            $groupBy = ' Group By '.$this->_groupBy;
        }
        
        if($orden != ''){
            $orden = ' ORDER BY '.$orden;
        }

        if($where != ''){
            $where = ' WHERE '.$where;
        }

        if(strtolower($this->_funcion) == "insert"){
            foreach ($this->_colValue as $valor){
                $val[] = "'" . $valor . "'";
            }
            $valores = implode(', ', $val);
            return $this->_funcion. " INTO ". $from . " (".$select.") VALUES (".$valores.")";
        }

        if(strtolower($this->_funcion) == "update"){
            foreach ($this->_colValue as $valor){
                $val[] = "'" . $valor . "'";
            }
            $valores = implode(', ', $val);
            return $this->_funcion. " " .$from. " SET ".$select." ".$where;
        }
        
        if(strtolower($this->_funcion) == "select"){
            if ($select == ''){
                $select .= "*";
            }
        }
        if(strtolower($this->_funcion) == "delete"){
            $select = "";
        }
        
        $sql = $this->_funcion .$select.' FROM '.$from.$where.$groupBy.$orden.$limit;
        return  $sql;
    }
    
    /**
     * Obtiene la funci�n para la que fue creado el Sql
     * @return String
     */
    public function getFuncion()
    {
        return $this->_funcion;
    }
    
    /**
     * Devuelve un String del objeto Sql
     * @return String
     */
    public function __toString()
    {
        return $this->_generar();
    }
}