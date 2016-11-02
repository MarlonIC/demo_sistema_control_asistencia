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
    <!--[if lte IE 9]><script src="<?php echo base_url();?>js/html5.js"></script><![endif]-->
    <script src="<?php echo base_url();?>js/jquery-1.9.1.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url();?>js/form.js"></script>
	<script>
		$(document).ready(function() {
			jQuery(this).on('submit',function(e){
				jQuery('input[type=submit]').attr('disabled',true);
			});
		}); // End Jquery document.ready
		
		function grabarFiltros(){
			var obj = document.forms[0];
			var fun_nombre = objFormulario(obj,'fun_nombre').value;
 
			$.ajax({
				async: false,
				type: "POST",
				url: '<?php echo base_url().'funcionalidad/grabafiltros_ajax';?>',
				data: 'fun_nombre='+ fun_nombre +'&ajax=1',
                success: function(){}
            });
		}
		
		function consistenciar(){
			grabarFiltros();			
			document.forms[0].action = '<?php echo base_url().'funcionalidad/index';?>';
			return true;
		}
		
		function registrar(){
			grabarFiltros();
            window.location = '<?php echo base_url().'funcionalidad/registrar'; ?>';
        }

        function editar(fun_codigo){
			grabarFiltros();
            window.location = '<?php echo base_url().'funcionalidad/editar/'; ?>' + fun_codigo;
        }

        function detalle(fun_codigo){
			grabarFiltros();
            window.location = '<?php echo base_url().'funcionalidad/detalle/'; ?>' + fun_codigo;			
        }
		
		function confirmareliminar(link){
			grabarFiltros();
			var answer = confirm('¿Está seguro de eliminar el registro?,\nRecuerde que este proceso es irreversible')
			if (answer){
				window.location = link;
			}
			return false;  
		}
		
		function exportar(){
			document.forms[0].action = '<?php echo base_url().'funcionalidad/exportar';?>';
			document.forms[0].submit();
		}
	</script>
