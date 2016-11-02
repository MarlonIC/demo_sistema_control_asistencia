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
    <script src="<?php echo base_url();?>js/ajaxfileupload.js"></script>
	<script src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
    <script src="<?php echo base_url();?>js/datepicker-init.js"></script>
    <script src="<?php echo base_url();?>js/messages_es.js"></script>
	<script src="<?php echo base_url();?>js/form.js"></script>
    <script>var base_url = '<?php echo base_url(); ?>';</script>
    <script src="<?php echo base_url();?>windowfiles/dhtmlwindow.js"></script>
    <script src="<?php echo base_url();?>modalfiles/modal.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
        	$("#per_fecha_ingreso").datepicker();
			$("#per_fecha_nacimiento").datepicker();
            $("#Form1").validate({
                rules: {}
            });
            $(".xcssfile").click(function (e) {
                e.preventDefault();
                if($(this).attr("disabled") != undefined) return false;

                //-----------                
               	var filename = 'input_file';
                if(document.getElementById(filename).value=='') return false;
                //---------------------
                $.ajaxFileUpload({
                    url: '<?php echo base_url().'persona/uploadfile_ajax';?>',
                    secureuri : false,
                    fileElementId : filename,
                    dataType : 'json',
                    data : {'filename':filename},
                    success : function(data, status){
                        if(data.status == 'success'){           
                            document.getElementById('div_ifile').style.display = 'none';
                            document.getElementById('div_ilist').style.display = 'block';
                            document.getElementById('h_per_foto').value = data.value;
                            document.getElementById('div_list').innerHTML = '<a id="a_list" href="'+ base_url +'files/'+ data.value +'" class="uviewfile" target="_blank">'+ data.value +'</a>';
                        }else{
                            alert(data.value);
                        }
                    }
                });
            });
        });

        function retorna_Provincia(ubi_coddep, select_value, tipo){
            if(ubi_coddep == ''){
                if(tipo == 0) alert('Seleccione el Departamento');
                return false;
            }
            
            $("#ubi_codprov > option").remove();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url().'ubigeo/retornaprovincia_ajax';?>',
                dataType: "json",
                data: 'ubi_coddep=' + ubi_coddep + '&tipo=3&ajax=1',
                success: function(provincia){
                    $.each(provincia, function(ubi_codprov, ubi_provincia){
                        var opt = $('<option />');
                        opt.val(ubi_codprov);
                        opt.text(replaceAll('&nbsp;',' ',ubi_provincia));
                        $('#ubi_codprov').append(opt);
                    });
                },
                complete: function() {
                    if(select_value != '0'){ 
                        document.getElementById('ubi_codprov').value = select_value;
                    }
                }
            });
        }
        
        function retorna_Distrito(ubi_codprov, select_value, tipo){
            if(ubi_codprov == '0'){
                if(tipo == 0) alert('Seleccione la Provincia');
                return false;
            }

            var objdep = document.getElementById('ubi_coddep');
            ubi_coddep = objdep.options[objdep.selectedIndex].value;

            $("#ubi_coddis > option").remove();
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url().'ubigeo/retornadistrito_ajax';?>',
                dataType: "json",
                data: 'ubi_coddep=' + ubi_coddep + '&ubi_codprov=' + ubi_codprov + '&tipo=3&ajax=1',
                success: function(distrito){
                    $.each(distrito, function(ubi_coddis, ubi_distrito){
                        var opt = $('<option />');
                        opt.val(ubi_coddis);
                        opt.text(replaceAll('&nbsp;',' ',ubi_distrito));
                        $('#ubi_coddis').append(opt);
                    });
                },
                complete: function() {
                    if(select_value != '0'){ 
                        document.getElementById('ubi_coddis').value = select_value;
                    }
                }
            });         
        }
		
	

		function eliminar_file(obj){
            if(verificaExisteProperty(obj,'disabled')) return false;
            var answer = confirm('¿Está seguro de quitar el archivo seleccionado?')
		    if (answer){
                var file_name = document.getElementById('a_list').innerHTML;
                //---------------------
                $.ajaxFileUpload({
                    url: '<?php echo base_url().'persona/deletefile_ajax';?>',
                    dataType : 'json',
                    data : {'file_name':file_name},
                    success : function(data){
                        if(data.status == 'success'){
                        	document.getElementById('div_ifile').style.display = 'block';
                            document.getElementById('div_ilist').style.display = 'none';
                            document.getElementById('h_per_foto').value = '';
                            document.getElementById('div_list').innerHTML = '';
                        }else{
                            alert(data.msg);
                        }
                    }
                });
		    }
            return false;
        }

        function evaluarSeleccion2(valor){
            var obj = document.forms[0];
            switch(valor){
                case 'S':
                    document.getElementById('tr_seleccione_1').style.display='';
                    document.getElementById('tr_seleccione_2').style.display='';
					document.getElementById('tr_seleccione_3').style.display='';
					document.getElementById('tr_usu_login').style.display='';
					document.getElementById('tr_usu_password').style.display='';
					break;
                case 'N':
                case '':
                    document.getElementById('tr_seleccione_1').style.display='none';
                    document.getElementById('tr_seleccione_2').style.display='none';
					document.getElementById('tr_seleccione_3').style.display='none';
					document.getElementById('tr_usu_login').style.display='none';
					document.getElementById('tr_usu_password').style.display='none';
                    break;                
                default:
            }
        }	

        function retornarCodRoles(){
        	var table = document.getElementById("tb_resultado");
			var inputs = table.getElementsByTagName('input');
			listado_roles = "";
			for(var i=0; i<inputs.length; i++){
				if(inputs[i].type == "checkbox"){
					var checkbox = inputs[i];
					if(checkbox.checked){
						listado_roles += checkbox.value + ",";
					}
				}
			}
			
			if(listado_roles.length==0){
				return "";
			}else{
				return listado_roles.substring(0, listado_roles.length-1);
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

		function consistenciar(){
			var obj = document.forms[0];

            if (!verifica(objFormulario(obj,'per_apellidos'),'Debe ingresar los Apellidos')){
				return false;
			}
			if (!verifica(objFormulario(obj,'per_nombres'),'Debe ingresar los Nombres')){
				return false;
			}
			if (!verifica(objFormulario(obj,'per_direccion'),'Debe ingresar la direccion')){
				return false;
			}
			if (!verifica(objFormulario(obj,'per_fecha_nacimiento'),'Debe ingresar la Fecha de Nacimiento')){
				return false;
			}
			if (!validar_Fecha(objFormulario(obj,'per_fecha_nacimiento'))) {
			    return false;
		    }
			if (!verifica(objFormulario(obj,'per_dni'),'Debe ingresar el DNI')){
				return false;
			}
			if (!verifica(objFormulario(obj,'per_telefono_fijo'),'Debe ingresar un telefono fijo')){
				return false;
			}
			if (!verifica(objFormulario(obj,'per_telefono_movil'),'Debe ingresar un telefono movil')){
				return false;
			}
			if (validaSeleccion(objFormulario(obj, 'seg_codigo'), '')) {
                msgErrorCamp(objFormulario(obj, 'seg_codigo'), 'Seleccione un Seguro');
                return false;
            }
			if (!verifica(objFormulario(obj,'per_fecha_ingreso'),'Debe ingresar la Fecha de Ingreso')){
				return false;
			}
			if (!validar_Fecha(objFormulario(obj,'per_fecha_ingreso'))) {
			    return false;
		    }
		    if (validaSeleccion(objFormulario(obj, 'car_codigo'), '')) {
                msgErrorCamp(objFormulario(obj, 'car_codigo'), 'Seleccione un Cargo');
                return false;
            }
            if (validaSeleccion(objFormulario(obj, 'ubi_coddep'), '')) {
                msgErrorCamp(objFormulario(obj, 'ubi_coddep'), 'Seleccione el Departamento');
                return false;
            }

            var objdep = document.getElementById('ubi_coddep');
            if(objdep.options[objdep.selectedIndex].value != ''){
                ubi_coddep = objdep.options[objdep.selectedIndex].value;
                var objprov = document.getElementById('ubi_codprov');
                if(objprov.options[objprov.selectedIndex].value == '0'){
                    msgErrorCamp(objprov, 'Seleccione la Provincia');
                    return false;
                }
                if(objprov.options[objprov.selectedIndex].value != '0'){
                    ubi_codprov = objprov.options[objprov.selectedIndex].value;
                    var objdist = document.getElementById('ubi_coddis');
                    if(objdist.options[objdist.selectedIndex].value == '0'){
                        msgErrorCamp(objdist, 'Seleccione el Distrito');
                        return false;                       
                    }
                    ubi_coddis = objdist.options[objdist.selectedIndex].value;
                }
            }
			if (!verifica(objFormulario(obj,'per_codigo_empleado'),'Debe ingresar Codigo del Empleado')){
				return false;
			}
			if (validaSeleccion(objFormulario(obj, 'ban_codigo'), '')) {
                msgErrorCamp(objFormulario(obj, 'ban_codigo'), 'Seleccione un Nivel de Estudios');
                return false;
            }
			if (!verifica(objFormulario(obj,'per_cuenta_banco'),'Debe ingresar cuenta bancaria')){
				return false;
			}
			if (validaSeleccion(objFormulario(obj, 'nie_codigo'), '')) {
                msgErrorCamp(objFormulario(obj, 'nie_codigo'), 'Seleccione un Nivel de Estudios');
                return false;
            }
            if (!verifica(objFormulario(obj,'per_lugar_estudios'),'Debe ingresar un lugar de Estudios')){
				return false;
			}
			if (validaSeleccion(objFormulario(obj, 'esc_codigo'), '')) {
                msgErrorCamp(objFormulario(obj, 'esc_codigo'), 'Seleccione un Estado Civil');
                return false;
            }
            if (!verifica(objFormulario(obj,'per_nro_hijos'),'Debe ingresar numero de hijos')){
				return false;
			}

			if (validaSeleccion(objFormulario(obj, 'per_tiene_acceso'), '')) {
                msgErrorCamp(objFormulario(obj, 'per_tiene_acceso'), 'Seleccione el Acceso al Sistema');
                return false;
            }

            var objpas = document.getElementById('per_tiene_acceso');
            var per_tiene_acceso = objpas.options[objpas.selectedIndex].value;
        	if(per_tiene_acceso == 'S'){
        		var listado_roles = retornarCodRoles();
        		if(listado_roles==''){
        			alert("Debe seleccionar al menos un rol del listado");
        			return false;
        		}
        		if (!verifica(objFormulario(obj,'usu_login'),'Debe ingresar el Login de Acceso')){
					return false;
				}
				if (!verifica(objFormulario(obj,'usu_password'),'Debe ingresar la Contraseña')){
					return false;
				}
        	}else{
        		document.getElementById('usu_login').value = "";
        	}
        	
            obj.h_ubi_codprov.value = ubi_codprov;
            obj.h_ubi_coddis.value = ubi_coddis;
			obj.h_listado_roles.value = listado_roles;
			return true;
		}	
		
		function regresar(){
			window.location = '<?php echo obtener_paginicio('PER'); ?>';
		}
    </script>
</head>
<body>
<?php echo form_open('persona/registrarsave', array('id'=>'Form1','enctype'=>'multipart/form-data')); ?>
	<input id="h_ubi_codprov" type="hidden" name="h_ubi_codprov" />
    <input id="h_ubi_coddis" type="hidden" name="h_ubi_coddis" />
	<input id="h_listado_roles" type="hidden" name="h_listado_roles" />
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Registro de Persona</h1></hgroup>
            <br>
            <?php if (isset($mensaje_error)): ?>
                <div class="mensaje_error"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
			<table style="width:100%;" class="Formulario">
				<tr>
					<td colspan="6" class="subtitulo">DATOS PERSONALES</td>
				</tr>
                <tr>
                	<td class="etiqueta" colspan="1">APELLIDOS<span class="required">*</span></td>
                    <td colspan="3">
                        <input id="per_apellidos" type="text" name="per_apellidos" maxlength="30" style="width:200px;" value="<?php echo set_value('per_apellidos'); ?>" />
						<?php echo form_error('per_apellidos','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta" colspan="1">FECHA DE INGRESO<span class="required">*</span></td>
                    <td colspan="1">
                        <input id="per_fecha_ingreso" type="text" name="per_fecha_ingreso" maxlength="30" style="width:200px;" value="<?php echo set_value('per_fecha_ingreso'); ?>" />
						<?php echo form_error('per_fecha_ingreso','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta"colspan="1">NOMBRES<span class="required">*</span></td>
                    <td colspan="3">
                        <input id="per_nombres" type="text" name="per_nombres" maxlength="30" style="width:200px;" value="<?php echo set_value('per_nombres'); ?>" />
						<?php echo form_error('per_nombres','<div class="error">','</div>'); ?>
                    </td colspan="1">
                    <td class="etiqueta">CARGO<span class="required">*</span></td>
                    <td colspan="1">
                        <?php echo form_dropdown('car_codigo', $comboCargo, set_value('car_codigo', (isset($_POST['car_codigo'])?$_POST['car_codigo']:null)),'id="car_codigo"');?>
						<?php echo form_error('car_codigo','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">DIRECCIÓN<span class="required">*</span></td>
                    <td colspan="3">
                        <input id="per_direccion" type="text" name="per_direccion" maxlength="30" style="width:200px;" value="<?php echo set_value('per_direccion'); ?>" />
						<?php echo form_error('per_direccion','<div class="error">','</div>'); ?>
                    </td>                    
                    <td class="etiqueta">LOCALIDAD<span class="required">*</span></td>
                    <td>
                        <table style="width:100%;" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <?php echo form_dropdown('ubi_coddep', $Combo_Departamento, set_value('ubi_coddep', (isset($_POST['ubi_coddep'])?$_POST['ubi_coddep']:null)),'id="ubi_coddep" onchange="retorna_Provincia(this.value, 0, 0)"');?>
                                    <br>
                                    <select id="ubi_codprov" name="ubi_codprov" onchange="retorna_Distrito(this.value, 0, 0)">
                                        <option value="" selected="selected">-- Seleccione --</option>
                                    </select>
                                    <br>
                                    <select id="ubi_coddis" name="ubi_coddis">
                                        <option value="" selected="selected">-- Seleccione --</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php echo form_error('ubi_coddep','<div class="error">','<BR></div>'); ?>
                                    <?php echo form_error('h_ubi_codprov','<div class="error">','<BR></div>'); ?>
                                    <?php echo form_error('h_ubi_coddis','<div class="error">','<BR></div>'); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">F. DE NAC.<span class="required">*</span></td>
                    <td>
                        <input id="per_fecha_nacimiento" type="text" name="per_fecha_nacimiento" maxlength="10" style="width:100px;" value="<?php echo set_value('per_fecha_nacimiento'); ?>" />
						<?php echo form_error('per_fecha_nacimiento','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">D.N.I<span class="required">*</span></td>
                    <td>
                        <input id="per_dni" type="text" name="per_dni" maxlength="10" style="width:100px;" value="<?php echo set_value('per_dni'); ?>" />
						<?php echo form_error('per_dni','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">COD. DE EMP<span class="required">*</span></td>
                    <td>
                        <input id="per_codigo_empleado" type="text" name="per_codigo_empleado" maxlength="10" style="width:200px;" value="<?php echo set_value('per_codigo_empleado'); ?>" />
						<?php echo form_error('per_codigo_empleado','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">TELF. FIJO<span class="required">*</span></td>
                    <td>
                        <input id="per_telefono_fijo" type="text" name="per_telefono_fijo" maxlength="10" style="width:100px;" value="<?php echo set_value('per_telefono_fijo'); ?>" />
						<?php echo form_error('per_telefono_fijo','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">TELF. MOVIL<span class="required">*</span></td>
                    <td>
                        <input id="per_telefono_movil" type="text" name="per_telefono_movil" maxlength="30" style="width:100px;" value="<?php echo set_value('per_telefono_movil'); ?>" />
						<?php echo form_error('per_telefono_movil','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">BANCO<span class="required">*</span></td>
                    <td>
                        <?php echo form_dropdown('ban_codigo', $comboBanco, set_value('ban_codigo', (isset($_POST['ban_codigo'])?$_POST['ban_codigo']:null)),'id="ban_codigo"');?>
                        <?php echo form_error('ban_codigo','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">SEGURO<span class="required">*</span></td>
                    <td colspan="3">
                        <?php echo form_dropdown('seg_codigo', $comboSeguro, set_value('seg_codigo', (isset($_POST['seg_codigo'])?$_POST['seg_codigo']:null)),'id="seg_codigo"');?>
						<?php echo form_error('seg_codigo','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">CUENTA BANCO<span class="required">*</span></td>
                    <td>
                        <input id="per_cuenta_banco" type="text" name="per_cuenta_banco" maxlength="20" style="width:200px;" value="<?php echo set_value('per_cuenta_banco'); ?>" />
						<?php echo form_error('per_cuenta_banco','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
					<td colspan="6" class="subtitulo">ESTUDIOS REALIZADOS</td>
				</tr>
                <tr>
                    <td class="etiqueta">NIVEL ESTUDIOS<span class="required">*</span></td>
                    <td  colspan="5">
                        <?php echo form_dropdown('nie_codigo', $comboNivelEstudios, set_value('nie_codigo', (isset($_POST['nie_codigo'])?$_POST['nie_codigo']:null)),'id="nie_codigo"');?>
						<?php echo form_error('nie_codigo','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">LUGAR DE ESTUDIOS<span class="required">*</span></td>
                    <td colspan="5">
                        <input id="per_lugar_estudios" type="text" name="per_lugar_estudios" maxlength="30" style="width:400px;" value="<?php echo set_value('per_lugar_estudios'); ?>" />
						<?php echo form_error('per_lugar_estudios','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
					<td colspan="6" class="subtitulo">REFERENCIAS DE ULTIMO TRABAJO</td>
				</tr>
				<tr>
                    <td class="etiqueta">EMPRESA</td>
                    <td colspan="5">
                        <input id="per_ultrabajo_empresa" type="text" name="per_ultrabajo_empresa" maxlength="30" style="width:400px;" value="<?php echo set_value('per_ultrabajo_empresa'); ?>" />
						<?php echo form_error('per_ultrabajo_empresa','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">CARGO</td>
                    <td colspan="5">
                        <input id="per_ultrabajo_cargo" type="text" name="per_ultrabajo_cargo" maxlength="30" style="width:400px;" value="<?php echo set_value('per_ultrabajo_cargo'); ?>" />
						<?php echo form_error('per_ultrabajo_cargo','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">TELEFONO</td>
                    <td colspan="5">
                        <input id="per_ultrabajo_telefono" type="text" name="per_ultrabajo_telefono" maxlength="30" style="width:400px;" value="<?php echo set_value('per_ultrabajo_telefono'); ?>" />
						<?php echo form_error('per_ultrabajo_telefono','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
					<td colspan="6" class="subtitulo">REFERENCIAS PERSONALES</td>
				</tr>
				<tr>
                    <td class="etiqueta">REFERENCIA(1)</td>
                    <td colspan="2">
                        <input id="refp_referencia[0]" type="text" name="refp_referencia[0]" maxlength="30" style="width:200px;" value="<?php echo set_value('refp_referencia[0]'); ?>" />
						<?php echo form_error('refp_referencia[0]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">REFERENCIA(2)</td>
                    <td colspan="2">
                        <input id="refp_referencia[1]" type="text" name="refp_referencia[1]" maxlength="30" style="width:200px;" value="<?php echo set_value('refp_referencia[1]'); ?>" />
						<?php echo form_error('refp_referencia[1]','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">EDAD</td>
                    <td colspan="2">
                        <input id="refp_edad[0]" type="text" name="refp_edad[0]" maxlength="30" style="width:200px;" value="<?php echo set_value('refp_edad[0]'); ?>" />
						<?php echo form_error('refp_edad[0]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">EDAD</td>
                    <td colspan="2">
                        <input id="refp_edad[1]" type="text" name="refp_edad[1]" maxlength="30" style="width:200px;" value="<?php echo set_value('refp_edad[1]'); ?>" />
						<?php echo form_error('refp_edad[1]','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">TELEFONO</td>
                    <td colspan="2">
                        <input id="refp_telefono[0]" type="text" name="refp_telefono[0]" maxlength="30" style="width:200px;" value="<?php echo set_value('refp_telefono[0]'); ?>" />
						<?php echo form_error('refp_telefono[0]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">TELEFONO</td>
                    <td colspan="2">
                        <input id="refp_telefono[1]" type="text" name="refp_telefono[1]" maxlength="30" style="width:200px;" value="<?php echo set_value('refp_telefono[1]'); ?>" />
						<?php echo form_error('refp_telefono[1]','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
					<td colspan="6" class="subtitulo">ESTADO CIVIL</td>
				</tr>
				<tr>
                    <td class="etiqueta">Estado Civil<span class="required">*</span></td>
                    <td colspan="2">
                        <?php echo form_dropdown('esc_codigo', $Combo_Estadocivil, set_value('esc_codigo', (isset($_POST['esc_codigo'])?$_POST['esc_codigo']:null)),'id="esc_codigo"');?>
						<?php echo form_error('esc_codigo','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">NUMERO DE HIJOS<span class="required">*</span></td>
                    <td colspan="2">
                        <input id="per_nro_hijos" type="number" min="0" max="10" name="per_nro_hijos" maxlength="30" style="width:100px;" value="<?php echo set_value('per_nro_hijos'); ?>" />
						<?php echo form_error('per_nro_hijos','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
					<td colspan="6" class="subtitulo">DATOS DE HIJO Y CONYUGE</td>
				</tr>
				<tr>
                    <td class="etiqueta">1. NOMBRES</td>
                    <td>
                        <input id="hic_nombres[0]" type="text" name="hic_nombres[0]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_nombres[0]'); ?>" />
						<?php echo form_error('hic_nombres[0]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">APELLIDOS</td>
                    <td>
                        <input id="hic_apellidos[0]" type="text" name="hic_apellidos[0]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_apellidos[0]'); ?>" />
						<?php echo form_error('hic_apellidos[0]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">EDAD</td>
                    <td>
                        <input id="hic_edad[0]" type="text" name="hic_edad[0]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_edad[0]'); ?>" />
						<?php echo form_error('hic_edad[0]','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">2. NOMBRES</td>
                    <td>
                        <input id="hic_nombres[1]" type="text" name="hic_nombres[1]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_nombres[1]'); ?>" />
						<?php echo form_error('hic_nombres[1]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">APELLIDOS</td>
                    <td>
                        <input id="hic_apellidos[1]" type="text" name="hic_apellidos[1]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_apellidos[1]'); ?>" />
						<?php echo form_error('hic_apellidos[1]]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">EDAD</td>
                    <td>
                        <input id="hic_edad[1]" type="text" name="hic_edad[1]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_edad[1]'); ?>" />
						<?php echo form_error('hic_edad[1]','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">3. NOMBRES</td>
                    <td>
                        <input id="hic_nombres[2]" type="text" name="hic_nombres[2]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_nombres[2]'); ?>" />
						<?php echo form_error('hic_nombres[2]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">APELLIDOS</td>
                    <td>
                        <input id="hic_apellidos[2]" type="text" name="hic_apellidos[2]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_apellidos[2]'); ?>" />
						<?php echo form_error('hic_apellidos[2]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">EDAD</td>
                    <td>
                        <input id="hic_edad[2]" type="text" name="hic_edad[2]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_edad[2]'); ?>" />
						<?php echo form_error('hic_edad[2]','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">4. NOMBRES</td>
                    <td>
                        <input id="hic_nombres[3]" type="text" name="hic_nombres[3]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_nombres[3]'); ?>" />
						<?php echo form_error('hic_nombres[3]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">APELLIDOS</td>
                    <td>
                        <input id="hic_apellidos[3]" type="text" name="hic_apellidos[3]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_apellidos[3]'); ?>" />
						<?php echo form_error('hic_apellidos[3]','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">EDAD</td>
                    <td>
                        <input id="hic_edad[3]" type="text" name="hic_edad[3]" maxlength="30" style="width:100px;" value="<?php echo set_value('hic_edad[3]'); ?>" />
						<?php echo form_error('hic_edad[3]','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
					<td colspan="6" class="subtitulo">EN CASO DE EMERGENCIA LLAMAR</td>
				</tr>
				<tr>
                    <td class="etiqueta">1. NOMBRES</td>
                    <td>
                        <input id="per_casoemer_nombres" type="text" name="per_casoemer_nombres" maxlength="30" style="width:100px;" value="<?php echo set_value('per_casoemer_nombres'); ?>" />
						<?php echo form_error('per_casoemer_nombres','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">APELLIDOS</td>
                    <td>
                        <input id="per_casoemer_apellidos" type="text" name="per_casoemer_apellidos" maxlength="30" style="width:100px;" value="<?php echo set_value('per_casoemer_apellidos'); ?>" />
						<?php echo form_error('per_casoemer_apellidos','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">TELEFONO</td>
                    <td>
                        <input id="per_casoemer_telefono" type="text" name="per_casoemer_telefono" maxlength="30" style="width:100px;" value="<?php echo set_value('per_casoemer_telefono'); ?>" />
						<?php echo form_error('per_casoemer_telefono','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
					<td colspan="6" class="subtitulo">RECOMENDADO POR</td>
				</tr>
				<tr>
                    <td class="etiqueta">1. NOMBRES</td>
                    <td>
                        <input id="per_recomendado_nombres" type="text" name="per_recomendado_nombres" maxlength="30" style="width:100px;" value="<?php echo set_value('per_recomendado_nombres'); ?>" />
						<?php echo form_error('per_recomendado_nombres','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">APELLIDOS</td>
                    <td>
                        <input id="per_recomendado_apellidos" type="text" name="per_recomendado_apellidos" maxlength="30" style="width:100px;" value="<?php echo set_value('per_recomendado_apellidos'); ?>" />
						<?php echo form_error('per_recomendado_apellidos','<div class="error">','</div>'); ?>
                    </td>
                    <td class="etiqueta">TELEFONO</td>
                    <td>
                        <input id="per_recomendado_telefono" type="text" name="per_recomendado_telefono" maxlength="30" style="width:100px;" value="<?php echo set_value('per_recomendado_telefono'); ?>" />
						<?php echo form_error('per_recomendado_telefono','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Foto Persona</td>
                    <td colspan="5">      
						<div id="div_ifile" style="DISPLAY:block;"><input id="input_file" type="file" name="input_file" size="35" accept="image/jpeg, image/png" /><br><img width="60px" id="img_ufile" src="<?php echo base_url(); ?>images/ufile.png" class="xcssfile" style="cursor:pointer;" alt="Cargar Archivo" title="Cargar Archivo" /><br><label class="urestricciones">Cargar archivos de tipo: JPG, PNG</label></div>
		                <div id="div_ilist" style="DISPLAY:none;"><div id="div_list" style="float:left"><a id="a_list" class="uviewfile" target="_blank"></a></div>&nbsp;<img id="img_dfile" src="<?php echo base_url(); ?>images/delete.gif" style="cursor:pointer;" alt="Quitar Archivo" title="Quitar Archivo" onClick="return eliminar_file(this);" /></div>
		                <input id="h_per_foto" type="hidden" name="h_per_foto" value="<?php echo set_value('h_per_foto'); ?>" /></td>
                    </td>
                </tr>           
				<tr>
                    <td class="etiqueta">Acceso al Sistema<span class="required">*</span></td>
                    <td colspan="5">
                        <select id="per_tiene_acceso" name="per_tiene_acceso" onchange="evaluarSeleccion2(this.value)">
                            <option value="">-- Seleccione --</option>
                            <option value="S" <?php echo set_select('per_tiene_acceso', 'S'); ?>>Si</option>
                            <option value="N" <?php echo set_select('per_tiene_acceso', 'N'); ?>>No</option>
                        </select>
                        <?php echo form_error('per_tiene_acceso','<div class="error">','</div>'); ?>
                    </td>
                </tr>
				<tr id="tr_seleccione_1" style="DISPLAY:none">
					<td colspan="6">&nbsp;</td>
				</tr>
				<tr id="tr_seleccione_2" style="DISPLAY:none">
					<td colspan="6" class="subtitulo">Datos de Acceso</td>
				</tr>
                <tr id="tr_seleccione_3" style="DISPLAY:none">
                    <td class="etiqueta" colspan="2">Seleccione Roles</td>
                    <td colspan="4">
                    	<?php $this->table->set_heading(
											array('data'=>'Rol','width'=>'80%'), 
											array('data'=>'Seleccionar','width'=>'20%')); ?>
						<?php $template = array('table_open'=>'<table id="tb_resultado" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:50%;"'); ?>
						<?php $this->table->set_template($template); ?>
						<?php foreach ($Listado_Entidad as $lentidad) : ?>
							<?php $this->table->add_row(
													array('data'=>$lentidad->rol_nombre,'class'=>'left'), 
													array('data'=>'<input id="seleccionar_'.$lentidad->rol_codigo.'" type="checkbox" name="seleccionar_'.$lentidad->rol_codigo.'" value="'.$lentidad->rol_codigo.'">','class'=>'center')); ?>
						<?php endforeach; ?>
						<?php echo $this->table->generate(); ?>
                    </td>
                </tr>
                <tr id="tr_usu_login" style="DISPLAY:none">
                    <td class="etiqueta" colspan="2">Login de Acceso<span class="required">*</span></td>
                    <td colspan="4">
                        <input id="usu_login" type="text" name="usu_login" maxlength="30" autocomplete="off" style="width:150px;" value="<?php echo set_value('usu_login'); ?>" />
						<?php echo form_error('usu_login','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr id="tr_usu_password" style="DISPLAY:none">
                    <td class="etiqueta" colspan="2">Contraseña<span class="required">*</span></td>
                    <td colspan="4">
                        <input id="usu_password" type="password" name="usu_password" maxlength="30" autocomplete="off" style="width:150px;" value="<?php echo set_value('usu_password'); ?>" />
						<?php echo form_error('usu_password','<div class="error">','</div>'); ?>
                    </td>
                </tr>
				<tr>
                    <td class="etiqueta">Estado<span class="required">*</span></td>
                    <td colspan="5">
                        <input name="per_estado" type="radio" id="per_estadoA" value="A" <?php echo set_radio('per_estado', 'A', TRUE); ?>><label>Activo</label>
						<input name="per_estado" type="radio" id="per_estadoB" value="B" <?php echo set_radio('per_estado', 'B'); ?>><label>Bloqueado</label>
						<?php echo form_error('per_estado','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" height="40" align="center" style="vertical-align:middle;">
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
<script>	
    var obj = document.forms[0];

    var objpas = document.getElementById('per_tiene_acceso');
    var per_tiene_acceso = objpas.options[objpas.selectedIndex].value;
    evaluarSeleccion2(per_tiene_acceso);

    <?php if(isset($_POST['ubi_coddep'])): ?>
    retorna_Provincia('<?php echo $_POST['ubi_coddep']; ?>', '<?php echo $_POST['h_ubi_codprov']; ?>', 1)
    <?php endif; ?>
    <?php if(isset($_POST['h_ubi_codprov'])): ?>
    retorna_Distrito('<?php echo $_POST['h_ubi_codprov']; ?>', '<?php echo $_POST['h_ubi_coddis']; ?>', 1)
    <?php endif; ?>

    <?php if(isset($_POST['h_per_foto'])): ?>
        var per_foto = '<?php echo $_POST['h_per_foto']; ?>'
        if(per_foto != ''){
            document.getElementById('div_ifile').style.display = 'none';
            document.getElementById('div_ilist').style.display = 'block';
            document.getElementById('div_list').innerHTML = '<a id="a_list" href="'+ base_url +'files/'+ per_foto +'" class="uviewfile" target="_blank">'+ per_foto +'</a>';
        }
    <?php endif; ?> 

    <?php if(isset($_POST['h_listado_roles'])): ?>
        seleccionarChecked('<?php echo $_POST['h_listado_roles']; ?>');
    <?php endif; ?>
</script>