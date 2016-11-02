<?php
class distribucion_puesto_stockModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }
    
    function transaction_distribucion_puesto_stock_agregar($dips_fecha, $luv_codigo, $pue_codigo, $pro_codigo, $var_codigo, $cal_codigo, $dips_cantidad, $dips_peso){
        $result = TRUE; 
        $query = $this->db->query("call sp_transaction_distribucion_puesto_stock_agregar(?,?,?,?,?,?,?,?)",array('_dips_fecha'=>$dips_fecha, '_luv_codigo'=>$luv_codigo, '_pue_codigo'=>$pue_codigo, '_pro_codigo'=>$pro_codigo, '_var_codigo'=>$var_codigo, '_cal_codigo'=>$cal_codigo, '_dips_cantidad'=>$dips_cantidad, '_dips_peso'=>$dips_peso));     
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function transaction_distribucion_puesto_stock_descontar($luv_codigo, $pue_codigo, $pro_codigo, $var_codigo, $cal_codigo, $dips_cantidad, $dips_peso){
        $result = TRUE; 
        $query = $this->db->query("call sp_transaction_distribucion_puesto_stock_descontar(?,?,?,?,?,?,?)",array('_luv_codigo'=>$luv_codigo, '_pue_codigo'=>$pue_codigo, '_pro_codigo'=>$pro_codigo, '_var_codigo'=>$var_codigo, '_cal_codigo'=>$cal_codigo, '_dips_cantidad'=>$dips_cantidad, '_dips_peso'=>$dips_peso));     
        $query->free_result();
        $query->next_result();
        return $result;
    }
    
    function transaction_distribucion_puesto_stock_eliminar($dips_fecha, $luv_codigo, $pue_codigo, $pro_codigo, $var_codigo, $cal_codigo){
        $result = TRUE; 
        $query = $this->db->query("call sp_transaction_distribucion_puesto_stock_eliminar(?,?,?,?,?,?)",array('_dips_fecha'=>$dips_fecha, '_luv_codigo'=>$luv_codigo, '_pue_codigo'=>$pue_codigo, '_pro_codigo'=>$pro_codigo, '_var_codigo'=>$var_codigo, '_cal_codigo'=>$cal_codigo));     
        $query->free_result();
        $query->next_result();
        return $result;
    }
    
    function transaction_distribucion_puesto_stock_finalizar($dips_fecha, $luv_codigo){
        $result = TRUE; 
        $query = $this->db->query("call sp_transaction_distribucion_puesto_stock_finalizar(?,?)",array('_dips_fecha'=>$dips_fecha, '_luv_codigo'=>$luv_codigo));     
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_distribucion_puesto_stock_codigo($pue_codigo) {
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_distribucion_puesto_stock_codigo(?)", array('_pue_codigo'=>$pue_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_distribucion_puesto_stock_codigo2($pue_codigo) {
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_distribucion_puesto_stock_codigo2(?)", array('_pue_codigo'=>$pue_codigo));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function consulta_distribucion_puesto_stock_stock($luv_codigo, $pue_codigo) {
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_distribucion_puesto_stock_stock(?,?)", array('_luv_codigo'=>$luv_codigo,'_pue_codigo'=>$pue_codigo));
        if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result;
    }
}
?>