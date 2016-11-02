<?php
class correos_corporativosModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function consulta_correos_corporativos_todos() {
    	$result = FALSE;
        $query = $this->db->query("call sp_consulta_correos_corporativos_todos()");
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
    }
	
}
?>