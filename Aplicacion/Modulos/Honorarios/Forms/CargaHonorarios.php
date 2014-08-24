<?php
require_once 'Zend/Form.php';
require_once 'App/LibQ/Config.php';
require_once 'App/LibQ/Form/Decorator/IconoInformacion.php';
require_once LibQ . 'Zend/Locale.php';
require_once LibQ . 'Zend/Translate.php';


class Form_CargaHonorarios extends Zend_Form
{
    private $_profesionales= array();
    private $_cuentas = array();
    private $_config;
    private $_varForm = array();
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

    public $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );
    
    function __construct($cuentas,$profesionales, $honorarios = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 
                             'App/LibQ/Form/Decorator',
                             'decorator');
        $this->_config = Config::singleton();
        $this->_cuentas = $cuentas;
        $this->_profesionales = $profesionales;
        $this->_varForm = $honorarios;
        parent::__construct();
    }
    
    public function mostrar()
    {
        $this->setMethod("POST");
        if ($this->_varForm['id']==0){
            $this->setAction('index.php?option=honorarios&sub=agregar');
        }else{
            $this->setAction('index.php?option=honorarios&sub=editar&id='.$this->_varForm['id']);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmhonorarios');
        /** Cuenta **/
        $cuenta = $this->createElement('select','cuentah',
                                       array('decorators' => $this->elementRequeridoDecorators,
                                             'value'=>$this->_varForm['cuenta']));
        $cuenta->setLabel('Cuenta:');
        $cuenta->setRequired(true);
        $cuenta->setOptions(array('multiOptions' => $this->_cuentas));
        /** Profesionales **/
        $profesionales = $this->createElement('select', 'profesional',
                                              array('decorators' => $this->elementRequeridoDecorators,
                                              'value'=>$this->_varForm['profesional']));
        $profesionales->setLabel('Profesionales:');
        $profesionales->setRequired(true);
        $profesionales->setOptions(array('multiOptions' => $this->_profesionales));
        /** Fecha de Compra **/
        $fecha_compra = $this->createElement('text', 'fecha_comprobante', array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['fecha_comprobante']));
        $fecha_compra->setLabel('Fecha:');
        $fecha_compra->setRequired(true);
        /** Comprobante **/
        $comprobante = $this->createElement('select', 'comprobante',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['comprobante']));
        $comprobante->setLabel('Comprobante:');
        $comprobante->setRequired(true);
        $comprobante->setOptions(array('multiOptions' => $this->_config->get('lista_de_comprobantes')));
        /** Tipo de comprobante **/
        $tipoComprobante = $this->createElement('select', 'tipo_comprobante',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['tipo_comprobante']));
        $tipoComprobante->setOptions(array('multiOptions' => $this->_config->get('tipos_de_comprobantes')));
        $tipoComprobante->setLabel('Tipo:');
        $tipoComprobante->setRequired(true);
        /** Número de comprobante **/
        $nro_comprobante = $this->createElement('text', 'nro_comprobante',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['nro_comprobante']));
        $nro_comprobante->setLabel('Nro:');
        $nro_comprobante->setRequired(true);
        $nro_comprobante->addValidator('Alnum');
        $nro_comprobante->addValidator('StringLength', false, array(12 , 12));
        /** Importe Gravado **/
        $importe_gravado = $this->createElement('text', 'importe_gravado',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['importe_gravado']));
        $importe_gravado->setLabel('Importe Neto Gravado:');
        $importe_gravado->setRequired(true);
        /** Importe No Gravado **/
        $importe_nogravado = $this->createElement('text', 'importe_nogravado',array('decorators' => $this->elementDecorators, 'value'=>$this->_varForm['importe_nogravado']));
        $importe_nogravado->setLabel('Importe Neto No Gravado:');
        /** Importe del IVA **/
        $iva_inscripto = $this->createElement('text', 'iva_inscripto',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['iva_inscripto']));
        $iva_inscripto->setLabel('IVA');
        $iva_inscripto->setRequired(true);
        /** Importe del IVA Diferencial **/
        $iva_diferencial = $this->createElement('text', 'iva_diferencial',array('decorators' => $this->elementDecorators, 'value'=>$this->_varForm['iva_diferencial']));
        $iva_diferencial->setLabel('IVA Dif.:');
        /** Importe de las percepciones **/
        $percepciones = $this->createElement('text', 'percepcion',array('decorators' => $this->elementDecorators, 'value'=>$this->_varForm['percepciones']));
        $percepciones->setLabel('Percepciones:');
        /** Importe Total **/
        $total = $this->createElement('text', 'total',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['total']));
        $total->setLabel('Total:');
        $total->setRequired(true);
                
        //Agrego todos los elementos
        $this->addElement($cuenta);
        $this->addElement($profesionales);
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
        /** establezco ubicación **/
        $local = new Zend_Locale();
        //creo un translate
        $translate = new Zend_Translate('array', 'App/LibQ/Idiomas/es/' . $local . '.php', 'es');
        //establezco el idioma del decorador
        $this->setDefaultTranslator($translate);
        return $this;        
    }
}
