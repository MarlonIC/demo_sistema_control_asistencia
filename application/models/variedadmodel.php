<?php
class variedadModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function listado_variedad_todos($var_nombre, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_variedad_todos(?,?,?,?,?)", array('_var_nombre'=>$var_nombre,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function listado_variedad_cantidad($var_nombre){
        $query = $this->db->query("call sp_listado_variedad_cantidad(?)", array('_var_nombre'=>$var_nombre));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->num_rows;
	}

    function consulta_variedad_codvar($var_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_variedad_codvar(?)", array('_var_codigo'=>$var_codigo));
		if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
	}

    function consulta_variedad_codpro($pro_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_variedad_codpro(?)", array('_pro_codigo'=>$pro_codigo));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
    function consulta_variedad_todos($var_estado){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_variedad_todos(?)", array('_var_estado'=>$var_estado));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
}
?>