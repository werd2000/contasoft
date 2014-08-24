<?php
require_once 'App/LibQ/ModelBase.php';
class GastosModelo extends ModelBase
{
    protected static $_cantReg;
        
    
    public function listadoProveedores($campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo){
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_proveedores');
        $sql->addOrder('razon_social');
        $sql->addWhere('eliminado='.$this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->_db->fetchAll($sql);
        return $result;
    }
    
    public function listadoCuentas($campos=Array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo){
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_cuentas');
        $sql->addOrder('cuenta');
        $sql->addWhere('eliminado='.$this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    
    /**
     * Guarda los datos del formulario de gastos en la BD
     * @param array $datos
     * @return int 
     */
    public function guardar($datos=array())
    {
        $this->_db->insert('conta_gastos',$datos);
        return $this->_db->lastInsertId();
    }
    
    public function actualizar($datos=array(),$where='')
    {
        return $this->_db->update('conta_gastos', $datos, $where);
    }
    
    public function getCantidadRegistros($filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('*');
        $sql->addTable('conta_gastos');
        $sql->addWhere('eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }
    
    public function listadoGastos($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('gastos.id,
        		cuentas.cuenta,
        		proveedores.razon_social,
        		gastos.fecha_comprobante,
        		gastos.comprobante,
        		gastos.tipo_comprobante,
        		gastos.nro_comprobante,
        		gastos.importe_gravado,
                        gastos.importe_nogravado,
                        gastos.iva_inscripto,
                        gastos.iva_diferencial,
                        gastos.percepcion,
                        gastos.total
        ');
        $sql->addTable('
        	conta_gastos as gastos LEFT JOIN conta_proveedores as proveedores ON gastos.proveedor=proveedores.id
                LEFT JOIN conta_cuentas as cuentas ON gastos.cuenta=cuentas.id
        ');
        $fin = $inicio + 30;
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('gastos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
            
    public function resumenGastosProveedor($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('gastos.id,
                        gastos.proveedor,
        		proveedores.razon_social AS ayn,
        		SUM(gastos.importe_gravado),
                        SUM(gastos.importe_nogravado),
                        SUM(gastos.iva_inscripto),
                        SUM(gastos.iva_diferencial),
                        SUM(gastos.percepcion),
                        SUM(gastos.total)
        ');
        $sql->addTable('
        	conta_gastos as gastos LEFT JOIN conta_proveedores as proveedores ON gastos.proveedor=proveedores.id
                LEFT JOIN conta_cuentas as cuentas ON gastos.cuenta=cuentas.id
        ');
        $fin = $inicio + 29;
//        $sql->addLimit($inicio, 30);
//        $sql->addOrder($orden);
        $sql->addWhere('gastos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $sql = $sql->__toString(). ' GROUP BY proveedores.razon_social';
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    
    public function resumenGastosAnual($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('YEAR(gastos.fecha_comprobante),
        		SUM(gastos.importe_gravado),
                        SUM(gastos.importe_nogravado),
                        SUM(gastos.iva_inscripto),
                        SUM(gastos.iva_diferencial),
                        SUM(gastos.percepcion),
                        SUM(gastos.total)
        ');
//        SELECT YEAR( gastos.fecha_comprobante ) , SUM( gastos.importe_gravado ) , SUM( gastos.importe_nogravado ) , SUM( gastos.iva_inscripto ) , SUM( gastos.iva_diferencial ) , SUM( gastos.percepcion ) , SUM( gastos.total ) 
//FROM conta_gastos AS gastos
//WHERE gastos.eliminado = 
//FALSE GROUP BY YEAR( gastos.fecha_comprobante ) 
//LIMIT 0 , 30
        $sql->addTable('conta_gastos as gastos');
        $sql->addLimit($inicio, 30);
        $sql->addGroupBy($orden);
        $sql->addWhere('gastos.eliminado='.$this->_verEliminados);
//        if (! $filtro == ''){
//            $sql->addWhere($filtro . '-' . date('Y'));
//        }
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
//        print_r($resultado);
        return $resultado;
    }
    
    /**
     *
     * @param int $inicio
     * @param string $orden
     * @param string $filtro
     * @return Zend_Table_Row 
     */
    public function resumenGastosMensual($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('MONTH(gastos.fecha_comprobante),
        		SUM(gastos.importe_gravado),
                        SUM(gastos.importe_nogravado),
                        SUM(gastos.iva_inscripto),
                        SUM(gastos.iva_diferencial),
                        SUM(gastos.percepcion),
                        SUM(gastos.total)
        ');
//        SELECT YEAR( gastos.fecha_comprobante ) , SUM( gastos.importe_gravado ) , SUM( gastos.importe_nogravado ) , SUM( gastos.iva_inscripto ) , SUM( gastos.iva_diferencial ) , SUM( gastos.percepcion ) , SUM( gastos.total ) 
//FROM conta_gastos AS gastos
//WHERE gastos.eliminado = 
//FALSE GROUP BY YEAR( gastos.fecha_comprobante ) 
//LIMIT 0 , 30
        $sql->addTable('conta_gastos as gastos');
        $sql->addLimit($inicio, 30);
        $sql->addGroupBy($orden);
        $sql->addWhere('gastos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
//        print_r($resultado);
        return $resultado;
    }
    
    public function totalGastosMensual($anio,$mes)
    {
        $sql = new Sql();
        $sql->addFuncion('SELECT');
        $sql->addSelect('SUM(gastos.total) as gtotal');
        $sql->addTable('conta_gastos as gastos');
        $sql->addWhere('gastos.eliminado='.$this->_verEliminados);
        $sql->addWhere("MONTH(gastos.fecha_comprobante) = MONTH('" . $anio . "-" . $mes . "-01" . "')");
        $sql->addWhere("YEAR( gastos.fecha_comprobante ) = YEAR('" . $anio . "-" . $mes . "-01" . "')");
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

//FROM gastos.fecha_comprobante ) AS OrderYear, gastos.comprobante, gastos.importe_gravado, gastos.importe_nogravado, gastos.iva_inscripto, gastos.iva_diferencial, gastos.percepcion, SUM( gastos.total ) 
//FROM conta_gastos AS gastos
//LEFT JOIN conta_proveedores AS proveedores ON gastos.proveedor = proveedores.id
//WHERE gastos.eliminado =0
//GROUP BY proveedores.razon_social, OrderYear    
    public function resumenGastosProveedorAnio($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('gastos.id,
        		proveedores.razon_social,
                        EXTRACT( YEAR FROM gastos.fecha_comprobante ) AS Anio,
        		SUM(gastos.importe_gravado),
                        SUM(gastos.importe_nogravado),
                        SUM(gastos.iva_inscripto),
                        SUM(gastos.iva_diferencial),
                        SUM(gastos.percepcion),
                        SUM(gastos.total)
        ');
        $sql->addTable('
        	conta_gastos as gastos LEFT JOIN conta_proveedores as proveedores ON gastos.proveedor=proveedores.id
                LEFT JOIN conta_cuentas as cuentas ON gastos.cuenta=cuentas.id
        ');
        $fin = $inicio + 29;
//        $sql->addLimit($inicio, 30);
//        $sql->addOrder($orden);
        $sql->addWhere('gastos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $sql = $sql->__toString(). ' GROUP BY proveedores.razon_social, Anio';
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    /**
     * Busca los datos de una factura determinada por la expresion $where
     * @param $where
     * @return unknown_type
     */
    public function buscarGasto($where)
    {
        if (!is_string($where)){
            throw new Zend_Exception("La condicion de consulta no es valida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_gastos.id,
                        conta_gastos.cuenta,
                        conta_gastos.proveedor,
                        conta_gastos.fecha_comprobante,
                        conta_gastos.comprobante,
                        conta_gastos.tipo_comprobante,
                        conta_gastos.nro_comprobante,
                        conta_gastos.importe_gravado,
                        conta_gastos.importe_nogravado,
                        conta_gastos.iva_inscripto,
                        conta_gastos.iva_diferencial,
                        conta_gastos.percepcion,
                        conta_gastos.total,
                        conta_gastos.eliminado
        ');
        $sql->addTable('conta_gastos');
        $sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);    
//        var_dump($resultado);
        return $resultado;
    }
    
    public function eliminar ($where = '')
    {
        $n = $this->_db->delete('conta_gastos', $where);
        return $n;
    }
}
