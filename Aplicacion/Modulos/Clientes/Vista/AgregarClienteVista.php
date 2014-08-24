<?php
require_once 'Zend/Form.php';


/** Desde aqu� contenido propio del men� **/
echo $this->LibQ_BarraHerramientas;
echo $this->mensajes;
echo $this->form->render($this);
/** Fin del contenido del menu  **/
