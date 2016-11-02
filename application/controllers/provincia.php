<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Provincia extends CI_Controller {
	
    public function __construct(){
        parent::__construct();
		$this->load->model('provinciaModel','',TRUE);	
    }
    	
	function retornaprovincia_ajax(){
		$session_data = $this->session->userdata('logged_in');
		
		if($this->input->post('ajax') == '1') {
			$comboProvincia = $this->provinciaModel->consulta_provincia_codDep($this->input->post('dep_codigo'));


			$Combo_Provincia = set_dropdown($comboProvincia, 'prov_codigo', 'provincia', $this->input->post('tipo'));
			echo json_encode($Combo_Provincia);
		}
	}
}
?>