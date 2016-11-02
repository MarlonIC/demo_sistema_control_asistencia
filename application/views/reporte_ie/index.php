<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
    <title>:: MAHARASPERU - Sistema de Gestión de Operaciones ::</title>
	<link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/estilos.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/form.css">
    <link rel="stylesheet" href="<?php echo base_url();?>simplemodal/css/simplemodal.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/nav.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/pagination.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.multiselect.css" />
    <!--[if lte IE 9]><script src="<?php echo base_url();?>js/html5.js"></script><![endif]-->
    <script src="<?php echo base_url();?>js/jquery-1.9.1.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery.multiselect.js"></script>
    <script src="<?php echo base_url();?>js/datepicker-init.js"></script>
    <script src="<?php echo base_url();?>js/form.js"></script>
    <script>jQuery.noConflict();</script>
    <script>var base_url = '<?php echo base_url(); ?>';</script>
    <script type="text/javascript">
    	jQuery(document).ready(function() {
			jQuery(this).on('submit',function(e){
				jQuery('input[type=submit]').attr('disabled',true);
			});
			jQuery("#coa_fecha_I").datepicker();
			jQuery("#coa_fecha_F").datepicker();			
		}); // End Jquery document.ready

		function consistenciar(){
			var obj = document.forms[0];

			if (!verifica(objFormulario(obj,'coa_fecha_I'),'Debe ingresar la Fecha Inicio')){
				return false;
			}
			if (!validar_Fecha(objFormulario(obj,'coa_fecha_I'))) {
			    return false;
		    }
			if (!verifica(objFormulario(obj,'coa_fecha_F'),'Debe ingresar la Fecha Final')){
				return false;
			}
			if (!validar_Fecha(objFormulario(obj,'coa_fecha_F'))) {
			    return false;
		    }
			if(!compareFechas(objFormulario(obj,'coa_fecha_I').value, objFormulario(obj,'coa_fecha_F').value, 'La Fecha Inicio no puede ser mayor a la Fecha Final')){
				return false;
			}

			return true;
		}

		function exportar(){
			document.forms[0].action = '<?php echo base_url().'reporte_ie/exportar';?>';
			document.forms[0].submit();
		}

		function grabarFiltros(){
			var obj = document.forms[0];
			var coa_fecha_I = objFormulario(obj,'coa_fecha_I').value;
			var coa_fecha_F = objFormulario(obj,'coa_fecha_F').value;
			
			jQuery.ajax({
				async: false,
				type: "POST",
				url: '<?php echo base_url().'reporte_ie/grabafiltros_ajax';?>',
				data: 'coa_fecha_I='+ coa_fecha_I +'&coa_fecha_F='+ coa_fecha_F+'&ajax=1',
                success: function(){}
            });
		}
    </script>
</head>
<body>
<?php echo form_open('reporte_ie/index'); ?>
<input id="h_tot_listado" type="hidden" name="h_tot_listado" value="<?php echo $Total_ListEntidad; ?>" />
<input type="hidden" value="<?php echo $result2->per_codigo_empleado; ?>" id="codigo_empleado" name="codigo_empleado" />
<header>
	<?php echo $header; ?>
</header>
<nav>
	<?php echo $nav; ?>
