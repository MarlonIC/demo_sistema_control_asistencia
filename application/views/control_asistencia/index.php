<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
    <title>:: MAHARASPERU - Sistema de Gestión de Planilla ::</title>
	<link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/estilos.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/form.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/reloj.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/nav.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/pagination.css">
    <!--[if lte IE 9]><script src="<?php echo base_url();?>js/html5.js"></script><![endif]-->
    <script src="<?php echo base_url();?>js/jquery-1.9.1.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url();?>js/form.js"></script>
    
	<script>
	function digiClock() {
		var vDate = new Date();
		var vHora = vDate.getHours();
		var vMin  = vDate.getMinutes();
		var vSeg  = vDate.getSeconds();
		vMin  =  ( vMin < 10 ? "0" : "") + vMin;
		vSeg  =  ( vSeg < 10 ? "0" : "") + vSeg;
		var vAMPM = ( vHora < 12) ? "a.m" : "p.m.";
		vHora  = ( vHora < 10 ? "0" : "") + vHora;

		var vHoraSistema = vHora + ":" + vMin + ":" + vSeg + " " + vAMPM;
		$("#clock").html(vHoraSistema);
	}

	$(document).ready(function(){
		setInterval("digiClock()",1000);
		var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
		var f=new Date();
		document.getElementById('fecha').innerHTML = diasSemana[f.getDay()] + ", " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
	});

	function entrada() {
		var text_dni = trim(document.getElementById('text_dni').value);
				
		$.ajax({
			async: false,
			type: "POST",
			url: '<?php echo base_url().'control_asistencia/entrada_ajax';?>',
			data: 'text_dni='+ text_dni,
            success: function(response){
            	var datos = JSON.parse(response);
            	if(datos.estado == 'marcado') {
            		alert(datos.mensaje);
            		var hora_entrada = datos.result2[0].coa_entrada;
            		var hora_salida = datos.result2[0].coa_salida;
            		var observacion = datos.result2[0].coa_observaciones;
            		var foto = datos.result[0].per_foto;
            		if(foto == null) {
            			foto = "noimage.gif";
            		}
            		var url = "<?php echo base_url()."/files/"; ?>";
            		$("#imagencarnet").attr("src",url+foto);

            		document.getElementById('marc_entrada').innerHTML = hora_entrada;
            		document.getElementById('marc_salida').innerHTML = hora_salida;
            		document.getElementById('observacion').innerHTML = observacion;
            	} 
            	if(datos.estado == 'sinmarcado') {
            		var hora_entrada = datos.result2[0].coa_entrada;
            		var hora_salida = datos.result2[0].coa_salida;
            		var observacion = datos.result2[0].coa_observaciones;
            		var foto = datos.result[0].per_foto;
            		if(foto == null) {
            			foto = "noimage.gif";
            		}
            		var url = "<?php echo base_url()."/files/"; ?>";
            		$("#imagencarnet").attr("src",url+foto);
            		document.getElementById('marc_entrada').innerHTML = hora_entrada;
            		document.getElementById('marc_salida').innerHTML = hora_salida;
            		document.getElementById('observacion').innerHTML = observacion;
            	} 
            	if(datos.estado == 'error') {
            		alert(datos.mensaje);
            		var url = "<?php echo base_url()."/files/noimage.gif"; ?>";
            		$("#imagencarnet").attr("src",url);
            		document.getElementById('marc_entrada').innerHTML = '';
            		document.getElementById('marc_salida').innerHTML = '';
            		document.getElementById('observacion').innerHTML = '';
            	}
            } 
        });
	}

	function salida() {
		var text_dni = trim(document.getElementById('text_dni').value);
				
		$.ajax({
			async: false,
			type: "POST",
			url: '<?php echo base_url().'control_asistencia/salida_ajax';?>',
			data: 'text_dni='+ text_dni,
            success: function(response){
            	var datos = JSON.parse(response);
            	if(datos.estado == 'marcado') {
            		alert(datos.mensaje);
            		var hora_entrada = datos.result2[0].coa_entrada;
            		var hora_salida = datos.result2[0].coa_salida;
            		var observacion = datos.result2[0].coa_observaciones;
            		var foto = datos.result[0].per_foto;
            		if(foto == null) {
            			foto = "noimage.gif";
            		}
            		var url = "<?php echo base_url()."/files/"; ?>";
            		$("#imagencarnet").attr("src",url+foto);
            		document.getElementById('marc_entrada').innerHTML = hora_entrada;
            		document.getElementById('marc_salida').innerHTML = hora_salida;
            		document.getElementById('observacion').innerHTML = observacion;
            	} 
            	if(datos.estado == 'sinmarcado') {
            		var hora_entrada = datos.result2[0].coa_entrada;
            		var hora_salida = datos.result2[0].coa_salida;
            		var observacion = datos.result2[0].coa_observaciones;
            		var foto = datos.result[0].per_foto;
            		if(foto == null) {
            			foto = "noimage.gif";
            		}
            		var url = "<?php echo base_url()."/files/"; ?>";
            		$("#imagencarnet").attr("src",url+foto);
            		document.getElementById('marc_entrada').innerHTML = hora_entrada;
            		document.getElementById('marc_salida').innerHTML = hora_salida;
            		document.getElementById('observacion').innerHTML = observacion;
            	} 
            	if(datos.estado == 'error') {
            		alert(datos.mensaje);
            		var url = "<?php echo base_url()."/files/noimage.gif"; ?>";
            		$("#imagencarnet").attr("src",url);
            		document.getElementById('marc_entrada').innerHTML = '';
            		document.getElementById('marc_salida').innerHTML = '';
            		document.getElementById('observacion').innerHTML = '';
            	}
            } 
        });
	}
	</script>
