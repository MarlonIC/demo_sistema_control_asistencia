<?php
class tipo_personaModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function consulta_tipo_persona_todos($tir_estado){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_tipo_persona_todos(?)", array('_tir_estado'=>$tir_estado));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
}
?>