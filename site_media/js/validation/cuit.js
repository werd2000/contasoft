function validaCuit(cuit) {
	if (typeof (cuit) == 'undefined')
		return true;

	cuit = cuit.toString().replace(/[-_]/g, "");
	if (cuit == '')
		return true; // No estamos validando si el campo esta vacio, eso
						// queda para el "required"

	if (cuit.length != 11)
		return false;
	else {
		var mult = [ 5, 4, 3, 2, 7, 6, 5, 4, 3, 2 ];
		var total = 0;
		for ( var i = 0; i < mult.length; i++) {
			total += parseInt(cuit[i]) * mult[i];
		}

		var mod = total % 11;
		var digito = mod == 0 ? 0 : mod == 1 ? 9 : 11 - mod;
	}

	return digito == parseInt(cuit[10]);
}

$.validator.addMethod("cuit", validaCuit, 'CUIT/CUIL Inválido');

$.validator.addMethod("nro_comprobante", !!function() { 
	var num_comp = $("#nro_comprobante").val();
	var cod_prov = $("#proveedor").val();
	var comprob = $("#comprobante").val();
	var tipo_comprob = $("#tipoComprobante").val();
	$.get("index2.php?option=controlar_nro_comprobante&nro_comprobante=num_comp&proveedor=cod_prov&comprobante=comprob&tipoComprobante=tipo_comprob",
	      data);
}, 'El número de comprobante ya existe'); 
