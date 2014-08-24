<?php
require_once 'App/LibQ/ModelBase.php';
class IngresosModelo extends ModelBase
{
    protected static $_cantReg;
        
    
    public function listadoProveedores($campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo){
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_clientes');
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
        $sql->addWhere('grupo_cuenta=9');
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    
    /**
     * Guarda los datos del formulario de ingresos en la BD
     * @param array $datos
     * @return int 
     */
    public function guardar($datos=array())
    {
        $this->_db->insert('conta_ingresos',$datos);
        return $this->_db->lastInsertId();
    }
    
    public function actualizar($datos=array(),$where='')
    {
        return $this->_db->update('conta_ingresos', $datos, $where);
    }
    
    public function getCantidadRegistros($filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('*');
        $sql->addTable('conta_ingresos');
        $sql->addWhere('eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }
    
    public function listadoIngresos($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('ingresos.id,
        		cuentas.cuenta,
        		clientes.razon_social,
        		ingresos.fecha_comprobante,
        		ingresos.comprobante,
        		ingresos.tipo_comprobante,
        		ingresos.nro_comprobante,
                        ingresos.total
        ');
        $sql->addTable('
        	conta_ingresos as ingresos LEFT JOIN conta_clientes as clientes ON ingresos.cliente=clientes.id
                LEFT JOIN conta_cuentas as cuentas ON ingresos.cuenta=cuentas.id
        ');
        $fin = $inicio + 30;
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('ingresos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro . '-' . date('Y'));
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
            
    public function resumenIngresosProveedor($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('ingresos.id,
        		clientes.razon_social,
        		SUM(ingresos.importe_gravado),
                        SUM(ingresos.importe_nogravado),
                        SUM(ingresos.iva_inscripto),
                        SUM(ingresos.iva_diferencial),
                        SUM(ingresos.percepcion),
                        SUM(ingresos.total)
        ');
        $sql->addTable('
        	conta_ingresos as ingresos LEFT JOIN conta_clientes as clientes ON ingresos.cliente=clientes.id
                LEFT JOIN conta_cuentas as cuentas ON ingresos.cuenta=cuentas.id
        ');
        $fin = $inicio + 29;
//        $sql->addLimit($inicio, 30);
//        $sql->addOrder($orden);
        $sql->addWhere('ingresos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $sql = $sql->__toString(). ' GROUP BY clientes.razon_social';
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    
    public function resumenIngresosMensual($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('ingresos.id,
        		cuentas.cuenta,
        		clientes.razon_social,
                        CONCAT_WS("-", MONTH(ingresos.fecha_comprobante),YEAR(ingresos.fecha_comprobante)),
        		ingresos.comprobante,
        		ingresos.tipo_comprobante,
        		ingresos.nro_comprobante,
        		ingresos.importe_gravado,
                        ingresos.importe_nogravado,
                        ingresos.iva_inscripto,
                        ingresos.iva_diferencial,
                        ingresos.percepcion,
                        ingresos.total
        ');
        $sql->addTable('
        	conta_ingresos as ingresos LEFT JOIN conta_clientes as clientes ON ingresos.cliente=clientes.id
                LEFT JOIN conta_cuentas as cuentas ON ingresos.cuenta=cuentas.id
        ');
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('ingresos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro . '-' . date('Y'));
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

//FROM ingresos.fecha_comprobante ) AS OrderYear, ingresos.comprobante, ingresos.importe_gravado, ingresos.importe_nogravado, ingresos.iva_inscripto, ingresos.iva_diferencial, ingresos.percepcion, SUM( ingresos.total ) 
//FROM conta_ingresos AS ingresos
//LEFT JOIN conta_clientes AS clientes ON ingresos.cliente = clientes.id
//WHERE ingresos.eliminado =0
//GROUP BY clientes.razon_social, OrderYear    
    public function resumenIngresosProveedorAnio($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('ingresos.id,
        		clientes.razon_social,
                        EXTRACT( YEAR FROM ingresos.fecha_comprobante ) AS Anio,
        		SUM(ingresos.importe_gravado),
                        SUM(ingresos.importe_nogravado),
                        SUM(ingresos.iva_inscripto),
                        SUM(ingresos.iva_diferencial),
                        SUM(ingresos.percepcion),
                        SUM(ingresos.total)
        ');
        $sql->addTable('
        	conta_ingresos as ingresos LEFT JOIN conta_clientes as clientes ON ingresos.cliente=clientes.id
                LEFT JOIN conta_cuentas as cuentas ON ingresos.cuenta=cuentas.id
        ');
        $fin = $inicio + 29;
//        $sql->addLimit($inicio, 30);
//        $sql->addOrder($orden);
        $sql->addWhere('ingresos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $sql = $sql->__toString(). ' GROUP BY clientes.razon_social, Anio';
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    /**
     * Busca los datos de una factura determinada por la expresion $where
     * @param $where
     * @return unknown_type
     */
    public function buscarIngreso($where)
    {
        if (!is_string($where)){
            throw new Zend_Exception("La condicion de consulta no es valida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_ingresos.id,
                        conta_ingresos.cuenta,
                        conta_ingresos.cliente,
                        conta_ingresos.fecha_comprobante,
                        conta_ingresos.comprobante,
                        conta_ingresos.tipo_comprobante,
                        conta_ingresos.nro_comprobante,
                        conta_ingresos.condicion_venta,
                        conta_ingresos.total,
                        conta_ingresos.fecha_cobro,
                        conta_ingresos.recibo_nro,
                        conta_ingresos.eliminado
        ');
        $sql->addTable('conta_ingresos');
        $sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);    
//        var_dump($resultado);
        return $resultado;
    }
    
    public function eliminar ($where = '')
    {
        $n = $this->_db->delete('conta_ingresos', $where);
        return $n;
    }
    
    public function totalIngresosMensual($anio,$mes)
    {
        $sql = new Sql();
        $sql->addFuncion('SELECT');
        $sql->addSelect('SUM(ingresos.total) as gtotal');
        $sql->addTable('conta_ingresos as ingresos');
        $sql->addWhere('ingresos.eliminado='.$this->_verEliminados);
        $sql->addWhere("MONTH(ingresos.fecha_comprobante) = MONTH('" . $anio . "-" . $mes . "-01" . "')");
        $sql->addWhere("YEAR( ingresos.fecha_comprobante ) = YEAR('" . $anio . "-" . $mes . "-01" . "')");
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
}
