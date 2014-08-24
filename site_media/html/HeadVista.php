<?php
$this->headMeta()->appendName('keywords', KEYWORDS);
$this->headMeta()->appendHttpEquiv('Content-Type',
                                   'text/html; charset=UTF-8')
                 ->appendHttpEquiv('Content-Language', 'es-ES');
echo $this->headMeta();
echo $this->headTitle(SITENAME);
$this->headLink()->appendStylesheet(CSS.'basico/basico.css')
                 ->appendStylesheet(CSS.'basico/ui.jqgrid.css')
                 ->appendStylesheet(CSS.'basico/redmond/jquery-ui-1.8.2.custom.css')
                 ->headLink(array('rel' => 'shortcut icon','href' => IMG . 'favicon.ico'),'PREPEND');
//                 ->prependStylesheet('/styles/moz.css','screen', true, array('id' => 'my_stylesheet'));
$this->headScript()->appendFile(JS . 'jquery-1.7.min.js');
$this->headScript()->appendFile(JS . 'grid.locale-es.js');
$this->headScript()->appendFile(JS . 'jquery.jqGrid.min.js');
$this->headScript()->appendFile(JS . 'jquery-ui-1.8.17.custom/js/jquery-ui-1.8.17.custom.min.js');
$this->headScript()->appendFile(JS . 'jquery-ui-1.8.17.custom/development-bundle/ui/i18n/jquery.ui.datepicker-es.js');
$this->headScript()->appendFile(JS . 'fecha.js'); 
$this->headScript()->appendFile(JS . 'utiles.js'); 
$this->headScript()->appendFile('https://www.google.com/jsapi'); 
//$this->headScript()->appendFile(JS . 'utilgrilla.js'); 
$this->headScript()->appendFile(JS . 'js_mensajes.js');
$this->headScript()->appendFile(JS . 'util_ajax.js');
$this->headScript()->appendFile(JS . 'utils_gastos.js'); 
echo $this->headLink();
echo $this->headScript();
//type="image/x-icon">
