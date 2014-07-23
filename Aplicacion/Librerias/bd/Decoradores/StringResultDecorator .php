<?php
require_once ('App/LibQ/bd/ResultDecorator.php');
class StringResultDecorator extends ResultDecorator
{
    private $resultDecorator;
    // pass 'ResultDecorator' object to the constructor
    public function __construct(ResultDecorator $resultDecorator){
        $this->resultDecorator=$resultDecorator;
    }
    // display result set as formatted string
    public function displayString(){
        $result=$this->resultDecorator->resultSet;
        $str='';
        while($row=mysql_fetch_assoc($result)){
            $str.=$row['id'].' '.$row['name'].' '.$row
                ['email'].'<br />';
        }
        return $str;
    }
}
?>