</nav>
<section>
<div id="contenido">	
		<table border="0" cellpadding="1" cellspacing="0" class="FormFiltro" style="width:94%;margin:0 auto;">
			<tr>
				<td width="20%" class="etiqueta">Codigo:</td>
				<td width="30%">
					<?php echo $result2->per_codigo_empleado; ?>
				</td>
				<td width="18%" class="etiqueta">Fecha:</td>
				<td width="32%">
					<input id="coa_fecha_I" type="text" name="coa_fecha_I" maxlength="10" style="width:100px;" value="<?php $d=isset($coa_fecha_I)?$coa_fecha_I:null; echo set_value('coa_fecha_I',$d) ?>"  />					
				</td>			
			</tr>			
			</tr>
				<td class="etiqueta">Empleado</td>
				<td>
					<?php echo $result2->per_nombres_persona; ?>
				</td>
				<td>&nbsp;</td>
				<td><span style="padding-left:40px;padding-right:40px;">Hasta</span>
					<input type="submit" id="btn_consulta" value="Buscar" class="button" onclick="return consistenciar();" />
				</td>
			</tr>
			<tr>
				<td class="etiqueta">Cargo</td>
				<td>
					<?php echo $result2->cargo; ?>
				</td>
				<td>&nbsp;</td>
				<td>
					<input id="coa_fecha_F" type="text" name="coa_fecha_F" maxlength="10" style="width:100px;" value="<?php $d=isset($coa_fecha_F)?$coa_fecha_F:null; echo set_value('coa_fecha_F',$d) ?>"  />
				</td>
			</tr>
			<tr>
				<td colspan="5" height="30" align="center" style="vertical-align:middle;">
					
				</td>
			</tr>
		</table>
		<br />
		<hgroup><h1><center>Reporte de Asistencia</center></h1></hgroup>
		<br/>
        <?php if (isset($mensaje_error)): ?>
            <div class="mensaje_error"><?php echo $mensaje_error; ?></div>
        <?php endif; ?>
		<?php if ($Total_ListEntidad == 0) : ?>
			<span class="mensaje_error">No existen registros</span>
		<?php else: ?>
			<div style="margin:0 0 5px 25px;float:left;">
				<img src="<?php echo base_url();?>images/export.gif" />&nbsp;					
				<a href="#" onclick="javascript:exportar();"><span style="font-weight:bold;">Exportar</span>&nbsp;<img src="<?php echo base_url();?>images/excel.gif" alt="Exportar a Excel" title="Exportar a Excel" /></a>
			</div>
            <div class="i_resultados">
                Mostrando del <b><?php echo $bdini_reg; ?></b> al <b><span id="pag_counter"><?php echo $bdfin_reg; ?></span></b> de <b><?php echo $Total_ListEntidad; ?></b> resultados
            </div>
			<?php $this->table->set_heading(
										array('data'=>'Fecha','width'=>'15%'), 
										array('data'=>'Codigo','width'=>'15%'), 
										array('data'=>'Turno','width'=>'15%'),
										array('data'=>'Tipo de Marca','width'=>'15%'),
										array('data'=>'Hora Entrada','width'=>'10%'),
										array('data'=>'Hora Salida','width'=>'10%'),
										array('data'=>'Tardanza','width'=>'10%'),
										array('data'=>'Falta','width'=>'10%')); ?>
			<?php $template = array('table_open'=>'<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:100%;"'); ?>
			<?php $this->table->set_template($template); ?>
			<?php foreach ($result as $lentidad) : ?>
				<?php 
				$tiempo = '';
				$tardanza = explode(":", $lentidad->coa_entrada);
				if($tardanza[0] > 8) {
					$tiempo = $tardanza[0] - 8;
				} else {
					$tiempo = "0";
				}
				$tiempo .= ":" .$tardanza[1];
				?>
				<?php $this->table->add_row(
										array('data'=>date('d/m/Y', strtotime($lentidad->coa_fecha)),'class'=>'center'),
										array('data'=>$result2->per_codigo_empleado,'class'=>'center'), 
										array('data'=>'Mañana','class'=>'center'), 
										array('data'=>'Entrada / Salida','class'=>'center'), 
										array('data'=>$lentidad->coa_entrada,'class'=>'center'),
										array('data'=>$lentidad->coa_salida,'class'=>'center'),  
										array('data'=>$tiempo,'class'=>'center'), 
										array('data'=>'','class'=>'center')); ?>
			<?php endforeach; ?>
			<?php echo $this->table->generate(); ?>
			<div id="pagination"><BR>
			<ul class="tsc_pagination">
				<?php foreach ($links as $link) { echo "<li>". $link."</li>"; } ?>
			</div>
		<?php endif; ?>
    </div>
</section>
<footer>
<?php echo $footer; ?>
</footer>
<?php echo form_close(); ?>
</body>
</html>