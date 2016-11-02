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
        $(document).ready(function(){            
			jQuery(this).on('submit',function(e){
				jQuery('input[type=submit]').attr('disabled',true);
			});
        });

		function consistenciar(){
			var obj = document.forms[0];
			
			if (!verifica(objFormulario(obj,'fun_nombre'),'Debe ingresar el Nombre')){
				return false;
			}
			if (!verifica(objFormulario(obj,'fun_alias'),'Debe ingresar el Alias')){
				return false;
			}
			if (!verifica(objFormulario(obj,'fun_orden'),'Debe ingresar el Orden Vista')){
				return false;
			}
            if (validaSeleccion(objFormulario(obj, 'fun_tipo'), '')) {
                msgErrorCamp(objFormulario(obj, 'fun_tipo'), 'Seleccione el Tipo de Enlace');
                return false;
            }
			
			return true;
		}
		
		function regresar(){
			window.location = '<?php echo obtener_paginicio('FUN'); ?>';
		}
    </script>
</head>
<body>
<?php echo form_open('funcionalidad/registrarsave', array('id'=>'Form1')); ?>
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Registro de Funcionalidad</h1></hgroup>
            <br>
            <?php if (isset($mensaje_error)): ?>
                <div class="mensaje_error"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
			<table style="width:100%;" class="Formulario">
				<tr>
					<td colspan="2" class="subtitulo">Datos Generales</td>
				</tr>
                <tr>
                    <td width="38%" class="etiqueta">Nombre<span class="required">*</span></td>
                    <td width="62%">
                        <input id="fun_nombre" type="text" name="fun_nombre" maxlength="30" style="width:200px;" value="<?php echo set_value('fun_nombre'); ?>" />
						<?php echo form_error('fun_nombre','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Alias<span class="required">*</span></td>
                    <td>
                        <input id="fun_alias" type="text" name="fun_alias" maxlength="6" style="width:120px;" value="<?php echo set_value('fun_alias'); ?>" />
						<?php echo form_error('fun_alias','<div class="error">','</div>'); ?>
                    </td>
                </tr>
				<tr>
                    <td class="etiqueta">Agrupador</td>
                    <td>
                        <?php echo form_dropdown('fun_codigo_padre', $Combo_Funcionalidad, set_value('fun_codigo_padre', (isset($_POST['fun_codigo_padre'])?$_POST['fun_codigo_padre']:null)));?>
						<?php echo form_error('fun_codigo_padre','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Dirección URL</td>
                    <td>
                        <input id="fun_url" type="text" name="fun_url" maxlength="120" style="width:200px;" value="<?php echo set_value('fun_url'); ?>" />
						<?php echo form_error('fun_url','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Orden Vista<span class="required">*</span></td>
                    <td>
                        <input id="fun_orden" type="text" name="fun_orden" maxlength="5" style="width:120px;" value="<?php echo set_value('fun_orden'); ?>" />
						<?php echo form_error('fun_orden','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Tipo Enlace<span class="required">*</span></td>
                    <td>
                        <select id="fun_tipo" name="fun_tipo">
                            <option value="">-- Seleccione --</option>
                            <option value="I" <?php echo set_select('fun_tipo', 'I'); ?>>Interno</option>
                            <option value="E" <?php echo set_select('fun_tipo', 'E'); ?>>Externo</option>
                        </select>
                        <?php echo form_error('fun_tipo','<div class="error">','</div>'); ?>
                    </td>
                </tr>
				<tr>
                    <td class="etiqueta">Estado<span class="required">*</span></td>
                    <td>
                        <input name="fun_estado" type="radio" id="fun_estadoA" value="A" <?php echo set_radio('fun_estado', 'A', TRUE); ?>><label>Activo</label>
						<input name="fun_estado" type="radio" id="fun_estadoB" value="B" <?php echo set_radio('fun_estado', 'B'); ?>><label>Bloqueado</label>
						<?php echo form_error('fun_estado','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" height="40" align="center" style="vertical-align:middle;">
                        <?php echo form_input($data = array('id'=>'btn_grabar', 'type'=>'submit', 'name'=>'btn_grabar', 'class'=>'button', 'value'=>'Grabar Datos', 'onClick'=>'return consistenciar();')); ?>
                        &nbsp;&nbsp;&nbsp;
                        <?php echo form_input($data = array('id'=>'btn_regresar', 'type'=>'button', 'name'=>'btn_regresar', 'class'=>'button', 'value'=>'Regresar', 'onClick'=>'return regresar();')); ?>
                    </td>
                </tr>
			</table>
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