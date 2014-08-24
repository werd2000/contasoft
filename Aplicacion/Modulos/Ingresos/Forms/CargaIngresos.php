<?php   
require_once 'Zend/Form.php';
require_once 'App/LibQ/Config.php';
require_once 'App/LibQ/Form/Decorator/IconoInformacion.php';
require_once LibQ . 'Zend/Locale.php';
require_once LibQ . 'Zend/Translate.php';

/**
 *  Clase para armar el formulario donde se cargan los ingresos
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Ingresos
 */
class Form_CargaIngresos extends Zend_Form
{
    private $_cuentas = array();
    private $_clientes = array();
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
    
    function __construct($cuentas, $clientes, $gasto = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 
                             'App/LibQ/Form/Decorator',
                             'decorator');
        $this->addPrefixPath('App_LibQ_ZendX_JQuery_Form_Decorator', 
                             'App/LibQ/ZendX/JQuery/Form/Decorator',
                             'decorator');
        $this->_config = Config::singleton();
        $this->_cuentas = $cuentas;
        $this->_clientes = $clientes;
        $this->_varForm = $gasto;
        parent::__construct();
    }
    
    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorCuenta = $this->_varForm['cuenta'];
            $valorCliente = $this->_varForm['cliente'];
            $valorFecha_comprobante = $this->_varForm['fecha_comprobante'];
            $valorComprobante = $this->_varForm['comprobante'];
            $valorTipo_comprobante = $this->_varForm['tipo_comprobante'];
            $valorNro_comprobante = $this->_varForm['nro_comprobante'];
            $valorCondicion_venta = $this->_varForm['condicion_venta'];
            $valorFecha_cobro = $this->_varForm['fecha_cobro'];
            $valorRecibo_nro = $this->_varForm['recibo_nro'];
            $valorTotal = $this->_varForm['total'];
            $valorEliminado = $this->_varForm['eliminado'];            
        }else{
            $valorId = '';
            $valorCuenta = '';
            $valorCliente = '';
            $valorFecha_comprobante = '';
            $valorComprobante = '';
            $valorTipo_comprobante = '';
            $valorNro_comprobante = '';
            $valorCondicion_venta = '';
            $valorFecha_cobro = '';
            $valorRecibo_nro = '';
            $valorTotal = '';
            $valorEliminado = '';                        
        }
        if ($valorId == 0){
            $this->setAction('index.php?option=ingresos&sub=agregar');
        }else{
            $this->setAction('index.php?option=ingresos&sub=editar&id='.$valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmingresos');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$valorId));
        /** Cuenta **/
        $cuenta = $this->createElement('select','cuenta',
                                       array('decorators' => $this->elementRequeridoDecorators));
        $cuenta->setLabel('Cuenta:');
        $cuenta->setRequired(true);
        $cuenta->setOptions(array('multiOptions' => $this->_cuentas, 'value'=>$valorCuenta));
        /** Cliente **/
        $cliente = $this->createElement('select', 'cliente',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorCliente));
        $cliente->setLabel('Cliente:');
        $cliente->setRequired(true);
        $cliente->setOptions(array('multiOptions' => $this->_clientes));
        /** Fecha de Compra **/
        $fecha_compra = $this->createElement('text', 'fecha_comprobante',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorFecha_comprobante));
        $fecha_compra->setLabel('Fecha:');
        $fecha_compra->setRequired(true);
        /** Comprobante **/
        $comprobante = $this->createElement('select', 'comprobante',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorComprobante));
        $comprobante->setLabel('Comprobante:');
        $comprobante->setRequired(true);
        $comprobante->setOptions(array('multiOptions' => $this->_config->get('lista_de_comprobantes')));
        /** Tipo de comprobante **/
        $tipoComprobante = $this->createElement('select', 'tipo_comprobante',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorTipo_comprobante));
        $tipoComprobante->setOptions(array('multiOptions' => $this->_config->get('tipos_de_comprobantes')));
        $tipoComprobante->setLabel('Tipo:');
        $tipoComprobante->setRequired(true);
        /** Numero de comprobante **/
        $nro_comprobante = $this->createElement('text', 'nro_comprobante',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorNro_comprobante));
        $nro_comprobante->setLabel('Nro:');
        $nro_comprobante->setRequired(true);
        $nro_comprobante->addValidator('Alnum');
        $nro_comprobante->addValidator('StringLength', false, array(12 , 12));
        /** Condici�n de Venta **/
        $condicionVenta = $this->createElement('select', 'condicion_venta',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorCondicion_venta));
        $condicionVenta->setLabel('Condicion Venta:');
        $condicionVenta->setRequired(true);
        $condicionVenta->setOptions(array('multiOptions' => $this->_config->get('condicion_de_venta')));
       
        /** Importe Total **/
        $total = $this->createElement('text', 'total',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorTotal));
        $total->setLabel('Total:');
        $total->setRequired(true);
/** Fecha de Pago **/
        $fecha_cobro = $this->createElement('text', 'fecha_cobro',array('decorators' => $this->elementDecorators, 'value'=>$valorFecha_cobro));
        $fecha_cobro->setLabel('Fecha Pago:');
        /** N�mero de recibo **/
        $recibo_nro = $this->createElement('text', 'recibo_nro',array('decorators' => $this->elementDecorators, 'value'=>$valorRecibo_nro));
        $recibo_nro->setLabel('Recibo Nro:');
        $recibo_nro->addValidator('Alnum');
        
        require_once 'App/LibQ/ZendX/JQuery/Form/Element/DatePicker.php';
        $date1 = new ZendX_JQuery_Form_Element_DatePicker('date', array('label' => 'Date:'));
        $date1->setJQueryParam('dateFormat', 'dd-mm-yy');
        $date1->setDecorators($this->elementZendDecorators);
        /** Bot�n Guardar **/
        $enviar = $this->createElement('submit', 'Guardar',array('decorators' => $this->buttonDecorators));
        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($cuenta);
        $this->addElement($cliente);
        $this->addElement($fecha_compra);
        $this->addElement($comprobante);
        $this->addElement($tipoComprobante);
        $this->addElement($nro_comprobante);
        $this->addElement($condicionVenta);

        $this->addElement($total);
        $this->addElement($fecha_cobro);
        $this->addElement($recibo_nro);
        /** establezco ubicaci�n **/
        $local = new Zend_Locale('es_AR');
        //creo un translate
        $translate = new Zend_Translate('array', 'App/LibQ/Idiomas/es/' . $local . '.php', 'es');
        //establezco el idioma del decorador
        $this->setDefaultTranslator($translate);
        return $this;        
    }
}
