<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function obtener_responsabilidad(){
	$CI =& get_instance();
	$session_data = $CI->session->userdata('logged_in');

	if (($session_data['Session_AliasRol']=='CAJERO') || ($session_data['Session_AliasRol']=='VENDED')){
		return $session_data['Session_NombreResponsabilidad'];
	}else{
		return $session_data['Session_NombreEmpresa'];
	}
}

function genera_numero_correlativo($tipo_entidad){
	$CI =& get_instance();

	$numero_correlativo = $CI->parametros_generalesModel->genera_numero_correlativo($tipo_entidad);
	return $numero_correlativo;
}

function genera_numero_correlativo2($tipo_entidad){
	$CI =& get_instance();

	$numero_correlativo = $CI->parametros_generalesModel->genera_numero_correlativo2($tipo_entidad);
	return $numero_correlativo;
}

function genera_numero_documento($zod_codigo, $zodn_tipo){
	$CI =& get_instance();

	$numero_documento = $CI->zona_distribucionModel->genera_numero_documento($zod_codigo, $zodn_tipo);
	return $numero_documento;
}

function genera_numero_documento_save($zod_codigo, $zodn_tipo, $zodn_numero_completo){
	$CI =& get_instance();

	$CI->zona_distribucionModel->genera_numero_documento_save($zod_codigo, $zodn_tipo, $zodn_numero_completo);
}

function obtener_roles(){
	$CI =& get_instance();

	$session_data = $CI->session->userdata('logged_in');
	$comboRol = $CI->usuario_rolModel->consulta_usuario_rol_codusu($session_data['Session_IdUsuario']);
    return set_dropdown($comboRol, 'rol_codigo', 'rol_nombre', 0);
}

function obtener_rol_selected(){
	$CI =& get_instance();

	$session_data = $CI->session->userdata('logged_in');
	return $session_data['Session_RolUsuario'];
}

function obtener_alias_rol_selected(){
	$CI =& get_instance();

	$session_data = $CI->session->userdata('logged_in');
	return $session_data['Session_AliasRol'];
}

function obtener_estado_rol_selected(){
	$CI =& get_instance();

	$session_data = $CI->session->userdata('logged_in');
	return $session_data['Session_RolSelected'];
}

function obtener_menu(){
	$CI =& get_instance();

	$session_data = $CI->session->userdata('logged_in');
	return $session_data['Session_RolMenu'];
}

function obtener_paginicio($entidad){
	$CI =& get_instance();
	$text_session = 'Session_form'.$entidad.'Paginicio';
	$Session_variable = $CI->session->userdata($text_session);
	if(isset($Session_variable)) {
		if(!empty($Session_variable)) return $Session_variable;
	}

	return "#";
}

function generar_menu($rol_codigo){
	$CI =& get_instance();

	if($CI->menu == ''){
		//$rol_codigo = obtener_rol_selected();
		$result = $CI->rol_funcionalidad_permisoModel->consulta_rol_funcionalidad_permiso_codrol($rol_codigo);
		
		$CI->menu .= '<ul id="navmenu">';
		construir_menu($result, 0, TRUE);
		$CI->menu .= '</ul>';
	}
	return $CI->menu;
}

function construir_menu($result, $padre, $b_ini){
	$CI =& get_instance();

	$t_hijos = buscar_menu($result, $padre);
	if(count($t_hijos) == 0){
		return;
	}else{
		if($b_ini == FALSE) $CI->menu .= '<ul>';			
		foreach ($t_hijos as $row_h){
			$ThisID = $row_h[0];
			$ThisName = $row_h[1];
			$thisNewURL = $row_h[2];
			if ($thisNewURL != '#') $thisNewURL = base_url().$thisNewURL;
			if (tieneHijos($result, $ThisID) == TRUE){
				$CI->menu .= '<li><a href="'.$thisNewURL.'" target="_self">'.$ThisName.'</a>';
			}else{
				$CI->menu .= '<li><a href="'.$thisNewURL.'" target="_self">'.$ThisName.'</a></li>';
			}
			
			construir_menu($result, $ThisID, False);
		}
		if($b_ini == FALSE) $CI->menu .= "</ul></li>";
	}
}

