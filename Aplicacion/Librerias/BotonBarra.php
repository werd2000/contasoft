<?php

class BotonBarra {
	private $_retorno;
	private $_class;
	private $_evento;
	private $_href;
	private $_icono;
	private $_titulo;
	
	function __construct($opciones = null) 
	{
		if (is_array($opciones)){
		    $this->setOptions($opciones);
		}
	}
	
	public function setClass($class)
	{
	    $this->_class = $class;    
	}
	
	public function setEvento($evento)
	{
	    $this->_evento = $evento;
	}
	
	public function setHref($href)
	{
	    $this->_href = $href;   
	}
	
	public function setIcono($icono)
	{
	    $this->_icono = $icono;
	}
	
	public function setTitle($titulo)
	{
	    $this->_titulo = $titulo;
	}
	
	public function render()
	{
	    $this->_retorno = "<a class=\"$this->_class\" $this->_evento href=$this->_href >";
	    $this->_retorno .= "<span class=\"$this->_icono\" title=\"$this->_titulo\"> </span>";
	    $this->_retorno .= "$this->_titulo</a>\n";
	    return $this->_retorno;
	}
	
	public function agregarBoton($nombre, $vista)
	{
        $bt  = "<a class=\"toolbar\" onclick=\"javascript: submitbutton('save')\" href=\"javascript:void(0);\">";
        $bt .="<span class=\"icono-guardar32\" title=\"Guardar\"> </span>";
        $bt .="$nombre</a>";
	    $submit = self::_envolverBoton($bt);
        $this->_retorno .= $submit; //->render($vista);
	    
	}
	
	
    public function setOptions(array $options)
    {
        if (isset($options['class'])) {
            $this->_class = $options['class'];
        }

        if (isset($options['evento'])) {
            $this->_evento = $options['evento'];
        }
        
        if (isset($options['href'])) {
            $this->_href = $options['href'];
        }

        if (isset($options['icono'])) {
            $this->_icono = $options['icono'];
        }
        
        if (isset($options['titulo'])) {
            $this->_titulo = $options['titulo'];
        }
        
        return $this;
    }
		
	public function __toString()
	{
	    $this->render();
	}
}

?>