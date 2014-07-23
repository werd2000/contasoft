<?php
require_once 'Aplicacion/Librerias/Config.php';

$config = Config::singleton();
 
$config->set('tipos_de_iva', array('R.I.'=>'R.I.', 'N.I.'=>'N.I.', 'MONOTRIBUTO'=>'MONOTRIBUTO', 'MONOTRIBUTO SOCIAL'=>'MONOTRIBUTO SOCIAL', 'EXCENTO'=>'EXCENTO','CF'=>'CF'));
$config->set('condicion_de_venta', array('CONTADO'=>'CONTADO', 'CHEQUE'=>'CHEQUE'));

$config->set('lista_de_comprobantes',array('FACTURA'=>'FACTURA', 'RECIBO'=>'RECIBO', 'TICKET'=>'TICKET'));
$config->set('tipos_de_comprobantes', array('A'=>'A', 'B'=>'B', 'C'=>'C'));
$config->set('viewsFolder', 'views/');
 
$config->set('dbhost', 'localhost');
$config->set('dbname', 'pruebas');
$config->set('dbuser', 'root');
$config->set('dbpass', '');

$config->set('limiteGrilla', 30);
$config->set('verEliminados',0);    //0 = NO || 1 = SI

$config->set('tiposUsuarios', array('ADMINISTRADOR'=>'ADMINISTRADOR', 'USUARIO'=>'USUARIO', 'CONSULTOR'=>'CONSULTOR'));
//$categoria_usuario=array(''=>'','Administrador'=>'Administrador', 'Usuario'=>'Usuario', 'Consultor'=>'Consultor');
$config->set('datosguardados','Los datos se guardaron correctamente');
$config->set('datoseliminados','Los datos se borraron correctamente');
$config->set('facturaexiste','Ya existe un comprobante con ese Número. Verifique por favor');
$config->set('meses', Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"));

if ($_SERVER['HTTP_HOST']== 'localhost'){
    $config->set('livesite','http://localhost/contasoft/');
}else{
    $config->set('livesite','http://www.pequehogar.com.ar/contasoft');
}



define('SITENAME','ContaSoft');
define('SITEDESCRIPTION', 'Sistema de administracion contable');
define('KEYWORDS','contabilidad, compras, gastos, impuestos, iva, dgr, ganancias');
//define('SMARTY_DIR', '/xampp/xampp/htdocs/contasoft/smarty/');
define ('App','App/');
define ('LibQ','Aplicacion/Librerias/');
define ('DIR_CONTROLADOR','controladores/');
if ($_SERVER['HTTP_HOST']== 'localhost')
{
    define('LIVESITE','http://localhost/Contasoft/');
}else{
    define('LIVESITE','http://www.pequehogar.com.ar/contasoft');
}

define('HASH_KEY', '50d8bab41b8c2');

// name of folder that application class files reside in
define('CLASSDIR', 'site_src');
// application absolute path to source files (should reside on a folder one level behind the public one)
define('DIRCONTROLADOR', 'App/controladores/');

define('CANT_FOTOS','1');
define('TAMANIO_FOTO','600000');
define ('LONGITUD_PASSWORD','6');
define ('WEBMASTER_MAIL','e2000posadas@fibertel.com.ar');
define ('WEBMASTER_NAME','Walter Enrique');
define ('LONGITUD_NOMBREUSUARIO','3');
define ('AVISAR_ADMIN',TRUE);
define ('MAX_SESIONES_DIA','3');

define ('FOTO_MUY_GRANDE','El tama&ntilde;o de la foto excede el valor permitido<br>');
define ('ERROR_TIPO_ARCHIVO','No se permite este tipo de archivos<br>');
define ('MALA_MODIFICACION','No se realiz&oacute; ning&uacute;n cambio<br>');
define ('NOMBRE_REAL','<h3>Informaci&oacute;n para Nombre:</h3><p>Introduzca su nombre completo.</p>');
define ('NOMBRE_USUARIO','<h3>Informaci&oacute;n para Nombre de usuario:</h3><p>Introduzca un nombre de usuario v&aacute;lido. Sin espacios, al menos de 3 caracteres y que contenga 0-9,a-z,A-Z</p>');
define ('PASSWORD1','<h3>Informaci&oacute;n para Contrase&ntilde;a:</h3><p>Introduzca una contrase&ntilde;a v&aacute;lida. Sin espacios, al menos de 6 caracteres y que contenga letras min&uacute;sculas y may&uacute;sculas, n&uacute;meros y s&iacute;mbolos</p>');
define ('PASSWORD2','<h3>Informaci&oacute;n para Confirmar Contrase&ntilde;a:</h3><p>Introduzca una contrase&ntilde;a v&aacute;lida. Sin espacios, al menos de 6 caracteres y que contenga letras min&uacute;sculas y may&uacute;sculas, n&uacute;meros y s&iacute;mbolos</p>');
define ('EMAIL_REGISTRO','<h3>Informaci&oacute;n para Email:</h3><p> Introduzca una direcci&oacute;n de correo electr&oacute;nico v&aacute;lida. Se enviar&aacute; un correo de confirmaci&oacute;n a esta direcci&oacute;n tras el registro.</p>');
define ('CAMPO_OBLIGATORIO','* Este campo es obligatorio');
define ('TIPO_USUARIO','Seleccione como quiere registrarse. Las opciones son: Usuario o Inmobiliaria');
define ('INFORMACION','Informaci&oacute;n: Dirija el rat&oacute;n al icono');
define ('PASSWORD_CORTO','El campo <b>Password</b> debe ser de al menos ' . LONGITUD_PASSWORD . ' caracteres.');
define ('PASSWORD_DISTINTOS','El campo <b>Confirmar Password</b> no coincide con el campo <b>Password</b>", por favor, int&eacute;ntelo de nuevo.');
define ('NOMBREUSUARIO_CORTO','El campo <b>Usuario</b> es muy corto. Debe tener al menos ' . LONGITUD_NOMBREUSUARIO . ' caracteres.');
define ('NOLOGUEADO','Ingrese primero su Usuario y Contrase&ntilde;a');
define ('TEXTO_PUBLICAR_FOTO','<p>Las fotos que subas a Posadas-Estudiantil podr&aacute;n ser vistas y comentadas por otros usuarios.<br />
	  Tus fotograf&iacute;as no pueden contener material pornogr&aacute;fico, escatol&oacute;gico, violento, ni nada que pueda ser considerado ofensivo por otros  usuarios.<br />
	  Realizar este tipo de actividades puede causar el cierre  definitivo de tu cuenta en  Posadas-Estudiantil</p>');
/* TESTING */
define('DEBUG', true);
define('DEBUG_FILE_LOG', SITENAME . '.log');
define ('ERROR_GUARDAR','Hubo un error al procesar la solicitud y no se guardaron los datos');

/* Variables globales */
$monedas=array('Peso Argentino','D&oacute;lar USA','Euro','Real');
$dia=array('1'=>'Lunes','2'=>'Martes','3'=>'Mi&eacute;rcoles','4'=>'Jueves','5'=>'Viernes');
$horatt=array('1'=>'14:00:00','2'=>'14:30:00','3'=>'15:00:00','4'=>'15:30:00','5'=>'16:00:00','6'=>'16:30:00','7'=>'17:00:00','8'=>'17:30:00','9'=>'18:00:00','10'=>'18:30:00');
$dias=array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
$meses=Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$paises=array('Argentina','Brasil','Chile','Paraguay','Uruguay','Bolivia','Per�','Colombia','Costa Rica','Cuba','Ecuador','El Salvador','Guatemala','Honduras','Mexico','Nicaragua','Panama','Puerto Rico','Venezuela','EEUU');
$error='';
$tipos_de_iva=array(''=>'','R.I.'=>'R.I.', 'N.I.'=>'N.I.', 'MONOTRIBUTO'=>'MONOTRIBUTO', 'MONOTRIBUTO SOCIAL'=>'MONOTRIBUTO SOCIAL', 'EXCENTO'=>'EXCENTO','CF'=>'CF');
$tipos_de_comprobantes=array(''=>'','A'=>'A', 'B'=>'B', 'C'=>'C');
$categoria_usuario=array(''=>'','Administrador'=>'Administrador', 'Usuario'=>'Usuario', 'Consultor'=>'Consultor');





//require_once  LibQ . 'Config.php';
//$config = Config::singleton();
//$config->set('nacionalidad',array('1'=>'ARGENTINA','2'=>'PARAGUAY'));    
//$config->set('tipos_de_iva', array('R.I.'=>'R.I.', 'N.I.'=>'N.I.', 'MONOTRIBUTO'=>'MONOTRIBUTO', 'MONOTRIBUTO SOCIAL'=>'MONOTRIBUTO SOCIAL', 'EXCENTO'=>'EXCENTO','CF'=>'CF'));
//$config->set('condicion_de_venta', array('CONTADO'=>'CONTADO', 'CHEQUE'=>'CHEQUE'));
//
//$config->set('lista_de_comprobantes',array('FACTURA'=>'FACTURA', 'RECIBO'=>'RECIBO', 'TICKET'=>'TICKET'));
//$config->set('tipos_de_comprobantes', array('A'=>'A', 'B'=>'B', 'C'=>'C'));
//$config->set('viewsFolder', 'views/');
// 
//$config->set('dbhost', 'localhost');
//$config->set('dbname', 'pruebas');
//$config->set('dbuser', 'root');
//$config->set('dbpass', '');
//
//$config->set('verEliminados',0);    //0 = NO || 1 = SI
//
//$config->set('tiposUsuarios', array('ADMINISTRADOR'=>'ADMINISTRADOR', 'USUARIO'=>'USUARIO', 'CONSULTOR'=>'CONSULTOR'));
////$categoria_usuario=array(''=>'','Administrador'=>'Administrador', 'Usuario'=>'Usuario', 'Consultor'=>'Consultor');
//$config->set('datosguardados','Los datos se guardaron correctamente');
//$config->set('facturaexiste','Ya existe un comprobante con ese Número. Verifique por favor');
//
//if ($_SERVER['HTTP_HOST']== 'localhost'){
//    $config->set('livesite','http://localhost/edusoft/');
//}else{
//    $config->set('livesite','http://www.pequehogar.com.ar/edusoft');
//}



//define('App', 'Aplicacion/');
define('DIR_MODULOS', 'App/Modulos/');
define('ZEND','Aplicacion/Librerias/Zend/');
define ('HTML','site_media/html/');
define ('CSS','site_media/css/');
define ('IMG','site_media/imagenes/');
define ('JS','site_media/js/');
define ('NACIONALIDADES', 'ARGENTINA,PARAGUAY');
define ('DATOSGUARDADOS','Los datos se guardaron correctamente');
define ('DATOSELIMINADOS','Los datos se borraron correctamente');
define ('LIMITEGRILLA', 30);
define ('FACTURAEXISTE','El comprobante ya existe');
define('DIRMODULOS', 'App/Modulos/');

/* Variables globales */
$monedas = array('Peso Argentino', 'D&oacute;lar USA', 'Euro', 'Real');
$dia = array('1' => 'Lunes', '2' => 'Martes', '3' => 'Mi&eacute;rcoles', '4' => 'Jueves', '5' => 'Viernes');
$horatt = array('1' => '14:00:00', '2' => '14:30:00', '3' => '15:00:00', '4' => '15:30:00', '5' => '16:00:00', '6' => '16:30:00', '7' => '17:00:00', '8' => '17:30:00', '9' => '18:00:00', '10' => '18:30:00');
$dias = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31');
$meses = Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$paises = array('Argentina', 'Brasil', 'Chile', 'Paraguay', 'Uruguay', 'Bolivia', 'Perú', 'Colombia', 'Costa Rica', 'Cuba', 'Ecuador', 'El Salvador', 'Guatemala', 'Honduras', 'Mexico', 'Nicaragua', 'Panama', 'Puerto Rico', 'Venezuela', 'EEUU');
$nacionalidades = array('ARGENTINA' => 'ARGENTINA', 'PARAGUAY' => 'PARAGUAY');
$error = '';
$tipos_de_iva = array('' => '', 'R.I.' => 'R.I.', 'N.I.' => 'N.I.', 'MONOTRIBUTO' => 'MONOTRIBUTO', 'MONOTRIBUTO SOCIAL' => 'MONOTRIBUTO SOCIAL', 'EXCENTO' => 'EXCENTO', 'CF' => 'CF');
$tipos_de_comprobantes = array('' => '', 'A' => 'A', 'B' => 'B', 'C' => 'C');
$categoria_usuario = array('' => '', 'Administrador' => 'Administrador', 'Usuario' => 'Usuario', 'Consultor' => 'Consultor');
?>