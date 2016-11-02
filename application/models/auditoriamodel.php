<?php
class auditoriaModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

	function listado_auditoria_todos($aud_fecha_registroI, $aud_fecha_registroF, $usu_codigo, $ent_codigo, $acc_codigo, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_auditoria_todos(?,?,?,?,?,?,?,?,?)", array('_aud_fecha_registroI'=>$aud_fecha_registroI,'_aud_fecha_registroF'=>$aud_fecha_registroF,'_usu_codigo'=>$usu_codigo,'_ent_codigo'=>$ent_codigo,'_acc_codigo'=>$acc_codigo,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}

	function listado_auditoria_cantidad($aud_fecha_registroI, $aud_fecha_registroF, $usu_codigo, $ent_codigo, $acc_codigo){
        $query = $this->db->query("call sp_listado_auditoria_cantidad(?,?,?,?,?)", array('_aud_fecha_registroI'=>$aud_fecha_registroI,'_aud_fecha_registroF'=>$aud_fecha_registroF,'_usu_codigo'=>$usu_codigo,'_ent_codigo'=>$ent_codigo,'_acc_codigo'=>$acc_codigo));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->num_rows;
	}

    function consulta_auditoria_codaud($aud_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_auditoria_codaud(?)", array('_aud_codigo'=>$aud_codigo));
		if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
	}

}
?>