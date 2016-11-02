<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
    <title>:: MAHARASPERU - Sistema de Gestión de Planillas ::</title>
	<link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/estilos.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/form.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/nav.css">
    <!--[if lte IE 9]><script src="<?php echo base_url();?>js/html5.js"></script><![endif]-->
    <script src="<?php echo base_url();?>js/jquery-1.9.1.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
	<script>
		function cambiar_rol_selected(rol_codigo){
			if(rol_codigo==''){
				alert('Seleccione un rol');
				return false;
			}

			document.forms[0].submit();
		}
	</script>
</head>
<body>
<?php echo form_open('home/seleccionarsave'); ?>
	<header>
		<?php echo $header; ?>
	</header>
	<nav>
		<div id="nav-line"></div>
		<div id="menu">
		</div>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Seleccione el Rol que va operar en el Sistema:</h1></hgroup>
            <br>
			<table style="width:100%" class="Formulario">
				<tr>
					<td align="center">
						<?php echo form_dropdown('rol_codigo_sel', $Combo_Rol, set_value('rol_codigo_sel'),'id="rol_codigo_sel" onchange="cambiar_rol_selected(this.value)"');?>
                        <?php echo form_error('rol_codigo_sel','<div class="error">','</div>'); ?>
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