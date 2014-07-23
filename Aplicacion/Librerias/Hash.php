<?php

class Hash
{
    /**
     * Permite obtener un hash a partir de un dato
     * @param string $algoritmo
     * @param string $data
     * @param string $key
     * @return resource 
     */
    public static function getHash($algoritmo, $data, $key)
    {
        $hash = hash_init($algoritmo, HASH_HMAC, $key);
        hash_update($hash, $data);
        
        return hash_final($hash);
    }
}


