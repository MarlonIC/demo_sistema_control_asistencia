function checkAllBoxes(id, ochecked){
	var obj = document.getElementById(id);
	var inputs = obj.getElementsByTagName('input');
	for (i=0; i<inputs.length; i++){
		var input = inputs[i];		
		if(input.type == 'checkbox'){
			if(ochecked == true)
				input.checked = true;
			else
				input.checked = false;
		}
	}
}

function verifica(obj, mensaje) { //Valida si el campo presenta caracteres en blanco
	if (trim(obj.value)=="") {
		if (trim(mensaje)!="") {
			alert(mensaje);
			obj.focus();
			obj.select();
		}
		return false;
	}
	else {
		return true;
	}
}

function validaSeleccion(obj,valorSeleccionado) { //Retorna si esta seleccionado el valor indicado
	//obj = document.formulario.campoSelection
	if (obj) {
		if (obj.options[obj.selectedIndex].value==valorSeleccionado)
			return true;
		else
			return false;
	}
}

function msgErrorCamp(obj, EtiqCamp) {
	if (trim(EtiqCamp)!='') 
		alert(EtiqCamp);
	obj.focus();
	if (obj.type=='text') obj.select();
}

function objFormulario(frm,nombre) { //Permite obtener un objeto (campo) de un formulario basado en el nombre del objeto
	for (i=0; i<frm.length; i++){				
		/*if (frm.elements[i].name.indexOf(nombre) !=-1){	
			return frm.elements[i];
		}*/
		if (frm.elements[i].name == nombre){	
			return frm.elements[i];
		}
	}
	return null;
}

function trim(cadena){ //Retorna cadena sin espacion emblanco a los extremos	
	while (cadena.substr(0,1)==" ")
		cadena=cadena.substr(1);
	while (cadena.substr(cadena.length-1,1)==" ")
		cadena=cadena.substr(0,cadena.length-1);
	while (cadena.indexOf("  ")!=-1)
		cadena=cadena.replace("  "," "); 
	return(cadena); 
}

function getNewProperty(obj,propiedad){
	var valor=obj.getAttribute? obj.getAttribute(propiedad).toString() : ""
	return valor;
}

function verificaExisteProperty(obj,propiedad){
    if(obj.getAttribute(propiedad) == null){
        return false;
    }
    if(obj.getAttribute(propiedad) == false){
        return false;
    }
    return true;
}

function retornaValorRadioBotton(rbgroup){
	for (var i=0; i<rbgroup.length; i++){ 
		if (rbgroup[i].checked){ 
			return rbgroup[i].value; 					
		} 
	} 
	return '';
}

function retornaPosRadioBotton(rbgroup){
	for (var i=0; i<rbgroup.length; i++){ 
		if (rbgroup[i].checked){ 
			return i + 1; 					
		} 
	} 
	return '-1';
}

function resetRadioBotton(rbgroup){
	for (var i=0; i<rbgroup.length; i++){ 
		rbgroup[i].checked = false;
	}
}

function retornaValorCheckBox(chkgroup) { 
    var cadena = '';
	for(var i=0; i<chkgroup.length; i++){
		if(chkgroup[i].checked) {
			cadena += chkgroup[i].value + ",";
		}
	}
    if(cadena.length>0) 
        cadena = cadena.substring(0,cadena.length-1);
	return cadena;
}

