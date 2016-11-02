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
	<link rel="stylesheet" href="<?php echo base_url();?>css/pagination.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css" />
    <!--[if lte IE 9]><script src="<?php echo base_url();?>js/html5.js"></script><![endif]-->
    <script src="<?php echo base_url();?>js/jquery-1.9.1.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url();?>js/datepicker-init.js"></script>
    <script src="<?php echo base_url();?>js/form.js"></script>
	<script>
		$(document).ready(function() {
			jQuery(this).on('submit',function(e){
				jQuery('input[type=submit]').attr('disabled',true);
			});
			$("#aud_fecha_registroI").datepicker();
			$("#aud_fecha_registroF").datepicker();
		}); // End Jquery document.ready
		
		function grabarFiltros(){
			var obj = document.forms[0];
			var aud_fecha_registroI = objFormulario(obj,'aud_fecha_registroI').value;
			var aud_fecha_registroF = objFormulario(obj,'aud_fecha_registroF').value;
			var objusu = objFormulario(obj,'usu_codigo');
			var objent = objFormulario(obj,'ent_codigo');
			var objacc = objFormulario(obj,'acc_codigo');
			var usu_codigo = objusu.options[objusu.selectedIndex].value;
			var ent_codigo = objent.options[objent.selectedIndex].value;
			var acc_codigo = objacc.options[objacc.selectedIndex].value;
 
			$.ajax({
				async: false,
				type: "POST",
				url: '<?php echo base_url().'auditoria/grabafiltros_ajax';?>',
				data: 'aud_fecha_registroI='+ aud_fecha_registroI +'&aud_fecha_registroF='+ aud_fecha_registroF +'&usu_codigo='+ usu_codigo +'&ent_codigo='+ ent_codigo +'&acc_codigo='+ acc_codigo +'&ajax=1',
                success: function(){}
            });
		}
		
		function consistenciar(){
			var obj = document.forms[0];

			if (!verifica(objFormulario(obj,'aud_fecha_registroI'),'Debe ingresar la Fecha Inicio')){
				return false;
			}
			if (!validar_Fecha(objFormulario(obj,'aud_fecha_registroI'))) {
			    return false;
		    }
			if (!verifica(objFormulario(obj,'aud_fecha_registroF'),'Debe ingresar la Fecha Final')){
				return false;
			}
			if (!validar_Fecha(objFormulario(obj,'aud_fecha_registroF'))) {
			    return false;
		    }
			if(!compareFechas(objFormulario(obj,'aud_fecha_registroI').value, objFormulario(obj,'aud_fecha_registroF').value, 'La Fecha Inicio no puede ser mayor a la Fecha Final')){
				return false;
			}

			grabarFiltros();	
			document.forms[0].action = '<?php echo base_url().'auditoria/index';?>';
			return true;
		}

        function detalle(aud_codigo){
			grabarFiltros();
            window.location = '<?php echo base_url().'auditoria/detalle/'; ?>' + aud_codigo;			
        }
		
		function exportar(){
			document.forms[0].action = '<?php echo base_url().'auditoria/exportar';?>';
			document.forms[0].submit();
		}
	</script>
