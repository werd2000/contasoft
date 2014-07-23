<?php
require_once ('App/LibQ/bd/ResultDecorator.php');
class ObjetoResultDecorator extends ResultDecorator
{
    private $_resultDecorator;
    private $_resultObjeto=array();
    
    // pass 'ResultDecorator' object to the constructor
    public function __construct(ResultDecorator $resultDecorator){
        $this->_resultDecorator=$resultDecorator;
    }
    // get result set as array
//    public function getArray(){
//        $resultado=$this->_resultDecorator->resultSet;
//        if (is_resource($resultado)){
//            while($row=mysql_fetch_object($resultado)){
//                $this->_resultObjeto[]=$row;
//            }
//        }
//        return $this->_resultObjeto;
//    }
    
    public function getObjeto(){
        $resultado=$this->_resultDecorator->resultSet;
        if (is_resource($resultado)){
            while($row=mysql_fetch_object($resultado)){
                $this->_resultObjeto[]=$row;
            }
        }
        return $this->_resultObjeto;
    }
}
?>