function invFecha(nTipFormat,dFecIni){   
	var dFecIni = dFecIni.replace(/-/g,"/");        // reemplaza el - por /    
		
	//primera division fecha   
	var nPosUno  = ponCero(dFecIni.substr(0,dFecIni.indexOf("/")));   
	//2º divicion fecha   
	var nPosDos  = ponCero(dFecIni.substr(parseInt(dFecIni.indexOf("/")) + 1,parseInt(dFecIni.lastIndexOf("/")) - parseInt(dFecIni.indexOf("/")) - 1));   
	//3º divicion fecha   
	var nPosTres = ponCero(dFecIni.substr(parseInt(dFecIni.lastIndexOf("/")) + 1));   
	
	switch(nTipFormat){   
		case 1 :    //  YYYY/MM/DD   
			dReturnFecha = nPosTres + "" + nPosDos + "" + nPosUno;   
			break;
		case 2 :    //  YYYY/DD/MM   
			dReturnFecha = nPosTres + "" + nPosUno + "" +nPosDos;   
			break;
		case 3 :    //  DD/MM/YYYY   
			dReturnFecha = nPosUno + "" + nPosDos + "" +nPosTres;   
			break;
		case 4 :    //  DD/YYYY/MM   
			dReturnFecha = nPosUno + "" + nPosTres + "" +nPosDos;   
			break;   
	}   
		
	return dReturnFecha;    // retorna la fecha        
}

function ponCero(strPon){   
	if(parseInt(strPon.length) < 2)   
		strPon = "0" + strPon;   
	return strPon;   
}

function formatFecha(oElement){
	var fecha = oElement.value;
	var fechaarr = fecha.split("/");
	
	dia = fechaarr[0];
	mes = fechaarr[1];
	anio = fechaarr[2];
	
	if (mes.length == 1)   
		mes = '0' + mes;   
	if (dia.length == 1)   
		dia = '0' + dia;
		
	return dia + "/" + mes + "/" + anio;
}

function validar_Fecha(fecha){   
	if (fecha != undefined && fecha.value != "" ){ 
		if (fecha.value.split("/").length - 1 != 2){
			fecha.focus();
			alert("Fecha introducida errónea"); 
			return false; 			
		}
		if (!/^\d{2}\/\d{2}\/\d{4}$/.test(fecha.value)){ 
			fecha.value = formatFecha(fecha);
		} 
		
		var dia = parseInt(fecha.value.substring(0,2),10); 
		var mes = parseInt(fecha.value.substring(3,5),10); 
		var anio = parseInt(fecha.value.substring(6),10); 
		switch(mes){ 
			case 1: 
			case 3: 
			case 5: 
			case 7: 
			case 8: 
			case 10: 
			case 12: 
				numDias=31; 
				break; 
			case 4: case 6: case 9: case 11: 
				numDias=30; 
				break; 
			case 2: 
				if (comprobarSiBisisesto(anio)){ numDias=29 }else{ numDias=28}; 
				break; 
			default: 
				fecha.focus();
				alert("Fecha introducida errónea"); 
				return false; 
		} 
		if (dia>numDias || dia==0){ 
			fecha.focus();
			alert("Fecha introducida errónea"); 
			return false; 
		} 
		return true; 
	}
} 	

function comprobarSiBisisesto(anio){ 
	if (( anio % 100 != 0) && ((anio % 4 == 0) || (anio % 400 == 0)))
		return true; 	
	else
		return false; 
} 

var formatoFecha= 1;
function compareFechas(fecha_inicio, fecha_fin, mensaje){
	fecha_inicio = invFecha(formatoFecha, fecha_inicio);   
	fecha_fin = invFecha(formatoFecha, fecha_fin);   
				
	if(fecha_inicio > fecha_fin){
		alert(mensaje);
		return false;
	}
	return true
}
        
function soloLetras(e){
	key = e.keyCode || e.which;
	tecla = String.fromCharCode(key).toLowerCase();
	letras = " áéíóúabcdefghijklmnñopqrstuvwxyz0123456789";
	especiales = [8,37,39,46];
	
	tecla_especial = false
	for(var i in especiales){
		if(key == especiales[i]){
			tecla_especial = true;
			break;
		}
	}
	
	if(letras.indexOf(tecla)==-1 && !tecla_especial){
		return false;
	}
}
        
function soloNumeros(e){
	key = e.keyCode || e.which;
	tecla = String.fromCharCode(key).toLowerCase();
	letras = "0123456789";
	especiales = [8,37,39];
	
	tecla_especial = false
	for(var i in especiales){
		if(key == especiales[i]){
			tecla_especial = true;
			break;
		}
	}
	
	if(letras.indexOf(tecla)==-1 && !tecla_especial){
		return false;
	}
}
        
