<?php
class cargoModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

     function consulta_cargo_todos() {
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_cargo_todos()");
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }
}