<?php
class rolModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function listado_rol_todos($rol_nombre, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_rol_todos(?,?,?,?,?)", array('_rol_nombre'=>$rol_nombre,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function listado_rol_cantidad($rol_nombre){
        $query = $this->db->query("call sp_listado_rol_cantidad(?)", array('_rol_nombre'=>$rol_nombre));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->num_rows;
	}

    function consulta_rol_codrol($rol_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_rol_codrol(?)", array('_rol_codigo'=>$rol_codigo));
		if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
    function consulta_rol_todos($rol_estado){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_rol_todos(?)", array('_rol_estado'=>$rol_estado));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function verifica_rol_alias($rol_alias){
        $result = FALSE;	
        $query = $this->db->query("call sp_verifica_rol_alias(?)",array('_rol_alias'=>$rol_alias));		
		if($query->num_rows() == 1)
			$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
    }
}
?>