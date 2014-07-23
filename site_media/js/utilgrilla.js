var x;
x = $(document);
x.ready(inicializarEventos);

function inicializarEventos() {
	var x = $('a[role|="link"]');
	x.click(ocultarGrilla);
	x = $('tr[role|="row"]');
	x.hover(pintarFilaIn,pintarFilaOut);
	x.dblclick(clickFila);
}

function ocultarGrilla() {
	jQuery("#grilla").fadeToggle(400);
	var vcss = $('span[class="ui-icon ui-icon-circle-triangle-n"]').length;
	if (vcss == 1){
		$('span[class="ui-icon ui-icon-circle-triangle-n"]').removeClass('ui-icon ui-icon-circle-triangle-n').addClass('ui-icon ui-icon-circle-triangle-s');
	}else{
		$('span[class="ui-icon ui-icon-circle-triangle-s"]').removeClass('ui-icon ui-icon-circle-triangle-s').addClass('ui-icon ui-icon-circle-triangle-n');
	}
}

function pintarFilaIn() {
	$(this).addClass('ui-state-hover');
}

function pintarFilaOut() {
	$('tr[role|="row"]').removeClass('ui-state-hover');
}

function clickFila() {
	x = ($(this).attr('id'));
	url = document.URL.split('&', 1) ;
//	alert (document.URL);
	window.location= url + '&sub=editar&id=' + x;
}


