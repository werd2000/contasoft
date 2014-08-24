<?php
/** Muestra el inicio del Html hasta el menu **/
//echo $this->partial('HtmlVista.php');
/** Desde aqui contenido propio del login **/
echo "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
echo "<div id=\"ingresar\" class=\"ui-corner-all\">\n";
echo "<div class=\"titulo ui-corner-all\">\n";
echo "<span style=\"line-height: 24px;\">".$this->escape($this->contenido)."</span>\n";
echo "</div>\n";
echo "<div class=\"formulario\">\n";
echo '<form action="index.php?option=login&sub=validarLogin" method="post">';
echo "<div class=\"Fila1Col\"><label><u>N</u>ombre de usuario: </label>" . $this->formText('username', '', array('size' => 15)) . "</div>\n";
echo "<div class=\"Fila1Col\"><label><u>P</u>assword: </label>" . $this->formPassword('password', '', array('size' => 15)) . "</div>\n";
echo "<div class=\"Fila1Col\">".$this->formSubmit('login','Ingresar') .'</div>';
echo '</form>';
echo '</div>';
echo '</div>';
/** Fin del contenido del login **/
/** Muestra el final del Html **/
//echo $this->partial('FinHtml.php');