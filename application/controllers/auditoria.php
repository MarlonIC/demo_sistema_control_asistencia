<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auditoria extends CI_Controller {
	private $ENT_AUDITORIA = 34;
	private $FUN_A_AUDITORIA = "AUDITO";

    public function __construct(){
        parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('account/index', 'refresh');		
        if(!acceso_bloqueado('AUD'))	redirect('home/seleccionar', 'refresh');
		$this->load->model('auditoriaModel','',TRUE);	
		$this->load->model('usuarioModel','',TRUE);	
		$this->load->model('entidadModel','',TRUE);		
		$this->load->model('accionModel','',TRUE);		
    }

    public function index(){    
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_AUDITORIA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		$this->session->set_userdata('Session_formAUDPaginicio', current_url());

		$aud_fecha_registroI = $this->input->post('aud_fecha_registroI');
		$aud_fecha_registroF = $this->input->post('aud_fecha_registroF');		
		$usu_codigo = sies_vacio($this->input->post('usu_codigo'),'');
		$ent_codigo = sies_vacio($this->input->post('ent_codigo'),'');
		$acc_codigo = sies_vacio($this->input->post('acc_codigo'),'');

		$num_pagina = 1;
		$segmento3 = $this->uri->segment(3);
		if($segmento3 == 'page'){
			$num_pagina = $this->uri->segment(4);
			if(empty($num_pagina)) $num_pagina = 1;
		}

		$bdsortfield = 'aud_fecha_registro';
		$bdorder = 'desc';
		$segmento5 = $this->uri->segment(5);
		$segmento6 = $this->uri->segment(6);
		if(!empty($segmento5)){
			$bdsortfield = $segmento5;
			if(!empty($segmento6)) $bdorder = $segmento6;
		}
		
		$aud_descripcion = 'Se realizó la consulta';
		$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_AUDITORIA, 'acc_codigo' => ACC_CONSULTA, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
		$this->hitaloagroModel->add('auditoria', $data);

		$pagreferrer = $this->agent->referrer();
		if(strrpos($pagreferrer, "auditoria") > -1){
			$Session_aud_fecha_registroI = obtiene_ValorSession('Session_formAUDAudfecharegistroI');
			if(!empty($Session_aud_fecha_registroI)) $aud_fecha_registroI = $Session_aud_fecha_registroI;
			$Session_aud_fecha_registroF = obtiene_ValorSession('Session_formAUDAudfecharegistroF');
			if(!empty($Session_aud_fecha_registroF)) $aud_fecha_registroF = $Session_aud_fecha_registroF;
			$Session_usu_codigo = obtiene_ValorSession('Session_formAUDUsucodigo');
			if(!empty($Session_usu_codigo)) $usu_codigo = $Session_usu_codigo;
			$Session_ent_codigo = obtiene_ValorSession('Session_formAUDEntcodigo');
			if(!empty($Session_ent_codigo)) $ent_codigo = $Session_ent_codigo;
			$Session_acc_codigo = obtiene_ValorSession('Session_formAUDAcccodigo');
			if(!empty($Session_acc_codigo)) $acc_codigo = $Session_acc_codigo;
		}

		//Inicialización de Filtros
		if(empty($aud_fecha_registroI)) $aud_fecha_registroI = date('d/m/Y');
		if(empty($aud_fecha_registroF)) $aud_fecha_registroF = date('d/m/Y');
		
		$aud_fecha_registroI2 = dateFormat_i($aud_fecha_registroI,'A');		
		$aud_fecha_registroF2 = dateFormat_i($aud_fecha_registroF,'F');
		
		$usu_codigo2 = $usu_codigo;
		if($usu_codigo2 == '') $usu_codigo2 = '%';	
		
		$ent_codigo2 = $ent_codigo;
		if($ent_codigo2 == '') $ent_codigo2 = '%';	
		
		$acc_codigo2 = $acc_codigo;
		if($acc_codigo2 == '') $acc_codigo2 = '%';

		$bdini_reg = ($num_pagina - 1) * GL_CANTIDAD_PAGINA1;
		$bdnum_reg = GL_CANTIDAD_PAGINA1;

		$this->data['Listado_Entidad'] = $this->auditoriaModel->listado_auditoria_todos($aud_fecha_registroI2, $aud_fecha_registroF2, $usu_codigo2, $ent_codigo2, $acc_codigo2, $bdini_reg, $bdnum_reg, $bdsortfield, $bdorder);
        $this->data['Total_ListEntidad'] = $this->auditoriaModel->listado_auditoria_cantidad($aud_fecha_registroI2, $aud_fecha_registroF2, $usu_codigo2, $ent_codigo2, $acc_codigo2);
		$this->data['aud_fecha_registroI'] = $aud_fecha_registroI;
		$this->data['aud_fecha_registroF'] = $aud_fecha_registroF;
		$this->data['usu_codigo'] = $usu_codigo;
		$this->data['ent_codigo'] = $ent_codigo;
		$this->data['acc_codigo'] = $acc_codigo;			
		
		$comboUsuario = $this->usuarioModel->consulta_usuario_todos();
		$this->data['Combo_Usuario'] = set_dropdown($comboUsuario, 'usu_codigo', 'usu_nombres_completo', 2);

		$comboEntidad = $this->entidadModel->consulta_entidad_todos();
		$this->data['Combo_Entidad'] = set_dropdown($comboEntidad, 'ent_codigo', 'ent_nombre', 2);

		$comboAccion = $this->accionModel->consulta_accion_todos();
		$this->data['Combo_Accion'] = set_dropdown($comboAccion, 'acc_codigo', 'acc_nombre', 2);
		
	    $config['total_rows'] = $this->data['Total_ListEntidad'];
	    $config["per_page"] = $bdnum_reg;
	    $this->pagination->initialize($config);
	    $str_links = $this->pagination->create_links();
		$this->data["links"] = explode('&nbsp;',$str_links );
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
		$this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
	    $this->load->view('auditoria/index', $this->data);
    }

    public function detalle(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_AUDITORIA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		
        $aud_codigo =  $this->uri->segment(3);			
		$result = $this->auditoriaModel->consulta_auditoria_codaud($aud_codigo);
        if(!$result){
            $this->session->set_userdata('Session_MensajeError', 'Error. El registro de auditoria que está consultando no existe');
            redirect('error/index', 'refresh');
        }

        $this->data['result'] = $result;
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('auditoria/detalle', $this->data);
    }
	
	public function exportar(){
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_AUDITORIA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
			
		$aud_fecha_registroI = $this->input->post('aud_fecha_registroI');
		$aud_fecha_registroF = $this->input->post('aud_fecha_registroF');		
		$usu_codigo = $this->input->post('usu_codigo');
		$ent_codigo = $this->input->post('ent_codigo');
		$acc_codigo = $this->input->post('acc_codigo');
		
		$aud_fecha_registroI = dateFormat_i($aud_fecha_registroI,'A');		
		$aud_fecha_registroF = dateFormat_i($aud_fecha_registroF,'F');
				
		$usu_codigo2 = $usu_codigo;
		if($usu_codigo2 == '') $usu_codigo2 = '%';	
		
		$ent_codigo2 = $ent_codigo;
		if($ent_codigo2 == '') $ent_codigo2 = '%';	
		
		$acc_codigo2 = $acc_codigo;
		if($acc_codigo2 == '') $acc_codigo2 = '%';

		$bdsortfield = 'aud_fecha_registro';
		$bdorder = 'desc';

		$result = $this->auditoriaModel->listado_auditoria_todos($aud_fecha_registroI, $aud_fecha_registroF, $usu_codigo2, $ent_codigo2, $acc_codigo2, 0, $this->input->post('h_tot_listado'), $bdsortfield, $bdorder);
		if(!$result){ return false; }

		$this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $sheet = $this->excel->getActiveSheet();
        $sheet->setTitle('auditoria');			 
		
		setCellValueH($sheet, 'A1', 'Código');
        setCellValueH($sheet, 'B1', 'Usuario');
        setCellValueH($sheet, 'C1', 'Entidad');
        setCellValueH($sheet, 'D1', 'Acción');
        setCellValueH($sheet, 'E1', 'Descripción');
        setCellValueH($sheet, 'F1', 'Fecha Registro');
        setCellValueH($sheet, 'G1', 'IP Acceso');
		
		$fila = 2;
		foreach($result as $row){
			setCellValueI($sheet, 'A'.$fila, $row->aud_codigo, 'C');
			setCellValueI($sheet, 'B'.$fila, $row->usu_nombres_completo, 'L');
			setCellValueI($sheet, 'C'.$fila, $row->ent_nombre, 'C');
			setCellValueI($sheet, 'D'.$fila, $row->acc_nombre, 'C');
			setCellValueI($sheet, 'E'.$fila, $row->aud_descripcion, 'C');
			setCellValueI($sheet, 'F'.$fila, date('d/m/Y H:i:s', strtotime($row->aud_fecha_registro)), 'C');			
			setCellValueI($sheet, 'G'.$fila, $row->aud_ip_acceso, 'C');
			$fila += 1;		
		}
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);

		//-------------------------- 
		while (ob_get_level() > 0) {
			ob_end_clean();
        }
		
        $filename = 'Listado_de_Registros_Auditoria.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$objWriter->save('documentos/'.$filename);
		$this->excel->disconnectWorksheets();
		unset($this->excel);
		
	    $this->load->helper('download');		
		$this->data['download_path'] = $filename;
		$this->load->view('template/descargar',$this->data);
	}
	
	//---------------------------------
	
	function grabafiltros_ajax(){
		$session_data = $this->session->userdata('logged_in');
        
		$this->session->set_userdata('Session_formAUDAudfecharegistroI', $this->input->post('aud_fecha_registroI'));
		$this->session->set_userdata('Session_formAUDAudfecharegistroF', $this->input->post('aud_fecha_registroF'));
		$this->session->set_userdata('Session_formAUDUsucodigo', $this->input->post('usu_codigo'));
		$this->session->set_userdata('Session_formAUDEntcodigo', $this->input->post('ent_codigo'));
		$this->session->set_userdata('Session_formAUDAcccodigo', $this->input->post('acc_codigo'));
	}

}
?>