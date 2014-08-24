<?php
require_once 'App/LibQ/ModelBase.php';
class SueldosModelo extends ModelBase
{
    protected static $_cantReg;
        
    
    public function listadoEmpleados($campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo){
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('cronos_personal');
        $sql->addOrder('apellidos, nombres');
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
     * Guarda los datos del formulario de sueldos en la BD
     * @param array $datos
     * @return int 
     */
    public function guardar($datos=array())
    {
        $this->_db->insert('conta_sueldos',$datos);
        return $this->_db->lastInsertId();
    }
    
    public function actualizar($datos=array(),$where='')
    {
        return $this->_db->update('conta_sueldos', $datos, $where);
    }
    
    public function getCantidadRegistros($filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('*');
        $sql->addTable('conta_sueldos as sueldos');
        $sql->addWhere('sueldos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }
    
    public function listadoSueldos($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('sueldos.id,
        		CONCAT_WS(",", empleados.apellidos,empleados.nombres),
        		sueldos.periodo_pago,
        		sueldos.nro_recibo,
        		sueldos.remuneracion_gravada,
        		sueldos.remuneracion_nogravada,
        		sueldos.descuentos,
                        sueldos.total
        ');
        $sql->addTable('
        	conta_sueldos as sueldos LEFT JOIN cronos_personal as empleados ON sueldos.empleado=empleados.id
        ');
        $fin = $inicio + 29;
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('sueldos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro . '-' . date('Y'));
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
            
    public function resumenSueldosProveedor($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('sueldos.id,
        		proveedores.razon_social,
        		SUM(sueldos.importe_gravado),
                        SUM(sueldos.importe_nogravado),
                        SUM(sueldos.iva_inscripto),
                        SUM(sueldos.iva_diferencial),
                        SUM(sueldos.percepcion),
                        SUM(sueldos.total)
        ');
        $sql->addTable('
        	conta_sueldos as sueldos LEFT JOIN conta_proveedores as proveedores ON sueldos.proveedor=proveedores.id
                LEFT JOIN conta_cuentas as cuentas ON sueldos.cuenta=cuentas.id
        ');
        $fin = $inicio + 29;
//        $sql->addLimit($inicio, 30);
//        $sql->addOrder($orden);
        $sql->addWhere('sueldos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $sql = $sql->__toString(). ' GROUP BY proveedores.razon_social';
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    
    /**
     * Busca los datos en la BD y emite un resumen anual
     * @param int $inicio
     * @param string $orden
     * @param string $filtro
     * @return Zend_DB_TABLE 
     */
    public function resumenSueldosAnual($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('YEAR(sueldos.periodo_pago),
        		SUM(sueldos.remuneracion_gravada),
                        SUM(sueldos.remuneracion_nogravada),
                        SUM(sueldos.descuentos),
                        SUM(sueldos.total)
        ');
        $sql->addTable('conta_sueldos as sueldos');
        $sql->addLimit($inicio, 30);
        $sql->addGroupBy($orden);
        $sql->addWhere('sueldos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro . '-' . date('Y'));
        }
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
//        print_r($resultado);
        return $resultado;
    }
    
    public function resumenSueldosMensual($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('MONTH(sueldos.periodo_pago),
        		SUM(sueldos.remuneracion_gravada),
                        SUM(sueldos.remuneracion_nogravada),
                        SUM(sueldos.descuentos),
                        SUM(sueldos.total)
        ');
        $sql->addTable('conta_sueldos as sueldos');
        $sql->addLimit($inicio, 30);
        $sql->addGroupBy($orden);
        $sql->addWhere('sueldos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
//        print_r($resultado);
        return $resultado;
    }

//FROM sueldos.fecha_comprobante ) AS OrderYear, sueldos.comprobante, sueldos.importe_gravado, sueldos.importe_nogravado, sueldos.iva_inscripto, sueldos.iva_diferencial, sueldos.percepcion, SUM( sueldos.total ) 
//FROM conta_sueldos AS sueldos
//LEFT JOIN conta_proveedores AS proveedores ON sueldos.proveedor = proveedores.id
//WHERE sueldos.eliminado =0
//GROUP BY proveedores.razon_social, OrderYear    
    public function resumenSueldosProveedorAnio($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('sueldos.id,
        		proveedores.razon_social,
                        EXTRACT( YEAR FROM sueldos.fecha_comprobante ) AS Anio,
        		SUM(sueldos.importe_gravado),
                        SUM(sueldos.importe_nogravado),
                        SUM(sueldos.iva_inscripto),
                        SUM(sueldos.iva_diferencial),
                        SUM(sueldos.percepcion),
                        SUM(sueldos.total)
        ');
        $sql->addTable('
        	conta_sueldos as sueldos LEFT JOIN conta_proveedores as proveedores ON sueldos.proveedor=proveedores.id
                LEFT JOIN conta_cuentas as cuentas ON sueldos.cuenta=cuentas.id
        ');
        $fin = $inicio + 29;
//        $sql->addLimit($inicio, 30);
//        $sql->addOrder($orden);
        $sql->addWhere('sueldos.eliminado='.$this->_verEliminados);
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
    public function buscarSueldo($where)
    {
        if (!is_string($where)){
            throw new Zend_Exception("La condicion de consulta no es valida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_sueldos.id,
                        conta_sueldos.empleado,
                        conta_sueldos.periodo_pago,
                        conta_sueldos.nro_recibo,
                        conta_sueldos.remuneracion_gravada,
                        conta_sueldos.remuneracion_nogravada,
                        conta_sueldos.descuentos,
                        conta_sueldos.total,
                        conta_sueldos.eliminado
        ');
        $sql->addTable('conta_sueldos');
        $sql->addWhere($where);
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);    
//        var_dump($resultado);
        return $resultado;
    }
    
    public function eliminar ($where = '')
    {
        $n = $this->_db->delete('conta_sueldos', $where);
        return $n;
    }
    
    public function totalSueldosMensual($anio,$mes)
    {
        $sql = new Sql();
        $sql->addFuncion('SELECT');
        $sql->addSelect('SUM(sueldos.total) as gtotal');
        $sql->addTable('conta_sueldos as sueldos');
        $sql->addWhere('sueldos.eliminado='.$this->_verEliminados);
        $sql->addWhere("MONTH(sueldos.periodo_pago) = MONTH('" . $anio . "-" . $mes . "-01" . "')");
        $sql->addWhere("YEAR( sueldos.periodo_pago ) = YEAR('" . $anio . "-" . $mes . "-01" . "')");
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
}
