// JavaScript Document

function fecha() {
  //obtiene fecha y hora
  var fecha = new Date();
  var Anio = fecha.getFullYear();
  var Mes = fecha.getMonth();
  var DiaActual = fecha.getDate();
  var Dia = fecha.getDay();

  //array nombres meses
  var mes = new Array();
  mes[0] = "Enero";
  mes[1] = "Febrero";
  mes[2] = "Marzo";
  mes[3] = "Abril";
  mes[4] = "Mayo";
  mes[5] = "Junio";
  mes[6] = "Julio";
  mes[7] = "Agosto";
  mes[8] = "Septiembre";
  mes[9] = "Octubre";
  mes[10] = "Noviembre";
  mes[11] = "Diciembre";
  
  //array nombres dias
  var dia = new Array();
  dia[0] = "Domingo";
  dia[1] = "Lunes";
  dia[2] = "Martes";
  dia[3] = "Mi&eacute;rcoles";
  dia[4] = "Jueves";
  dia[5] = "Viernes";
  dia[6] = "S&aacute;bado";
  
  //obtiene nombre mes
  Mes = mes[Mes];

  //obtiene nombre dia
  Dia = dia[Dia];
  
 
  //cadena final
  textoFecha = (Dia+", "+DiaActual+" de "+Mes+" de "+Anio);

  document.write (textoFecha);

}