function buscar_menu($result, $id){
	$array = array();
	foreach ($result as $row){
		$id_r = sies_null($row->fun_codigo_padre, 0);
		if ($id_r === $id)
			$array[] = array_merge((array)$row->fun_codigo, (array)$row->fun_nombre, (array)sies_null($row->fun_url,"#"));
	}
	return $array;
}

function tieneHijos($result, $id){
	foreach ($result as $row){
		$id_r = sies_null($row->fun_codigo_padre, 0);
		if ($id_r === $id)
			return TRUE;
	}
	return FALSE;
}

function enviar_email($from, $fromname, $toarr, $subject, $body){
	require_once(str_replace("\\","/",APPPATH).'libraries/PHPMailer/class.phpmailer.php');
		
	$mail = new PHPMailer();
	
	$mail->From = $from;
	$mail->FromName = $fromname;
	foreach($toarr as $to){
		$mail->AddAddress($to);
	}
	
	$mail->WordWrap = 50;
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->CharSet = 'UTF-8';
	
	$mail->Mailer = "smtp";
	$mail->Host = GL_CORREO_SERVIDOR;
	$mail->SMTPDebug  = 1;
	$mail->SMTPAuth = true;
	$mail->Username = GL_CORREO_USUARIO;
	$mail->Password = GL_CORREO_CONTRASENA;

	if(!$mail->Send()){
		return false;
	}else{
		return true;
	}
}

function obtener_NroPendientes(){
	$CI =& get_instance();
	$CI->load->model('operacionesModel','',TRUE);
	$session_data = $CI->session->userdata('logged_in');
	$result = $CI->operacionesModel->consulta_operaciones_pendientes();
	return $result;
}

function acceso_denegado($pagina){
	$CI =& get_instance();
	$session_data = $CI->session->userdata('logged_in');
	
	$aud_descripcion = "Página: '".$pagina."'";
	$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => ENT_SISTEMA, 'acc_codigo' => ACC_ACCESO_DENEGADO, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
	$CI->hitaloagroModel->add('auditoria', $data);
	
	redirect('home/index', 'refresh');
}

function acceso_bloqueado($entidad){
	//delete_session($entidad);

	$CI =& get_instance();	
	$session_data = $CI->session->userdata('logged_in');
	if(empty($session_data['Session_RolSelected'])){
		return false;		
	}

	return true;
}
/*
function delete_session($entidad){
	$CI =& get_instance();

	$user_data = $CI->session->all_userdata();
	foreach ($user_data as $key => $value) {
		$key = $key.'';
		if(strrpos($key, "Session_form") !== false){
			if(strrpos($key, "Session_form".$entidad) === false){
				$CI->session->unset_userdata($key);
			}
		}
	}
}
*/
function obtiene_ValorSession($text_session){
	$CI =& get_instance();

	$Session_variable = $CI->session->userdata($text_session);
	if(isset($Session_variable)) {
		if(!empty($Session_variable)) return $Session_variable;
	}

	return "";
}

function obtiene_ValorSession2($text_session){
	$CI =& get_instance();
	$session_data = $CI->session->userdata('logged_in');

	if(isset($session_data[$text_session])) {
		return $session_data[$text_session];
	}

	return NULL;
}

function obtiene_ValorDec($valor){	
	return substr($valor, strpos($valor, ".")+1);
}

function setCellValueH($sheet, $colrow, $value){
	$sheet->setCellValue($colrow, $value);
	$sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$sheet->getStyle($colrow)->getFont()->setName('Arial')
									->setSize(10)
									->setBold(true);
}

function setCellValueI($sheet, $colrow, $value, $aligment){
	$sheet->setCellValue($colrow, $value);		
	if ($aligment=='C') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); }
	elseif ($aligment=='L') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); }
	elseif ($aligment=='R') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); }
}

function setCellValueIExplicit($sheet, $colrow, $value, $aligment){
	$sheet->setCellValueExplicit($colrow,$value,PHPExcel_Cell_DataType::TYPE_STRING);
	if ($aligment=='C') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); }
	elseif ($aligment=='L') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); }
	elseif ($aligment=='R') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); }
}

function setCellValueI2($sheet, $colrow, $value, $aligment, $color){
	$sheet->setCellValue($colrow, $value);		
	if ($aligment=='C') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); }
	elseif ($aligment=='L') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); }
	elseif ($aligment=='R') { $sheet->getStyle($colrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); }

	$sheet->getStyle($colrow)->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $color)
            )
        )
    );
}

