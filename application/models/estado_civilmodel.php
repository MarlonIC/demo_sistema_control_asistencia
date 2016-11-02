<?php
class estado_civilModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function consulta_estado_civil_todos($esc_estado){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_estado_civil_todos(?)", array('_esc_estado'=>$esc_estado));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
}
?>