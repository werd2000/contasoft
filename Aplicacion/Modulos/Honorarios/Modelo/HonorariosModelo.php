<?php

require_once 'App/LibQ/ModelBase.php';

class HonorariosModelo extends ModelBase
{

    public function listadoProfesionales($campos = array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
//        $sql->addTable('conta_profesionales');
        $sql->addTable('cronos_personal');
        $sql->addOrder('apellidos, nombres');
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->_db->fetchAll($sql);
        return $result;
    }

    public function listadoCuentas($campos = Array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_cuentas');
        $sql->addOrder('cuenta');
        $sql->addWhere('grupo_cuenta = 3');
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->_db->fetchAll($sql);
        return $result;
    }

    public function guardar($datos = array())
    {
        $this->_db->insert('conta_honorarios', $datos);
        return $this->_db->lastInsertId();
    }

    public function listadoHonorarios($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('honorarios.id,
        		cuentas.cuenta,
        		CONCAT_WS(", ", profesionales.apellido,profesionales.nombre),
        		honorarios.fecha_comprobante,
        		honorarios.comprobante,
        		honorarios.tipo_comprobante,
        		honorarios.nro_comprobante,
        		honorarios.importe_gravado,
                        honorarios.importe_nogravado,
                        honorarios.iva_inscripto,
                        honorarios.iva_diferencial,
                        honorarios.percepcion,
                        honorarios.total
        ');
        //        $sql->addSelect('conta_profesionales.apellido');
        $sql->addTable('
        	conta_honorarios as honorarios LEFT JOIN conta_profesionales as profesionales ON honorarios.profesional=profesionales.id
                LEFT JOIN conta_cuentas as cuentas ON honorarios.cuenta=cuentas.id
        ');
        $fin = $inicio + 29;
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('honorarios.eliminado=' . $this->_verEliminados);
        if ($filtro != ''){
            $sql->addWhere($filtro);
        }
//                echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function getCantidadRegistros()
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_honorarios.id');
        $sql->addTable('conta_honorarios');
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        $cantidad = count($resultado);
        return $cantidad;
    }

    public function buscarHonorario($where)
    {
        if (!is_string($where)) {
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_honorarios.id,
                        conta_honorarios.cuenta,
                        conta_honorarios.profesional,
                        conta_honorarios.fecha_comprobante,
                        conta_honorarios.comprobante,
                        conta_honorarios.tipo_comprobante,
                        conta_honorarios.nro_comprobante,
                        conta_honorarios.importe_gravado,
                        conta_honorarios.importe_nogravado,
                        conta_honorarios.iva_inscripto,
                        conta_honorarios.iva_diferencial,
                        conta_honorarios.percepcion,
                        conta_honorarios.total
        ');
        $sql->addTable('conta_honorarios');
        $sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        //        var_dump($resultado);
        return $resultado;
    }

    public function actualizar($datos = array(), $where = '')
    {
        try {
            $regModif = $this->_db->update('conta_honorarios', $datos, $where);
            return $regModif;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function eliminar($where = '')
    {
        $n = $this->_db->delete('conta_honorarios', $where);
        return $n;
    }
    
    public function totalHonorariosMensual($anio,$mes)
    {
        $sql = new Sql();
        $sql->addFuncion('SELECT');
        $sql->addSelect('SUM(honorarios.total) as gtotal');
        $sql->addTable('conta_honorarios as honorarios');
        $sql->addWhere('honorarios.eliminado='.$this->_verEliminados);
        $sql->addWhere("MONTH(honorarios.fecha_comprobante) = MONTH('" . $anio . "-" . $mes . "-01" . "')");
        $sql->addWhere("YEAR( honorarios.fecha_comprobante ) = YEAR('" . $anio . "-" . $mes . "-01" . "')");
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
    
    public function resumenHonorariosAnual($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('YEAR(honorarios.fecha_comprobante),
        		SUM(honorarios.importe_gravado),
                        SUM(honorarios.importe_nogravado),
                        SUM(honorarios.iva_inscripto),
                        SUM(honorarios.iva_diferencial),
                        SUM(honorarios.percepcion),
                        SUM(honorarios.total)
        ');
        $sql->addTable('conta_honorarios as honorarios');
        $sql->addLimit($inicio, 30);
        $sql->addGroupBy($orden);
        $sql->addWhere('honorarios.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro . '-' . date('Y'));
        }
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
    public function resumenHonorariosMensual($inicio, $orden, $filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('MONTH(honorarios.fecha_comprobante),
        		SUM(honorarios.importe_gravado),
                        SUM(honorarios.importe_nogravado),
                        SUM(honorarios.iva_inscripto),
                        SUM(honorarios.iva_diferencial),
                        SUM(honorarios.percepcion),
                        SUM(honorarios.total)
        ');
        $sql->addTable('conta_honorarios as honorarios');
        $sql->addLimit($inicio, 30);
        $sql->addGroupBy($orden);
        $sql->addWhere('honorarios.eliminado='.$this->_verEliminados);
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
//        print_r($resultado);
        return $resultado;
    }
    
    public function resumenHonorariosProfesional($inicio, $orden, $filtro)
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('profesionales.id,
                        CONCAT_WS(", ", profesionales.apellido,profesionales.nombre) AS ayn,
                        SUM(honorarios.total)
                        ');
            
//        $sql->addSelect('profesionales.id,
//                        CONCAT_WS(", ", profesionales.apellido,profesionales.nombre),
//        		honorarios.importe_gravado,
//                        honorarios.importe_nogravado,
//                        honorarios.iva_inscripto,
//                        honorarios.iva_diferencial,
//                        honorarios.percepcion,
//                        honorarios.total
//            ');
        $sql->addTable('conta_honorarios AS honorarios, conta_profesionales as profesionales');
//        $fin = $inicio + 29;
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('honorarios.eliminado=' . $this->_verEliminados);
        $sql->addWhere('honorarios.profesional = profesionales.id');
        if (! $filtro == ''){
            $sql->addWhere($filtro);
        }
        $sql->addGroupBy('honorarios.profesional');
//                echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
//        print_r($resultado);
        return $resultado;
    }

}
