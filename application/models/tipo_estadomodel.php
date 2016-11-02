<?php
class tipo_estadoModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function consulta_tipo_estado_codgre($gre_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_tipo_estado_codgre(?)", array('_gre_codigo'=>$gre_codigo));
		if($query->num_rows() > 0)
			$result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}

    function consulta_tipo_estado_codgre2($gre_codigo, $tie_codigo_list){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_tipo_estado_codgre2(?,?)", array('_gre_codigo'=>$gre_codigo,'_tie_codigo_list'=>$tie_codigo_list));
        if($query->num_rows() > 0)
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

}
?>