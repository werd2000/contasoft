<?php
require_once 'App/LibQ/ModelBase.php';

/**
 *  Clase para interactuar con la BD en el modulo Usuarios
 *  @author Walter Ruiz Diaz
 *  @see LibQ_ModelBase
 *  @category Modelo
 *  @package Usuarios
 */
class UsuariosModelo extends ModelBase
{
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Busca un usuarios en la tabla usuarios
     * @param array $where la condicion de la consulta = el usuario a buscar
     * @return Zend_Db_Table_Row_Abstract|null 
     */
    public function buscarUsuario($where)
    {
        if (!is_string($where)){
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_usuarios.id,
                        conta_usuarios.nombre,
        		conta_usuarios.userName,
        		conta_usuarios.password,
        		conta_usuarios.categoria,
        		conta_usuarios.bloqueado,
        		conta_usuarios.enviarMail,
        		conta_usuarios.fechaRegistro,
        		conta_usuarios.ultimaVisita,
        		conta_usuarios.activo,
                        conta_usuarios.email,
                        conta_usuarios.ultima_ip
        ');
        $sql->addTable('conta_usuarios');
        $sql->addWhere($where);
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);    
        return $resultado;
    }
    
    /**
     * Lista los usuarios de la tabla usuarios
     * @param int $inicio. Desde donde se muestran los registros
     * @param string $orden. Los campos por los que se ordenan los datos
     * @param array $campos. Los campos a obtener de la tabla
     * @return Zend_Db_Table_Rowset_Abstract 
     */
    public function listaUsuarios($inicio, $orden, $campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo){
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_usuarios');
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
        $sql->addSelect('conta_usuarios.id');
        $sql->addTable('conta_usuarios');
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
            $regModif = $this->_db->update('conta_usuarios', $datos, $where);
            return $regModif;
        } catch (Exception $e){
            echo $e->getMessage();
        }
    }
    
    /**
     * Guarda en la tabla usuarios los datos del usuario
     * @param Array $datos corresponde a los datos a guardar
     * @return lastInsertId
     * @access Public 
     */
    public function guardar($datos=array())
    {
        try {
            $this->_db->insert('conta_usuarios', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
