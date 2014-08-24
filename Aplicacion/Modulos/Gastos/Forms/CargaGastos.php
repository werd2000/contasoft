<?php   
require_once 'Zend/Form.php';
require_once 'App/LibQ/Config.php';
require_once 'App/LibQ/Form/Decorator/IconoInformacion.php';
require_once LibQ . 'Zend/Translate.php';

/**
 *  Clase para armar el formulario donde se cargan los gastos
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Gastos
 */
class Form_CargaGastos extends Zend_Form
{
    private $_cuentas = array();
    private $_proveedores = array();
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
    
    function __construct($cuentas, $proveedores, $gasto = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 
                             'App/LibQ/Form/Decorator',
                             'decorator');
        $this->addPrefixPath('App_LibQ_ZendX_JQuery_Form_Decorator', 
                             'App/LibQ/ZendX/JQuery/Form/Decorator',
                             'decorator');
        $this->_config = Config::singleton();
        $this->_cuentas = $cuentas;
        $this->_proveedores = $proveedores;
        $this->_varForm = $gasto;
        parent::__construct();
    }
    
    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorCuenta = $this->_varForm['cuenta'];
            $valorProveedor = $this->_varForm['proveedor'];
            $valorFecha_comprobante = $this->_varForm['fecha_comprobante'];
            $valorComprobante = $this->_varForm['comprobante'];
            $valorTipo_comprobante = $this->_varForm['tipo_comprobante'];
            $valorNro_comprobante = $this->_varForm['nro_comprobante'];
            $valorImporte_gravado = $this->_varForm['importe_gravado'];
            $valorImporte_nogravado = $this->_varForm['importe_nogravado'];
            $valorIva_inscripto = $this->_varForm['iva_inscripto'];
            $valorIva_diferencial = $this->_varForm['iva_diferencial'];
            $valorPercepciones = $this->_varForm['percepciones'];
            $valorTotal = $this->_varForm['total'];
            $valorEliminado = $this->_varForm['eliminado'];            
        }else{
            $valorId = '';
            $valorCuenta = '';
            $valorProveedor = '';
            $valorFecha_comprobante = '';
            $valorComprobante = '';
            $valorTipo_comprobante = '';
            $valorNro_comprobante = '';
            $valorImporte_gravado = '';
            $valorImporte_nogravado = '';
            $valorIva_inscripto = '';
            $valorIva_diferencial = '';
            $valorPercepciones = '';
            $valorTotal = '';
            $valorEliminado = '';                        
        }
        if ($valorId == 0){
            $this->setAction('index.php?option=gastos&sub=agregar');
        }else{
            $this->setAction('index.php?option=gastos&sub=editar&id='.$valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmgastos');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$valorId));
        /** Cuenta **/
        $cuenta = $this->createElement('select','cuenta',
                                       array('decorators' => $this->elementRequeridoDecorators));
        $cuenta->setLabel('Cuenta:');
        $cuenta->setRequired(true);
        $cuenta->setOptions(array('multiOptions' => $this->_cuentas, 'value'=>$valorCuenta));
        /** Proveedor **/
        $proveedor = $this->createElement('select', 'proveedor',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorProveedor));
        $proveedor->setLabel('Provedor:');
        $proveedor->setRequired(true);
        $proveedor->setOptions(array('multiOptions' => $this->_proveedores));
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
        /** Importe Gravado **/
        $importe_gravado = $this->createElement('text', 'importe_gravado',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorImporte_gravado));
        $importe_gravado->setLabel('Importe Neto Gravado:');
        $importe_gravado->setRequired(true);
        /** Importe No Gravado **/
        $importe_nogravado = $this->createElement('text', 'importe_nogravado',array('decorators' => $this->elementDecorators,
            'value'=>$valorImporte_nogravado));
        $importe_nogravado->setLabel('Importe Neto No Gravado:');
        /** Importe del IVA **/
        $iva_inscripto = $this->createElement('text', 'iva_inscripto',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorIva_inscripto));
        $iva_inscripto->setLabel('IVA');
        $iva_inscripto->setRequired(true);
        /** Importe del IVA Diferencial **/
        $iva_diferencial = $this->createElement('text', 'iva_diferencial',array('decorators' => $this->elementDecorators,
            'value'=>$valorIva_diferencial));
        $iva_diferencial->setLabel('IVA Dif.:');
        /** Importe de las percepciones **/
        $percepciones = $this->createElement('text', 'percepcion',array('decorators' => $this->elementDecorators,
            'value'=>$valorPercepciones));
        $percepciones->setLabel('Percepciones:');
        /** Importe Total **/
        $total = $this->createElement('text', 'total',array('decorators' => $this->elementRequeridoDecorators,
            'value'=>$valorTotal));
        $total->setLabel('Total:');
        $total->setRequired(true);
        require_once 'App/LibQ/ZendX/JQuery/Form/Element/DatePicker.php';
        $date1 = new ZendX_JQuery_Form_Element_DatePicker('date', array('label' => 'Date:'));
        $date1->setJQueryParam('dateFormat', 'dd-mm-yy');
        $date1->setDecorators($this->elementZendDecorators);
        /** Bot�n Guardar **/
        $enviar = $this->createElement('submit', 'Guardar',array('decorators' => $this->buttonDecorators));
        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($cuenta);
        $this->addElement($proveedor);
        $this->addElement($fecha_compra);
        $this->addElement($comprobante);
        $this->addElement($tipoComprobante);
        $this->addElement($nro_comprobante);
        $this->addElement($importe_gravado);
        $this->addElement($importe_nogravado);
        $this->addElement($iva_inscripto);
        $this->addElement($iva_diferencial);
        $this->addElement($percepciones);
        $this->addElement($total);
        /** establezco ubicaci�n **/
        $local = new Zend_Locale('es_AR');
        //creo un translate
        $translate = new Zend_Translate('array', 'App/LibQ/Idiomas/es/' . $local . '.php', 'es');
        //establezco el idioma del decorador
        $this->setDefaultTranslator($translate);
        return $this;        
    }
}
