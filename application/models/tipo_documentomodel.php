<?php
class tipo_documentoModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function consulta_tipo_documento_todos($tid_estado){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_tipo_documento_todos(?)", array('_tid_estado'=>$tid_estado));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
}
?>