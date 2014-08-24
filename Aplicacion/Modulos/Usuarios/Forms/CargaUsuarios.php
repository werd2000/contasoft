<?php
require_once LibQ . 'Zend/Form.php';
require_once LibQ . 'Form/Decorator/IconoInformacion.php';

/**
 *  Clase para armar el formulario donde se cargan los usuarios
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Usuarios
 */
class Form_CargaUsuarios extends Zend_Form
{
    private $_varForm = array();
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
    
    function __construct($usuarios = null)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 'App/LibQ/Form/Decorator', 'decorator');
        $this->addPrefixPath('App_LibQ_ZendX_JQuery_Form_Decorator', 'App/LibQ/ZendX/JQuery/Form/Decorator', 'decorator');       
        $this->_varForm = $usuarios;
        parent::__construct();
    }
    
    public function mostrar()
    {
        $categoria_usuario = array('' => '', 'Administrador' => 'Administrador', 'Usuario' => 'Usuario', 'Consultor' => 'Consultor');
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorNombre = $this->_varForm['nombre'];
            $valorUserName = $this->_varForm['userName'];
            $valorPassword = $this->_varForm['password'];
            $valorCategoria = $this->_varForm['categoria'];
            $valorBloqueado = $this->_varForm['bloqueado'];
            $valorEnviarMail = $this->_varForm['enviarMail'];
            $valorActivo = $this->_varForm['activo'];
            $valorEmail = $this->_varForm['email'];
        }else{
            $valorId = '';
            $valorNombre = '';
            $valorUserName = '';
            $valorPassword = '';
            $valorCategoria = '';
            $valorBloqueado = '';
            $valorEnviarMail = '';
            $valorActivo = '';
            $valorEmail = '';
        }
        if ($valorId == 0) {
            $this->setAction('index.php?option=usuarios&sub=agregar');
        } else {
            $this->setAction('index.php?option=usuarios&sub=editar&id=' . $valorId);
        }
        $this->setAttrib('id', 'frmusuarios');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$valorId));
        /** nombre **/
        $nombre = $this->createElement('text', 'nombre',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorNombre));
        $nombre->setLabel('Nombre:');
        $nombre->setRequired(true);
        /** userName **/
        $userName = $this->createElement('text', 'userName',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorUserName));
        $userName->setLabel('Nombre de Usuario:');
        $userName->setRequired(true);
        /** password **/
        $password = $this->createElement('text', 'password',array('decorators' => $this->elementDecorators, 'value'=>$valorPassword));
        $password->setLabel('Password:');
//        $password->addValidator('Alnum');
        /** categoria **/
        $categoria = $this->createElement('select', 'categoria',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorCategoria));
        $categoria->setOptions(array('multiOptions' => $categoria_usuario));
        $categoria->setLabel('Tipo de Usuario:');
        $categoria->setRequired(true);
        /** bloqueado **/
        $bloqueado = $this->createElement('Checkbox', 'bloqueado',array('decorators' => $this->elementDecorators, 'value'=>$valorBloqueado));
        $bloqueado->setLabel('Bloqueado:');
        /** enviarMail **/
        $enviarMail = $this->createElement('Checkbox', 'enviarMail',array('decorators' => $this->elementDecorators, 'value'=>$valorEnviarMail));
        $enviarMail->setLabel('Enviar E-Mail:');
        /** activo **/
        $activo = $this->createElement('Checkbox', 'activo',array('decorators' => $this->elementDecorators, 'value'=>$valorActivo));
        $activo->setLabel('Activo:');
        /** Email **/
        $email = $this->createElement('text', 'email',array('decorators' => $this->elementDecorators, 'value'=>$valorEmail));
        $email->setLabel('Email');
        $email->addValidator('EmailAddress');
        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($nombre);
        $this->addElement($userName);
        $this->addElement($password);
        $this->addElement($categoria);
        $this->addElement($bloqueado);
        $this->addElement($enviarMail);
        $this->addElement($activo);
        $this->addElement($email);
        
        return $this;        
    }
}
