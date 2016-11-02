<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ubigeo extends CI_Controller {
	
    public function __construct(){
        parent::__construct();
		$this->load->model('ubigeoModel','',TRUE);	
    }
    	
	function retornaprovincia_ajax(){
		$session_data = $this->session->userdata('logged_in');
		
		if($this->input->post('ajax') == '1') {
			$comboProvincia = $this->ubigeoModel->consulta_ubigeo_provincia($this->input->post('ubi_coddep'));
			$Combo_Provincia = set_dropdown($comboProvincia, 'ubi_codprov', 'ubi_provincia', $this->input->post('tipo'));
			echo json_encode($Combo_Provincia);
		}		
	}
	
	function retornadistrito_ajax(){
		$session_data = $this->session->userdata('logged_in');
		
		if($this->input->post('ajax') == '1') {
			$comboDistrito = $this->ubigeoModel->consulta_ubigeo_distrito($this->input->post('ubi_coddep'), $this->input->post('ubi_codprov'));
			$Combo_Distrito = set_dropdown($comboDistrito, 'ubi_coddis', 'ubi_distrito', $this->input->post('tipo'));
			echo json_encode($Combo_Distrito);
		}		
	}

}
?>