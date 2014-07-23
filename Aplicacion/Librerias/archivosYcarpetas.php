<?php

/**
 * Clase usada para administrar archivos y carpetas
 * @author Walter Ruiz Diaz
 * @category archivosYcarpetas
 * @package LibQ
 */
class archivosYcarpetas
{

    function __construct()
    {
        
    }

    public static function listar_directorios_ruta($ruta)
    {
        // abrir un directorio y listarlo recursivo 
        if (is_dir($ruta)) {
            if ($gestor = opendir($ruta)) {
                /* Esta es la forma correcta de iterar sobre el directorio. */
                while (false !== ($entrada = readdir($gestor))) {
                    if ($entrada != "." && $entrada != "..") {
                        $retorno[]=$entrada;
                    }
                }
                closedir($gestor);
            }
        } else {
            echo "<br>No es ruta valida";
        }
        return $retorno;
    }
    
    public function ifExisteFile ($file)
    {
        if (! file_exists($file)) {
            die('No existe el archivo');
        }
        return true;
    }

}

