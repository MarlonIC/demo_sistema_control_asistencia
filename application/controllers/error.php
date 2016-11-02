<?php

class Error extends CI_Controller {
	public $menu;

    function __construct() {
        parent::__construct();
	}

    function index(){
        $this->data['mensaje_error'] = $this->session->userdata('Session_MensajeError');
        $this->session->set_userdata('Session_MensajeError', '');

        $session_data = $this->session->userdata('logged_in');
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
        $this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
		$this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
	    $this->load->view('error/index', $this->data);
    }
}
?>