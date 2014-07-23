<?php
$config = Config::singleton();
 
$config->set('controllersFolder', 'controllers/');
$config->set('modelsFolder', 'models/');
$config->set('viewsFolder', 'views/');
 
$config->set('dbhost', 'localhost');
$config->set('dbname', 'pruebas');
$config->set('dbuser', 'root');
$config->set('dbpass', '');
define('HASH_KEY', '50d8bab41b8c2');



$config->set(espaniol, array(
    'IS_EMPTY' => 'Debe ingresar este dato',    
	'message1' => 'message1',
    'message2' => 'message2',
    'message3' => 'message3'));