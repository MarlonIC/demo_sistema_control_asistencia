<?php
class personaModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function listado_persona_todos($per_nombre, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_persona_todos(?,?,?,?,?)", array('_per_nombre'=>$per_nombre,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function listado_persona_cantidad($per_nombre){
        $query = $this->db->query("call sp_listado_persona_cantidad(?)", array('_per_nombre'=>$per_nombre));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->num_rows;
	}

	function consulta_persona_nroDni($per_dni) {
		$result = FALSE;
        $query = $this->db->query("call sp_consulta_persona_nroDni(?)", array('_per_dni'=>$per_dni));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function consulta_det_hijos_conyuge_perCod($per_codigo) {
		$result = FALSE;
        $query = $this->db->query("call sp_consulta_det_hijos_conyuge_perCod(?)", array('_per_codigo'=>$per_codigo));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}

	function consulta_det_referencias_personales_perCod($per_codigo) {
		$result = FALSE;
        $query = $this->db->query("call sp_consulta_det_referencias_personales_perCod(?)", array('_per_codigo'=>$per_codigo));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}

    function consulta_persona_codPer($per_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_persona_codPer(?)", array('_per_codigo'=>$per_codigo));
		if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
	}    

	
	function transaction_det_personas_eliminar($per_codigo){
        $result = TRUE;    
        $query = $this->db->query("call sp_transaction_det_personas_eliminar(?)",array('_per_codigo'=>$per_codigo));
        $query->free_result();
        $query->next_result();
        return $result;
    }
}
?>