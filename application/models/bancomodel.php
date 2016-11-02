<?php
class bancoModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

     function consulta_banco_todos() {
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_banco_todos()");
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }
}