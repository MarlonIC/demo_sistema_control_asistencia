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
        $(document).ready(function(){
			jQuery(this).on('submit',function(e){
				jQuery('input[type=submit]').attr('disabled',true);
			});
        });

		function consistenciar(){
			var obj = document.forms[0];
			
			if (!verifica(objFormulario(obj,'rol_nombre'),'Debe ingresar el Nombre')){
				return false;
			}
			if (!verifica(objFormulario(obj,'rol_alias'),'Debe ingresar el Alias')){
				return false;
			}
			return true;
		}
		
		function regresar(){
			window.location = '<?php echo obtener_paginicio('ROL'); ?>';
		}
    </script>
</head>
<body>
<?php echo form_open('rol/editarsave', array('id'=>'Form1')); ?>
	<input id="rol_codigo" type="hidden" name="rol_codigo" value="<?php $d=isset($result)?$result->rol_codigo:null; echo set_value('rol_codigo',$d) ?>" />
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Edición de Rol</h1></hgroup>
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
                        <input id="rol_nombre" type="text" name="rol_nombre" maxlength="30" style="width:200px;" value="<?php $d=isset($result)?$result->rol_nombre:null; echo set_value('rol_nombre',$d) ?>"  />
						<?php echo form_error('rol_nombre','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Alias<span class="required">*</span></td>
                    <td>
                        <input id="rol_alias" type="text" name="rol_alias" maxlength="6" style="width:120px;" value="<?php $d=isset($result)?$result->rol_alias:null; echo set_value('rol_alias',$d) ?>"  />
						<?php echo form_error('rol_alias','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Estado<span class="required">*</span></td>
                    <td>
                        <input name="rol_estado" type="radio" id="rol_estadoA" value="A" <?php echo set_radio('rol_estado', 'A', (isset($result)?(setbool_radio($result->rol_estado,'A')):FALSE)); ?>><label>Activo</label>
						<input name="rol_estado" type="radio" id="rol_estadoB" value="B" <?php echo set_radio('rol_estado', 'B', (isset($result)?(setbool_radio($result->rol_estado,'B')):FALSE)); ?>><label>Bloqueado</label>
					    <?php echo form_error('usu_estado','<div class="error">','</div>'); ?>
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