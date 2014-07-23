<?php
require_once ('App/LibQ/Bd/ResultDecorator.php');

class ArrayResultDecorator extends ResultDecorator
{
    private $resultDecorator;
    private $resultArray=array();
    // pass 'ResultDecorator' object to the constructor
    public function __construct(ResultDecorator $resultDecorator){
        $this->resultDecorator=$resultDecorator;
    }
    // get result set as array
    public function getArray(){
        $result=$this->resultDecorator->resultSet;
        while($row = mysql_fetch_assoc ($result)){
            $this->resultArray[]=$row;
        }
        return $this->resultArray;
    }
}
?>