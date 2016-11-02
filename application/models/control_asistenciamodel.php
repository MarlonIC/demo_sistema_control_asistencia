<?php
class control_asistenciaModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function consulta_control_asistencia_codPer($per_codigo) {
    	$result = FALSE;
        $query = $this->db->query("call sp_consulta_control_asistencia_codPer(?)",array('_per_codigo'=>$per_codigo));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
    }
	
    function listado_control_asistencia_todos($per_codigo, $coa_fecha_I, $coa_fecha_F, $ini_reg=0, $num_reg=0, $sortfield, $order) {
        $result = FALSE;
        $query = $this->db->query("call sp_listado_control_asistencia_todos(?,?,?,?,?,?,?)", array('_per_codigo'=>$per_codigo,'_coa_fecha_I'=>$coa_fecha_I,'_coa_fecha_F'=>$coa_fecha_F,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function listado_control_asistencia_cantidad($per_codigo, $coa_fecha_I, $coa_fecha_F) {
        $result = FALSE;
        $query = $this->db->query("call sp_listado_control_asistencia_cantidad(?,?,?)", array('_per_codigo'=>$per_codigo,'_coa_fecha_I'=>$coa_fecha_I,'_coa_fecha_F'=>$coa_fecha_F));
        $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result->num_rows;
    }
}
?>