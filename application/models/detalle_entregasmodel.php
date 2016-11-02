<?php
class detalle_entregasModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function listado_detalle_entregas_todos($zod_codigo, $dee_fecha_I, $dee_fecha_F, $dee_tipo, $dee_numero, $ent_codigo, $per_nro_documento, $per_nombres_completo, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_detalle_entregas_todos(?,?,?,?,?,?,?,?,?,?,?,?)", array('_zod_codigo'=>$zod_codigo,'_dee_fecha_I'=>$dee_fecha_I,'_dee_fecha_F'=>$dee_fecha_F,'_dee_tipo'=>$dee_tipo,'_dee_numero'=>$dee_numero,'_ent_codigo'=>$ent_codigo,'_per_nro_documento'=>$per_nro_documento,'_per_nombres_completo'=>$per_nombres_completo,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }
    
    function listado_detalle_entregas_cantidad($zod_codigo, $dee_fecha_I, $dee_fecha_F, $dee_tipo, $dee_numero, $ent_codigo, $per_nro_documento, $per_nombres_completo){
        $query = $this->db->query("call sp_listado_detalle_entregas_cantidad(?,?,?,?,?,?,?,?)", array('_zod_codigo'=>$zod_codigo,'_dee_fecha_I'=>$dee_fecha_I,'_dee_fecha_F'=>$dee_fecha_F,'_dee_tipo'=>$dee_tipo,'_dee_numero'=>$dee_numero,'_ent_codigo'=>$ent_codigo,'_per_nro_documento'=>$per_nro_documento,'_per_nombres_completo'=>$per_nombres_completo));
        $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result->num_rows;
    }

    function consulta_detalle_entregas_coddee($dee_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_detalle_entregas_coddee(?)", array('_dee_codigo'=>$dee_codigo));
        if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_detalle_entregas_codgul($gul_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_detalle_entregas_codgul(?)", array('_gul_codigo'=>$gul_codigo));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
}
?>