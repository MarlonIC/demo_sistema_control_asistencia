<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consulta_reclamo extends CI_Controller {
	public $menu;
	private $ENT_PERSONA = 2;
	private $FUN_A_CONSULTA_RECLAMO = "CONREC";

    public function __construct(){
        parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('account/index', 'refresh');		
		if(!acceso_bloqueado('REC')) redirect('home/seleccionar', 'refresh');
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
		$this->load->model('correos_corporativosModel','',TRUE);		
    }

    public function index(){    
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_CONSULTA_RECLAMO,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		$this->session->set_userdata('Session_formRECPaginicio', current_url());

		$result2 = $this->personaModel->consulta_persona_codPer($session_data['Session_IdPersona']);

		$Combo_CorreosCorporativos = $this->correos_corporativosModel->consulta_correos_corporativos_todos();
		$this->data['Combo_CorreosCorporativos'] = set_dropdown($Combo_CorreosCorporativos, 'coc_codigo', 'coc_correos_corporativos');
		
		$this->data['result2'] = $result2;
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
		$this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
	    $this->load->view('consulta_reclamo/index', $this->data);
    }    

    public function registrarsave(){		
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_CONSULTA_RECLAMO,GL_PERMISO_ACCION)) acceso_denegado(current_url());

		$this->form_validation->set_rules('h_per_codigo', 'Codigo de Persona', 'trim|xss_clean');		
		$this->form_validation->set_rules('h_coc_codigo', 'Codigo de Correo Corporativo', 'trim|required|xss_clean');		
		$this->form_validation->set_rules('consulta_text', '', 'trim|required|xss_clean');
		
        if ($this->form_validation->run() == false) {
        	$result2 = $this->personaModel->consulta_persona_codPer($session_data['Session_IdPersona']);

			$Combo_CorreosCorporativos = $this->correos_corporativosModel->consulta_correos_corporativos_todos();
			$this->data['Combo_CorreosCorporativos'] = set_dropdown($Combo_CorreosCorporativos, 'coc_codigo', 'coc_correos_corporativos');
			
			$this->data['result2'] = $result2;
	        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
            $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		    $this->data['nav'] = $this->load->view('template/nav', null, true);
			$this->data['mensaje_error'] = 'Hubo problemas por validacion';
		    $this->data['footer'] = $this->load->view('template/footer', null, true);
		    $this->load->view('consulta_reclamo/index', $this->data);
        } else {       
            $data = array(
				'per_codigo' => $this->input->post('h_per_codigo'),
				'coc_codigo' => $this->input->post('h_coc_codigo'),
				'coc_observaciones' => $this->input->post('consulta_text')
            );
	       
			if($this->hitaloagroModel->add('consulta_reclamo',$data) == TRUE) {
				$this->data['mensaje_exito'] = 'Se grabó correctamente los datos de su Consulta y/o Reclamo';
		        $this->data['pagina_retorno'] = base_url().'consulta_reclamo/index';
				
				$result2 = $this->personaModel->consulta_persona_codPer($session_data['Session_IdPersona']);

				$Combo_CorreosCorporativos = $this->correos_corporativosModel->consulta_correos_corporativos_todos();
				$this->data['Combo_CorreosCorporativos'] = set_dropdown($Combo_CorreosCorporativos, 'coc_codigo', 'coc_correos_corporativos');
				
				$this->data['result2'] = $result2;

				$this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
		        $this->data['informacion'] = $this->load->view('template/informacion', null, true);
			    $this->load->view('consulta_reclamo/index', $this->data);		    	
			} else {
				$result2 = $this->personaModel->consulta_persona_codPer($session_data['Session_IdPersona']);

				$Combo_CorreosCorporativos = $this->correos_corporativosModel->consulta_correos_corporativos_todos();
				$this->data['Combo_CorreosCorporativos'] = set_dropdown($Combo_CorreosCorporativos, 'coc_codigo', 'coc_correos_corporativos');
				
				$this->data['result2'] = $result2;

				$this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
				$this->data['mensaje_error'] = 'Hubo problemas al grabar los datos de la entrega';
				$this->load->view('consulta_reclamo/index', $this->data);
			}
		}
	}
}
?>