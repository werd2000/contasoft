<?php   
require_once 'Zend/Form.php';
require_once 'App/LibQ/Config.php';
require_once 'App/LibQ/Form/Decorator/IconoInformacion.php';
require_once LibQ . 'Zend/Locale.php';
require_once LibQ . 'Zend/Translate.php';

/**
 *  Clase para armar el formulario donde se cargan los sueldos
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Sueldos
 */
class Form_CargaSueldos extends Zend_Form
{
    private $_empleados = array();
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
    
    function __construct($empleados, $sueldo = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 
                             'App/LibQ/Form/Decorator',
                             'decorator');
        $this->addPrefixPath('App_LibQ_ZendX_JQuery_Form_Decorator', 
                             'App/LibQ/ZendX/JQuery/Form/Decorator',
                             'decorator');
        $this->_config = Config::singleton();
        $this->_empleados = $empleados;
        $this->_varForm = $sueldo;
        parent::__construct();
    }
    
    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorEmpleado = $this->_varForm['empleado'];
            $valorPeriodo_pago = $this->_varForm['periodo_pago'];
            $valorNro_recibo = $this->_varForm['nro_recibo'];
            $valorRemuneracion_gravada = $this->_varForm['remuneracion_gravada'];
            $valorRemuneracion_nogravada = $this->_varForm['remuneracion_nogravada'];
            $valorDescuentos = $this->_varForm['descuentos'];
            $valorTotal = $this->_varForm['total'];
            $valorEliminado = $this->_varForm['eliminado'];            
        }else{
            $valorId = '';
            $valorEmpleado = '';
            $valorPeriodo_pago = '';
            $valorNro_recibo = '';
            $valorRemuneracion_gravada = '';
            $valorRemuneracion_nogravada = '';
            $valorDescuentos = '';
            $valorTotal = '';
            $valorEliminado = '';                        
        }
        if ($valorId == 0){
            $this->setAction('index.php?option=sueldos&sub=agregar');
        }else{
            $this->setAction('index.php?option=sueldos&sub=editar&id='.$valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmsueldos');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$valorId));
        /** Empleados **/
        $empleado = $this->createElement('select',
        				'empleado',
                                       array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorEmpleado));
        $empleado->setLabel('Empleados:');
        $empleado->setRequired(true);
        $empleado->setOptions(array('multiOptions' => $this->_empleados));
        /** Período de Pago **/
        $periodo_pago = $this->createElement('text', 'periodo_pago',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorPeriodo_pago));
        $periodo_pago->setLabel('Periodo Pago:');
        $periodo_pago->setRequired(true);
        /** Recibo N�mero **/
        $nro_recibo = $this->createElement('text', 'nro_recibo',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorNro_recibo));
        $nro_recibo->setLabel('Recibo Numero:');
        $nro_recibo->setRequired(true);
//        $nro_recibo->addValidator('Alnum');
//        $nro_recibo->addValidator('StringLength', false, array(12 , 12));
        /** Remuneraci�n Gravada **/
        $remuneracion_gravada = $this->createElement('text', 'remuneracion_gravada',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorRemuneracion_gravada));
        $remuneracion_gravada->setLabel('Remuneracion Gravada:');
        $remuneracion_gravada->setRequired(true);
        /** Remuneraci�n No Gravada **/
        $remuneracion_nogravada = $this->createElement('text', 'remuneracion_nogravada',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorRemuneracion_nogravada));
        $remuneracion_nogravada->setLabel('Remuneracion no Gravada:');
        $remuneracion_nogravada->setRequired(true);
        /** Descuentos **/
        $descuentos = $this->createElement('text', 'descuentos',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorDescuentos));
        $descuentos->setLabel('Descuentos:');
        $descuentos->setRequired(true);
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
        $this->addElement($empleado);
        $this->addElement($periodo_pago);
        $this->addElement($nro_recibo);
        $this->addElement($remuneracion_gravada);
        $this->addElement($remuneracion_nogravada);
        $this->addElement($descuentos);
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
