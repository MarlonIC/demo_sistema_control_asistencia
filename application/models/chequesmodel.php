<?php
class chequesModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function listado_cheques_todos($zod_codigo, $che_fecha_I, $che_fecha_F, $che_nro_cheque, $cub_codigo, $per_nro_documento, $tie_codigo, $per_nombres_completo, $ini_reg=0, $num_reg=0, $sortfield, $order){
        $result = FALSE;
        $query = $this->db->query("call sp_listado_cheques_todos(?,?,?,?,?,?,?,?,?,?,?,?)", array('_zod_codigo'=>$zod_codigo,'_che_fecha_I'=>$che_fecha_I,'_che_fecha_F'=>$che_fecha_F,'_che_nro_cheque'=>$che_nro_cheque,'_cub_codigo'=>$cub_codigo,'_per_nro_documento'=>$per_nro_documento,'_tie_codigo'=>$tie_codigo,'_per_nombres_completo'=>$per_nombres_completo,'_ini_reg'=>$ini_reg,'_num_reg'=>$num_reg,'_sortfield'=>$sortfield,'_order'=>$order));
        if($query->num_rows() > 0) 
            $result = $query->result();
        $query->free_result();
        $query->next_result();
        return $result;
    }

    function listado_cheques_cantidad($zod_codigo, $che_fecha_I, $che_fecha_F, $che_nro_cheque, $cub_codigo, $per_nro_documento, $tie_codigo, $per_nombres_completo){
        $query = $this->db->query("call sp_listado_cheques_cantidad(?,?,?,?,?,?,?,?)", array('_zod_codigo'=>$zod_codigo,'_che_fecha_I'=>$che_fecha_I,'_che_fecha_F'=>$che_fecha_F,'_che_nro_cheque'=>$che_nro_cheque,'_cub_codigo'=>$cub_codigo,'_per_nro_documento'=>$per_nro_documento,'_tie_codigo'=>$tie_codigo,'_per_nombres_completo'=>$per_nombres_completo,));
        $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result->num_rows;
    }

    function consulta_cheques_codche($che_codigo) {
        $result = FALSE;
        $query = $this->db->query("call sp_consulta_cheques_codche(?)", array('_che_codigo'=>$che_codigo));
        if($query->num_rows() > 0) 
            $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result;
    }
}