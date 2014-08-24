<?php   
require_once 'Zend/Form.php';
require_once 'App/LibQ/Config.php';
require_once 'App/LibQ/Form/Decorator/IconoInformacion.php';
/**
 *  Clase para armar el formulario donde se cargan los pagoimpuestos
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package PagoImpuestos
 */
class Form_CargaPagoImpuestos extends Zend_Form
{
    private $_varForm = array();
    private $_listaImpuestos;
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
    
    function __construct($listaImpuestos=array(), $pagoImpuesto = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 
                             'App/LibQ/Form/Decorator',
                             'decorator');
        $this->addPrefixPath('App_LibQ_ZendX_JQuery_Form_Decorator', 
                             'App/LibQ/ZendX/JQuery/Form/Decorator',
                             'decorator');
        $this->_config = Config::singleton();
        $this->_varForm = $pagoImpuesto;
        $this->_listaImpuestos = $listaImpuestos;
        parent::__construct();
    }
    
    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorImpuesto = $this->_varForm['impuesto'];
            $valorFechaPago = $this->_varForm['fecha_comprobante'];
            $valorTotal = $this->_varForm['total'];
            $valorObservaciones = $this->_varForm['observaciones'];
            $valorEliminado = $this->_varForm['eliminado'];            
        }else{
            $valorId = '';
            $valorImpuesto = '';
            $valorFechaPago = '';
            $valorTotal = '';
            $valorObservaciones = '';
            $valorEliminado = '';                        
        }
        if ($valorId == 0){
            $this->setAction('index.php?option=PagoImpuestos&sub=agregar');
        }else{
            $this->setAction('index.php?option=PagoImpuestos&sub=editar&id='.$valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmpagoimpuestos');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$valorId));
        /** Impuesto **/
        $impuesto = $this->createElement('select','impuesto',
                                       array('decorators' => $this->elementRequeridoDecorators));
        $impuesto->setLabel('Impuesto:');   
        $impuesto->setOptions(array('multiOptions' => $this->_listaImpuestos, 'value'=>$valorImpuesto));
        $impuesto->setRequired(true);
        $impuesto->setAttrib('style', 'width:280px;');
        /** Fecha Pago **/
        $fechaPago = $this->createElement('text', 'fecha_comprobante',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorFechaPago));
        $fechaPago->setLabel('Fecha:');
        $fechaPago->setRequired(true);

        /** Importe Pagado **/
        $total = $this->createElement('text', 'total',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorTotal));
        $total->setLabel('Total:');
        $total->setRequired(true);
        
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
        $this->addElement($fechaPago);
        $this->addElement($total);
        $this->addElement($observaciones);
        return $this;        
    }
}
