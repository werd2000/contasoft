<?php
require_once 'Zend/Form.php';
require_once 'App/LibQ/Config.php';
require_once 'App/LibQ/Form/Decorator/IconoInformacion.php';
require_once LibQ . 'Zend/Locale.php';
require_once LibQ . 'Zend/Translate.php';
require_once LibQ . 'Zend/Locale.php';
require_once LibQ . 'Zend/Translate.php';

class Form_CargaClientes extends Zend_Form
{
    private $_varForm = array();
    private $_config;
    public $elementRequeridoDecorators = array(
        'ViewHelper',
        array('Description', 	array('tag' => 'span', 'class' => 'element-description')),
    	array('Errors'),
        array('Label', array('separator' => ' ')),
        array('IconoInformacion',array('placement'=>'append')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementDecorators = array(
        'ViewHelper',
        array('Description', 	array('tag' => 'span', 'class' => 'element-description')),
    	array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementZendDecorators = array(
        'UiWidgetElement',
        array('Description', 	array('tag' => 'span', 'class' => 'element-description')),
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
    
    function __construct($clientes = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 
                             'App/LibQ/Form/Decorator',
                             'decorator');
        $this->_config = Config::singleton();
        $this->_varForm = $clientes;
        parent::__construct();
    }
    
    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorRazon_social = $this->_varForm['razon_social'];
            $valorDomicilio = $this->_varForm['domicilio'];
            $valorCondicion_iva = $this->_varForm['condicion_iva'];
            $valorCuit = $this->_varForm['cuit'];
            $valorTel = $this->_varForm['tel'];
            $valorCel = $this->_varForm['cel'];
            $valorEmail = $this->_varForm['email'];
            $valorEliminado = $this->_varForm['eliminado'];            
        }else{
            $valorId = '';
            $valorRazon_social = '';
            $valorDomicilio = '';
            $valorCondicion_iva = '';
            $valorCuit = '';
            $valorTel = '';
            $valorCel = '';
            $valorEmail = '';
            $valorEliminado = '';                        
        }
        if ($valorId==0){
            $this->setAction('index.php?option=clientes&sub=agregar');
        }else{
            $this->setAction('index.php?option=clientes&sub=editar&id='.$valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmclientes');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$valorId));
        /** Raz�n Social **/
        $razon_social = $this->createElement('text', 'razon_social',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorRazon_social));
        $razon_social->setLabel('Razón Social:');
        $razon_social->setRequired(true);
        /** Domicilio **/
        $domicilio = $this->createElement('text', 'domicilio',array('decorators' => $this->elementDecorators, 'value'=>$valorDomicilio));
        $domicilio->setLabel('Domicilio:');
//        $domicilio->addValidator('Alnum');
        /** Condición ante el IVA **/
        $condicion_iva = $this->createElement('select', 'condicion_iva',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorCondicion_iva));
        $condicion_iva->setOptions(array('multiOptions' => $this->_config->get('tipos_de_iva')));
        $condicion_iva->setLabel('Condición IVA:');
        $condicion_iva->setRequired(true);
        /** Cuit **/
        $cuit = $this->createElement('text', 'cuit',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorCuit));
        $cuit->setLabel('Cuit:');
        $cuit->setRequired(true);
        $cuit->addValidator('Digits');
        $cuit->addValidator('StringLength', false, array(11 , 11));
        /** Telefono **/
        $tel = $this->createElement('text', 'tel',array('decorators' => $this->elementDecorators, 'value'=>$valorTel));
        $tel->setLabel('Teléfono:');
        /** Celular **/
        $cel = $this->createElement('text', 'cel',array('decorators' => $this->elementDecorators, 'value'=>$valorCel));
        $cel->setLabel('Celular:');
        /** Email **/
        $email = $this->createElement('text', 'email',array('decorators' => $this->elementDecorators, 'value'=>$valorEmail));
        $email->setLabel('Email');
        $email->addValidator('EmailAddress');
        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($razon_social);
        $this->addElement($domicilio);
        $this->addElement($condicion_iva);
        $this->addElement($cuit);
        $this->addElement($tel);
        $this->addElement($cel);
        $this->addElement($email);
//        $this->addElement($date1);
//        $this->addElement($enviar);
        /** establezco ubicaci�n **/
        $local = new Zend_Locale();
        //creo un translate
        $translate = new Zend_Translate('array', 'App/LibQ/Idiomas/es/' . $local . '.php', 'es');
        //establezco el idioma del decorador
        $this->setDefaultTranslator($translate);
        return $this;        
    }
}
