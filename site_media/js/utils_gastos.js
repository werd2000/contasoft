var x;
x = $(document);
x.ready(inicializarEventos);

function inicializarEventos() {
	var x = $("#tipo_comprobante");
	x.change(controlar_comprobante);
	x = $("#importe_gravado");
	x.keyup(calcularTotal);
	x = $("#iva_inscripto");
	x.keyup(calcularTotal);
	x = $("#iva_diferencial");
	x.keyup(calcularTotal);
	x = $("#percepcion");
	x.keyup(calcularTotal);
	x = $("#importe_nogravado");
	x.keyup(calcularTotal);
	$("#cuenta").bind('change', comboCuenta);
	x = $("#bsdata");
	x.click(buscar);
}

function buscar() {
	jQuery("#gastos").jqGrid('searchGrid', {
		sopt : [ 'cn', 'bw', 'eq', 'ne', 'lt', 'gt', 'ew' ]
	});
}

$(function() {
    $("#fecha_comprobante").datepicker({
    showOn: 'both',
//    buttonImage: 'calendar.png',
//    buttonImageOnly: true,
    changeYear: true,
//    numberOfMonths: 2,
    onSelect: function(textoFecha, objDatepicker){
    $("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
   }
});
});

$(function() {
    $("#periodo_pago").datepicker({
    showOn: 'both',
    changeYear: true,
    onSelect: function(textoFecha, objDatepicker){
    $("#mensaje").html("<p>Has seleccionado: " + textoFecha + "</p>");
   }
});
});

function comboCuenta() {
	var x = $("#cuenta");
	var value = x.val();
	switch (value) {
	case '7':
		//$.post("http://localhost/zcontasoft/ajaxgastos.php?cuenta=" + value,
		$.post("http://www.pequehogar.com.ar/contasoft/ajaxgastos.php?cuenta=" + value,
				function(datos) {
					// $.post("http://www.pequehogar.com.ar/contasoft/ajaxgastos.php?cuenta="
					// + value , function(datos){
				$("#proveedor").empty();
				$("#proveedor").append(datos);
			});
		// var padre1 = $("#importe_gravado").parent(); //oculto el importe
		// gravado
		// var padre2 = padre1.parent().css("display","none");
		var padre1 = $("#importe_nogravado").parent(); // oculto el importe
														// gravado
		var padre2 = padre1.parent().css("display", "none");
		padre1 = $("#iva_inscripto").parent(); // oculto el importe gravado
		padre2 = padre1.parent().css("display", "none");
		padre1 = $("#iva_diferencial").parent(); // oculto el importe
														// gravado
		padre2 = padre1.parent().css("display", "none");
		padre1 = $("#percepciones").parent(); // oculto el importe gravado
		padre2 = padre1.parent().css("display", "none");
		$("#comprobante").empty();
		$("#comprobante").append("<option value=RECIBO>RECIBO</option>");
		$("#tipoComprobante").empty();
		$("#tipoComprobante").append("<option value=S>S</option>");
		$("#iva_inscripto").val(0);
		break;
	case '1':
	case '2':
	case '3':
	case '4':
	case '5':
	case '6':
	case '16':
		//$.post("http://localhost/zcontasoft/ajaxgastos.php?cuenta=" + value,
		$.post("http://www.pequehogar.com.ar/contasoft/ajaxgastos.php?cuenta=" + value,
				function(datos) {
					// $.post("http://www.pequehogar.com.ar/contasoft/ajaxgastos.php?cuenta="
					// + value , function(datos){
				$("#proveedor").empty();
				$("#proveedor").append(datos);
			});
		padre1 = $("#importe_nogravado").parent(); // oculto el importe
														// gravado
		padre2 = padre1.parent().css("display", "none");
		padre1 = $("#iva_inscripto").parent(); // oculto el importe gravado
		padre2 = padre1.parent().css("display", "none");
		$("#iva_inscripto").val(0);
		break;
	default:

	}

}

function calcularTotal() {
        var importe_gravado = controldevalor($("#importe_gravado").val());
	var iva_inscripto = controldevalor($("#iva_inscripto").val());
	var iva_diferencial = controldevalor($("#iva_diferencial").val());
	var percepciones = controldevalor($("#percepcion").val());
	var importe_nogravado = controldevalor($("#importe_nogravado").val());
	var total = importe_gravado + iva_inscripto + iva_diferencial
			+ percepciones + importe_nogravado;
	$("#total").val(total);
}

function controldevalor(valor) {
	if (valor == '') {
		var resultado = 0;
	} else {
		var resultado = parseFloat(valor);
	}
	return resultado;
}

function controlar_comprobante() {
	var tc = $("#tipo_comprobante").val();
	if (tc != 'A') {
		$("#importe_gravado").val('0');
                $("#importe_gravado").parent().css("display", "none");
		$("#iva_inscripto").val('0');
                $("#iva_inscripto").parent().css("display", "none"); // oculto el iva_inscripto
		$("#iva_diferencial").parent().css("display", "none"); // oculto el
		$("#percepcion").parent().css("display", "none"); // oculto percepciones
		$("#importe_nogravado").parent().css("display", "none"); // oculto percepciones
	} else {
		$("#importe_gravado").parent().css("display", "");
		$("#iva_inscripto").parent().css("display", "");
		$("#iva_diferencial").parent().css("display", ""); // muestro el
		$("#percepcion").parent().css("display", ""); // muestro percepciones
		$("#importe_nogravado").parent().css("display", ""); // muestro percepciones
	}
}
