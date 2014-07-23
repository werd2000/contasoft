<?php
require_once ('App/LibQ/bd/ResultDecorator.php');
class XMLResultDecorator extends ResultDecorator
{
    private $resultDecorator;
    // pass 'ResultDecorator' object to the constructor
    public function __construct(ResultDecorator $resultDecorator){
        $this->resultDecorator=$resultDecorator;
    }
    // display result set as formatted string
    public function displayXML(){
        $result=$this->resultDecorator->resultSet;
        $xml='<?xml version="1.0" encoding="iso-8859-1"?>';
        $xml.='<users>'."n";
        while($row=mysql_fetch_assoc($result)){
            $xml.='<user><id>'.$row['id'].'</id><name>'.$row
                ['name'].'</name><email>'.$row['email'].'</email></user>'."n";
        }
        $xml.='</users>';
        return $xml;
    }
}
?>