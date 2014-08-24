<?php

//require_once 'Zend/View.php';
require_once 'App/LibQ/ControladorBase.php';
require_once LibQ . 'ControlarSesion.php';
require_once DIRMODULOS . 'Gastos/Controlador/GastosControlador.php';
require_once DIRMODULOS . 'Honorarios/Controlador/HonorariosControlador.php';
require_once DIRMODULOS . 'Ingresos/Controlador/IngresosControlador.php';

class WidgetsControlador extends ControladorBase {

    protected $_objeto;
    protected $_contenido = '';
    protected $_titulo = '';
    protected $_vista = 'lista';

    function __construct($controlador, $metodo) {
        if (!is_string($controlador)) {
            throw new Exception('Widget inválido');
        }
        $this->_objeto = new $controlador();
        $this->_contenido = $this->_objeto->$metodo();
        $this->_titulo = $metodo;
    }

    public function getTitulo() {
        $ubicacion = 0;
        $busqueda = preg_match_all("/[A-Z]/", $this->_titulo, $coincidencias, PREG_OFFSET_CAPTURE);
        if ($busqueda > 0) {
            if (isset($coincidencias[0][0][1])) {
                $ubicacion = $coincidencias[0][0][1];
            }
            $caption_modulo = ucfirst(substr($this->_titulo, 0, $ubicacion)) . ' ' . substr($this->_titulo, $ubicacion);
            $ubicacion = 0;
        }
        return $caption_modulo;
    }

    public function getContenido() {
        return $this->_contenido;
    }
    
    public function setVista($vista){
        $this->_vista = $vista;
    }

}
