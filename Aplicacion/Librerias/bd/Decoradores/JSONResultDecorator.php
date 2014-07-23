<?php
require_once ('App/LibQ/Bd/ResultDecorator.php');
class JSONResultDecorator extends ResultDecorator
{
    private $_resultDecorator;
    private $_resultJSON = array();
    // pass 'ResultDecorator' object to the constructor
    public function __construct (ResultDecorator $resultDecorator)
    {
        $this->_resultDecorator = $resultDecorator;
    }
        // get result set as array
    public function getJson ()
    {
        $result = $this->_resultDecorator->resultSet;
        if (is_resource($result)){
            $i = 0;
            while ($row = mysql_fetch_assoc($result)) {
                $responce->rows[$i]['id'] = $row['id'];
                $count = $this->_resultDecorator->getCantidadCampos();
                for ($j = 0; $j < $count; $j ++) {
                        echo $row[$i][$j];
                }
                $i++;
            }
        }
        return Zend_Json::encode($responce); 
    }
}
?>