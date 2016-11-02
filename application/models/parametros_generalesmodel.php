<?php
class parametros_generalesModel extends CI_Model{
    
    function __construnct(){
        parent::__construnct();
    }

    function consulta_parametros_generales_nombre($pag_nombre){
        $query = $this->db->query("call sp_consulta_parametros_generales_nombre(?)", array('_pag_nombre'=>$pag_nombre));
		$result = $query->row();
        $query->free_result();
		$query->next_result();
		return $result->pag_valor;
	}

    function genera_numero_correlativo($tipo_entidad){
        $query = $this->db->query("call sp_genera_numero_correlativo(?)", array('_tipo_entidad'=>$tipo_entidad));
        $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result->numero_correlativo;
    }

    function genera_numero_correlativo2($tipo_entidad){
        $query = $this->db->query("call sp_genera_numero_correlativo2(?)", array('_tipo_entidad'=>$tipo_entidad));
        $result = $query->row();
        $query->free_result();
        $query->next_result();
        return $result->numero_correlativo;
    }

}
?>