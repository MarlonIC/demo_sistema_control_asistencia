<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	public $menu;
	
    function __construct(){
        parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('account/index', 'refresh');  
        $this->load->model('rolModel','',TRUE);
    }

    function index(){
        $session_data = $this->session->userdata('logged_in');
        if(empty($session_data['Session_RolSelected'])) redirect('home/seleccionar', 'refresh');
        
        $this->data['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->data['Session_IPUsuario'] = $session_data['Session_IPUsuario'];
        $this->data['Session_FechaIngreso'] = $session_data['Session_FechaIngreso'];

        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
        $this->data['nav'] = $this->load->view('template/nav', null, true);
        $this->data['footer'] = $this->load->view('template/footer', null, true);

        $this->load->view('home/index', $this->data);
    }

    function seleccionar(){
        $session_data = $this->session->userdata('logged_in');

        if(empty($session_data['Session_RolSelected'])){
            $comboRol = $this->usuario_rolModel->consulta_usuario_rol_codusu($session_data['Session_IdUsuario']);
            $this->data['Combo_Rol'] = set_dropdown($comboRol, 'rol_codigo', 'rol_nombre', 1);
            
            $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
            $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
            $this->data['nav'] = $this->load->view('template/nav', null, true);
            $this->data['footer'] = $this->load->view('template/footer', null, true);
            $this->load->view('home/seleccionar', $this->data);
        }else{
            redirect('home/index', 'refresh');
        }
    }

    function seleccionarsave(){
        $session_data = $this->session->userdata('logged_in');

        if(isset($_POST['rol_codigo_sel'])){
            $result = $this->rolModel->consulta_rol_codrol($this->input->post('rol_codigo_sel'));

            $rol_alias = $result->rol_alias;
            $sess_array = array(
                'Session_IdUsuario' => $session_data['Session_IdUsuario'],
                'Session_IdPersona' => $session_data['Session_IdPersona'],
                'Session_LoginUsuario' => $session_data['Session_LoginUsuario'],
                'Session_NombreUsuario' => $session_data['Session_NombreUsuario'],
                'Session_NombreEmpresa' => $session_data['Session_NombreEmpresa'],
                'Session_NombreResponsabilidad' => $session_data['Session_NombreResponsabilidad'],
                'Session_RolUsuario' => $this->input->post('rol_codigo_sel'),
                'Session_AliasRol' => $rol_alias,
                'Session_RolSelected' => 'SI',
                'Session_IPUsuario' => $session_data['Session_IPUsuario'],
                'Session_FechaIngreso' => $session_data['Session_FechaIngreso'],
                'Session_RolMenu' => generar_menu($this->input->post('rol_codigo_sel'))
            );
            $this->session->set_userdata('logged_in',$sess_array);
        }        

        redirect('home/index', 'refresh');
    }
	
    function logout(){
		$session_data = $this->session->userdata('logged_in');
		
        $aud_descripcion = 'Salió del Sistema';
		$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => ENT_SISTEMA, 'acc_codigo' => ACC_CERRAR_SESION, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
		$this->hitaloagroModel->add('auditoria', $data);
			
		$this->session->sess_destroy();
        redirect('home/index', 'refresh');
    }
	
    public function download($download_path){
        $this->load->helper('download');
        if(!empty($download_path)){
            $this->data['download_path'] = $download_path;                                 
            $this->load->view('home/download', $this->data);
        }
    }
}
?>