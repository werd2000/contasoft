<?php
require_once LibQ . 'BotonBarra.php';

class LibQ_BarraHerramientas {
	private $_retorno;
	
    public $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'button', 'id'=>'toolbar-save')),
    );
	
	function __construct() {
		$this->_retorno = "<div id=\"LibQ_BarraHerramientas\" class=\"ui-state-default ui-jqgrid-pager ui-corner-all\">\n";
		$this->_retorno .= "<div id=\"toolbar\" class=\"toolbar\">";
		$this->_retorno .="<table class=\"toolbar\"><tbody><tr>";
	}
	
	public function agregarBoton(BotonInterface $boton)
	{
	    $submit = self::_envolverBoton($boton);
            $this->_retorno .= $submit;
	}
	
	
	private function _envolverBoton($boton)
	{
            $retorno  = "<td id=\"toolbar\" class=\"button\">";
	    $retorno .= $boton->render();
            $retorno .= "</td>\n";
            return $retorno;
	}
	
	public function render()
	{
	    $this->_retorno .= "</tr></table>\n";
	    $this->_retorno .= "</div>\n";
	    $this->_retorno .= "</div>\n";
            return $this->_retorno;   
	}
        
        private function _ifExisteClase ($class)
        {
            $file = LibQ . 'Html/Boton/' . 'Boton' . ucfirst($class) . '.php';
            if (! file_exists($file)) {
                die('No se puede crear el botón '.$class);
            }
            return 'Boton' . ucfirst($class);
        }

        public function addBoton($tipo,$arg)
        {
            $clase = self::_ifExisteClase($tipo);
            $file = LibQ . 'Html/Boton/' . $clase . '.php';
            require_once ($file);
            $boton = new $clase($arg);
            $this->_retorno .= self::_envolverBoton($boton);
        }
        
        private function _addInscribir($menu)
        {
            $btnNuevo = new BotonBarra(array('class' => 'toolbar' , 'href' => 'index.php?option='.$menu.'&sub=inscribir' , 'icono' => 'icono-nuevo32' , 'titulo' => 'Inscribir'));
            $btnNuevo = self::_envolverBoton($btnNuevo);
            $this->_retorno .= $btnNuevo;
        }
        
        private function _addNuevo($menu)
        {
            $btnNuevo = new BotonBarra(array('class' => 'toolbar' , 'href' => 'index.php?option='.$menu.'&sub=agregar' , 'icono' => 'icono-nuevo32' , 'titulo' => 'Nuevo'));
            $btnNuevo = self::_envolverBoton($btnNuevo);
            $this->_retorno .= $btnNuevo;
        }
        
        private function _addLista($menu)
        {
            $btnLista = new BotonBarra(array('class' => 'toolbar' , 'href' => 'index.php?option='.$menu.'&sub=listar' , 'icono' => 'icono-lista32' , 'titulo' => 'Lista'));            
            $btnLista = self::_envolverBoton($btnLista);
            $this->_retorno .= $btnLista;
        }
        
        private function _addGuardar($menu)
        {
            $btnGuardar = new BotonBarra();
            $btnGuardar->setClass('toolbar');
            $btnGuardar->setEvento("onclick=\"javascript: submitbutton('save')\"");
            $btnGuardar->setHref("\"javascript:void(0);\"");
            $btnGuardar->setIcono("icono-guardar32");
            $btnGuardar->setTitle("Guardar");
            $btnGuardar = self::_envolverBoton($btnGuardar);
            $this->_retorno .= $btnGuardar;
        }
        
        private function _addEliminar($menu, $arg)
        {
            $btnEliminar = new BotonBarra(array('class' => 'toolbar' , 'href' => 'index.php?option='.$menu.'&sub=eliminar&id='.$arg , 'icono' => 'icono-eliminar32' , 'titulo' => 'Eliminar'));            
            $btnEliminar = self::_envolverBoton($btnEliminar);
            $this->_retorno .= $btnEliminar;
        }
        
        private function _addFiltrar ($menu, $arg)
        {   
            $btnEliminar = new BotonBarra();
            $btnEliminar->setClass('btn_filtrar');
            $btnEliminar->setEvento("onclick=\"javascript: submitbutton('filtrar')\"");
            $btnEliminar->setHref("\"javascript:void(0);\"");
            $btnEliminar->setIcono("icono-filtrar32");
            $btnEliminar->setTitle("Filtrar");
            $btnEliminar = self::_envolverBoton($btnEliminar);
            $this->_retorno .= $btnEliminar;
        }
        
        private function _addExportar ($menu, $arg)
        {
            if ($arg == ''){
                $btnExportar = new BotonBarra(array('class' => 'toolbar' , 'href' => 'index.php?option='.$menu.'&sub=exportar', 'icono' => 'icono-exportar32' , 'titulo' => 'Exportar'));
            }else{
                $btnExportar = new BotonBarra(array('class' => 'toolbar' , 'href' => 'index.php?option='.$menu.'&sub=exportar&'.$arg , 'icono' => 'icono-exportar32' , 'titulo' => 'Exportar'));
            }
            $btnExportar = self::_envolverBoton($btnExportar);
            $this->_retorno .= $btnExportar;
        }

        public function __toString()
	{
	    $this->render();
	}
}

?>