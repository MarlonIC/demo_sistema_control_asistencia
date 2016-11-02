<?php
class pagos_detModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function consulta_pagos_det_codpag($pag_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_pagos_det_codpag(?)", array('_pag_codigo'=>$pag_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_pagos_det_codven($ven_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_pagos_det_codven(?)", array('_ven_codigo'=>$ven_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function transaction_pagos_det_nuevo($pag_codigo, $ven_codigo, $pagd_tipo_pago, $pagd_monto){
        $result = TRUE;    
        $query = $this->db->query("call sp_transaction_pagos_det_nuevo(?,?,?,?)",array('_pag_codigo'=>$pag_codigo,'_ven_codigo'=>$ven_codigo,'_pagd_tipo_pago'=>$pagd_tipo_pago,'_pagd_monto'=>$pagd_monto));       
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function transaction_pagos_det_editar($pag_codigo, $ven_codigo, $pagd_tipo_pago, $pagd_monto){
        $result = TRUE;    
        $query = $this->db->query("call sp_transaction_pagos_det_editar(?,?,?,?)",array('_pag_codigo'=>$pag_codigo,'_ven_codigo'=>$ven_codigo,'_pagd_tipo_pago'=>$pagd_tipo_pago,'_pagd_monto'=>$pagd_monto));       
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function transaction_pagos_det_eliminar($pag_codigo, $ven_codigo){
        $result = TRUE;    
        $query = $this->db->query("call sp_transaction_pagos_det_eliminar(?,?)",array('_pag_codigo'=>$pag_codigo,'_ven_codigo'=>$ven_codigo));       
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function transaction_pagos_det_anular($pag_codigo){
        $result = TRUE;    
        $query = $this->db->query("call sp_transaction_pagos_det_anular(?)",array('_pag_codigo'=>$pag_codigo));
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function elimina_pagos_det($pag_codigo, $per_codigo){
        $result = TRUE;    
        $query = $this->db->query("call sp_elimina_pagos_det(?,?)",array('_pag_codigo'=>$pag_codigo,'_per_codigo'=>$per_codigo));       
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_pagos_det_monto_pago($ven_fecha, $luv_codigo, $pue_codigo) {
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_pagos_det_monto_pago(?,?,?)", array('_ven_fecha'=>$ven_fecha,'_luv_codigo'=>$luv_codigo,'_pue_codigo'=>$pue_codigo));
        if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result;
    }
}
?>