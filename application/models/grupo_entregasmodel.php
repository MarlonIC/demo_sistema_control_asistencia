<?php
class grupo_entregasModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
    function consulta_grupo_entregas_todos($get_estado){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_grupo_entregas_todos(?)", array('_get_estado'=>$get_estado));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
    
    function consulta_grupo_entregas_todos2($get_vista_zona_distribucion){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_grupo_entregas_todos2(?)", array('_get_vista_zona_distribucion'=>$get_vista_zona_distribucion));
        if($query->num_rows() > 0)
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }
}
?>