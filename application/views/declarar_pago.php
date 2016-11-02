<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
    <title>:: Hitalo Agro - Sistema de Gestión de Operaciones ::</title>
	<link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.ico" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/normalize.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/estilos.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/form.css">
    <link rel="stylesheet" href="<?php echo base_url();?>simplemodal/css/simplemodal.css" />
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
    <script>jQuery.noConflict();</script>
    <script>var base_url = '<?php echo base_url(); ?>';</script>
    <script src="<?php echo base_url();?>windowfiles/dhtmlwindow.js"></script>
    <script src="<?php echo base_url();?>modalfiles/modal.js"></script>
    <script src="<?php echo base_url();?>simplemodal/js/mootools-core-1.3.1.js"></script>
    <script src="<?php echo base_url();?>simplemodal/js/mootools-more-1.3.1.1.js"></script>
    <script src="<?php echo base_url();?>simplemodal/js/simple-modal.js"></script>
    <script src="<?php echo base_url();?>simplemodal/js/init10.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){            
			jQuery(this).on('submit',function(e){
				jQuery('input[type=submit]').attr('disabled',true);
			});
			jQuery("#eap_fecha_pago").datepicker();
			jQuery("#per_nombres_completo").autocomplete({
				minLength: 1,
				source: function( request, response ) {
					var objzod = document.getElementById('zod_codigo');
					var zod_codigo = objzod.options[objzod.selectedIndex].value;

					jQuery.ajax({
                		type: 'POST',
						url: '<?php echo base_url().'persona/lista_productor_ajax';?>',
						dataType: "json",
						data: 'per_nombres_completo='+ request.term +'&zod_codigo='+ zod_codigo +'&tir_codigo=1&ajax=1',
						success: function( data ) {
							response( data );							
						}
					});
				},
				response: function(event, ui) {
					if (ui.content.length === 0){
						limpia_persona();
						limpia_detalle_entregas();
					}
				},
				select: function( event, ui ) {
					event.preventDefault();
					jQuery(this).val(ui.item.label);
					var per_codigo = ui.item.value;
					jQuery("#h_per_codigo").val(per_codigo);
					jQuery("#per_direccion_domicilio").val(ui.item.per_direccion_domicilio);
					jQuery("#per_nro_documento").val(ui.item.per_documento_identidad);					
					obtiene_entregas_agricultor(per_codigo, 0);
				},
				open: function() {
					jQuery(this).removeClass("ui-corner-all").addClass("ui-corner-top");
					limpia_persona();
					limpia_detalle_entregas();
				},
				close: function() {
					jQuery(this).removeClass("ui-corner-top").addClass("ui-corner-all");
				}
		  	});	  	
		  	jQuery.ui.autocomplete.prototype._renderItem = function( ul, item){
	          var term = this.term.split(' ').join('|');
	          var re = new RegExp("(" + term + ")", "gi");
	          var t = normalize(item.label).replace(re,"<strong>$1</strong>");
	          return jQuery( "<li></li>" )
	             .data( "item.autocomplete", item )
	             .append( "<a>" + t + "</a>" )
	             .appendTo( ul );
	        };
        });

		function obtiene_entregas_agricultor(per_codigo, tipo){
			var obj = document.forms[0];
			limpia_detalle_entregas();

			jQuery.ajax({
				type: "POST",
				url: "<?php echo site_url("entregas_agricultor/retornaentregas_ajax"); ?>",
				data: "per_codigo="+per_codigo+"&ajax=1",
				dataType: "json",
				beforeSend: function() {                            
					jQuery("#div_more").toggleClass("div_more_pag loadingAjaxClass")
										.css("display", "block");
				},
				success: function(data) {
					var newRecords = jQuery("tbody", data.newtable).html();
					jQuery("#tb_resultado2 tr:last").after(newRecords);

					var gea_detalle = document.getElementById('h_gea_detalle').value;
					llena_detalle_entregas(gea_detalle);
				},
				complete: function() {
					jQuery("#div_more").css("display", "none");                      
				}
			});
		}

		function verifica_persona_vacio(value){
			if(trim(value)==""){
				limpia_persona();
				limpia_detalle_entregas();
			}
		}

		function limpia_persona(){
			document.getElementById('h_per_codigo').value = "";
			document.getElementById('per_nro_documento').value = "";
			document.getElementById('per_direccion_domicilio').value = "";
		}

		function limpia_detalle_entregas(){
			var table = document.getElementById('tb_resultado2');
			var filas = table.getElementsByTagName('tr');
			if(filas.length > 1){
				for(var i=filas.length; i>1; i--){
					table.deleteRow(i-1);
				}
			}
		}

		function valida_detalle_entregas(){
			var table = document.getElementById('tb_resultado2');
			var filas = table.getElementsByTagName('input');
			var filas_selected = 0;
			var filas_nro = filas.length / 5;
			for(var i=1; i<=filas_nro; i++){				
				var h_eag_codigo = document.getElementById('h_eag_codigo_'+i).value;
				var obj_seleccionar = document.getElementById('seleccionar_'+i);
				if(obj_seleccionar.checked){
					var objtipo = document.getElementById('eag_tipo_pago_'+i);
					var eag_tipo_pago = objtipo.options[objtipo.selectedIndex].value;
					if(eag_tipo_pago==""){
						alert("La fila N° "+i+" debe seleccionar el tipo de cancelación");
						objtipo.focus();
						return false;
					}
					if(eag_tipo_pago=="P"){
						var gea_monto = document.getElementById('gea_monto_'+i).value;
						if(gea_monto == ""){
							alert("La fila N° "+i+" debe ingresar el monto parcial");
							document.getElementById('gea_monto_'+i).focus();
							return false;
						}

						if(parseFloat(gea_monto) <= 0){
							alert("La fila N° "+i+" debe ingresar un monto mayor a 0");
							document.getElementById('gea_monto_'+i).focus();
							return false;
						}
					}
					filas_selected += 1;
				}			
			}

			if(filas_selected == 0){
				return false;
			}

			return true;
		}

		function obtiene_detalle_entregas(){
			var cadena_detalle = "";
			var table = document.getElementById('tb_resultado2');
			var filas = table.getElementsByTagName('input');
			var filas_vacio = 0;
			var filas_nro = filas.length / 5;
			for(var i=1; i<=filas_nro; i++){
				var h_eag_codigo = document.getElementById('h_eag_codigo_'+i).value;
				var obj_seleccionar = document.getElementById('seleccionar_'+i);
				if(obj_seleccionar.checked){
					var objtipo = document.getElementById('eag_tipo_pago_'+i);
					var eag_tipo_pago = objtipo.options[objtipo.selectedIndex].value;
					var gea_monto = document.getElementById('gea_monto_'+i).value;

					cadena_detalle += h_eag_codigo+'#'+eag_tipo_pago+'#'+gea_monto+'@';
				}
			}

			if(cadena_detalle != ''){
				cadena_detalle = cadena_detalle.substring(0,cadena_detalle.length-1);	
			}

			return cadena_detalle;
		}

		function llena_detalle_entregas(gea_detalle){
			if(gea_detalle=="") return false;

			var gea_detallearr = gea_detalle.split('@');
			var filas_nro = gea_detallearr.length;
			for(var i=1; i<=filas_nro; i++){
				var fila_detalle = gea_detallearr[i-1];
				var fila_detallearr = fila_detalle.split('#');

				if(fila_detallearr[2] != ""){
					var eag_codigo = fila_detallearr[0];
					var nro_fila = document.getElementById('h_nro_fila_'+eag_codigo).value;

					document.getElementById('seleccionar_'+nro_fila).checked = true;
					document.getElementById('eag_tipo_pago_'+nro_fila).value = fila_detallearr[1];
					document.getElementById('gea_monto_'+nro_fila).value = fila_detallearr[2];

					document.getElementById('eag_tipo_pago_'+nro_fila).disabled = false;
	    			document.getElementById('gea_monto_'+nro_fila).disabled = false;

	            	var eag_tipo_pago = fila_detallearr[1];
					if(eag_tipo_pago != "P"){
	        			document.getElementById('gea_monto_'+nro_fila).readOnly = false;
					}
				}
			}
			calcula_geatot();
		}

		function calcula_geatot(){
			var gea_monto_tot = 0;
			var table = document.getElementById('tb_resultado2');
			var inputs = table.getElementsByTagName('input');
			for(var i=1; i<=inputs.length; i++){
				if(inputs[i-1].id.indexOf('gea_monto') > -1){
					var gea_monto = trim(inputs[i-1].value);
					if(gea_monto != ''){
						var get_alias = getNewProperty(inputs[i-1],'xget_alias');						
						gea_monto_tot += parseFloat(gea_monto);
					}
				}
			}
			document.getElementById('td_gea_monto_tot').innerHTML = roundOff(gea_monto_tot, 2);
		}

		function evaluarIngreso(valor, nro_fila){
			if(valor==""){
				calcula_geatot();
				return false;
			}

			var eag_saldo = parseFloat(document.getElementById('td_eag_saldo_'+nro_fila).innerHTML);
			var gea_monto = parseFloat(valor);
			if(eag_saldo<=gea_monto){
				alert('Debe ingresar un monto menor al saldo pendiente');				
            	document.getElementById('gea_monto_'+nro_fila).value = "";
            	document.getElementById('gea_monto_'+nro_fila).focus();
			}

			calcula_geatot();
		}
		
        function evaluarSeleccion2(valor, nro_fila){
        	var obj = document.forms[0];
            switch(valor){
                case 'T':
                	var eag_saldo = document.getElementById('td_eag_saldo_'+nro_fila).innerHTML;
                    document.getElementById('gea_monto_'+nro_fila).value = eag_saldo;
    				document.getElementById('gea_monto_'+nro_fila).readOnly = true;
    				calcula_geatot();
					break;
                case 'P':
                	document.getElementById('gea_monto_'+nro_fila).value = "";
                	document.getElementById('gea_monto_'+nro_fila).readOnly = false;
                	document.getElementById('gea_monto_'+nro_fila).focus();
                	calcula_geatot();
                    break; 
                case '':
                	document.getElementById('gea_monto_'+nro_fila).value = "";
                	document.getElementById('gea_monto_'+nro_fila).readOnly = true;
                	calcula_geatot();
            }
        }

        function evaluarSeleccion3(valor, tipo){
        	if(valor == ''){
        		document.getElementById('per_nombres_completo').disabled = true;
        	}else{
        		document.getElementById('per_nombres_completo').disabled = false;
        	}

        	if(tipo == 0){
        		document.getElementById('per_nombres_completo').value = "";
        		limpia_persona();
				limpia_detalle_entregas();
        	}
        }

        function evaluarChecked(obj, nro_fila){
    		if(obj.checked){
    			document.getElementById('eag_tipo_pago_'+nro_fila).disabled = false;
    			document.getElementById('gea_monto_'+nro_fila).disabled = false;
            	document.getElementById('gea_monto_'+nro_fila).readOnly = true;
    		}else{
    			document.getElementById('eag_tipo_pago_'+nro_fila).disabled = true;
    			document.getElementById('gea_monto_'+nro_fila).disabled = true;
    			document.getElementById('eag_tipo_pago_'+nro_fila).selectedIndex = 0;
    			document.getElementById('gea_monto_'+nro_fila).value = "";
    			calcula_geatot();
    		}
        }
		
		function consistenciar(){
			var obj = document.forms[0];
			
			if (!verifica(objFormulario(obj,'h_per_codigo'),'Debe seleccionar el Nombre del productor')){
				return false;
			}
			if (!verifica(objFormulario(obj,'eap_fecha_pago'),'Debe ingresar la Fecha de pago')){
				return false;
			}
			if (!validar_Fecha(objFormulario(obj,'eap_fecha_pago'))) {
			    return false;
		    }		    
		    if(!compareFechas(objFormulario(obj,'eap_fecha_pago').value,objFormulario(obj,'h_fecha_actual').value, 'La Fecha de pago no puede ser mayor a la Fecha actual')){
				return false;
			}			
            if(!valida_detalle_entregas()){
            	alert('El detalle de entregas se encuentra vacío, no se puede procesar')
            	return false;
            }
			
            var objzod = document.getElementById('zod_codigo');
			var zod_codigo = objzod.options[objzod.selectedIndex].value;
			document.getElementById('h_zod_codigo').value = zod_codigo;
			
            obj.h_gea_detalle.value = obtiene_detalle_entregas();
			return true;
		}

		function regresar(){
			window.location = '<?php echo obtener_paginicio('EAG'); ?>';
		}
    </script>