function settext_estado($value){
	if ($value == 'A')
		return 'Activo';
	else if (($value == 'X'))
		return 'Eliminado';
	else
		return 'Bloqueado';	
}

function settext_estado2($value){
	if ($value == 'C')
		return 'Confirmado';
	else
		return 'No Confirmado';	
}

function settext_estado3($value){
	if ($value == 'S')
		return 'Si';
	else if($value == 'N')
		return 'No';	
}

function settext_estado4($value){
	if ($value == 'M')
		return 'Monto';
	else if($value == 'Y')
		return 'Monto y Cantidad';	
}

function settext_estado5($value){
	if ($value == 'I')
		return 'Interno';
	else if($value == 'E')
		return 'Externo';	
}

function settext_estado6($value){
	if ($value == 'Z')
		return 'Zonal';
	else if($value == 'P')
		return 'Puesto';	
	else if($value == 'E')
		return 'Empresa';	
}

function settext_estado7($value){
	if ($value == 'N')
		return 'Natural';
	else if($value == 'J')
		return 'Jurídica';
}

function settext_estado8($value){
	if ($value == 'M')
		return 'Masculino';
	else if($value == 'F')
		return 'Femenino';
}

function settext_estado9($value){
	if ($value == 'L')
		return 'Guía Liquidación';
	else if($value == 'A')
		return 'Abastecimiento';
}

function settext_estado10($value){
	if ($value == 'P')
		return 'Parcial';
	else if($value == 'T')
		return 'Total';
}

function settext_estado11($value){
	if ($value == 'P')
		return 'Pendiente';
	else if($value == 'C')
		return 'Cancelado';
}

function settext_estado12($value){
	if ($value == 'C')
		return 'A Crédito';
	else if($value == 'O')
		return 'Al Contado';
}

function settext_estado13($value){
	if ($value == 'B')
		return 'Boleta';
	else if($value == 'F')
		return 'Factura';
}

function settext_estado14($value){
	if ($value == 'A')
		return 'A cuenta';
	else if($value == 'P')
		return 'Pago';
}

function sies_null_ZD($valor){
	if(isset($valor)){
        return $valor;
    }else{
        return 'Gerencia Lima';
    }
}

function sies_null($valor, $valor_cambio){
    if(isset($valor)){
        return $valor;
    }else{
        return $valor_cambio;
    }
}

function sies_vacio($valor, $valor_cambio){
    if($valor == ''){
        return $valor_cambio;
    }else{
        return $valor;
    }
}

function set_tvacio($valor){
    if(trim($valor) == 'vacio'){
        return NULL;
    }else{
        return $valor;
    }
}

function set_null($valor){
    if(trim($valor) == ''){
        return NULL;
    }else{
        return $valor;
    } 
}

function set_null2($valor){
	if(trim($valor) == ''){
        return NULL;
    }else{
        if(floatval($valor) == 0){
	        return NULL;
	    }else{
	        return $valor;
	    } 
    }   
}

function setvalue_checkbox($input, $value_true, $value_false){
    if(!empty($input))
        return $value_true;
    else
        return $value_false;
}

function setbool_checkbox($value, $value_true){
    if($value == $value_true)
        return TRUE;
    else
        return FALSE;
}

function setbool_radio($value, $value_true){
    if($value == $value_true)
        return TRUE;
    else
        return FALSE;
}

function setbool_radio2($value, $value_true, $value_true2){
    if($value != ''){
    	if($value_true == $value_true2)
        	return TRUE;
    }else{
        if($value_true != $value_true2)
        	return TRUE;
    }
}

function set_bool($value, $value_true){
    if($value == $value_true)
        return TRUE;
    else
        return FALSE;
}
    
