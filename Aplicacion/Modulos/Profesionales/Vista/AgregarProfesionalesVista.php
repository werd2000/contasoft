<?php
require_once 'Zend/Form.php';


/** Desde aquí contenido propio del menú **/
echo $this->LibQ_BarraHerramientas;
echo $this->mensajes;
echo $this->form->render($this);
/** Fin del contenido del menu  **/
