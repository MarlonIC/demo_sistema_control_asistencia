<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
	public $menu;

    public function __construct(){
        parent::__construct();
		$this->load->library(array('encrypt'));
        $this->load->model('rolModel','',TRUE);
        $this->load->model('usuarioModel','',TRUE);
        $this->load->model('parametros_generalesModel','',TRUE);
    }

    public function index(){
	    $this->load->view('account/index');
    }
    
    public function verificar(){
        $this->form_validation->set_rules('usu_login', 'Usuario', 'trim|required|xss_clean');
        $this->form_validation->set_rules('usu_password', 'Contraseña', 'trim|required|xss_clean|callback_verificarBD');
		
        if($this->form_validation->run() == FALSE){
            $this->load->view('account/index');
        }else{
			$session_data = $this->session->userdata('logged_in');
			
			$aud_descripcion = 'Accedió al Sistema';
			$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => ENT_SISTEMA, 'acc_codigo' => ACC_ACCESO_AL_SISTEMA, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
			$this->hitaloagroModel->add('auditoria', $data);

			$Total_ListEntidad = $this->usuario_rolModel->consulta_usuario_rol_cantidad($session_data['Session_IdUsuario']);
			if($Total_ListEntidad == 1){
				$result = $this->usuario_rolModel->consulta_usuario_rol_codusu($session_data['Session_IdUsuario']);

				$sess_array = array(
					'Session_IdUsuario' => $session_data['Session_IdUsuario'],
					'Session_IdPersona' => $session_data['Session_IdPersona'],
					'Session_LoginUsuario' => $session_data['Session_LoginUsuario'],
					'Session_NombreUsuario' => $session_data['Session_NombreUsuario'],
					'Session_NombreEmpresa' => $session_data['Session_NombreEmpresa'],
					'Session_NombreResponsabilidad' => $session_data['Session_NombreResponsabilidad'],
					'Session_RolUsuario' => $result[0]->rol_codigo,
					'Session_AliasRol' => $result[0]->rol_alias,					
					'Session_RolSelected' => 'SI',
					'Session_IPUsuario' => $session_data['Session_IPUsuario'],
					'Session_FechaIngreso' => $session_data['Session_FechaIngreso'],
					'Session_RolMenu' => generar_menu($result[0]->rol_codigo)
				);				
				$this->session->set_userdata('logged_in',$sess_array);  
				
				redirect('home/index', 'refresh');	
			}else{
				redirect('home/seleccionar', 'refresh');	
			}			
        }
    }

    function verificarBD($usu_password){
        $usu_login = $this->input->post('usu_login');
    
        $result = $this->usuarioModel->verifica_usuario_login_existe($usu_login);		
        if($result){
			$usu_contrasena = $this->encrypt->decode($result->usu_password);
			if($usu_contrasena == $usu_password){
				$nombre_empresa = $this->parametros_generalesModel->consulta_parametros_generales_nombre('nombre_empresa');

				$sess_array = array(
					'Session_IdUsuario' => $result->usu_codigo,
					'Session_IdPersona' => $result->per_codigo,
					'Session_LoginUsuario' => $result->usu_login,
					'Session_NombreUsuario' => $result->usu_nombres_completo,
					'Session_NombreEmpresa' => $nombre_empresa,
					'Session_NombreResponsabilidad' => $nombre_empresa,
					'Session_RolUsuario' => '',
					'Session_AliasRol' => '',
					'Session_RolSelected' => '',					
					'Session_IPUsuario' => getIPAddress(),
					'Session_FechaIngreso' => date('Y-m-d H:i:s')
				);
				$this->session->set_userdata('logged_in', $sess_array);
				return TRUE;
			}else{
				$this->form_validation->set_message('verificarBD', 'Password inválido');
				return FALSE;
			}
        }else{
            $this->form_validation->set_message('verificarBD', 'Usuario inválido');
            return FALSE;
        }
    }
}
?>