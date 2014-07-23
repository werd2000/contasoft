<?php
require_once 'App/LibQ/Bd/ManejadorBaseDeDatosInterface.php';
require_once 'App/LibQ/Bd/Sql.php';
require_once 'App/LibQ/Bd/ResultDecorator.php';
require_once 'App/LibQ/Bd/Decoradores/ObjetoResultDecorator.php';
require_once 'App/LibQ/Bd/Decoradores/ArrayResultDecorator.php';

class BaseDeDatos
{
    private $_manejador;
    private $_tipoDatos='Objeto';    //Array, Json, String, Xml
    
    public function __construct (ManejadorBaseDeDatosInterface $manejador)
    {
        $this->_manejador = $manejador;
    }
    
    public function setTipoDatos($tipo)
    {
        $this->_tipoDatos = $tipo;
    }
    
    public function setTipoResultado($tipoResultado)
    {
        $this->_tipoResultado = $tipoResultado;
    }
    
    public function ejecutar (Sql $sql)
    {
        $this->_manejador->getInstance();
        $datos = $this->_manejador->ejecutar($sql); 
        var_dump($datos);
        switch ($this->_tipoDatos){
            case 'Objeto':
                $resultDecorator = new ResultDecorator($datos);
                $result = new ObjetoResultDecorator ($resultDecorator);
                $retorno = $result->getObjeto();
                break;
            case 'Array':
                $resultDecorator=new ResultDecorator($datos);
                $result = new ArrayResultDecorator ($resultDecorator);
                $retorno = $result->getArray();
                break;
            case 'JSON':
                $resultDecorator=new ResultDecorator($datos);
                $result = new JSONResultDecorator ($resultDecorator);
                $retorno = $result->getJson();
                break;
            case 'XML':
                $resultDecorator=new ResultDecorator($datos);
                $result = new XMLResultDecorator ($resultDecorator);
                $retorno = $result->displayXML();
                break;
        }
        return $retorno;
    }
    
    public function fetchRow(Sql $sql)
    {
        $this->_manejador->getInstance();
        $result = $this->_manejador->ejecutar($sql)->fetchRow(); 
//        $resultDecorator = new ResultDecorator($datos->fetchRow());
//        $retorno = $resultDecorator->fetchRow();
        $retorno = self::_configurarDato($result);
        return $retorno;
    }
    
    private function _configurarDato($datos)
    {
        switch ($this->_tipoDatos){
            case 'Objeto':
                var_dump($datos);
                $resultDecorator = new ResultDecorator($datos);
                $result = new ObjetoResultDecorator ($resultDecorator);
                $retorno = $result->getObjeto();
                break;
            case 'Array':
                $resultDecorator=new ResultDecorator($datos);
                $result = new ArrayResultDecorator ($resultDecorator);
                $retorno = $result->getArray();
                break;
            case 'JSON':
                $resultDecorator=new ResultDecorator($datos);
                $result = new JSONResultDecorator ($resultDecorator);
                $retorno = $result->getJson();
                break;
            case 'XML':
                $resultDecorator=new ResultDecorator($datos);
                $result = new XMLResultDecorator ($resultDecorator);
                $retorno = $result->displayXML();
                break;
        }
        return $retorno;
    }
}

