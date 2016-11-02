<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
    <title>:: MAHARASPERU - Sistema de Control de Asistencia ::</title>
	<link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/normalize.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/estilos.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/form.css">
    <link rel="stylesheet" href="<?php echo base_url();?>simplemodal/css/simplemodal.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/nav.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo base_url();?>windowfiles/dhtmlwindow.css">
    <link rel="stylesheet" href="<?php echo base_url();?>modalfiles/modal.css">
    <!--[if lte IE 9]><script src="<?php echo base_url();?>js/html5.js"></script><![endif]-->
    <script src="<?php echo base_url();?>js/jquery-1.9.1.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
    <script src="<?php echo base_url();?>js/datepicker-init.js"></script>
    <script src="<?php echo base_url();?>js/messages_es.js"></script>
    <script src="<?php echo base_url();?>js/form.js"></script>
    <script>jQuery.noConflict();</script>
    <script>var base_url = '<?php echo base_url(); ?>';</script>
    <script src="<?php echo base_url();?>windowfiles/dhtmlwindow.js"></script>
    <script src="<?php echo base_url();?>modalfiles/modal.js"></script>
    <script src="<?php echo base_url();?>simplemodal/js/mootools-core-1.3.1.js"></script>
    <script src="<?php echo base_url();?>simplemodal/js/mootools-more-1.3.1.1.js"></script>
    <script src="<?php echo base_url();?>simplemodal/js/simple-modal.js"></script>
    <style type="text/css">
    	#tb_resultado tr td{
    		padding: 5px;
    		font-size: 15px;
    		font-weight: bold;
    	}
    </style>
    <script type="text/javascript">

    	function registrar(){
    		var obj = document.forms[0];
    		if (validaSeleccion(objFormulario(obj, 'coc_codigo'), '')) {
                msgErrorCamp(objFormulario(obj, 'coc_codigo'), 'Seleccione un correo corporativo');
                return false;
            }
            if (!verifica(objFormulario(obj,'consulta_text'),'Debe su consulta o reclamo')){
				return false;
			}

			var objcoc = document.getElementById('coc_codigo');
			var coc_codigo = objcoc.options[objcoc.selectedIndex].value;
			document.getElementById('h_coc_codigo').value = coc_codigo;

			document.forms[0].action = '<?php echo base_url().'consulta_reclamo/registrarsave'; ?>';
			document.forms[0].submit();
    	}

    	function cleartext() {
    		var objcoc = document.getElementById('coc_codigo');
			var coc_codigo = objcoc.options[objcoc.selectedIndex].value;
			document.getElementById('coc_codigo').value = '';
    		document.getElementById('consulta_text').value="";
    	}

    	function salirinterface() {
    		window.location = '<?php echo base_url().'/home'; ?>';
    	}
    </script>
</head>
<body>
<?php echo form_open('consulta_reclamo/index'); ?>
<header>
<?php echo $header; ?>
</header>
<nav>
<?php echo $nav; ?>
</nav>
<section>
<input type="hidden" value="<?php echo $result2->per_codigo; ?>" name="h_per_codigo" id="h_per_codigo">
<input type="hidden" name="h_coc_codigo" id="h_coc_codigo">

<div id="contenido">
<h1 style="color: #0E417A;"><u><center>SISTEMA  MAHARASPERU E.I.R.L</center></u></h1>
	<?php if (isset($mensaje_error)): ?>
        <div class="mensaje_error"><?php echo $mensaje_error; ?></div>
    <?php endif; ?>
	<center>
		<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" style="width:50%;margin-top:20px;">
			<tr>
				<td>Codigo</td>
				<td colspan="3">
					<?php echo $result2->per_codigo_empleado; ?>
				</td>
			</tr>
			<tr>
				<td>Usuario</td>
				<td colspan="3">
					<?php echo $result2->per_nombres_persona; ?>
				</td>
			</tr>
			<tr>
				<td>Para<span class="required" style="color:red;">*</span></td>
				<td colspan="3">
					<?php echo form_dropdown('coc_codigo', $Combo_CorreosCorporativos, set_value('coc_codigo', (isset($_POST['coc_codigo'])?$_POST['coc_codigo']:null)), 'id="coc_codigo"');?>
				</td>
			</tr>
			<tr>
				<td>Consulta<span class="required" style="color:red;">*</span></td>
				<td colspan="3">
					<textarea rows="12" cols="35" id="consulta_text" name="consulta_text"></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<?php echo form_input($data = array('id'=>'enviar', 'type'=>'submit', 'name'=>'enviar', 'class'=>'button', 'value'=>'Enviar', 'onClick'=>'return registrar();')); ?>
				</td>
				<td>
					<?php echo form_input($data = array('id'=>'borrar', 'type'=>'button', 'name'=>'borrar', 'class'=>'button', 'value'=>'Borrar', 'onClick'=>'return cleartext();')); ?>
				</td>
				<td>
					<?php echo form_input($data = array('id'=>'salir', 'type'=>'button', 'name'=>'salir', 'class'=>'button', 'value'=>'Salir', 'onClick'=>'return salirinterface();')); ?>
				</td>
			</tr>
		</table>
	</center>
</div>
</section>
<footer>
	<?php echo $footer; ?>
</footer>
<?php if(isset($informacion)) echo $informacion; ?>
<?php echo form_close(); ?>
</body>
</html>
<?php if(isset($mensaje_exito)): ?>
    <?php if(!empty($mensaje_exito)) echo JScript_ModalMensajeWeb($mensaje_exito, $pagina_retorno); ?>
<?php endif; ?>