<?php

require_once 'App/LibQ/ModelBase.php';

class ProveedoresModelo extends ModelBase
{

    public function listadoProfesionales($campos = array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_profesionales');
        $sql->addOrder('apellido, nombre');
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
        $this->_db->insert('conta_proveedores', $datos);
        return $this->_db->lastInsertId();
    }

    public function listadoProveedores($inicio, $orden)
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('proveedores.id,
        		proveedores.razon_social,
        		proveedores.domicilio,
        		proveedores.condicion_iva,
        		proveedores.cuit,
        		proveedores.tel,
                        proveedores.cel,
                        proveedores.email
        ');
        //        $sql->addSelect('conta_profesionales.apellido');
        $sql->addTable('conta_proveedores as proveedores');
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('proveedores.eliminado=' . $this->_verEliminados);
//                echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function getCantidadRegistros()
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_proveedores.id');
        $sql->addTable('conta_proveedores');
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
        $sql->addSelect('conta_proveedores.id,
                        conta_proveedores.cuenta,
                        conta_proveedores.profesional,
                        conta_proveedores.fecha_comprobante,
                        conta_proveedores.comprobante,
                        conta_proveedores.tipo_comprobante,
                        conta_proveedores.nro_comprobante,
                        conta_proveedores.importe_gravado,
                        conta_proveedores.importe_nogravado,
                        conta_proveedores.iva_inscripto,
                        conta_proveedores.iva_diferencial,
                        conta_proveedores.percepcion,
                        conta_proveedores.total
        ');
        $sql->addTable('conta_proveedores');
        $sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        //        var_dump($resultado);
        return $resultado;
    }

    public function actualizar($datos = array(), $where = '')
    {
        try {
            $regModif = $this->_db->update('conta_proveedores', $datos, $where);
            return $regModif;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function eliminar($where = '')
    {
        $n = $this->_db->delete('conta_proveedores', $where);
        return $n;
    }

}
