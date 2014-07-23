<?php
class ResultDecorator
{
    private $_resultObj;
    public $resultSet;
    
    /**
     * Constructor del objeto decorador
     * @param Result $resultObj
     * @access public
     */
    public function __construct (Result $resultObj)
    {
        $this->_resultObj = $resultObj;
        $this->_resetResult();
    }
    
    private function _resetResult ()
    {
        $this->resultSet = $this->_resultObj->getResult();
    }
    
    public function getCantidadCampos ()
    {
        return mysql_num_fields($this->_resultObj->getResult());
    }
    
    public function getCampos ()
    {
        $cantCampos = self::getCantidadCampos();
        for ($i = 0; $i < $cantCampos; $i ++) {
////            $type = mysql_field_type($this->_result, $i);
////            $name = mysql_field_name($this->_result, $i);
//            $campo['nombre']=mysql_field_name($this->_result, $i);
            $campos[] = mysql_field_name( $this->_resultObj->getResult(), $i );
////            $len = mysql_field_len($this->_result, $i);
////            $flags = mysql_field_flags($this->_result, $i);
////            echo $type . " " . $name . " " . $len . " " . $flags . "\n";
        }
        return $campos;
        
    }
}
