/**
* Default function.  Usually would be overriden by the component
*/
function submitbutton(pressbutton) {
    submitform(pressbutton);
}

/**
* Submit the admin form
*/
function submitform(pressbutton){
    if (pressbutton=='Guardar') {
        document.forms[0].submit();
    }
    if (pressbutton=='Eliminar') {
        var modulo = getParameter('option');        
        var valor = getParameter('id');
        url = document.location.hostname;
        window.location = 'index.php?option=' + modulo + '&sub=eliminar&id=' + valor;
    }
    if (pressbutton=='filtrar') {
//        var modulo = getParameter('option');
//        var sub = getParameter('sub');
//        var x = $("#campo");
//        var campo = x.val();
//        var y = $("#valor");
//        var valor = y.val();        
//        url = document.location.hostname;
//        window.location = 'index.php?option=' + modulo + '&sub=' + sub + '&campo=' + campo + '&valor=' + valor;
        jQuery("#grilla").jqGrid('searchGrid',
		{sopt:['cn','bw','eq','ne','lt','gt','ew']}
	);
    }
    if (pressbutton=='verHistorialDocente') {
        var modulo = getParameter('option');
        var id = getParameter('id');
        
        url = document.location.hostname;
        window.location = 'index.php?option=' + modulo + '&sub=historial&idDocente=' + id;
    }
    if (typeof document.adminForm.onsubmit == "function") {
        document.adminForm.onsubmit();
    }
    document.adminForm.submit();
}

function getParameter(parameter){
    // Obtiene la cadena completa de URL
    var url = location.href;
    /* Obtiene la posicion donde se encuentra el signo ?, 
ahi es donde empiezan los parametros */
    var index = url.indexOf("?");
    /* Obtiene la posicion donde termina el nombre del parametro
e inicia el signo = */
    index = url.indexOf(parameter,index) + parameter.length;
    /* Verifica que efectivamente el valor en la posicion actual 
es el signo = */ 
    if (url.charAt(index) == "="){
        // Obtiene el valor del parametro
        var result = url.indexOf("&",index);
        if (result == -1){
            result=url.length;
        };
        // Despliega el valor del parametro
        return (url.substring(index + 1,result));
    }
} 