</head>
<body>
<?php echo form_open('entregas_agricultor/declarar_pagosave', array('id'=>'Form1')); ?>
	<input id="h_fecha_actual" type="hidden" name="h_fecha_actual" value="<?php $d=isset($fecha_actual)?$fecha_actual:null; echo set_value('h_fecha_actual',$d) ?>" />
    <input id="h_zod_codigo" type="hidden" name="h_zod_codigo" />
	<input id="h_gea_detalle" type="hidden" name="h_gea_detalle" />
    <header>
		<?php echo $header; ?>
	</header>
	<nav>
		<?php echo $nav; ?>
	</nav>
	<section>
		<div id="contenido">
			<hgroup><h1>Declarar Pago Directo Entregas</h1></hgroup>
            <br>
            <?php if (isset($mensaje_error)): ?>
                <div class="mensaje_error"><?php echo $mensaje_error; ?></div>
            <?php endif; ?>
			<table style="width:100%;" class="Formulario">
				<tr>
					<td colspan="2" class="subtitulo">Datos Generales</td>
				</tr>
                </tr>
					<td class="etiqueta">Zona Distribución<span class="required">*</span></td>
                    <td>
						<?php (!empty($zod_codigo))?$cob_style='disabled':$cob_style=''; ?>
						<?php echo form_dropdown('zod_codigo', $Combo_Zonadistribucion, set_value('zod_codigo', (isset($_POST['h_zod_codigo'])?$_POST['h_zod_codigo']:$zod_codigo)), 'id="zod_codigo" onchange="evaluarSeleccion3(this.value, 0)" '.$cob_style);?>
						<?php echo form_error('h_zod_codigo','<div class="error">','</div>'); ?>
					</td>
                </tr>
                <tr>
                    <td width="38%" class="etiqueta">Nombres Productor<span class="required">*</span></td>
                    <td width="62%">
                    	<input id="per_nombres_completo" type="text" name="per_nombres_completo" maxlength="120" style="width:250px;" value="<?php echo set_value('per_nombres_completo'); ?>" onblur="verifica_persona_vacio(this.value)" disabled />
                    	<input id="h_per_codigo" type="hidden" name="h_per_codigo" value="<?php echo set_value('h_per_codigo'); ?>" />
						<?php echo form_error('h_per_codigo','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta2">Dirección</td>
                    <td>
                    	<input id="per_direccion_domicilio" type="text" name="per_direccion_domicilio" maxlength="120" style="width:300px;" value="<?php echo set_value('per_direccion_domicilio'); ?>" readonly />
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta2">Documento Identidad</td>
                    <td>
                    	<input id="per_nro_documento" type="text" name="per_nro_documento" maxlength="30" style="width:150px;" value="<?php echo set_value('per_nro_documento'); ?>" readonly />
                    </td>
                </tr>
                <tr>
                    <td class="etiqueta">Fecha Pago<span class="required">*</span></td>
                    <td>
                    	<input id="eap_fecha_pago" type="text" name="eap_fecha_pago" maxlength="10" style="width:100px;" value="<?php echo set_value('eap_fecha_pago'); ?>" />
						<?php echo form_error('eap_fecha_pago','<div class="error">','</div>'); ?>
                    </td>
                </tr>
                <tr>
		            <td class="etiqueta">Observaciones</td>
		            <td>
						<div style="width:325px;text-align:right;font-size:11px;"><input id="label_eap_observaciones" type="text" name="label_eap_observaciones" class="ta_caracteres" style="width:22px;" value="300" tabindex="-1" readonly>caracteres restantes</div>
						<textarea id="eap_observaciones" type="text" name="eap_observaciones"  style="width:325px;height:65px;" maxlength="300" xlabel="label_eap_observaciones" onkeyup="contarCaracteres(this);"; ><?php echo set_value('eap_observaciones'); ?></textarea>
		            </td>
		        </tr>
                <tr>
					<td colspan="2">&nbsp;</td>
				</tr>
                <tr>
					<td colspan="2" class="subtitulo">Detalle de Entregas a Agricultor</td>
				</tr>
				<tr>
                    <td colspan="2">
                    	<div id="div_more" class="div_more_pag" style="width:32px;DISPLAY:none;"></div>
                        <?php $this->table->set_heading(
                                                    array('data'=>'N°','width'=>'3%'), 
                                                    array('data'=>'Fecha','width'=>'8%'), 
                                                    array('data'=>'Desc. Entregas','width'=>'24%'), 
                                                    array('data'=>'Responsable','width'=>'10%'),
                                                    array('data'=>'Cantidad','width'=>'7%'),
                                                    array('data'=>'Precio/Tasa','width'=>'8%'),
                                                    array('data'=>'Valor','width'=>'7%'),
                                                    array('data'=>'Saldo','width'=>'7%'),
                                                    array('data'=>'&nbsp;','width'=>'4%'),
                                                    array('data'=>'Tipo Cancelación','width'=>'10%'),
                                                    array('data'=>'Monto Pago','width'=>'9%'),
                                                    array('data'=>'&nbsp;','width'=>'3%')); ?>
                        <?php $template = array('table_open'=>'<table id="tb_resultado2" border="1" cellpadding="1" cellspacing="0" class="Grilla" style="width:100%;"'); ?>
                        <?php $this->table->set_template($template); ?>
                        <?php echo $this->table->generate(); ?>
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
<script>	
	var obj = document.forms[0];

    var objzod = document.getElementById('zod_codigo');
    var zod_codigo = objzod.options[objzod.selectedIndex].value;
    evaluarSeleccion3(zod_codigo, 1);

    var label_eap_observaciones = document.getElementById('label_eap_observaciones').value;
    document.getElementById('label_eap_observaciones').value = label_eap_observaciones - obj.eap_observaciones.value.length;

	<?php if(isset($_POST['h_per_codigo'])): ?>
		var h_gea_detalle = '<?php echo $_POST['h_gea_detalle']; ?>';
		document.getElementById('h_gea_detalle').value = h_gea_detalle;
		obtiene_entregas_agricultor('<?php echo $_POST['h_per_codigo']; ?>', 1);
	<?php endif; ?>
</script>