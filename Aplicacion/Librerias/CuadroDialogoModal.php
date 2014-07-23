<?php

/*
 * Dibuja un cuadro de diálogo modal
 */

/**
 * Description of CuadroDialogoModal
 *
 * @author Walter Ruiz Diaz
 */

class CuadroDialogoModal {
    
    function __construct() {
        $retorno  = '<a href="#dialog" name="modal">Simple Modal Window</a>';
        $retorno .= '<div id="boxes">';
    /* #customize your modal window here */
        $retorno .= '<div id="dialog" class="window">';
        $retorno .= '<b>Testing of Modal Window</b> | ';
        /* -- close button is defined as close class */
        $retorno .= '<a href="#" class="close">Close it</a>';
        $retorno .= '</div>';
     
        /*Do not remove div#mask, because you'll need it to fill the whole screen */
        $retorno .= '<div id="mask"></div>';
        $retorno .= '</div>';
    }
    
    function render(){
        
    }
}

?>
