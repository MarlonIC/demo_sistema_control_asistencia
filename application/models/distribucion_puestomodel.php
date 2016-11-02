<?php
class distribucion_puestoModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function listado_distribucion_puesto_todos($pue_codigo, $rem_fecha_I, $rem_fecha_F, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_distribucion_puesto_todos(?,?,?,?,?,?,?)", array('_pue_codigo'=>$pue_codigo,'_rem_fecha_I'=>$rem_fecha_I,'_rem_fecha_F'=>$rem_fecha_F,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }
    
    function listado_distribucion_puesto_cantidad($pue_codigo, $rem_fecha_I, $rem_fecha_F){
        $query = $this->db->query("call sp_listado_distribucion_puesto_cantidad(?,?,?)", array('_pue_codigo'=>$pue_codigo,'_rem_fecha_I'=>$rem_fecha_I,'_rem_fecha_F'=>$rem_fecha_F));
        $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result->num_rows;
    }

    function consulta_distribucion_puesto_codigo($rem_codigo, $per_codigo, $pue_codigo, $pro_codigo, $var_codigo, $cal_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_distribucion_puesto_codigo(?,?,?,?,?,?)", array('_rem_codigo'=>$rem_codigo,'_per_codigo'=>$per_codigo,'_pue_codigo'=>$pue_codigo,'_pro_codigo'=>$pro_codigo,'_var_codigo'=>$var_codigo,'_cal_codigo'=>$cal_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_distribucion_puesto_codrem($rem_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_distribucion_puesto_codrem(?)", array('_rem_codigo'=>$rem_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_distribucion_puesto_codrem2($rem_codigo,$per_codigo,$pro_codigo,$var_codigo,$cal_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_distribucion_puesto_codrem2(?,?,?,?,?)", array('_rem_codigo'=>$rem_codigo,'_per_codigo'=>$per_codigo,'_pro_codigo'=>$pro_codigo,'_var_codigo'=>$var_codigo,'_cal_codigo'=>$cal_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_distribucion_puesto_stock($pue_codigo){
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_distribucion_puesto_stock(?)", array('_pue_codigo'=>$pue_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

}
?>