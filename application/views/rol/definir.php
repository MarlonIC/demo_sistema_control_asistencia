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
    <!--[if lte IE 9]><script src="<?php echo base_url();?>js/html5.js"></script><![endif]-->
    <script src="<?php echo base_url();?>js/jquery-1.9.1.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url();?>js/form.js"></script>
	<script>		
		$(document).ready(function() {
			jQuery('#tb_resultado input[type=checkbox]').click(function () {                
                var fun_codigo = jQuery(this).val();
                var checked = jQuery(this).is(":checked")?'true':'false';				
				var rol_codigo = document.getElementById('rol_codigo').value;
                //---
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo base_url().'rol/definir_ajax';?>',
                    data: 'fun_codigo=' + fun_codigo + '&rol_codigo=' + rol_codigo + '&checked=' + checked + '&ajax=1',
                    success: function (output_string) {
                        if(output_string == ''){
							//mostrará un mensaje tootip en el centro que aparece y desaparece
                        }
                    }
                });
            });
		}); // End Jquery document.ready
		
		function regresar(){
			window.location = '<?php echo base_url().'rol/index' ?>';
		}
	</script>
</head>
<body>
<?php echo form_open('rol/definir', array('id'=>'Form1')); ?> 
	<input id="rol_codigo" type="hidden" name="rol_codigo" value="<?php $d=isset($result)?$result->rol_codigo:null; echo set_value('rol_codigo',$d) ?>" />
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Definición de Funcionalidades por Rol</h1></hgroup>
			<br>
            <table class="Formulario" style="width:50%;margin:0 auto;">
				<tr>
                    <td class="etiqueta" style="width:100px;">Rol</td>
                    <td><?php echo $result->rol_nombre; ?></td>
                </tr>
			</table>
			<br/>
			<br/>
			<?php if (!$Listado_Entidad) : ?>
				<span class="mensaje_error">No existen funcionalidades registradas</span>
			<?php else: ?>
				<?php $this->table->set_heading(
											array('data'=>'Código','width'=>'16%'), 
											array('data'=>'Funcionalidad','width'=>'64%'), 
											array('data'=>'Seleccionar','width'=>'20%')); ?>
				<?php $template = array('table_open'=>'<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:50%;margin:0 auto;"'); ?>
				<?php $this->table->set_template($template); ?>
				<?php foreach ($Listado_Entidad as $lentidad) : ?>
					<?php
						$checked = isset($lentidad->fun_codigo_RFP)?'checked':'';
					?>
					<?php $this->table->add_row(
											array('data'=>$lentidad->fun_codigo,'class'=>'center'), 
											str_replace(' ','&nbsp;',$lentidad->fun_nombre), 
											array('data'=>'<input id="seleccionar_'.$lentidad->fun_codigo.'" type="checkbox" name="seleccionar_'.$lentidad->fun_codigo.'" value="'.$lentidad->fun_codigo.'" '.$checked.'>','class'=>'center')); ?>
				<?php endforeach; ?>
				<?php echo $this->table->generate(); ?>
			<?php endif; ?>
			<br/>
			<table style="width:100%;">
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