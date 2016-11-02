<script>
	function cambiar_rol_selected(rol_codigo){
		document.forms[0].action = '<?php echo base_url().'home/seleccionarsave';?>';
		document.forms[0].submit();
	}
</script>
<div>
	<a id="logo" href="#" target="_blank"></a>
</div>
<div id="head-slide">
	<table>
		<tr>
			<td>Bienvenido&nbsp;</td>
			<td><b style="color:#395C82;font-size:12px;"><?php echo $Session_NombreUsuario; ?></b></td>
			<td>&nbsp;|&nbsp;<a href="<?php echo base_url(); ?>home/logout" class="cerrarsesion">Cerrar Sesión</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2"><b style="color:#DA251C;font-size:10px;"><?php echo obtener_responsabilidad(); ?></b></td>
		</tr>
		<?php $rol_selected = obtener_estado_rol_selected(); ?>
		<?php if(!empty($rol_selected)): ?>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">
				<?php $rol_codigo_selected = obtener_rol_selected(); ?>
				<?php echo form_dropdown('rol_codigo_sel', obtener_roles(), set_value('rol_codigo_sel', $rol_codigo_selected),'id="rol_codigo_sel" onchange="cambiar_rol_selected(this.value)"');?>
			</td>
		</tr>
		<?php endif; ?>
	</table>
</div>