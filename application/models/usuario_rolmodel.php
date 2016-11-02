<?php
class usuario_rolModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function consulta_usuario_rol_existe($usu_codigo){
		$result = FALSE;	
        $query = $this->db->query("call sp_consulta_usuario_rol_existe(?)",array('_usu_codigo'=>$usu_codigo));		
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}

	function consulta_usuario_rol_cantidad($usu_codigo){
        $query = $this->db->query("call sp_consulta_usuario_rol_cantidad(?)", array('_usu_codigo'=>$usu_codigo));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->num_rows;
	}

    function consulta_usuario_rol_codusu($usu_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_usuario_rol_codusu(?)", array('_usu_codigo'=>$usu_codigo));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
    
    function consulta_usuario_rol_alias($rol_alias) {
    	$result = FALSE;
    	$query = $this->db->query("call sp_consulta_usuario_rol_alias(?)", array('_rol_alias'=>$rol_alias));
    	if($query->num_rows() > 0)
    		$result = $query->result();
    	$query->free_result();
    	$query->next_result();
    	return $result;
    }

    function consulta_usuario_rol_2alias($rol_alias1, $rol_alias2) {
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_usuario_rol_2alias(?,?)", array('_rol_alias1'=>$rol_alias1,'_rol_alias2'=>$rol_alias2));
        if($query->num_rows() > 0)
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }
}
?>