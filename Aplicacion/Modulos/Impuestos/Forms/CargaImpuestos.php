<?php   
require_once 'Zend/Form.php';
require_once 'App/LibQ/Config.php';
require_once 'App/LibQ/Form/Decorator/IconoInformacion.php';
/**
 *  Clase para armar el formulario donde se cargan los impuestos
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Impuestos
 */
class Form_CargaImpuestos extends Zend_Form
{
    private $_varForm = array();
    private $_config;
    public $elementRequeridoDecorators = array(
        'ViewHelper',
        array('Description', 	array('tag' => 'span', 'class' => 'element-description')),
    	array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
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
    
    function __construct($impuesto = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 
                             'App/LibQ/Form/Decorator',
                             'decorator');
        $this->addPrefixPath('App_LibQ_ZendX_JQuery_Form_Decorator', 
                             'App/LibQ/ZendX/JQuery/Form/Decorator',
                             'decorator');
        $this->_config = Config::singleton();
        $this->_varForm = $impuesto;
        parent::__construct();
    }
    
    public function mostrar()
    {
        $listaCaracter = array('NACIONAL'=>'NACIONAL','PROVINCIAL'=>'PROVINCIAL','MUNICIPAL'=>'MUNICIPAL');
        $listaTipoVencimiento = array('ANUAL'=>'ANUAL','SEMESTRAL'=>'SEMESTRAL','CUATRIMESTRAL'=>'CUATRIMESTRAL','TRIMESTRAL'=>'TRIMESTRAL','BIMESTRAL'=>'BIMESTRAL','MENSUAL'=>'MENSUAL');
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorImpuesto = $this->_varForm['impuesto'];
            $valorCaracter = $this->_varForm['caracter'];
            $valorTipo_Vencimiento = $this->_varForm['tipo_vencimiento'];
            $valorObservaciones = $this->_varForm['observaciones'];
            $valorEliminado = $this->_varForm['eliminado'];            
        }else{
            $valorId = '';
            $valorImpuesto = '';
            $valorCaracter = '';
            $valorTipo_Vencimiento = '';
            $valorObservaciones = '';
            $valorEliminado = '';                        
        }
        if ($valorId == 0){
            $this->setAction('index.php?option=impuestos&sub=agregar');
        }else{
            $this->setAction('index.php?option=impuestos&sub=editar&id='.$valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmimpuestos');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$valorId));
        /** Impuesto **/
        $impuesto = $this->createElement('text','impuesto',
                                       array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorImpuesto));
        $impuesto->setLabel('Impuesto:');   
        $impuesto->setRequired(true);
        $impuesto->setAttrib('placeholder', "El nombre del impuesto o el nombre del formulario");
        $impuesto->setAttrib('style', 'width:280px;');
        /** Caracter **/
        $caracter = $this->createElement('select', 'caracter',array('decorators' => $this->elementRequeridoDecorators));
        $caracter->setLabel('Caracter:');
        $caracter->setRequired(true);
        $caracter->setOptions(array('multiOptions' => $listaCaracter, 'value'=>$valorCaracter));

        /** Tipo vencimiento **/
        $tipo_vencimiento = $this->createElement('select', 'tipo_vencimiento',array('decorators' => $this->elementRequeridoDecorators));
        $tipo_vencimiento->setLabel('Tipo Vencimiento:');
        $tipo_vencimiento->setRequired(true);
        $tipo_vencimiento->setOptions(array('multiOptions' => $listaTipoVencimiento, 'value'=>$valorTipo_Vencimiento));
        
        /** Observaciones **/
        $observaciones = $this->createElement('textarea','observaciones',
                                       array('decorators' => $this->elementDecorators,'value'=>$valorObservaciones));
        $observaciones->setAttrib('cols', 70);
        $observaciones->setAttrib('rows', 10);
        $observaciones->setLabel('Observaciones:');
        /** Bot�n Guardar **/
//        $enviar = $this->createElement('submit', 'Guardar',array('decorators' => $this->buttonDecorators));
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($impuesto);
        $this->addElement($caracter);
        $this->addElement($tipo_vencimiento);
        $this->addElement($observaciones);
        /** establezco ubicaci�n **/
//        $local = new Zend_Locale('es_AR');
//        //creo un translate
//        $translate = new Zend_Translate('array', 'App/LibQ/Idiomas/es/' . $local . '.php', 'es');
//        //establezco el idioma del decorador
//        $this->setDefaultTranslator($translate);
        return $this;        
    }
}
