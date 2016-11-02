<?php
class ventasModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
	
	function listado_ventas_todos($ven_fecha_I, $ven_fecha_F, $ven_estado_pago, $ven_numero, $ven_tipo, $ven_tipo_comprobante, $tie_codigo, $per_nro_documento, $per_nombres_completo, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_ventas_todos(?,?,?,?,?,?,?,?,?,?,?,?,?)", array('_ven_fecha_I'=>$ven_fecha_I,'_ven_fecha_F'=>$ven_fecha_F,'_ven_estado_pago'=>$ven_estado_pago,'_ven_numero'=>$ven_numero,'_ven_tipo'=>$ven_tipo,'_ven_tipo_comprobante'=>$ven_tipo_comprobante,'_tie_codigo'=>$tie_codigo,'_per_nro_documento'=>$per_nro_documento,'_per_nombres_completo'=>$per_nombres_completo,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function listado_ventas_cantidad($ven_fecha_I, $ven_fecha_F, $ven_estado_pago, $ven_numero, $ven_tipo, $ven_tipo_comprobante, $tie_codigo, $per_nro_documento, $per_nombres_completo){
        $query = $this->db->query("call sp_listado_ventas_cantidad(?,?,?,?,?,?,?,?,?)", array('_ven_fecha_I'=>$ven_fecha_I,'_ven_fecha_F'=>$ven_fecha_F,'_ven_estado_pago'=>$ven_estado_pago,'_ven_numero'=>$ven_numero,'_ven_tipo'=>$ven_tipo,'_ven_tipo_comprobante'=>$ven_tipo_comprobante,'_tie_codigo'=>$tie_codigo,'_per_nro_documento'=>$per_nro_documento,'_per_nombres_completo'=>$per_nombres_completo));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->num_rows;
	}
    
    function listado_ventas_todos2($pue_codigo, $ven_fecha_I, $ven_fecha_F, $ven_estado_pago, $ven_numero, $ven_tipo, $ven_tipo_comprobante, $tie_codigo, $per_nro_documento, $per_nombres_completo, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_ventas_todos2(?,?,?,?,?,?,?,?,?,?,?,?,?,?)", array('_pue_codigo'=>$pue_codigo,'_ven_fecha_I'=>$ven_fecha_I,'_ven_fecha_F'=>$ven_fecha_F,'_ven_estado_pago'=>$ven_estado_pago,'_ven_numero'=>$ven_numero,'_ven_tipo'=>$ven_tipo,'_ven_tipo_comprobante'=>$ven_tipo_comprobante,'_tie_codigo'=>$tie_codigo,'_per_nro_documento'=>$per_nro_documento,'_per_nombres_completo'=>$per_nombres_completo,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }
    
    function listado_ventas_cantidad2($pue_codigo, $ven_fecha_I, $ven_fecha_F, $ven_estado_pago, $ven_numero, $ven_tipo, $ven_tipo_comprobante, $tie_codigo, $per_nro_documento, $per_nombres_completo){
        $query = $this->db->query("call sp_listado_ventas_cantidad2(?,?,?,?,?,?,?,?,?,?)", array('_pue_codigo'=>$pue_codigo,'_ven_fecha_I'=>$ven_fecha_I,'_ven_fecha_F'=>$ven_fecha_F,'_ven_estado_pago'=>$ven_estado_pago,'_ven_numero'=>$ven_numero,'_ven_tipo'=>$ven_tipo,'_ven_tipo_comprobante'=>$ven_tipo_comprobante,'_tie_codigo'=>$tie_codigo,'_per_nro_documento'=>$per_nro_documento,'_per_nombres_completo'=>$per_nombres_completo));
        $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result->num_rows;
    }

    function consulta_ventas_codven($ven_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_ventas_codven(?)", array('_ven_codigo'=>$ven_codigo));
		if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function consulta_ventas_codper_saldo($per_codigo){
        $query = $this->db->query("call sp_consulta_ventas_codper_saldo(?)", array('_per_codigo'=>$per_codigo));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->ven_saldo;
	}
	
	function consulta_ventas_codper_saldo2($per_codigo, $ven_codigo){
        $query = $this->db->query("call sp_consulta_ventas_codper_saldo2(?,?)", array('_per_codigo'=>$per_codigo,'_ven_codigo'=>$ven_codigo));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->ven_saldo;
	}
	
	function consulta_ventas_codper_pendiente($per_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_ventas_codper_pendiente(?)", array('_per_codigo'=>$per_codigo));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}
	
	function consulta_ventas_codper_deudas($per_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_ventas_codper_deudas(?)", array('_per_codigo'=>$per_codigo));
		if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
		$query->next_result();
		return $result;
	}

    function consulta_ventas_codigo_deudas($pag_codigo, $per_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_ventas_codigo_deudas(?,?)", array('_pag_codigo'=>$pag_codigo,'_per_codigo'=>$per_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_ventas_codigo_deudas2($pag_codigo, $per_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_ventas_codigo_deudas2(?,?)", array('_pag_codigo'=>$pag_codigo,'_per_codigo'=>$per_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }
	
	function verifica_ventas_numero($ven_numero){
        $result = FALSE;	
        $query = $this->db->query("call sp_verifica_ventas_numero(?)",array('_ven_numero'=>$ven_numero));		
		if($query->num_rows() == 1)
			$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result;
    }
    
}
?>