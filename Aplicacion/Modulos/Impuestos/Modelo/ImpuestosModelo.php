<?php
require_once 'App/LibQ/ModelBase.php';
class ImpuestosModelo extends ModelBase
{
    protected static $_cantReg;
        
    
//    public function listadoImpuestos()
//    {
//        $sql = new Sql();
//        $sql->addFuncion('Select');
//        $sql->addTable('conta_impuestos');
//        $sql->addOrder('immpuesto');
//        $sql->addWhere('eliminado='.$this->_verEliminados);
//        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
//        echo ($sql);
//        $result = $this->_db->fetchAll($sql);
//        return $result;
//    }
    
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
     * Guarda los datos del formulario de impuestos en la BD
     * @param array $datos
     * @return int 
     */
    public function guardar($datos=array())
    {
        $this->_db->insert('conta_impuestos',$datos);
        return $this->_db->lastInsertId();
    }
    
    public function actualizar($datos=array(),$where='')
    {
        return $this->_db->update('conta_impuestos', $datos, $where);
    }
    
    public function getCantidadRegistros($filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('*');
        $sql->addTable('conta_impuestos');
        $sql->addWhere('eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }
    
    public function listadoImpuestos($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('impuestos.id,
        		impuestos.impuesto,
        		impuestos.caracter,
        		impuestos.tipo_vencimiento,
        		impuestos.observaciones
        ');
        $sql->addTable('conta_impuestos as impuestos');
//        $fin = $inicio + 30;
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('impuestos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
                
    public function resumenImpuestosMensual($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('impuestos.id,
        		cuentas.cuenta,
        		proveedores.razon_social,
                        CONCAT_WS("-", MONTH(impuestos.fecha_comprobante),YEAR(impuestos.fecha_comprobante)),
        		impuestos.comprobante,
        		impuestos.tipo_comprobante,
        		impuestos.nro_comprobante,
        		impuestos.importe_gravado,
                        impuestos.importe_nogravado,
                        impuestos.iva_inscripto,
                        impuestos.iva_diferencial,
                        impuestos.percepcion,
                        impuestos.total
        ');
        $sql->addTable('
        	conta_impuestos as impuestos LEFT JOIN conta_proveedores as proveedores ON impuestos.proveedor=proveedores.id
                LEFT JOIN conta_cuentas as cuentas ON impuestos.cuenta=cuentas.id
        ');
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('impuestos.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro . '-' . date('Y'));
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    
    public function totalImpuestosMensual($anio,$mes)
    {
        $sql = new Sql();
        $sql->addFuncion('SELECT');
        $sql->addSelect('SUM(impuestos.total) as gtotal');
        $sql->addTable('conta_impuestos as impuestos');
        $sql->addWhere('impuestos.eliminado='.$this->_verEliminados);
        $sql->addWhere("MONTH(impuestos.fecha_comprobante) = MONTH('" . $anio . "-" . $mes . "-01" . "')");
        $sql->addWhere("YEAR( impuestos.fecha_comprobante ) = YEAR('" . $anio . "-" . $mes . "-01" . "')");
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    /**
     * Busca los datos de una factura determinada por la expresion $where
     * @param $where
     * @return unknown_type
     */
    public function buscarImpuesto($where)
    {
        if (!is_string($where)){
            throw new Zend_Exception("La condicion de consulta no es valida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('id,
                        impuesto,
                        caracter,
                        tipo_vencimiento,
                        observaciones,
                        eliminado
        ');
        $sql->addTable('conta_impuestos');
        $sql->addWhere($where);
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);    
//        var_dump($resultado);
        return $resultado;
    }
    
    public function eliminar ($where = '')
    {
        $n = $this->_db->delete('conta_impuestos', $where);
        return $n;
    }
}
