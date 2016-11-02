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
			var rol_nombre = objFormulario(obj,'rol_nombre').value;
 
			$.ajax({
				async: false,
				type: "POST",
				url: '<?php echo base_url().'rol/grabafiltros_ajax';?>',
				data: 'rol_nombre='+ rol_nombre +'&ajax=1',
                success: function(){}
            });
		}
		
		function consistenciar(){
			grabarFiltros();			
			document.forms[0].action = '<?php echo base_url().'rol/index';?>';
			return true;
		}
		
		function registrar(){
			grabarFiltros();
            window.location = '<?php echo base_url().'rol/registrar'; ?>';
        }

        function editar(rol_codigo){
			grabarFiltros();
            window.location = '<?php echo base_url().'rol/editar/'; ?>' + rol_codigo;
        }

        function detalle(rol_codigo){
			grabarFiltros();
            window.location = '<?php echo base_url().'rol/detalle/'; ?>' + rol_codigo;			
        }

        function definir(rol_codigo){
			grabarFiltros();
            window.location = '<?php echo base_url().'rol/definir/'; ?>' + rol_codigo;
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
			document.forms[0].action = '<?php echo base_url().'rol/exportar';?>';
			document.forms[0].submit();
		}
	</script>
</head>
<body>
<?php echo form_open('rol/index'); ?>
	<input id="h_tot_listado" type="hidden" name="h_tot_listado" value="<?php echo $Total_ListEntidad; ?>" />
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Listado de Roles</h1></hgroup>
			<br/>
			<table border="0" cellpadding="1" cellspacing="0" class="FormFiltro" style="width:50%;margin:0 auto;">
				<tr>
					<td width="35%" class="etiqueta">Nombre Rol:</td>
					<td width="65%"><input type="text" id="rol_nombre" name="rol_nombre" maxlength="60" style="width:205px;" value="<?php $d=isset($rol_nombre)?$rol_nombre:null; echo set_value('rol_nombre',$d) ?>" /></td>
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
				<span class="mensaje_error">No existen roles registrados</span>
			<?php else: ?>
				<div style="margin:0 0 5px 25px;float:left;">
					<img src="<?php echo base_url();?>images/export.gif" />&nbsp;
					<span style="font-weight:bold;">Exportar</span>&nbsp;
					<a href="#" onclick="javascript:exportar();"><img src="<?php echo base_url();?>images/excel.gif" alt="Exportar a Excel" title="Exportar a Excel" /></a>
				</div>
				<?php 				  
					$cimg_rol_codigo=''; $cimg_rol_nombre=''; $cimg_rol_alias=''; $cimg_rol_estado=''; 
					$page_num = (int)$this->uri->segment(4);
					if($page_num==0) $page_num=1;
					$order_seg = $this->uri->segment(6,"asc");
					if($order_seg == "asc") $order = "desc"; else $order = "asc";

					$nom_column = $this->uri->segment(5);
					if($nom_column=="rol_codigo"){ if($order=="desc") $cimg_rol_codigo = img(base_url().'images/f_up.png'); else $cimg_rol_codigo = img(base_url().'images/f_down.png'); }
					if($nom_column=="rol_nombre"){ if($order=="desc") $cimg_rol_nombre = img(base_url().'images/f_up.png'); else $cimg_rol_nombre = img(base_url().'images/f_down.png'); }
					if($nom_column=="rol_alias"){ if($order=="desc") $cimg_rol_alias = img(base_url().'images/f_up.png'); else $cimg_rol_alias = img(base_url().'images/f_down.png'); }
					if($nom_column=="rol_estado"){ if($order=="desc") $cimg_rol_estado = img(base_url().'images/f_up.png'); else $cimg_rol_estado = img(base_url().'images/f_down.png'); }
			  	?>  
				<?php $this->table->set_heading(
											array('data'=>anchor(base_url().'rol/index/page/'.$page_num.'/rol_codigo/'.$order,'Código'.$cimg_rol_codigo),'width'=>'8%'), 
											array('data'=>anchor(base_url().'rol/index/page/'.$page_num.'/rol_nombre/'.$order,'Nombre'.$cimg_rol_nombre),'width'=>'42%'), 
											array('data'=>anchor(base_url().'rol/index/page/'.$page_num.'/rol_alias/'.$order,'Alias'.$cimg_rol_alias),'width'=>'30%'), 
											array('data'=>anchor(base_url().'rol/index/page/'.$page_num.'/rol_estado/'.$order,'Estado'.$cimg_rol_estado),'width'=>'10%'),
											array('data'=>'Acciones','width'=>'10%')); ?>
				<?php $template = array('table_open'=>'<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:100%;"'); ?>
				<?php $this->table->set_template($template); ?>
				<?php foreach ($Listado_Entidad as $lentidad) : ?>
					<?php
						$acciones = anchor('#', img(array('src'=>base_url().'images/candado.gif', 'alt'=>'Definir Permisos', 'title'=>'Definir Permisos')),array('onClick'=>'definir(\''.$lentidad->rol_codigo.'\');return false;')).'&nbsp;'.
									anchor('#', img(array('src'=>base_url().'images/edit.png', 'alt'=>'Editar', 'title'=>'Editar')),array('onClick'=>'editar(\''.$lentidad->rol_codigo.'\');return false;')).'&nbsp;'.
									anchor('#', img(array('src'=>base_url().'images/delete.gif', 'alt'=>'Eliminar', 'title'=>'Eliminar')),array('onClick'=>'return confirmareliminar(\' '.base_url().'rol/eliminar/'.$lentidad->rol_codigo.'\')')).'&nbsp;'.
									anchor('#', img(array('src'=>base_url().'images/ver.png', 'alt'=>'Ver Detalle', 'title'=>'Ver Detalle')),array('onClick'=>'detalle(\''.$lentidad->rol_codigo.'\'); return false;'));
					?>
					<?php $this->table->add_row(
											array('data'=>$lentidad->rol_codigo,'class'=>'center'), 
											$lentidad->rol_nombre, 
                                            array('data'=>$lentidad->rol_alias,'class'=>'center'), 
											array('data'=>settext_estado($lentidad->rol_estado),'class'=>'center'),
											array('data'=>$acciones,'id'=>'td_accion_'.$lentidad->rol_codigo,'class'=>'center')); ?>
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