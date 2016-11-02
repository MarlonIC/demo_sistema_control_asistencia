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
			var per_nombre = objFormulario(obj,'per_nombre').value;
 
			$.ajax({
				async: false,
				type: "POST",
				url: '<?php echo base_url().'persona/grabafiltros_ajax';?>',
				data: 'per_nombre='+ per_nombre +'&ajax=1',
                success: function(){}
            });
		}
		
		function consistenciar(){
			grabarFiltros();			
			document.forms[0].action = '<?php echo base_url().'persona/index';?>';
			return true;
		}
		
		function registrar(){
			grabarFiltros();
            window.location = '<?php echo base_url().'persona/registrar'; ?>';
        }

        function editar(per_codigo){
			grabarFiltros();
            window.location = '<?php echo base_url().'persona/editar/'; ?>' + per_codigo;
        }

        function detalle(per_codigo){
			grabarFiltros();
            window.location = '<?php echo base_url().'persona/detalle/'; ?>' + per_codigo;			
        }
		
		function confirmareliminar(link){
			grabarFiltros();
			var answer = confirm('¿Está seguro de eliminar el registro?,\nRecuerde que este proceso es irreversible')
			if (answer){
				window.location = link;
			}
			return false;  
		}
	</script>
</head>
<body>
<?php echo form_open('persona/index'); ?>
	<input id="h_tot_listado" type="hidden" name="h_tot_listado" value="<?php echo $Total_ListEntidad; ?>" />
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Listado de Personas</h1></hgroup>
			<br/>
			<table border="0" cellpadding="1" cellspacing="0" class="FormFiltro" style="width:50%;margin:0 auto;">
				<tr>
					<td width="42%" class="etiqueta">Nombres:</td>
					<td width="58%"><input type="text" id="per_nombre" name="per_nombre" maxlength="60" style="width:205px;" value="<?php $d=isset($per_nombre)?$per_nombre:null; echo set_value('per_nombre',$d) ?>" /></td>
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
				<span class="mensaje_error">No existen personas registradas</span>
			<?php else: ?>
				<?php 		
					$cimg_per_codigo=''; $cimg_per_nombres_persona=''; $cimg_per_direccion=''; $cimg_per_dni=''; $cimg_per_tiene_acceso=''; $cimg_per_estado=''; 
					$page_num = (int)$this->uri->segment(4);
					if($page_num==0) $page_num=1;
					$order_seg = $this->uri->segment(6,"asc");
					if($order_seg == "asc") $order = "desc"; else $order = "asc";

					$nom_column = $this->uri->segment(5);
					if($nom_column=="per_codigo"){ if($order=="desc") $cimg_per_codigo = img(base_url().'images/f_up.png'); else $cimg_per_codigo = img(base_url().'images/f_down.png'); }
					if($nom_column=="per_nombres_persona"){ if($order=="desc") $cimg_per_nombres_persona = img(base_url().'images/f_up.png'); else $cimg_per_nombres_persona = img(base_url().'images/f_down.png'); }
					if($nom_column=="per_direccion"){ if($order=="desc") $cimg_per_direccion = img(base_url().'images/f_up.png'); else $cimg_per_direccion = img(base_url().'images/f_down.png'); }
					if($nom_column=="per_dni"){ if($order=="desc") $cimg_per_dni = img(base_url().'images/f_up.png'); else $cimg_per_dni = img(base_url().'images/f_down.png'); }
					if($nom_column=="per_tiene_acceso"){ if($order=="desc") $cimg_per_tiene_acceso = img(base_url().'images/f_up.png'); else $cimg_per_tiene_acceso = img(base_url().'images/f_down.png'); }
					if($nom_column=="per_estado"){ if($order=="desc") $cimg_per_estado = img(base_url().'images/f_up.png'); else $cimg_per_estado = img(base_url().'images/f_down.png'); }
			  	?>  
				<?php $this->table->set_heading(
											array('data'=>anchor(base_url().'persona/index/page/'.$page_num.'/per_codigo/'.$order,'Código'.$cimg_per_codigo),'width'=>'6%'), 
											array('data'=>anchor(base_url().'persona/index/page/'.$page_num.'/per_nombres_persona/'.$order,'Nombres'.$cimg_per_nombres_persona),'width'=>'29%'), 
											array('data'=>anchor(base_url().'persona/index/page/'.$page_num.'/per_direccion/'.$order,'Direccion'.$cimg_per_direccion),'width'=>'18%'),
											array('data'=>anchor(base_url().'persona/index/page/'.$page_num.'/per_dni/'.$order,'Nro DNI'.$cimg_per_dni),'width'=>'14%'), 
											array('data'=>anchor(base_url().'persona/index/page/'.$page_num.'/per_tiene_acceso/'.$order,'Tiene Acceso'.$cimg_per_tiene_acceso),'width'=>'13%'), 
											array('data'=>anchor(base_url().'persona/index/page/'.$page_num.'/per_estado/'.$order,'Estado'.$cimg_per_estado),'width'=>'10%'),
											array('data'=>'Acciones','width'=>'10%')); ?>
				<?php $template = array('table_open'=>'<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:100%;"'); ?>
				<?php $this->table->set_template($template); ?>
				<?php foreach ($Listado_Entidad as $lentidad) : ?>
					<?php
						$acciones = anchor('#', img(array('src'=>base_url().'images/edit.png', 'alt'=>'Editar', 'title'=>'Editar')),array('onClick'=>'editar(\''.$lentidad->per_codigo.'\');return false;')).'&nbsp;'.
									anchor('#', img(array('src'=>base_url().'images/delete.gif', 'alt'=>'Eliminar', 'title'=>'Eliminar')),array('onClick'=>'return confirmareliminar(\' '.base_url().'persona/eliminar/'.$lentidad->per_codigo.'/'.$lentidad->per_tiene_acceso.'\')')).'&nbsp;'.
									anchor('#', img(array('src'=>base_url().'images/ver.png', 'alt'=>'Ver Detalle', 'title'=>'Ver Detalle')),array('onClick'=>'detalle(\''.$lentidad->per_codigo.'\'); return false;'));
					?>
					<?php $this->table->add_row(
											array('data'=>$lentidad->per_codigo,'class'=>'center'),
											array('data'=>$lentidad->per_nombres_persona,'class'=>'left'), 
											array('data'=>$lentidad->per_direccion,'class'=>'center'),
											array('data'=>$lentidad->per_dni,'class'=>'center'), 
											array('data'=>settext_estado3($lentidad->per_tiene_acceso),'class'=>'center'), 
											array('data'=>settext_estado($lentidad->per_estado),'class'=>'center'),
											array('data'=>$acciones,'id'=>'td_accion_'.$lentidad->per_codigo,'class'=>'center')); ?>
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