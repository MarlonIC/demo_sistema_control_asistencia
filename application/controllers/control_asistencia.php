<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Control_asistencia extends CI_Controller {
	public $menu;
	private $ENT_PERSONA = 2;
	private $FUN_A_CONTROL_ASISTENCIA = "OPECAS";

    public function __construct(){
        parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('account/index', 'refresh');
		if(!acceso_bloqueado('CAS')) redirect('home/seleccionar', 'refresh');
		$this->load->library(array('encrypt'));
		$this->load->model('rolModel','',TRUE);
		$this->load->model('usuarioModel','',TRUE);
		$this->load->model('tipo_personaModel','',TRUE);
		$this->load->model('estado_civilModel','',TRUE);
		$this->load->model('tipo_documentoModel','',TRUE);
		$this->load->model('cargoModel','',TRUE);		
		$this->load->model('seguroModel','',TRUE);
		$this->load->model('nivel_estudiosModel','',TRUE);
		$this->load->model('control_asistenciaModel','',TRUE);
		$this->load->model('personaModel','',TRUE);
		
    }

    public function index(){    
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_CONTROL_ASISTENCIA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		$this->session->set_userdata('Session_formCASPaginicio', current_url());
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
		$this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
	    $this->load->view('control_asistencia/index', $this->data);
    }


    function entrada_ajax() {
    	$nro_dni = $this->input->post('text_dni');

    	$hoy = getdate();
    	$hora_ingreso = $hoy['hours'] . ":" . $hoy['minutes']. ":" . $hoy['seconds'];

		$observacion = "";
		if((int)($hoy['hours']) >= 8 && (int)($hoy['minutes']) > 10) {
			$observacion = "Tarde";
		} else {
			$observacion = "Ok";
		}

		$result = $this->personaModel->consulta_persona_nroDni($nro_dni);
		if($result) {
			$result2 = $this->control_asistenciaModel->consulta_control_asistencia_codPer($result[0]->per_codigo);
			if(!$result2) {
				$data = array(
					'per_codigo' => $result[0]->per_codigo,
					'coa_fecha' => dateFormat(date("d/m/Y")),
					'coa_entrada' => $hora_ingreso,
					'coa_observaciones' => $observacion
				);
				if($this->hitaloagroModel->add('control_asistencia',$data)) {
					$result2 = $this->control_asistenciaModel->consulta_control_asistencia_codPer($result[0]->per_codigo);
					echo json_encode(array('estado'=>'sinmarcado','result'=>$result,'result2'=>$result2));
				}
			} else {
				echo json_encode(array('estado'=>'marcado','mensaje'=>'El empleado ya marco su hora de ingreso', 'result'=>$result,'result2'=>$result2));
			}
		} else {
			echo json_encode(array('estado'=>'error','mensaje'=>'El empleado con DNI '.$nro_dni.' no existe'));
		}
    }

    function salida_ajax() {
    	$nro_dni = $this->input->post('text_dni');

    	$hoy = getdate();
    	$hora_salida = $hoy['hours'] . ":" . $hoy['minutes']. ":" . $hoy['seconds'];

		$result = $this->personaModel->consulta_persona_nroDni($nro_dni);
		if($result) {
			$result2 = $this->control_asistenciaModel->consulta_control_asistencia_codPer($result[0]->per_codigo);
			if($result2[0]->coa_salida == null) {
				$data = array(
					'coa_salida' => $hora_salida
				);
				if($this->hitaloagroModel->edit('control_asistencia',$data,array('coa_codigo' => $result2[0]->coa_codigo,'per_codigo' => $result[0]->per_codigo))) {
					$result2 = $this->control_asistenciaModel->consulta_control_asistencia_codPer($result[0]->per_codigo);
					echo json_encode(array('estado'=>'sinmarcado','result'=>$result,'result2'=>$result2));
				}
			} else {
				echo json_encode(array('estado'=>'marcado','mensaje'=>'El empleado ya marco su hora de salida', 'result'=>$result,'result2'=>$result2));
			}
		} else {
			echo json_encode(array('estado'=>'error','mensaje'=>'El empleado con DNI '.$nro_dni.' no existe'));
		}
    }
}
?>