function set_dropdown($result, $id, $value, $iitem=1){
	$result_array = array();	
	$val_inicial;
	$item_inicial;
	if($iitem == 1){ $val_inicial=''; $item_inicial = '-- Seleccione --'; }
	elseif($iitem == 2){ $val_inicial=''; $item_inicial = '-- Todos --'; }
	elseif($iitem == 3){ $val_inicial='0'; $item_inicial = '-- Seleccione --'; }
	elseif($iitem == 4){ $val_inicial='0'; $item_inicial = '-- Todos --'; }
	elseif($iitem == 5){ $val_inicial=''; $item_inicial = '--'; }
	
	if($iitem != 0) $result_array[$val_inicial] = $item_inicial;
    if($result){
	    foreach($result as $item){
		    $result_array[$item->$id] = str_replace(' ', '&nbsp;', $item->$value);
	    }
    }
	return $result_array;
}

function getIPAddress(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	   
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
   
	return $_SERVER['REMOTE_ADDR'];
}

function dateFormat($date) {
    if ($date=='') return null;

    $date = str_replace('/', '-', $date);
    return date("Y-m-d", strtotime($date));
}

function dateFormat_i($date, $type) {
    if ($date=='') return null;

    $date = str_replace('/', '-', $date);
    $time = '';
    if($type=='A')
        $time = '00:00:00';
    else
        $time = '23:59:59';

    return date("Y-m-d H:i:s", strtotime($date." ".$time));
}
	
function verificar_caducidad($fecha){
	if ($fecha == null) return true;
	
	$fecha_actual = strtotime(date('Y-m-d'));
	$fecha_entrada = strtotime($fecha);
	if($fecha_actual > $fecha_entrada){
		return false;
	}
	return true;
}

function obtenerNroDiasMes($anio, $mes){
	$fecha = mktime(0, 0, 0, $mes, 1, $anio); 
	return date("t", $fecha);
}

function obtenerMesAnterior($anio, $mes, $restaMeses){
	$resultMes = ($mes - $restaMeses) + 1;
	if($resultMes >= 1){
		$anioI = $anio;
		$mesI = $resultMes;
		return $mesI.'/'.$anioI;
	}else{
		$anioI = $anio - 1;
		$mesI = (12 - ($restaMeses - $mes)) + 1;
		return $mesI.'/'.$anioI;
	}
}

function validarMostrarCal($mes){
	$resultMes = ($mes - GL_MESES_ANTERIOR) + 1;
	if($resultMes >= 1){
		return TRUE;
	}else{
		return FALSE;
	}
}

function permiteMostrarMes($mes_show, $mes){
	$resultMes = ($mes - GL_MESES_ANTERIOR) + 1;
	if(($resultMes <= $mes_show) && ($mes >= $mes_show)){
		return TRUE;
	}else{
		return FALSE;
	}
}

function retornaMeses($mes){
	$meses = array();
	$meses[0] = 'Ene';
	$meses[1] = 'Feb';
	$meses[2] = 'Mar';
	$meses[3] = 'Abr';
	$meses[4] = 'May';
	$meses[5] = 'Jun';
	$meses[6] = 'Jul';
	$meses[7] = 'Ago';
	$meses[8] = 'Set';
	$meses[9] = 'Oct';
	$meses[10] = 'Nov';
	$meses[11] = 'Dic';
	
	return $meses[$mes - 1];
}

function JScript_ModalMensajeWeb($mensaje_Alert, $nombre_Pagina){
    $cadenaJS = '';
    $cadenaJS .= '<script language="javascript">';
    $cadenaJS .= 'var tareaswindow;';
    $cadenaJS .= 'function mostrarModal() {';
    if(trim($mensaje_Alert) != ''){
        $cadenaJS .= '   tareaswindow = dhtmlmodal.open("idInformacion", "div", "div_informacion", "Aviso", "width=410px,height=60px,center=1,resize=0,scrolling=1");';
        $cadenaJS .= '   document.getElementById("label_mensaje").innerHTML = "'.$mensaje_Alert.'";';
        $cadenaJS .= '   tareaswindow.onclose = function () {';
    }
    if(trim($nombre_Pagina) != ''){
        $cadenaJS .= 'var fakeLink = document.createElement("a");';
        $cadenaJS .= 'if (typeof (fakeLink.click) == "undefined")';
        $cadenaJS .= '    location.href = "'.$nombre_Pagina.'";';
        $cadenaJS .= 'else {';
        $cadenaJS .= '    fakeLink.href = "'.$nombre_Pagina.'";';
        $cadenaJS .= '    document.body.appendChild(fakeLink);';
        $cadenaJS .= '    fakeLink.click();';
        $cadenaJS .= ' }';
    }
    if(trim($mensaje_Alert) != ''){
        $cadenaJS .= '       return true;';
        $cadenaJS .= '   }';
    }
    $cadenaJS .= '}';
    $cadenaJS .= 'window.setTimeout("mostrarModal();", 0);';
    $cadenaJS .= '</script>';
    return $cadenaJS;
}

