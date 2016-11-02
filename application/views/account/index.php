<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
    <title>:: MAHARASPERU - Sistema de Gestión de Planilla ::</title>
	<link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/estilos.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/login.css">
    <!--[if lte IE 9]><script src="<?php echo base_url();?>js/html5.js"></script><![endif]-->
</head>
<body>
<?php echo form_open('account/verificar'); ?>
    <header>
		<div id="logo"></div>
        <div id="head-slide"></div>
	</header>
	<nav>
		<div id="nav-line"></div>
        <div id="menu">&nbsp;</div>
        <div id="nav-line2"></div>
	</nav>
	<section>
		<div id="contenido">
	        <div id="container_login">
		        <label>Nombre de Usuario:</label>
		        <input id="usu_login" type="text" name="usu_login" maxlength="30" required/>
		        <?php echo form_error('usu_login','<div class="form_error">','</div>'); ?>
		        <label>Contrase&ntilde;a:</label>			
		        <input id="usu_password" type="password" name="usu_password" maxlength="30" required/>
		        <?php echo form_error('usu_password','<div class="form_error">','</div>'); ?>	
		        <div class="franja">		
			        <input type="submit" value="Ingresar"/>
		        </div>
	        </div>
        </div>
    </section>
	<footer>
		<div id="footer-line"></div>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="450" class="Fuente2">
					<span class="Estilo5">
					Copyright &copy; 2016. MAHARASPERU - Todos los derechos reservados.<br />
				</td>
				<td width="500" colspan="2" align="right">
					&nbsp;
				</td>
			</tr>
		</table>
	</footer>
<?php echo form_close(); ?>
</body>
</html>