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
</head>
<body>
<?php echo form_open('home/index', array('id'=>'Form1')); ?>
	<header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Bienvenido al Sistema de Gestión de Planillas</h1></hgroup>
            <br>
			<table style="width:100%" class="Formulario">
				<tr>
					<td><b>Usuario:</b> <?php echo $Session_NombreUsuario; ?></td>
				</tr>
				<tr>
					<td><b>Sesi&oacute;n iniciada el</b> <?php echo date('d/m/Y h:i A', strtotime($Session_FechaIngreso)); ?> - GMT</td>
				</tr>
				<tr>
					<td><b>desde la ubicaci&oacute;n IP</b> <?php echo $Session_IPUsuario; ?></td>
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