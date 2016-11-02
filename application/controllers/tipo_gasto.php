<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_gasto extends CI_Controller {
	public $menu;
	private $ENT_TIPO_GASTO = 33;
	private $FUN_A_TIPO_GASTO = "MTOTIG";

    public function __construct(){
        parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('account/index', 'refresh');		
		if(!acceso_bloqueado('TIG')) redirect('home/seleccionar', 'refresh');
		$this->load->model('tipo_gastoModel','',TRUE);	
    }

    public function index(){    
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_TIPO_GASTO,GL_PERMISO_ACCION)) acceso_denegado(currtig_url());
		$this->session->set_userdata('Session_formTIGPaginicio', current_url());

		$tig_nombre = sies_vacio($this->input->post('tig_nombre'),'');

		$num_pagina = 1;
		$segmento3 = $this->uri->segment(3);
		if($segmento3 == 'page'){
			$num_pagina = $this->uri->segment(4);
			if(empty($num_pagina)) $num_pagina = 1;
		}

		$bdsortfield = 'tig_codigo';
		$bdorder = 'asc';
		$segmento5 = $this->uri->segment(5);
		$segmento6 = $this->uri->segment(6);
		if(!empty($segmento5)){
			$bdsortfield = $segmento5;
			if(!empty($segmento6)) $bdorder = $segmento6;
		}

		$aud_descripcion = 'Se realizó la consulta';
		$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_TIPO_GASTO, 'acc_codigo' => ACC_CONSULTA, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
		$this->hitaloagroModel->add('auditoria', $data);

		$pagreferrer = $this->agent->referrer();
		if(strrpos($pagreferrer, "tipo_gasto") > -1){
			$Session_tig_nombre = obtiene_ValorSession('Session_formTIGTignombre');
			if(!empty($Session_tig_nombre)) $tig_nombre = $Session_tig_nombre;
		}

		$bdini_reg = ($num_pagina - 1) * GL_CANTIDAD_PAGINA;
		$bdnum_reg = GL_CANTIDAD_PAGINA;

		$this->data['Listado_Entidad'] = $this->tipo_gastoModel->listado_tipo_gasto_todos($tig_nombre, $bdini_reg, $bdnum_reg, $bdsortfield, $bdorder);
        $this->data['Total_ListEntidad'] = $this->tipo_gastoModel->listado_tipo_gasto_cantidad($tig_nombre);
		$this->data['tig_nombre'] = $tig_nombre;

	    $config['total_rows'] = $this->data['Total_ListEntidad'];
	    $this->pagination->initialize($config);
	    $str_links = $this->pagination->create_links();
		$this->data["links"] = explode('&nbsp;',$str_links );
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
		$this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
	    $this->load->view('tipo_gasto/index', $this->data);
    }

    public function registrar(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_TIPO_GASTO,GL_PERMISO_ACCION)) acceso_denegado(currtig_url());
		
		$this->data['Session_AliasRol'] = $session_data['Session_AliasRol'];
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('tipo_gasto/registrar', $this->data);
    }
	
	function registrarsave(){		
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_TIPO_GASTO,GL_PERMISO_ACCION)) acceso_denegado(currtig_url());
             
		$this->form_validation->set_rules('tig_nombre', 'Nombre', 'trim|required|xss_clean');
		$this->form_validation->set_rules('tig_tipo', 'Tipo Situación', 'trim|required|xss_clean');
		$this->form_validation->set_rules('tig_estado', 'Estado', 'trim|required|xss_clean');
		
        if ($this->form_validation->run() == false)
        {
        	$this->data['Session_AliasRol'] = $session_data['Session_AliasRol'];
            $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
			$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
            $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		    $this->data['nav'] = $this->load->view('template/nav', null, true);
		    $this->data['footer'] = $this->load->view('template/footer', null, true);
            $this->load->view('tipo_gasto/registrar', $this->data);
        } else
        {                            
            $data = array(
                'tig_nombre' => $this->input->post('tig_nombre'),
                'tig_tipo' => $this->input->post('tig_tipo'),
				'tig_estado' => $this->input->post('tig_estado')
            );
           
			$tig_codigo = $this->hitaloagroModel->add_return('tipo_gasto',$data);
            if(isset($tig_codigo)){
				
				$aud_descripcion = 'Se ingresó el registro '.$tig_codigo;
				$data2 = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_TIPO_GASTO, 'acc_codigo' => ACC_NUEVO, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
				$this->hitaloagroModel->add('auditoria', $data2);
				
                $this->data['mensaje_exito'] = 'Se grabó correctamente los datos del tipo de gasto';
                $this->data['pagina_retorno'] = base_url().'tipo_gasto/index';

				$this->data['Session_AliasRol'] = $session_data['Session_AliasRol'];
                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
		        $this->data['informacion'] = $this->load->view('template/informacion', null, true);
		        $this->load->view('tipo_gasto/registrar', $this->data);
			}
			else
			{
				$this->data['Session_AliasRol'] = $session_data['Session_AliasRol'];
                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
				$this->data['mensaje_error'] = 'Hubo problemas al grabar los datos del tipo de gasto';
				$this->load->view('tipo_gasto/registrar', $this->data);
			}
		}		   
    }	

    public function editar(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_TIPO_GASTO,GL_PERMISO_ACCION)) acceso_denegado(currtig_url());
        
        $tig_codigo =  $this->uri->segment(3);			
		$result = $this->tipo_gastoModel->consulta_tipo_gasto_codtig($tig_codigo);
        if(!$result){
            $this->session->set_userdata('Session_MensajeError', 'Error. El tipo de gasto que está consultando no existe');
            redirect('error/index', 'refresh');
        }

        $this->data['result'] = $result;

		$this->data['Session_AliasRol'] = $session_data['Session_AliasRol'];
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('tipo_gasto/editar', $this->data);
    }
	
	function editarsave(){        
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_TIPO_GASTO,GL_PERMISO_ACCION)) acceso_denegado(currtig_url());
        
        $this->form_validation->set_rules('tig_codigo', '', 'trim|xss_clean');	
		$this->form_validation->set_rules('tig_nombre', 'Nombre', 'trim|required|xss_clean');
		$this->form_validation->set_rules('tig_tipo', 'Tipo Situación', 'trim|required|xss_clean');
		$this->form_validation->set_rules('tig_estado', 'Estado', 'trim|required|xss_clean');
		
        if ($this->form_validation->run() == false)
        {
        	$this->data['Session_AliasRol'] = $session_data['Session_AliasRol'];
            $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
			$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
            $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		    $this->data['nav'] = $this->load->view('template/nav', null, true);
		    $this->data['footer'] = $this->load->view('template/footer', null, true);
            $this->load->view('tipo_gasto/editar', $this->data);
        } else
        {                            
            $data = array(
				'tig_nombre' => $this->input->post('tig_nombre'),
				'tig_tipo' => $this->input->post('tig_tipo'),
				'tig_estado' => $this->input->post('tig_estado')
            );
           
			if ($this->hitaloagroModel->edit('tipo_gasto', $data, array('tig_codigo'=>$this->input->post('tig_codigo'))) == TRUE)
			{
				$aud_descripcion = 'Se modificó el registro '.$this->input->post('rol_codigo');
				$data2 = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_TIPO_GASTO, 'acc_codigo' => ACC_MODIFICACION, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
				$this->hitaloagroModel->add('auditoria', $data2);
			
                $this->data['mensaje_exito'] = 'Se grabó correctamente los datos del tipo de gasto';
                $this->data['pagina_retorno'] = base_url().'tipo_gasto/index';
			
				$this->data['Session_AliasRol'] = $session_data['Session_AliasRol'];
                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
		        $this->data['informacion'] = $this->load->view('template/informacion', null, true);
		        $this->load->view('tipo_gasto/editar', $this->data);
			}
			else
			{	
				$this->data['Session_AliasRol'] = $session_data['Session_AliasRol'];
                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
				$this->data['mensaje_error'] = 'Hubo problemas al grabar los datos del tipo de gasto';
				$this->load->view('tipo_gasto/editar', $this->data);
			}
		}
    }
	
	function eliminar(){        
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_TIPO_GASTO,GL_PERMISO_ACCION)) acceso_denegado(currtig_url());
		
		$tig_codigo =  $this->uri->segment(3);
		if($this->hitaloagroModel->edit('tipo_gasto', array('tig_estado'=>'X'), array('tig_codigo'=>$tig_codigo)) == TRUE){
			$aud_descripcion = 'Se eliminó el registro '.$tig_codigo;
			$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_TIPO_GASTO, 'acc_codigo' => ACC_ELIMINACION, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
			$this->hitaloagroModel->add('auditoria', $data);
			
		    redirect(base_url().'tipo_gasto/index');
        }else{
            $this->data['mensaje_error'] = 'Hubo problemas al eliminar el tipo de gasto';
			$this->load->view('tipo_gasto/index', $this->data);
        }
    }

    public function detalle(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_TIPO_GASTO,GL_PERMISO_ACCION)) acceso_denegado(currtig_url());
		
        $tig_codigo =  $this->uri->segment(3);			
		$result = $this->tipo_gastoModel->consulta_tipo_gasto_codtig($tig_codigo);
        if(!$result){
            $this->session->set_userdata('Session_MensajeError', 'Error. El tipo de gasto que está consultando no existe');
            redirect('error/index', 'refresh');
        }

        $aud_descripcion = 'Se ingresó al detalle del registro '.$tig_codigo;
		$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_TIPO_GASTO, 'acc_codigo' => ACC_DETALLE, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
		$this->hitaloagroModel->add('auditoria', $data);

        $this->data['result'] = $result;
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('tipo_gasto/detalle', $this->data);
    }
	
	public function exportar(){
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_TIPO_GASTO,GL_PERMISO_ACCION)) acceso_denegado(currtig_url());
			
		$tig_nombre = sies_vacio($this->input->post('tig_nombre'),'');
		$bdsortfield = 'tig_codigo';
		$bdorder = 'asc';

		$result = $this->tipo_gastoModel->listado_tipo_gasto_todos($tig_nombre, 0, $this->input->post('h_tot_listado'), $bdsortfield, $bdorder);
		if(!$result){ return false; }

		$this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $sheet = $this->excel->getActiveSheet();
        $sheet->setTitle('tipo_gasto');			 
		
		setCellValueH($sheet, 'A1', 'Código');
        setCellValueH($sheet, 'B1', 'Nombre Entrega');
        setCellValueH($sheet, 'C1', 'Tipo Situación');
        setCellValueH($sheet, 'D1', 'Estado');
		
		$fila = 2;
		foreach($result as $row){
			setCellValueI($sheet, 'A'.$fila, $row->tig_codigo, 'C');
			setCellValueI($sheet, 'B'.$fila, $row->tig_nombre, 'L');
			setCellValueI($sheet, 'C'.$fila, settext_estado6($row->tig_tipo), 'C');
			setCellValueI($sheet, 'D'.$fila, settext_estado($row->tig_estado), 'C');	
			$fila += 1;		
		}
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);

		//-------------------------- 
		while (ob_get_level() > 0) {
			ob_end_clean();
        }
		
        $filename = 'Listado_de_Tipo_Gastos.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$objWriter->save('documentos/'.$filename);
		$this->excel->disconnectWorksheets();
		unset($this->excel);
		
	    $this->load->helper('download');		
		$this->data['download_path'] = $filename;
		$this->load->view('template/descargar',$this->data);
	}
	
	//---------------------------------
	
	function grabafiltros_ajax(){
		$session_data = $this->session->userdata('logged_in');
		
		$this->session->set_userdata('Session_formTIGTignombre', $this->input->post('tig_nombre'));
	}

}
?>