<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte_ie extends CI_Controller {
	public $menu;
	private $ENT_PERSONA = 2;
	private $FUN_A_REPORTEIE = "REPOIE";

    public function __construct(){
        parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('account/index', 'refresh');		
		if(!acceso_bloqueado('OIE')) redirect('home/seleccionar', 'refresh');
		$this->load->library(array('encrypt'));
		$this->load->model('rolModel','',TRUE);
		$this->load->model('personaModel','',TRUE);	
		$this->load->model('usuarioModel','',TRUE);
		$this->load->model('tipo_personaModel','',TRUE);
		$this->load->model('estado_civilModel','',TRUE);
		$this->load->model('tipo_documentoModel','',TRUE);	
		$this->load->model('cargoModel','',TRUE);		
		$this->load->model('seguroModel','',TRUE);
		$this->load->model('nivel_estudiosModel','',TRUE);
		$this->load->model('control_asistenciaModel','',TRUE);
    }

    public function index(){    
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_REPORTEIE,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		$this->session->set_userdata('Session_formOIEPaginicio', current_url());

		$coa_fecha_I = $this->input->post('coa_fecha_I');
		$coa_fecha_F = $this->input->post('coa_fecha_F');

		$num_pagina = 1;
		$segmento3 = $this->uri->segment(3);
		if($segmento3 == 'page'){
			$num_pagina = $this->uri->segment(4);
			if(empty($num_pagina)) $num_pagina = 1;
		}

		$bdsortfield = 'coa_fecha';
		$bdorder = 'desc';
		$segmento5 = $this->uri->segment(5);
		$segmento6 = $this->uri->segment(6);
		if(!empty($segmento5)){
			$bdsortfield = $segmento5;
			if(!empty($segmento6)) $bdorder = $segmento6;
		}

		$pagreferrer = $this->agent->referrer();
		//if(strrpos($pagreferrer, "guia_liquidacion") > -1){
		if(strposa($pagreferrer, array("reporte_ie"))){
			$Session_coa_fecha_I = obtiene_ValorSession('Session_formCOACoafechaI');
			if(!empty($Session_coa_fecha_I)) $coa_fecha_I = $Session_coa_fecha_I;
			$Session_coa_fecha_F = obtiene_ValorSession('Session_formCOACoafechaF');
			if(!empty($Session_coa_fecha_F)) $coa_fecha_F = $Session_coa_fecha_F;
		}

		//Inicialización de Filtros
		if(empty($coa_fecha_I)) $coa_fecha_I = '01/01/'.date('Y');
		if(empty($coa_fecha_F)) $coa_fecha_F = date('d/m/Y');
		
		$coa_fecha_I2 = dateFormat($coa_fecha_I);
		$coa_fecha_F2 = dateFormat($coa_fecha_F);

		$bdini_reg = ($num_pagina - 1) * GL_CANTIDAD_PAGINA1;
		$bdnum_reg = GL_CANTIDAD_PAGINA1;
		$bdtot_reg = $bdini_reg + $bdnum_reg;
		

		$result = $this->control_asistenciaModel->listado_control_asistencia_todos($session_data['Session_IdPersona'],$coa_fecha_I2,$coa_fecha_F2,$bdini_reg, $bdnum_reg, $bdsortfield, $bdorder);
		$result2 = $this->personaModel->consulta_persona_codPer($session_data['Session_IdPersona']);
		$this->data['Total_ListEntidad'] = $this->control_asistenciaModel->listado_control_asistencia_cantidad($session_data['Session_IdPersona'],$coa_fecha_I2,$coa_fecha_F2);
		
		if($this->data['Total_ListEntidad'] >= $bdtot_reg) $bdfin_reg = $bdnum_reg * $num_pagina; else $bdfin_reg = $this->data['Total_ListEntidad'];
		$this->data['bdini_reg'] = $bdini_reg + 1;		
        $this->data['bdfin_reg'] = $bdfin_reg;		
		$this->data['result'] = $result;
		$this->data['result2'] = $result2;
		$this->data['coa_fecha_I'] = $coa_fecha_I;
		$this->data['coa_fecha_F'] = $coa_fecha_F;

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
	    $this->load->view('reporte_ie/index', $this->data);
    }    

    public function grabafiltros_ajax(){
		$session_data = $this->session->userdata('logged_in');
		
		$this->session->set_userdata('Session_formCOACoafechaI', $this->input->post('coa_fecha_I'));
		$this->session->set_userdata('Session_formCOACoafechaF', $this->input->post('coa_fecha_F'));
	}

	public function exportar(){
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_REPORTEIE,GL_PERMISO_ACCION)) acceso_denegado(current_url());
			
		$coa_fecha_I = $this->input->post('coa_fecha_I');
		$coa_fecha_F = $this->input->post('coa_fecha_F');

		$bdsortfield = 'coa_fecha';
		$bdorder = 'desc';
		
		$coa_fecha_I2 = dateFormat($coa_fecha_I);		
		$coa_fecha_F2 = dateFormat($coa_fecha_F);
		
		$result = $this->control_asistenciaModel->listado_control_asistencia_todos($session_data['Session_IdPersona'],$coa_fecha_I2,$coa_fecha_F2,0, $this->input->post('h_tot_listado'), $bdsortfield, $bdorder);
		print_r($result);
		if(!$result){ return false; }

		$this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $sheet = $this->excel->getActiveSheet();
        $sheet->setTitle('control_asistencia');			 

		setCellValueH($sheet, 'A1', 'Fecha');
        setCellValueH($sheet, 'B1', 'Codigo');
        setCellValueH($sheet, 'C1', 'Turno');
        setCellValueH($sheet, 'D1', 'Tipo de Marca');
        setCellValueH($sheet, 'E1', 'Hora Entrada');
        setCellValueH($sheet, 'F1', 'Hora Salida');
        setCellValueH($sheet, 'G1', 'Tardanza');
        setCellValueH($sheet, 'H1', 'Falta');
		
		$fila = 2;
		foreach($result as $row){
			$tiempo = '';
			$tardanza = explode(":", $row->coa_entrada);
			if($tardanza[0] > 8) {
				$tiempo = $tardanza[0] - 8;
			} else {
				$tiempo = "0";
			}
			$tiempo .= ":" .$tardanza[1];

			setCellValueI($sheet, 'A'.$fila, date('d/m/Y', strtotime($row->coa_fecha)), 'C');
			setCellValueI($sheet, 'B'.$fila, $this->input->post('codigo_empleado'), 'C');
			setCellValueI($sheet, 'C'.$fila, 'Mañana', 'C');
			setCellValueI($sheet, 'D'.$fila, 'Entrada / Salida', 'C');
			setCellValueI($sheet, 'E'.$fila, $row->coa_entrada, 'C');
			setCellValueI($sheet, 'F'.$fila, $row->coa_salida, 'C');
			setCellValueI($sheet, 'G'.$fila, $tiempo, 'C');
			setCellValueI($sheet, 'H'.$fila, '', 'C');
			$fila += 1;		
		}
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);
		$sheet->getColumnDimension('H')->setAutoSize(true);

		//-------------------------- 
		while (ob_get_level() > 0) {
			ob_end_clean();
        }
		
        $filename = 'Listado_de_Control_Asistencia.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$objWriter->save('documentos/'.$filename);
		$this->excel->disconnectWorksheets();
		unset($this->excel);
		
	    $this->load->helper('download');		
		$this->data['download_path'] = $filename;
		$this->load->view('template/descargar',$this->data);
	}
}
?>