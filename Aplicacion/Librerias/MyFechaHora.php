<?php
//require_once LibQ . 'Zend_Date';

/**
 * Clase para manejar las fechas y horas
 * @see Zend_Date
 * @author Walter Ruiz Diaz
 */
class MyFechaHora
{
    function __construct()
    {
       
    }
    
    /**
     * Metodo para obtener la fecha en formato argentino
     * @param String $fecha
     * @return Zend_Date
     */
    public static function getFechaBd($fecha)
    {
        if (stripos($fecha,'/')>0){
            $myFecha = implode('/', array_reverse(explode('/', $fecha)));
        }else{
            $myFecha = implode('-', array_reverse(explode('-', $fecha)));
        }
        return $myFecha;
    }
    
    public static function getFechaAr($fecha)
    {
        if (stripos($fecha,'/')>0){
//        setlocale(LC_TIME , 'es_ES');
//        $myFecha = date('d-m-Y',$fecha);
            $myFecha = implode('/', array_reverse(explode('/', $fecha)));
        }else{
            $myFecha = implode('-', array_reverse(explode('-', $fecha)));
        }
        return $myFecha;
    }
}

?>
