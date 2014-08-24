<?php
require_once 'App/LibQ/ModelBase.php';

/**
 *  Clase para interactuar con la BD en el modulo Profesionales
 *  @author Walter Ruiz Diaz
 *  @see LibQ_ModelBase
 *  @category Modelo
 *  @package Profesionales
 */
class ProfesionalesModelo extends ModelBase
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Busca un profesionales en la tabla profesionales
     * @param array $where la condicion de la consulta = el usuario a buscar
     * @return Zend_Db_Table_Row_Abstract|null 
     */
    public function buscarProfesional($where)
    {
        if (!is_string($where)){
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_profesionales.id,
                        conta_profesionales.nombre,
        		conta_profesionales.apellido,
        		conta_profesionales.profesion,
        		conta_profesionales.nro_doc,
        		conta_profesionales.condicion_iva,
        		conta_profesionales.cuit,
        		conta_profesionales.tel,
        		conta_profesionales.cel,
        		conta_profesionales.email
        ');
        $sql->addTable('conta_profesionales');
        $sql->addWhere($where);
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);    
        return $resultado;
    }
    
    /**
     * Lista los profesionales de la tabla profesionales
     * @param int $inicio. Desde donde se muestran los registros
     * @param string $orden. Los campos por los que se ordenan los datos
     * @param array $campos. Los campos a obtener de la tabla
     * @return Zend_Db_Table_Rowset_Abstract 
     */
    public function listaProfesionales($inicio, $orden, $campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo){
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_profesionales');
        $sql->addWhere('eliminado='.$this->_verEliminados);
        $sql->addOrder($orden);
        $sql->addLimit($inicio, 30);
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $result = $this->_db->fetchAll($sql);
        return $result;
    }
    
    public function getCantidadRegistros()
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_profesionales.id');
        $sql->addTable('conta_profesionales');
//        $sql->addWhere('eliminado='.$this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        $cantidad = count($resultado);
        return $cantidad;
    }
    
    /**
     * Actualiza los datos de la tabla Alumnos
     * @param Array $datos son los datos a actualizar
     * @param string $where es la condición de la actualización
     */
    public function actualizar($datos=array(), $where='')
    {
        try {
            $regModif = $this->_db->update('conta_profesionales', $datos, $where);
            return $regModif;
        } catch (Exception $e){
            return $e->getMessage();
        }
    }
    
    /**
     * Guarda en la tabla profesionales los datos del usuario
     * @param Array $datos corresponde a los datos a guardar
     * @return lastInsertId
     * @access Public 
     */
    public function guardar($datos=array())
    {
        try {
            $this->_db->insert('conta_profesionales', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