</head>
<body>
<?php echo form_open('funcionalidad/index'); ?>
	<input id="h_tot_listado" type="hidden" name="h_tot_listado" value="<?php echo $Total_ListEntidad; ?>" />
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Listado de Funcionalidades</h1></hgroup>
			<br/>
			<table border="0" cellpadding="1" cellspacing="0" class="FormFiltro" style="width:50%;margin:0 auto;">
				<tr>
					<td width="35%" class="etiqueta">Nombre:</td>
					<td width="65%"><input type="text" id="fun_nombre" name="fun_nombre" maxlength="60" style="width:205px;" value="<?php $d=isset($fun_nombre)?$fun_nombre:null; echo set_value('fun_nombre',$d) ?>" /></td>
				</tr>
				<tr>
					<td colspan="5" height="30" align="center" style="vertical-align:middle;">
						<input type="submit" id="btn_consulta" value="Consultar" class="button" onclick="return consistenciar();" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" id="btn_nuevo" value="Nuevo Registro" class="button" onclick="registrar();" />
					</td>
				</tr>
			</table>
			<br/>
            <?php if (isset($mensaje_error)): ?>
                <div class="mensaje_error"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
			<?php if ($Total_ListEntidad == 0) : ?>
				<span class="mensaje_error">No existen funcionalidades registradas</span>
			<?php else: ?>
				<div style="margin:0 0 5px 25px;float:left;">
					<img src="<?php echo base_url();?>images/export.gif" />&nbsp;
					<span style="font-weight:bold;">Exportar</span>&nbsp;
					<a href="#" onclick="javascript:exportar();"><img src="<?php echo base_url();?>images/excel.gif" alt="Exportar a Excel" title="Exportar a Excel" /></a>
				</div>
				<?php 				  
					$cimg_fun_codigo=''; $cimg_fun_nombre=''; $cimg_fun_alias=''; $cimg_fun_tipo=''; $cimg_fun_estado='';
					$page_num = (int)$this->uri->segment(4);
					if($page_num==0) $page_num=1;
					$order_seg = $this->uri->segment(6,"asc");
					if($order_seg == "asc") $order = "desc"; else $order = "asc";

					$nom_column = $this->uri->segment(5);
					if($nom_column=="fun_codigo"){ if($order=="desc") $cimg_fun_codigo = img(base_url().'images/f_up.png'); else $cimg_fun_codigo = img(base_url().'images/f_down.png'); }
					if($nom_column=="fun_nombre"){ if($order=="desc") $cimg_fun_nombre = img(base_url().'images/f_up.png'); else $cimg_fun_nombre = img(base_url().'images/f_down.png'); }
					if($nom_column=="fun_alias"){ if($order=="desc") $cimg_fun_alias = img(base_url().'images/f_up.png'); else $cimg_fun_alias = img(base_url().'images/f_down.png'); }
					if($nom_column=="fun_tipo"){ if($order=="desc") $cimg_fun_tipo = img(base_url().'images/f_up.png'); else $cimg_fun_tipo = img(base_url().'images/f_down.png'); }
					if($nom_column=="fun_estado"){ if($order=="desc") $cimg_fun_estado = img(base_url().'images/f_up.png'); else $cimg_fun_estado = img(base_url().'images/f_down.png'); }
			  	?>  
				<?php $this->table->set_heading(
											array('data'=>anchor(base_url().'funcionalidad/index/page/'.$page_num.'/fun_codigo/'.$order,'Código'.$cimg_fun_codigo),'width'=>'8%'), 
											array('data'=>anchor(base_url().'funcionalidad/index/page/'.$page_num.'/fun_nombre/'.$order,'Nombre'.$cimg_fun_nombre),'width'=>'40%'), 
											array('data'=>anchor(base_url().'funcionalidad/index/page/'.$page_num.'/fun_alias/'.$order,'Alias'.$cimg_fun_alias),'width'=>'16%'), 
											array('data'=>anchor(base_url().'funcionalidad/index/page/'.$page_num.'/fun_tipo/'.$order,'Tipo'.$cimg_fun_tipo),'width'=>'16%'),
											array('data'=>anchor(base_url().'funcionalidad/index/page/'.$page_num.'/fun_estado/'.$order,'Estado'.$cimg_fun_estado),'width'=>'10%'),
											array('data'=>'Acciones','width'=>'10%')); ?>
				<?php $template = array('table_open'=>'<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:100%;"'); ?>
				<?php $this->table->set_template($template); ?>
				<?php foreach ($Listado_Entidad as $lentidad) : ?>
					<?php
						$acciones = anchor('#', img(array('src'=>base_url().'images/edit.png', 'alt'=>'Editar', 'title'=>'Editar')),array('onClick'=>'editar(\''.$lentidad->fun_codigo.'\');return false;')).'&nbsp;'.
									anchor('#', img(array('src'=>base_url().'images/delete.gif', 'alt'=>'Eliminar', 'title'=>'Eliminar')),array('onClick'=>'return confirmareliminar(\' '.base_url().'funcionalidad/eliminar/'.$lentidad->fun_codigo.'\')')).'&nbsp;'.
									anchor('#', img(array('src'=>base_url().'images/ver.png', 'alt'=>'Ver Detalle', 'title'=>'Ver Detalle')),array('onClick'=>'detalle(\''.$lentidad->fun_codigo.'\'); return false;'));
					?>
					<?php $this->table->add_row(
											array('data'=>$lentidad->fun_codigo,'class'=>'center'), 
											str_replace(' ','&nbsp;',$lentidad->fun_nombre), 
                                            array('data'=>$lentidad->fun_alias,'class'=>'center'), 
											array('data'=>settext_estado5($lentidad->fun_tipo),'class'=>'center'),
											array('data'=>settext_estado($lentidad->fun_estado),'class'=>'center'),
											array('data'=>$acciones,'id'=>'td_accion_'.$lentidad->fun_codigo,'class'=>'center')); ?>
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