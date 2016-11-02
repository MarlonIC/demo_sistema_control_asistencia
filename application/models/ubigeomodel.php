<?php
class ubigeoModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function listado_ubigeo_todos($ini_reg=0, $num_reg=0){//SIREMAX
        $result = FALSE;
        $query = $this->db->query("call sp_listado_ubigeo_todos(?,?)", array('_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function listado_ubigeo_cantidad(){//SIREMAX
        $query = $this->db->query("call sp_listado_ubigeo_cantidad()");
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->num_rows;
	}
	
	function consulta_ubigeo_departamento(){
		$result = FALSE;
        $query = $this->db->query("call sp_consulta_ubigeo_departamento()");		
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function consulta_ubigeo_provincia($ubi_coddep){
		$result = FALSE;
        $query = $this->db->query("call sp_consulta_ubigeo_provincia(?)",array('_ubi_coddep'=>$ubi_coddep));		
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function consulta_ubigeo_distrito($ubi_coddep, $ubi_codprov){
		$result = FALSE;
        $query = $this->db->query("call sp_consulta_ubigeo_distrito(?,?)",array('_ubi_coddep'=>$ubi_coddep,'_ubi_codprov'=>$ubi_codprov));		
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
}
?>