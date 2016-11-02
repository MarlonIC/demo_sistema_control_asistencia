<?php
class rol_funcionalidad_permisoModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function verifica_rol_funcionalidad_permiso_acceso($rol_codigo, $fun_alias, $per_codigo){
        $result = FALSE;	
        $query = $this->db->query("call sp_verifica_rol_funcionalidad_permiso_acceso(?,?,?)",array('_rol_codigo'=>$rol_codigo, '_fun_alias'=>$fun_alias, '_per_codigo'=>$per_codigo));		
		if($query->num_rows() == 1)
			$result = TRUE;
        $query->free_result();
		$query->next_result();
		return $result;
    }
	
	function consulta_rol_funcionalidad_permiso_existe($rol_codigo){
		$result = FALSE;	
        $query = $this->db->query("call sp_consulta_rol_funcionalidad_permiso_existe(?)",array('_rol_codigo'=>$rol_codigo));		
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function consulta_rol_funcionalidad_permiso_codrol($rol_codigo){
		$result = FALSE;	
        $query = $this->db->query("call sp_consulta_rol_funcionalidad_permiso_codrol(?)",array('_rol_codigo'=>$rol_codigo));		
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
    
}
?>