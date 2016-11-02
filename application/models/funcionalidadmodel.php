<?php
class funcionalidadModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function listado_funcionalidad_todos($fun_nombre, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_funcionalidad_todos(?,?,?,?,?)", array('_fun_nombre'=>$fun_nombre,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function listado_funcionalidad_cantidad($fun_nombre){
        $query = $this->db->query("call sp_listado_funcionalidad_cantidad(?)", array('_fun_nombre'=>$fun_nombre));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->num_rows;
	}

    function consulta_funcionalidad_codfun($fun_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_funcionalidad_codfun(?)", array('_fun_codigo'=>$fun_codigo));
		if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
    function consulta_funcionalidad_todos($fun_estado){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_funcionalidad_todos(?)", array('_fun_estado'=>$fun_estado));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function verifica_funcionalidad_alias($fun_alias){
        $result = FALSE;	
        $query = $this->db->query("call sp_verifica_funcionalidad_alias(?)",array('_fun_alias'=>$fun_alias));		
		if($query->num_rows() == 1)
			$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
    }
}
?>