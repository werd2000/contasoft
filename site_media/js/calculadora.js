var x;
x=$(document);
x.ready(inicializarEventos);

function inicializarEventos()
{
  var x=$("#Calculadora");
  x.click(mostrar_calculadora);
  var x=$("#Calculadora_Off");
  x.click(ocultar_calculadora);
}

function ocultar_calculadora()
{
  var x=$("#caja_calculadora");
  x.hide("slow");		//oculto la Calculadora
}

function mostrar_calculadora()
{
  var x=$("#caja_calculadora");
  x.slideDown("slow");	//muestro la Calculadora
}

	var displayText = "";
	var num1;
	var num2;
	var operatorType;
	// Write to display;
	function addDisplay(n){
	document.calc.display.value = "";
	displayText += n;
	document.calc.display.value = displayText;
	}
	
	// Addition;
	function addNumbers() {
	if (displayText == "") {
	 displayText = result;
	 }
	num1 = parseFloat(displayText);
	operatorType = "add";
	displayText = "";
	}
	
	// Subtraction;
	function subtractNumbers() {
	if (displayText == "") {
	 displayText = result;
	 }
	num1 = parseFloat(displayText);
	operatorType = "subtract";
	displayText = "";
	}
	
	// Multiplication;
	function multiplyNumbers() {
	if (displayText == "") {
	 displayText = result;
	 }
	num1 = parseFloat(displayText);
	operatorType = "multiply";
	displayText = "";
	}
	
	// Division;
	function divideNumbers() {
	if (displayText == "") {
	 displayText = result;
	 }
	num1 = parseFloat(displayText);
	operatorType = "divide";
	displayText = "";
	}
	
	// Sine;
	function sin() {
	if (displayText == "") {
	 num1 = result;
	 }else{
	 num1 = parseFloat(displayText);
	 }
	if (num1 != "") {
	 result = Math.sin(num1);
	 document.calc.display.value = result;
	 displayText = "";
	 }else {
	 alert("Please write the number first");
	 }
	}
	
	// Cosine;
	function cos() {
	if (displayText == "") {
	 num1 = result;
	 }	else {
	 num1 = parseFloat(displayText);
	 }
	if (num1 != "") {
	 result = Math.cos(num1);
	 document.calc.display.value = result;
	 displayText = "";
	 }	else {
	 alert("Please write the number first");
	 }
	}
	
	// ArcSine;
	function arcSin() {
	if (displayText == "") {
	 num1 = result;
	 }	else {
	 num1 = parseFloat(displayText);
	 }
	if (num1 != "") {
	 result = Math.asin(num1);
	 document.calc.display.value = result;
	 displayText = "";
	 }	else {
	 alert("Please write the number first");
	 }
	}
	
	// ArcCosine;
	function arcCos() {
	if (displayText == "") {
	 num1 = result;
	 }	else {
	 num1 = parseFloat(displayText);
	 }
	if (num1 != "") {
	 result = Math.acos(num1);
	 document.calc.display.value = result;
	 displayText = "";
	 }	else {
	 alert("Please write the number first");
	 }
	}
	
	// Square root;
	function sqrt() {
	if (displayText == "") {
	 num1 = result;
	 }	else {
	 num1 = parseFloat(displayText);
	 }
	if (num1 != "") {
	 result = Math.sqrt(num1);
	 document.calc.display.value = result;
	 displayText = "";
	 }	else {
	 alert("Please write the number first");
	 }
	}
	
	// Square number (number to the power of two);
	function square() {
	if (displayText == "") {
	 num1 = result;
	 }	else {
	 num1 = parseFloat(displayText);
	 }
	if (num1 != "") {
	 result = num1 * num1;
	 document.calc.display.value = result;
	 displayText = "";
	 }	else {
	 alert("Please write the number first");
	 }
	}

	// Convert degrees to radians;
	function degToRad() {
	if (displayText == "") {
	 num1 = result;
	 }	else {
	 num1 = parseFloat(displayText);
	 }
	if (num1 != "") {
	 result = num1 * Math.PI / 180;
	 document.calc.display.value = result;
	 displayText = "";
	 }	else {
	 alert("Please write the number first");
	 }
	}
	
	// Convert radians to degrees;
	function radToDeg() {
	if (displayText == "") {
	 num1 = result;
	 }	else {
	 num1 = parseFloat(displayText);
	 }
	if (num1 != "") {
	 result = num1 * 180 / Math.PI;
	 document.calc.display.value = result;
	 displayText = "";
	 }	else {
	 alert("Please write the number first");
	 }
	}
	
	// Calculations;
	function calculate() {
	if (displayText != "") {
	 num2 = parseFloat(displayText);
	// Calc: Addition;
	 if (operatorType == "add") {
	 result = num1 + num2;
	 document.calc.display.value = result;
	 }
	// Calc: Subtraction;
	 if (operatorType == "subtract") {
	 result = num1 - num2;
	 document.calc.display.value = result;
	 }
	// Calc: Multiplication;
	 if (operatorType == "multiply") {
	 result = num1 * num2;
	 document.calc.display.value = result;
	 }
	// Calc: Division;
	 if (operatorType == "divide") {
	 result = num1 / num2;
	 document.calc.display.value = result;
	 }
	 displayText = "";
	 }	 else {
	 document.calc.display.value = "Oops! Error!";
	 }
	}
	
	// Clear the display;
	function clearDisplay() {
	displayText = "";
	document.calc.display.value = "";
	}
