<?php
class hitaloagroModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function get($table,$fields,$where='',$perpage=0,$start=0,$one=false,$array='array'){        
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->limit($perpage,$start);
        if($where){
			$this->db->where($where);
        }
        
        $query = $this->db->get();        
        $result =  !$one  ? $query->result($array) : $query->row();
        return $result;
    }

    function get_row($query_s){   
        $result = FALSE;
        $query = $this->db->query($query_s);
        if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result;
    }
    
    function add($table,$data){
        $this->db->insert($table, $data);         
        if ($this->db->affected_rows() == 1){
			return TRUE;
		}		
		return FALSE;       
    }

    function add_return($table,$data){
        $this->db->insert($table, $data);         
        if ($this->db->affected_rows() == 1){
			return $this->db->insert_id();
		}		
		return NULL;       
    }
	
	function edit($table, $data, $ID_array){
		$this->db->where($ID_array);
		if($this->db->update($table, $data)) {
			return TRUE;
		}
		return FALSE;
    }

	function delete($table, $ID_array){
        $this->db->delete($table, $ID_array);
        if ($this->db->affected_rows() > 0){
			return TRUE;
		}		
		return FALSE;        
    } 

	function truncate($table){
        $this->db->truncate($table);
		return TRUE;
    } 			
}