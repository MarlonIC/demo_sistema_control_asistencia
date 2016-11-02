<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
    <title>:: MAHARASPERU - Sistema de Gestión de Planilla ::</title>
    <link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/normalize.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/estilos.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/form.css">
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
    <script>var base_url = '<?php echo base_url(); ?>';</script>
    <script src="<?php echo base_url();?>windowfiles/dhtmlwindow.js"></script>
    <script src="<?php echo base_url();?>modalfiles/modal.js"></script>
    <script type="text/javascript">
		function regresar(){
			window.location = '<?php echo obtener_paginicio('ROL'); ?>';
		}
    </script>
</head>
<body>
<?php echo form_open('rol/detalle', array('id'=>'Form1')); ?>
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Detalle del Rol</h1></hgroup>
            <br>
			<table style="width:100%;" class="Formulario">
                <tr>
                    <td colspan="2" class="subtitulo">Datos Generales</td>
                </tr>
                <tr>
                    <td width="38%" class="etiqueta">Nombre</td>
                    <td width="62%">
                        <?php echo $result->rol_nombre; ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Alias</td>
                    <td>
                        <?php echo $result->rol_alias; ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Estado</td>
                    <td>
                        <?php echo settext_estado($result->rol_estado); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" height="40" align="center" style="vertical-align:middle;">
                        <?php echo form_input($data = array('id'=>'btn_regresar', 'type'=>'button', 'name'=>'btn_regresar', 'class'=>'button', 'value'=>'Regresar', 'onClick'=>'return regresar();')); ?>
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