function soloDecimales(obj, e){
	key = e.keyCode || e.which;
	tecla = String.fromCharCode(key).toLowerCase();
	letras = "0123456789";
	especiales = [8,37,39,46];
	
	tecla_especial = false
	for(var i in especiales){
		if(key == especiales[i]){
			tecla_especial = true;
			break;
		}
	}
	
	if(letras.indexOf(tecla)==-1 && !tecla_especial){
		return false;
	}
	
	if(key == 46){//si presiono el 'punto'
		var contador = 0;
		var longitud = obj.value.length;
		for(var i=0; i<longitud; i++){
			if(obj.value[i] == '.') contador += 1;
		}
		if (contador > 0) return false;
	}	
}

function adicionarFuncionJS(obj, for_tipodato){
    switch(for_tipodato){
        case 'S':
            obj.onkeypress = function(){return soloLetras(event);} 
            break;
        case 'I':
            obj.onkeypress = function(){return soloNumeros(event);} 
            break; 
        case 'D':
            obj.onkeypress = function(){return soloDecimales(this, event);} 
            break;             
        default :
            //--
    }
}

function roundOff(value, precision)
{		
	value = "" + value //convert value to string
	precision = parseInt(precision);

	var whole = "" + Math.round(value * Math.pow(10, precision));

	var decPoint = whole.length - precision;

	if(decPoint != 0){
			result = whole.substring(0, decPoint);
			result += ".";
			result += whole.substring(decPoint, whole.length);
	}else{
			result = whole;
	}
	
	if (Math.abs(parseFloat(value))>0 && Math.abs(parseFloat(value))<1) {
		if (value.indexOf('-')=='-1') {
			if (result.indexOf('.')=='-1') 
				result='0.'+result;
			else 
				result='0'+result;
			
		}
		else {
			result='-0.'+result.replace('-.','');
		}
	}
	
	if (result=='.0' || result=='.00') result='0.00'
	
	return result;
}
		
function sleep(milliseconds) {
	var start = new Date().getTime();
	for (var i = 0; i < 1e7; i++) {
		if ((new Date().getTime() - start) > milliseconds){
			break;
		}
	}
}

function eliminaSeleccion(obj, valorEliminado) {
	if (obj) {
		for (i=0; i<obj.length; i++) {
			if (obj.options[i].value==valorEliminado) {
				obj.remove(i);
			}
		}
	}
}

function desabilitaSeleccion_radio(rbgroup){
	for (var i=0; i<rbgroup.length; i++){ 
		rbgroup[i].disabled = true;
	} 
}
		
function contarCaracteres(obj){
	var total_letras = obj.getAttribute('maxlength');
	var longitud = obj.value.length;
	var resto = total_letras - longitud;
	document.getElementById(obj.getAttribute('xlabel')).value = resto;
	if(resto <= 0){
		return false;
	}
}

function replaceAll(find, replace, str) {
  return str.replace(new RegExp(find, 'g'), replace);
}

function limpiar_espacios(obj){
	var valor = obj.value;
	obj.value = valor.replace(' ','');
}

function enlace_pagina(url){
	var a = document.createElement('a');
    a.href = url;
    a.target = '_blank';
    document.body.appendChild(a);
    a.click();
}

var normalize = (function() {
  var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç", 
      to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
      mapping = {};
 
  for(var i = 0, j = from.length; i < j; i++ )
      mapping[ from.charAt( i ) ] = to.charAt( i );
 
  return function( str ) {
      var ret = [];
      for( var i = 0, j = str.length; i < j; i++ ) {
          var c = str.charAt( i );
          if( mapping.hasOwnProperty( str.charAt( i ) ) )
              ret.push( mapping[ c ] );
          else
              ret.push( c );
      }      
      return ret.join( '' );
  }
 
})();

function obtiene_ValorDec(valor){
	if(valor=='') return 0;
	if(valor.indexOf(".")==-1) return 0;
	return valor.substring(valor.indexOf(".")+1, valor.length);
}