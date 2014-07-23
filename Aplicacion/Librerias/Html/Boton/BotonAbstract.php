<?php

/**
 * Botón Nuevo
 * @author walter
 */

require_once 'BotonInterface.php';

class BotonAbstract implements BotonInterface
{
    /**
     * @var string con el contenido html
     */
    private $_botonHtml = '';
    
    /**
     * @var string con el nombre de la clase CSS. Predeterminado "toolbar"
     */
    private $_class = 'toolbar';
    
    /**
     * @var string con el evento que se quiere usar
     */
    private $_evento = '';
    
    /**
     * @var string con el hipervínculo del botón 
     */
    private $_href = '';
    
    /**
     * @var int con el tamaño predeterminado de los iconos a usar 
     */
    private $_sizeIcono = 32;
    
    /**
     * @var string con el nombre del icono 
     */
    private $_icono = '';
    
    /**
     * @var string con el nombre de la clase a usar 
     */
    private $_classIcono = '';
    
    /**
     * @var string con el titulo a mostrar 
     */
    private $_titulo = '';
    
    /**
     * Crea el botón
     */
    function __construct($parametros)
    {
        if (is_array($parametros)){
            $this->_setOptions($parametros);
        }
    }
    
    /**
     * Establece el nombre de la clase CSS en el botón
     * @param string $class 
     */
    public function setClass($class)
    {
        $this->_class = $class;
    }
    
    /**
     * Devuelve el nombre de la clase CSS que usa el botón
     * @return string 
     */
    public function getClass()
    {
        return $this->_class;
    }
    
    /**
     * Establece el evento que se usa en el botón
     * @param string $evento 
     */
    public function setEvento($evento)
    {
        $this->_evento = $evento;
    }
    
    /**
     * Establece el hipervinculo donde va cuando se hace clic
     * @param string $href 
     */
    public function setHref($href)
    {
        $this->_href = $href;   
    }
    
    /**
     * Establece el tamaño del icono a usar
     * En forma predeterminada se usa el de 32x32
     * @param int $size 
     */
    public function setSizeIcono($size)
    {
        $this->_sizeIcono = $size;
    }
    
    /**
     * Establece el nombre del icono a usar
     * @param string $icono 
     */
    public function setIcono($icono)
    {
        $this->_icono = $icono;
    }
    
    /**
     * Establece el nombre de la clase CSS que usa <span> para mostrar el icono
     * @param string $classIcono 
     */
    public function setClassIcono($classIcono)
    {
        $this->_classIcono = $classIcono;
    }
    
    /**
     * Establece el texto a mostrar en el botón
     * @param string $titulo 
     */
    public function setTitle($titulo)
    {
        $this->_titulo = $titulo;
    }

    /**
     * Muestra el botón
     */
    public function render()
    {
        $this->_botonHtml = "<a class=\"$this->_class\" $this->_evento href=$this->_href >";
        $this->_botonHtml .= "<span class=\"$this->_classIcono\" title=\"$this->_titulo\"> </span>";
        $this->_botonHtml .= "$this->_titulo</a>\n";
        return $this->_botonHtml;
    }
    
    private function _setOptions(array $options)
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
        
        if (isset($options['classIcono'])) {
            $this->_classIcono = $options['classIcono'];
        }
        
        if (isset($options['sizeIcono'])) {
            $this->_sizeIcono = $options['sizeIcono'];
        }
        
        return $this;
    }
    
    public function __toString()
    {
        $this->render();
    }
}