</head>
<body>
<?php echo form_open('auditoria/index'); ?>
	<input id="h_tot_listado" type="hidden" name="h_tot_listado" value="<?php echo $Total_ListEntidad; ?>" />
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Listado de Auditoria</h1></hgroup>
			<br/>
			<table border="0" cellpadding="1" cellspacing="0" class="FormFiltro" style="width:50%;margin:0 auto;">
				<tr>
					<td width="38%" class="etiqueta">Fecha Registro:</td>
					<td width="62%">
						<input id="aud_fecha_registroI" type="text" name="aud_fecha_registroI" maxlength="10" style="width:100px;" value="<?php $d=isset($aud_fecha_registroI)?$aud_fecha_registroI:null; echo set_value('aud_fecha_registroI',$d) ?>"  />
						&nbsp;
						<input id="aud_fecha_registroF" type="text" name="aud_fecha_registroF" maxlength="10" style="width:100px;" value="<?php $d=isset($aud_fecha_registroF)?$aud_fecha_registroF:null; echo set_value('aud_fecha_registroF',$d) ?>"  />
					</td>
				</tr>
				<tr>
					<td class="etiqueta">Usuario:</td>
					<td>
						<?php echo form_dropdown('usu_codigo', $Combo_Usuario, set_value('usu_codigo', (isset($_POST['usu_codigo'])?$_POST['usu_codigo']:$usu_codigo)));?>
					</td>
				</tr>
				<tr>
					<td class="etiqueta">Entidad:</td>
					<td>
						<?php echo form_dropdown('ent_codigo', $Combo_Entidad, set_value('ent_codigo', (isset($_POST['ent_codigo'])?$_POST['ent_codigo']:$ent_codigo)));?>
					</td>
				</tr>
				<tr>
					<td class="etiqueta">Acción:</td>
					<td>
						<?php echo form_dropdown('acc_codigo', $Combo_Accion, set_value('acc_codigo', (isset($_POST['acc_codigo'])?$_POST['acc_codigo']:$acc_codigo)));?>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="30" align="center" style="vertical-align:middle;">
						<input type="submit" id="btn_consulta" value="Consultar" class="button" onclick="return consistenciar();" />
					</td>
				</tr>
			</table>
			<br/>
            <?php if (isset($mensaje_error)): ?>
                <div class="mensaje_error"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
			<?php if ($Total_ListEntidad == 0) : ?>
				<span class="mensaje_error">No existen registros de auditoria registradas</span>
			<?php else: ?>
				<div style="margin:0 0 5px 25px;float:left;">
					<img src="<?php echo base_url();?>images/export.gif" />&nbsp;
					<span style="font-weight:bold;">Exportar</span>&nbsp;
					<a href="#" onclick="javascript:exportar();"><img src="<?php echo base_url();?>images/excel.gif" alt="Exportar a Excel" title="Exportar a Excel" /></a>
				</div>
				<?php 	
					$cimg_aud_codigo=''; $cimg_usu_nombres_completo=''; $cimg_ent_nombre=''; $cimg_acc_nombre=''; $cimg_aud_fecha_registro=''; 
					$page_num = (int)$this->uri->segment(4);
					if($page_num==0) $page_num=1;
					$order_seg = $this->uri->segment(6,"asc");
					if($order_seg == "asc") $order = "desc"; else $order = "asc";

					$nom_column = $this->uri->segment(5);
					if($nom_column=="aud_codigo"){ if($order=="desc") $cimg_aud_codigo = img(base_url().'images/f_up.png'); else $cimg_aud_codigo = img(base_url().'images/f_down.png'); }
					if($nom_column=="usu_nombres_completo"){ if($order=="desc") $cimg_usu_nombres_completo = img(base_url().'images/f_up.png'); else $cimg_usu_nombres_completo = img(base_url().'images/f_down.png'); }
					if($nom_column=="ent_nombre"){ if($order=="desc") $cimg_ent_nombre = img(base_url().'images/f_up.png'); else $cimg_ent_nombre = img(base_url().'images/f_down.png'); }
					if($nom_column=="acc_nombre"){ if($order=="desc") $cimg_acc_nombre = img(base_url().'images/f_up.png'); else $cimg_acc_nombre = img(base_url().'images/f_down.png'); }
					if($nom_column=="aud_fecha_registro"){ if($order=="desc") $cimg_aud_fecha_registro = img(base_url().'images/f_up.png'); else $cimg_aud_fecha_registro = img(base_url().'images/f_down.png'); }
			  	?>  
				<?php $this->table->set_heading(
											array('data'=>anchor(base_url().'auditoria/index/page/'.$page_num.'/aud_codigo/'.$order,'Código'.$cimg_aud_codigo),'width'=>'8%'), 
											array('data'=>anchor(base_url().'auditoria/index/page/'.$page_num.'/usu_nombres_completo/'.$order,'Usuario'.$cimg_usu_nombres_completo),'width'=>'26%'), 											 
											array('data'=>anchor(base_url().'auditoria/index/page/'.$page_num.'/ent_nombre/'.$order,'Entidad'.$cimg_ent_nombre),'width'=>'19%'), 
											array('data'=>anchor(base_url().'auditoria/index/page/'.$page_num.'/acc_nombre/'.$order,'Acción'.$cimg_acc_nombre),'width'=>'19%'),
											array('data'=>anchor(base_url().'auditoria/index/page/'.$page_num.'/aud_fecha_registro/'.$order,'Fecha Registro'.$cimg_aud_fecha_registro),'width'=>'18%'),
											array('data'=>'Acciones','width'=>'10%')); ?>
				<?php $template = array('table_open'=>'<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:100%;"'); ?>
				<?php $this->table->set_template($template); ?>
				<?php foreach ($Listado_Entidad as $lentidad) : ?>
					<?php
						$acciones = anchor('#', img(array('src'=>base_url().'images/ver.png', 'alt'=>'Ver Detalle', 'title'=>'Ver Detalle')),array('onClick'=>'detalle(\''.$lentidad->aud_codigo.'\'); return false;'));
					?>
					<?php $this->table->add_row(
											array('data'=>$lentidad->aud_codigo,'class'=>'center'), 
											array('data'=>$lentidad->usu_nombres_completo,'class'=>'left'), 
                                            array('data'=>$lentidad->ent_nombre,'class'=>'center'), 
                                            array('data'=>$lentidad->acc_nombre,'class'=>'center'), 
											array('data'=>date('d/m/Y H:i:s', strtotime($lentidad->aud_fecha_registro)),'class'=>'center'),
											array('data'=>$acciones,'id'=>'td_accion_'.$lentidad->aud_codigo,'class'=>'center')); ?>
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