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
        function evaluarSeleccion2(valor){
            var obj = document.forms[0];
            switch(valor){
                case 'S':
                    document.getElementById('tr_seleccione_1').style.display='';
                    document.getElementById('tr_seleccione_2').style.display='';
                    document.getElementById('tr_seleccione_3').style.display='';
                    document.getElementById('tr_usu_login').style.display='';
                    break;
                case 'N':
                case '':
                    document.getElementById('tr_seleccione_1').style.display='none';
                    document.getElementById('tr_seleccione_2').style.display='none';
                    document.getElementById('tr_seleccione_3').style.display='none';
                    document.getElementById('tr_usu_login').style.display='none';
                    break;                
                default:
            }
        }

        function seleccionarChecked(listado_roles){
            var table = document.getElementById("tb_resultado");
            var inputs = table.getElementsByTagName('input');
            listado_roles = listado_roles+',';
            for(var i=0; i<inputs.length; i++){
                if(inputs[i].type == "checkbox"){
                    var checkbox = inputs[i];
                    if (listado_roles.indexOf(checkbox.value+',') !=-1){    
                        checkbox.checked = true;
                    }
                }
            }
        }

		function regresar(){
			window.location = '<?php echo obtener_paginicio('PER'); ?>';
		}
    </script>
</head>
<body>
<?php echo form_open('persona/detalle', array('id'=>'Form1')); ?>
	<header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Detalle de Persona</h1></hgroup>
            <br>
			<table style="width:100%;" class="Formulario">
				<tr>
					<td colspan="2" class="subtitulo">Datos Generales</td>
				</tr>
                <tr>
                    <td style="width:38%;" class="etiqueta">Nombres</td>
                    <td style="width:62%;">
                    	<?php echo $result->per_nombres; ?>
                    </td>
                </tr>
				<tr>
                    <td class="etiqueta">Apellido</td>
                    <td>
                    	<?php echo $result->per_apellidos; ?>
                    </td>
                </tr>
                <tr id="tr_per_nombres" style="DISPLAY:">
                    <td class="etiqueta">Direccion</td>
                    <td>
                    	<?php echo $result->per_direccion; ?>
                    </td>
                </tr>
                <tr id="tr_per_apellidos_paterno" style="DISPLAY:">
                    <td class="etiqueta">Fecha de Nacimiento</td>
                    <td>
                        <?php echo isset($result->per_fecha_nacimiento)?date('d/m/Y', strtotime($result->per_fecha_nacimiento)):null; ?>
                    </td>
                </tr>
                <tr id="tr_per_apellidos_materno" style="DISPLAY:">
                    <td class="etiqueta">Nro DNI</td>
                    <td>
                    	<?php echo $result->per_dni; ?>
                    </td>
                </tr>
                <tr id="tr_tid_codigo" style="DISPLAY:">
                    <td class="etiqueta">Telefono Fijo</td>
                    <td>
                    	<?php echo $result->per_telefono_fijo; ?>
                    </td>
                </tr>
                <tr id="tr_per_nro_documento" style="DISPLAY:">
                    <td class="etiqueta">Telefono movil</td>
                    <td>
                    	<?php echo $result->per_telefono_movil; ?>
                    </td>
                </tr>
                <tr id="tr_per_nro_documento" style="DISPLAY:">
                    <td class="etiqueta">Seguro</td>
                    <td>
                        <?php echo $result->seg_seguro; ?>
                    </td>
                </tr>
                <tr id="tr_per_apellidos_paterno" style="DISPLAY:">
                    <td class="etiqueta">Fecha de Ingreso</td>
                    <td>
                        <?php echo isset($result->per_fecha_ingreso)?date('d/m/Y', strtotime($result->per_fecha_ingreso)):null; ?>
                    </td>
                </tr>
                <tr id="tr_per_razon_social" style="DISPLAY:">
                    <td id="td_per_razon_social" class="etiqueta">Cargo</td>
                    <td>
                        <?php echo $result->cargo; ?>
                    </td>
                </tr>
                <tr id="tr_ubi_codigo" style="DISPLAY:">
                    <td class="etiqueta">Localidad</td>
                    <td>
                        <?php echo $result->ubi_departamento.' - '.$result->ubi_provincia.' - '.$result->ubi_distrito; ?>                        
                    </td>
                </tr>
                <tr id="tr_per_razon_social" style="DISPLAY:">
                    <td id="td_per_razon_social" class="etiqueta">Codigo de Empleado</td>
                    <td>
                        <?php echo $result->per_codigo_empleado; ?>
                    </td>
                </tr>
                <tr id="tr_per_nro_ruc" style="DISPLAY:">
                    <td id="td_per_nro_ruc" class="etiqueta">Banco</td>
                    <td>
                        <?php echo $result->ban_banco; ?>
                    </td>
                </tr>
                <tr id="tr_ubi_codigo" style="DISPLAY:">
                    <td class="etiqueta">Cuenta Bano</td>
                    <td>
                        <?php echo $result->per_cuenta_banco; ?>                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="subtitulo">Estudios Realizados</td>
                </tr>
                <tr id="tr_per_direccion_domicilio" style="DISPLAY:">
                    <td class="etiqueta">Nivel Estudios</td>
                    <td>
                        <?php echo $result->nie_estudios; ?>
                    </td>
                </tr>
				<tr id="tr_per_direccion_domicilio" style="DISPLAY:">
                    <td class="etiqueta">Lugar de Estudios</td>
                    <td>
                    	<?php echo $result->per_lugar_estudios; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="subtitulo">REFERENCIAS DE ULTIMO TRABAJO</td>
                </tr>
                <tr id="tr_per_domicilio_en_ubigeo" style="DISPLAY:">
                    <td class="etiqueta">Empresa</td>
                    <td>
                        <?php echo $result->per_ultrabajo_empresa; ?>
                    </td>
                </tr>
                <tr id="tr_per_genero" style="DISPLAY:">
                    <td class="etiqueta">Cargo</td>
                    <td>
                    	<?php echo $result->per_ultrabajo_cargo; ?>
                    </td>
                </tr>
                <tr id="tr_esc_codigo" style="DISPLAY:">
                    <td class="etiqueta">Telefono</td>
                    <td>
                    	<?php echo $result->per_ultrabajo_telefono; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="subtitulo">REFERENCIAS PERSONALES</td>
                </tr>                
                <?php $cont=1; ?>
                <?php foreach ($result_drp as $lentidad): ?>
                <tr>
                    <td class="etiqueta">Referencia(<?php echo $cont; ?>)</td>
                    <td>
                        <?php echo $lentidad->refp_referencia; ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Edad</td>
                    <td>
                        <?php echo $lentidad->refp_edad; ?> años
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">telefono</td>
                    <td>
                        <?php echo $lentidad->refp_telefono; ?>
                    </td>
                </tr>
                <?php $cont++; ?>
                <?php endforeach; ?>                
                <tr>
                    <td colspan="2" class="subtitulo">ESTADO CIVIL</td>
                </tr>
                <tr>
                    <td class="etiqueta">Estado Civil</td>
                    <td>
                        <?php echo $result->esc_nombre; ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Nro de Hijos</td>
                    <td>
                        <?php echo $result->per_nro_hijos; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="subtitulo">DATOS DE HIJO Y CONYUGE</td>
                </tr>
                <?php foreach ($result_dhc as $lentidad): ?>
                <tr>
                    <td class="etiqueta">Nombres</td>
                    <td>
                        <?php echo $lentidad->hic_nombres; ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Apellidos</td>
                    <td>
                        <?php echo $lentidad->hic_apellidos; ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Edad</td>
                    <td>
                        <?php echo $lentidad->hic_edad; ?> años
                    </td>
                </tr>
                <?php endforeach; ?>    
                <tr>
                    <td colspan="2" class="subtitulo">EN CASO DE EMERGENCIA LLAMAR</td>
                </tr>
                <tr id="tr_per_celular" style="DISPLAY:">
                    <td class="etiqueta">Nombres</td>
                    <td>
                        <?php echo $result->per_casoemer_nombres; ?>
                    </td>
                </tr>
                <tr id="tr_per_telefonos" style="DISPLAY:">
                    <td class="etiqueta">Apellido</td>
                    <td>
                    	<?php echo $result->per_casoemer_apellidos; ?>
                    </td>
                </tr>
                <tr id="tr_per_nombres_conyuge" style="DISPLAY:">
                    <td class="etiqueta">Telefono</td>
                    <td>
                        <?php echo $result->per_casoemer_telefono; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="subtitulo">RECOMENDADO POR</td>
                </tr>
                <tr id="tr_per_apellidos_conyuge" style="DISPLAY:">
                    <td class="etiqueta">Nombres</td>
                    <td>
                        <?php echo $result->per_recomendado_nombres; ?>
                    </td>
                </tr>
                <tr id="tr_tid_codigo_conyuge" style="DISPLAY:">
                    <td class="etiqueta">Apellidos</td>
                    <td>
                        <?php echo $result->per_recomendado_apellidos; ?>
                    </td>
                </tr>
                <tr id="tr_per_nro_documento_conyuge" style="DISPLAY:">
                    <td class="etiqueta">Telefono</td>
                    <td>
                        <?php echo $result->per_recomendado_telefono; ?>
                    </td>
                </tr>
                <tr id="tr_per_foto" style="DISPLAY:">
                    <td class="etiqueta">Dirección URL Foto</td>
                    <td>
                        <?php echo $result->per_foto; ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Acceso al Sistema</td>
                    <td>
                        <?php echo settext_estado3($result->per_tiene_acceso); ?>
                    </td>
                </tr>
                <tr id="tr_seleccione_1" style="DISPLAY:none">
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr id="tr_seleccione_2" style="DISPLAY:none">
                    <td colspan="2" class="subtitulo">Datos de Acceso</td>
                </tr>
                <tr id="tr_seleccione_3" style="DISPLAY:none">
                    <td class="etiqueta">Roles</td>
                    <td>
                        <?php $this->table->set_heading(
                                            array('data'=>'Rol','width'=>'80%'), 
                                            array('data'=>'Seleccionar','width'=>'20%')); ?>
                        <?php $template = array('table_open'=>'<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:50%;"'); ?>
                        <?php $this->table->set_template($template); ?>
                        <?php foreach ($Listado_Entidad as $lentidad) : ?>
                            <?php $this->table->add_row(
                                                    array('data'=>$lentidad->rol_nombre,'class'=>'left'), 
                                                    array('data'=>'<input id="seleccionar_'.$lentidad->rol_codigo.'" type="checkbox" name="seleccionar_'.$lentidad->rol_codigo.'" value="'.$lentidad->rol_codigo.'" disabled>','class'=>'center')); ?>
                        <?php endforeach; ?>
                        <?php echo $this->table->generate(); ?>
                    </td>
                </tr>
                <tr id="tr_usu_login" style="DISPLAY:none">
                    <td class="etiqueta">Login de Acceso</td>
                    <td>
                        <?php echo $result->usu_login; ?>
                    </td>
                </tr>
				<tr>
                    <td class="etiqueta">Estado</td>
                    <td>
                    	<?php echo settext_estado($result->per_estado); ?>
                    </td>
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
    <?php if(isset($informacion)) echo $informacion; ?>
<?php echo form_close(); ?>
</body>
</html>
<?php if(isset($mensaje_exito)): ?>
    <?php if(!empty($mensaje_exito)) echo JScript_ModalMensajeWeb($mensaje_exito, $pagina_retorno); ?>
<?php endif; ?>
<script>
    var per_tiene_acceso = '<?php echo $result->per_tiene_acceso; ?>';
    evaluarSeleccion2(per_tiene_acceso);

    var per_listado_roles = '<?php echo $result->per_listado_roles; ?>';
	seleccionarChecked(per_listado_roles);
</script>