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
    if (pressbutton=='filtrar') {
        var modulo = getParameter('option');
        var sub = getParameter('sub');
        var x = $("#campo");
        var campo = x.val();
        var y = $("#valor");
        var valor = y.val();
        
        url = document.location.hostname;
        window.location = 'index.php?option=' + modulo + '&sub=' + sub + '&campo=' + campo + '&valor=' + valor;
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

