<?php

require_once 'App/LibQ/ModelBase.php';

class ClientesModelo extends ModelBase
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
        $this->_db->insert('conta_clientes', $datos);
        return $this->_db->lastInsertId();
    }

    public function listadoClientes($inicio, $orden)
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('clientes.id,
        		clientes.razon_social,
        		clientes.domicilio,
        		clientes.condicion_iva,
        		clientes.cuit,
        		clientes.tel,
                        clientes.cel,
                        clientes.email
        ');
        //        $sql->addSelect('conta_profesionales.apellido');
        $sql->addTable('conta_clientes as clientes');
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('clientes.eliminado=' . $this->_verEliminados);
//                echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function getCantidadRegistros()
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_clientes.id');
        $sql->addTable('conta_clientes');
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
        $sql->addSelect('conta_clientes.id,
                        conta_clientes.cuenta,
                        conta_clientes.profesional,
                        conta_clientes.fecha_comprobante,
                        conta_clientes.comprobante,
                        conta_clientes.tipo_comprobante,
                        conta_clientes.nro_comprobante,
                        conta_clientes.importe_gravado,
                        conta_clientes.importe_nogravado,
                        conta_clientes.iva_inscripto,
                        conta_clientes.iva_diferencial,
                        conta_clientes.percepcion,
                        conta_clientes.total
        ');
        $sql->addTable('conta_clientes');
        $sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        //        var_dump($resultado);
        return $resultado;
    }

    public function actualizar($datos = array(), $where = '')
    {
        try {
            $regModif = $this->_db->update('conta_clientes', $datos, $where);
            return $regModif;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function eliminar($where = '')
    {
        $n = $this->_db->delete('conta_clientes', $where);
        return $n;
    }

}