</head>
<body>
<?php echo form_open('control_asistencia/index'); ?>
<header>
	<?php echo $header; ?>
</header>
<nav>
	<?php echo $nav; ?>
</nav>
<section>
<div id="contenido">
	<h1 style="color: #0E417A;"><u><center>SISTEMA  MAHARASPERU E.I.R.L</center></u></h1>
	<div class="general">
		<div id="clock"></div>
		<div id="fecha"></div>
	</div>
	<div style="display:inline;">
		<div class="image">
			<img id="imagencarnet" src="<?php echo base_url() ?>/images/noimage.gif" width="93px" height="95px">
		</div>
		<p class="text_foto">Foto</p>
	</div>

	<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:100%;margin-top:20px;">
		<tr>
			<th>ENTRADA</th>
			<th>SALIDA</th>
			<th>MARCA ENT.</th>
			<th>MARCA SAL.</th>
			<th>OBS</th>
		</tr>
		<tr style="text-align:center;font-weight:bold;">
			<td>08:00</td>
			<td>19:00</td>
			<td id="marc_entrada">&nbsp;</td>
			<td id="marc_salida">&nbsp;</td>
			<td id="observacion">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<br><br>
	<table>
		<tr>
			<td><span style="font-size: 14px;padding-left: 300px;padding-right: 10px">Ingresa DNI:</span></td>
			<td>
				<input type="text" name="text_dni" id="text_dni" style="margin-left: 10px;margin-right: 40px;height: 20px;text-align: center;font-weight: bold;font-size: 14px;">
			</td>
			<td>
				<input type="button" value="Entrar" onclick="entrada()" class="button" style="margin-right: 10px;padding: 10px;">
			</td>
			<td>
				<input type="button" value="Salir" onclick="salida()" class="button" style="margin-right: 10px;padding: 10px;">
			</td>
		</tr>
	</table>
</div>
</section>
<footer>
	<?php echo $footer; ?>
</footer>
<?php echo form_close(); ?>
</body>
</html>