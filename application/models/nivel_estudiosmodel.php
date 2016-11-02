<?php
class nivel_estudiosModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function consulta_nivel_estudios_todos() {
    	$result = FALSE;
        $query = $this->db->query("call sp_consulta_nivel_estudios_todos()");
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
    }
	
}
?>