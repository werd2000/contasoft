<?php

interface ManejadorBaseDeDatosInterface {

    /**
     * Conecta con la BD
     * @return void
     */
    public static function conectar();

    /**
     * Desconecta de la BD
     * @return void
     */
    public function desconectar();

    /**
     * Ejecuta una consulta para insert y update
     * @param SQL $sql
     * @return unknown_type
     */
    public function ejecutar(SQL $sql);

    /**
     * Obtiene multiples registros
     * @param string $sql Consulta SQL
     * @return array Retorna un arreglo con o sin resultados
     */
    function fetchAll(SQL $sql);

    /**
     * Obtiene un solo registro
     * @param string $sql Consulta SQL
     * @return array Retorna un arreglo con o sin resultados
     */
    function fetchRow(SQL $sql);
    
    /**
     * Ejecuta una consulta Select
     * @param $table, $where, $fields, $order, $limit, $offset
     * @return unknown_type
     */
    function select($table, $where, $fields, $order, $limit, $offset);

    /**
     * Ejecuta una consulta Insert
     * @param $table, $data
     * @return int
     */
    function insert($table, array $data);

    /**
     * Ejecuta una consulta Update
     * @param $table, $data, $where
     * @return int
     */
    function update($table, array $data, $where);

    /**
     * Ejecuta una consulta Delete
     * @param $table, $where
     * @return int
     */
    function delete($table, $where);

    /**
     * Obtiene el útlimo id
     * @return int
     */
    function getInsertId();

    /**
     * Obtiene la cantidad de registros
     * @return int
     */
    function countRows();

    /**
     * Obtiene la cantidad de filas afectadas
     * por la consulta Update y/o Delete
     * @return int
     */
    function getAffectedRows();
}