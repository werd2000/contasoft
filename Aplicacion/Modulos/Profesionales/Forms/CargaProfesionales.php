<?php
require_once LibQ . 'Zend/Form.php';
require_once LibQ . 'Form/Decorator/IconoInformacion.php';

/**
 *  Clase para armar el formulario donde se cargan los profesionales
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Profesionales
 */
class Form_CargaProfesionales extends Zend_Form
{
    private $_varForm = array();
    private $_config;
    public $elementRequeridoDecorators = array(
        'ViewHelper',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array('IconoInformacion', array('placement' => 'append')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementDecorators = array(
        'ViewHelper',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementZendDecorators = array(
        'UiWidgetElement',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
//        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );
    
    function __construct($profesionales = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 'App/LibQ/Form/Decorator', 'decorator');
        $this->addPrefixPath('App_LibQ_ZendX_JQuery_Form_Decorator', 'App/LibQ/ZendX/JQuery/Form/Decorator', 'decorator');       
        $this->_varForm = $profesionales;
        $this->_config = Config::singleton();
        parent::__construct();
    }
    
    public function mostrar()
    {
        $categoria_usuario = array('' => '', 'Administrador' => 'Administrador', 'Usuario' => 'Usuario', 'Consultor' => 'Consultor');
        $this->setMethod("POST");
        if (count($this->_varForm)>0){            
            $valorId = $this->_varForm['id'];
            $valorNombre = $this->_varForm['nombre'];
            $valorProfesion = $this->_varForm['profesion'];
            $valorApellido = $this->_varForm['apellido'];
            $valorCel = $this->_varForm['cel'];
            $valorCuit = $this->_varForm['cuit'];
            $valorDomicilio = $this->_varForm['domicilio'];
            $valorEmail = $this->_varForm['email'];
            $valorNro_doc = $this->_varForm['nro_doc'];
            $valorTel = $this->_varForm['tel'];
            $valorCondicion_iva = $this->_varForm['condicion_iva'];
        }else{
            $valorId = '';
            $valorNombre = '';
            $valorProfesion = '';
            $valorApellido = '';
            $valorCel = '';
            $valorCuit = '';
            $valorDomicilio = '';
            $valorEmail = '';
            $valorNro_doc = '';
            $valorTel = '';
            $valorCondicion_iva = '';
        }
        if ($valorId == 0) {
            $this->setAction('index.php?option=profesionales&sub=agregar');
        } else {
            $this->setAction('index.php?option=profesionales&sub=editar&id=' . $valorId);
        }
        $this->setAttrib('id', 'frmprofesionales');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$valorId));
        /** Apellido **/
        $apellido = $this->createElement('text', 'apellido',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorApellido));
        $apellido->setLabel('Apellido:');
        $apellido->setRequired(true);
        /** nombre **/
        $nombre = $this->createElement('text', 'nombre',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorNombre));
        $nombre->setLabel('Nombre:');
        $nombre->setRequired(true);
        /** Profesión **/
        $profesion = $this->createElement('text', 'profesion',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorProfesion));
        $profesion->setLabel('Profesión:');
        $profesion->setRequired(true);
        /** nrodoc **/
        $nrodoc = $this->createElement('text', 'nro_doc',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorNro_doc));
        $nrodoc->setLabel('Nro.Doc.');
        $nrodoc->setRequired(true);
       /** condicion iva **/
        $condicion_iva = $this->createElement('select', 'condicion_iva',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorCondicion_iva));
        $condicion_iva->setOptions(array('multiOptions' => $this->_config->get('tipos_de_iva')));
        $condicion_iva->setLabel('Condición Iva:');
        $condicion_iva->setRequired(true);
        /** cuit **/
        $cuit = $this->createElement('text', 'cuit',array('decorators' => $this->elementDecorators, 'value'=>$valorCuit));
        $cuit->setLabel('Cuit:');
        /** domicilio **/
        $domicilio = $this->createElement('text', 'domicilio',array('decorators' => $this->elementDecorators, 'value'=>$valorDomicilio));
        $domicilio->setLabel('Domicilio:');
        /** tel **/
        $tel = $this->createElement('text', 'tel',array('decorators' => $this->elementDecorators, 'value'=>$valorTel));
        $tel->setLabel('Teléfono:');
        /** cel **/
        $cel = $this->createElement('text', 'cel',array('decorators' => $this->elementDecorators, 'value'=>$valorCel));
        $cel->setLabel('Celular:');
        /** email **/
        $email = $this->createElement('text', 'email',array('decorators' => $this->elementDecorators, 'value'=>$valorEmail));
        $email->setLabel('Email:');
        
        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($apellido);
        $this->addElement($nombre);
        $this->addElement($profesion);
        $this->addElement($nrodoc);
        $this->addElement($condicion_iva);
        $this->addElement($cuit);
        $this->addElement($domicilio);
        $this->addElement($tel);
        $this->addElement($cel);
        $this->addElement($email);
        
        return $this;        
    }
}
