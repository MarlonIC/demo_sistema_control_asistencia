<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
    <title>:: MAHARASPERU - Sistema de Gestión de Operaciones ::</title>
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
			window.location = '<?php echo obtener_paginicio('AUD'); ?>';
		}
    </script>
</head>
<body>
<?php echo form_open('auditoria/detalle', array('id'=>'Form1')); ?>
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Detalle de Auditoria</h1></hgroup>
            <br>
			<table style="width:100%;" class="Formulario">
                <tr>
                    <td colspan="2" class="subtitulo">Datos Generales</td>
                </tr>
                <tr>
                    <td width="38%" class="etiqueta">Código Auditoria</td>
                    <td width="62%"><?php echo $result->aud_codigo; ?></td>
                </tr>
                <tr>
                    <td class="etiqueta">Nombre Usuario</td>
                    <td><?php echo $result->usu_nombres_completo; ?></td>
                </tr>
                <tr>
                    <td class="etiqueta">Entidad</td>
                    <td><?php echo $result->ent_nombre; ?></td>
                </tr>
                <tr>
                    <td class="etiqueta">Acción</td>
                    <td><?php echo $result->acc_nombre; ?></td>
                </tr>
                <tr>
                    <td class="etiqueta">Descripción</td>
                    <td><?php echo $result->aud_descripcion; ?></td>
                </tr>
                <tr>
                    <td class="etiqueta">Fecha Registro</td>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($result->aud_fecha_registro)); ?></td>
                </tr>
                <tr>
                    <td class="etiqueta">IP Acceso</td>
                    <td><?php echo $result->aud_ip_acceso; ?></td>
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