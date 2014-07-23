<?php
class Result
{

    private $_mysql;
    private $_result;
    
    public function __construct(&$mysql,$result){
        $this->_mysql=&$mysql;
        $this->_result=$result;
    }
    // fetch row
    public function fetchRow(){
        return mysql_fetch_assoc($this->_result);
    }
    // count rows
    public function countRows(){
        if(!$rows=mysql_num_rows($this->_result)){
            throw new Exception('Error counting rows');
        }
        return $rows;
    }
    // count affected rows
    public function countAffectedRows(){
        if(!$rows=mysql_affected_rows($this->_mysql->conId)){
            throw new Exception('Error counting affected rows');
        }
        return $rows;
    }
    // get insert ID
    public function getInsertID(){
        if(!$id=mysql_insert_id($this->_mysql->conId)){
            throw new Exception('Error getting ID');
        }
        return $id;
    }
    // seek rows
    public function seekRow($row=0)
    {
        if(!int($row)||$row<0){
            throw new Exception('Invalid result set offset');
        }
        if(!mysql_data_seek($this->_result,$row)){
            throw new Exception('Error seeking data');
        }
    }
    // return result set
    public function getResult(){
        return $this->_result;
    }
    
    public function getCantidadCampos()
    {
        return mysql_num_fields($this->_result);    
    }

}
?>