function JScript_Retorna_TipoEntregas($Result_TipoEntregas){
	$cadenaJS = '';
	$cadenaJS .= 'function retorna_TipoEntregas(ent_codigo){';
	$cadenaJS .= "if(ent_codigo == '0'){";
	$cadenaJS .= "return 'N';";
	$cadenaJS .= "}";
	$cadenaJS .= "switch(ent_codigo){";
	foreach($Result_TipoEntregas as $row){
		$cadenaJS .= "case '".$row->ent_codigo."':";
		$cadenaJS .= "return '".$row->ent_aplica_tipo_pago."';";
		$cadenaJS .= "break;";
	}
	$cadenaJS .= "}";
	$cadenaJS .= "}";
	return $cadenaJS;
}

function JScript_Retorna_TipoEntregas_cantidad($Result_TipoEntregas_cantidad){
	$cadenaJS = '';
	$cadenaJS .= 'function retorna_TipoEntregas_cantidad(ent_codigo){';
	$cadenaJS .= "if(ent_codigo == '0'){";
	$cadenaJS .= "return '0';";
	$cadenaJS .= "}";
	$cadenaJS .= "switch(ent_codigo){";
	foreach($Result_TipoEntregas_cantidad as $row){
		$cadenaJS .= "case '".$row->ent_codigo."':";
		$cadenaJS .= "return '".$row->env_cantidad."';";
		$cadenaJS .= "break;";
	}
	$cadenaJS .= "default:";
	$cadenaJS .= "return '0';";
	$cadenaJS .= "}";
	$cadenaJS .= "}";
	return $cadenaJS;
}

function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

function colocarCero($num){  
	if(strlen($num) < 2)   
		$num = '0'.$num;  
		
	return $num;   
}  

function strposa($haystack, $needle, $offset=0) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $query) {
        if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
    }
    return false;
}

function CalculaEdad($fecha) {
    list($Y,$m,$d) = explode("-",$fecha);
    return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
}

function limpiarEspacios($cadena){
	$cadena = str_replace(' ', '', $cadena);
	return $cadena;
}




function numtoletras($xcifra)
{
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
//
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }
 
    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }
 
            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                             
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                             
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                             
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO
 
        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";
 
        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";
 
        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO Y $xdecimales/100 nuevos soles";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN Y $xdecimales/100 nuevos soles ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= " Y $xdecimales/100 nuevos soles "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}
 
// END FUNCTION
 
function subfijo($xx)
{ // esta función regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
}

function obtenerFechaEnLetra($fecha){
    $num = date("j", strtotime($fecha));
    $anno = date("Y", strtotime($fecha));
    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
    return $num.' de '.$mes.' del '.$anno;
}

function conocerDiaSemanaFecha($fecha) {
    $dias = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
    $dia = $dias[date('w', strtotime($fecha))];
    return $dia;
}

function conocerEstadoCivil($esc_codigo) {
	switch ($esc_codigo) {
		case 1:
			return "Soltero";
			break;
		case 2:
			return "Casado";
			break;
		case 3:
			return "Viudo";
			break;
		case 4:
			return "Divorciado";
			break;		
		default:
			return "Sin Estado Civil";
			break;
	}
}

function settext_estado15($value){
	if ($value == 'I')
		return 'Ingreso';
	else if($value == 'E')
		return 'Egreso';
}

function settext_estado16($value) {
	if($value == 'E') 
		return "Entregas al Agricultor";
	else if($value == 'G')
		return "Gastos Operativos";
	else if($value == 'L')
		return "Guia de Liquidación";
	else if($value == 'T')
		return "Transferencia Bancaria";
	else if($value == 'R')
		return "Retiro Efectivo";
}


function sies_false($valor, $valor_cambio){
    if($valor === FALSE){
        return $valor_cambio;
    }else{
        return $valor;
    }
}