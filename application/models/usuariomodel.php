<?php
class usuarioModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

	function consulta_usuario_codper($per_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_usuario_codper(?)", array('_per_codigo'=>$per_codigo));
		if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
	}

	function consulta_usuario_todos(){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_usuario_todos()");
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}

    function verifica_usuario_login_existe($usu_login){
        $result = FALSE;	
        $query = $this->db->query("call sp_verifica_usuario_login_existe(?)",array('_usu_login'=>$usu_login));		
		if($query->num_rows() == 1)
			$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
    }
    
}
?>