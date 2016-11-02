<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Funcionalidad extends CI_Controller {
	public $menu;
	private $ENT_FUNCIONALIDAD = 11;
	private $FUN_A_FUNCIONALIDAD = "MTOFUN";

    public function __construct(){
        parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('account/index', 'refresh');		
		if(!acceso_bloqueado('FUN')) redirect('home/seleccionar', 'refresh');
		$this->load->model('funcionalidadModel','',TRUE);	
    }

    public function index(){    
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_FUNCIONALIDAD,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		$this->session->set_userdata('Session_formFUNPaginicio', current_url());

		$fun_nombre = sies_vacio($this->input->post('fun_nombre'),'');

		$num_pagina = 1;
		$segmento3 = $this->uri->segment(3);
		if($segmento3 == 'page'){
			$num_pagina = $this->uri->segment(4);
			if(empty($num_pagina)) $num_pagina = 1;
		}

		$bdsortfield = 'cod_orden, orden_funcionalidad';
		$bdorder = 'asc';
		$segmento5 = $this->uri->segment(5);
		$segmento6 = $this->uri->segment(6);
		if(!empty($segmento5)){
			$bdsortfield = $segmento5;
			if(!empty($segmento6)) $bdorder = $segmento6;
		}
		
		$aud_descripcion = 'Se realizó la consulta';
		$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_FUNCIONALIDAD, 'acc_codigo' => ACC_CONSULTA, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
		$this->hitaloagroModel->add('auditoria', $data);

		$pagreferrer = $this->agent->referrer();
		if(strrpos($pagreferrer, "funcionalidad") > -1){
			$Session_fun_nombre = obtiene_ValorSession('Session_formFUNFunnombre');
			if(!empty($Session_fun_nombre)) $fun_nombre = $Session_fun_nombre;
		}

		$bdini_reg = ($num_pagina - 1) * GL_CANTIDAD_PAGINA;
		$bdnum_reg = GL_CANTIDAD_PAGINA;

		$this->data['Listado_Entidad'] = $this->funcionalidadModel->listado_funcionalidad_todos($fun_nombre, $bdini_reg, $bdnum_reg, $bdsortfield, $bdorder);
        $this->data['Total_ListEntidad'] = $this->funcionalidadModel->listado_funcionalidad_cantidad($fun_nombre);
		$this->data['fun_nombre'] = $fun_nombre;

	    $config['total_rows'] = $this->data['Total_ListEntidad'];
	    $this->pagination->initialize($config);
	    $str_links = $this->pagination->create_links();
		$this->data["links"] = explode('&nbsp;',$str_links );
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
		$this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
	    $this->load->view('funcionalidad/index', $this->data);
    }

    public function registrar(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_FUNCIONALIDAD,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		
	    $comboFuncionalidad = $this->funcionalidadModel->consulta_funcionalidad_todos('A');
		$this->data['Combo_Funcionalidad'] = set_dropdown($comboFuncionalidad, 'fun_codigo', 'fun_nombre');
		
        $this->session->set_userdata('Session_formFUNAlias', '');

        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('funcionalidad/registrar', $this->data);
    }
	
	function registrarsave(){		
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_FUNCIONALIDAD,GL_PERMISO_ACCION)) acceso_denegado(current_url());
             
		$this->form_validation->set_rules('fun_nombre', 'Nombre', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fun_codigo_padre', 'Agrupador', 'trim|xss_clean'); 
		$this->form_validation->set_rules('fun_orden', 'Orden', 'trim|required|xss_clean');  
		$this->form_validation->set_rules('fun_alias', 'Alias', 'trim|required|xss_clean|callback_verificarBD');
		$this->form_validation->set_rules('fun_url', 'Dirección URL', 'trim|xss_clean'); 
		$this->form_validation->set_rules('fun_orden', 'Orden', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fun_tipo', 'Tipo', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fun_estado', 'Estado', 'trim|required|xss_clean');
		
        if ($this->form_validation->run() == false)
        {
        	$comboFuncionalidad = $this->funcionalidadModel->consulta_funcionalidad_todos('A');
			$this->data['Combo_Funcionalidad'] = set_dropdown($comboFuncionalidad, 'fun_codigo', 'fun_nombre');

            $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
			$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
            $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		    $this->data['nav'] = $this->load->view('template/nav', null, true);
		    $this->data['footer'] = $this->load->view('template/footer', null, true);
            $this->load->view('funcionalidad/registrar', $this->data);
        } else
        {                            
            $data = array(
                'fun_nombre' => $this->input->post('fun_nombre'),
				'fun_alias' => $this->input->post('fun_alias'),
				'fun_codigo_padre' => set_null($this->input->post('fun_codigo_padre')),
				'fun_url' => set_null($this->input->post('fun_url')),
				'fun_orden' => $this->input->post('fun_orden'),
				'fun_tipo' => $this->input->post('fun_tipo'),
				'fun_estado' => $this->input->post('fun_estado')
            );
           
			$fun_codigo = $this->hitaloagroModel->add_return('funcionalidad',$data);
            if(isset($fun_codigo)){
				
				$aud_descripcion = 'Se ingresó el registro '.$fun_codigo;
				$data2 = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_FUNCIONALIDAD, 'acc_codigo' => ACC_NUEVO, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
				$this->hitaloagroModel->add('auditoria', $data2);
				
                $this->data['mensaje_exito'] = 'Se grabó correctamente los datos de la funcionalidad';
                $this->data['pagina_retorno'] = base_url().'funcionalidad/index';

                $comboFuncionalidad = $this->funcionalidadModel->consulta_funcionalidad_todos('A');
				$this->data['Combo_Funcionalidad'] = set_dropdown($comboFuncionalidad, 'fun_codigo', 'fun_nombre');
			
                $this->session->set_userdata('Session_formFUNAlias', '');
				
                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
		        $this->data['informacion'] = $this->load->view('template/informacion', null, true);
		        $this->load->view('funcionalidad/registrar', $this->data);
			}
			else
			{
				$comboFuncionalidad = $this->funcionalidadModel->consulta_funcionalidad_todos('A');
				$this->data['Combo_Funcionalidad'] = set_dropdown($comboFuncionalidad, 'fun_codigo', 'fun_nombre');

                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
				$this->data['mensaje_error'] = 'Hubo problemas al grabar los datos de la funcionalidad';
				$this->load->view('funcionalidad/registrar', $this->data);
			}
		}		   
    }	

    public function editar(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_FUNCIONALIDAD,GL_PERMISO_ACCION)) acceso_denegado(current_url());
        
        $fun_codigo =  $this->uri->segment(3);			
		$result = $this->funcionalidadModel->consulta_funcionalidad_codfun($fun_codigo);
        if(!$result){
            $this->session->set_userdata('Session_MensajeError', 'Error. La funcionalidad que está consultando no existe');
            redirect('error/index', 'refresh');
        }

        $comboFuncionalidad = $this->funcionalidadModel->consulta_funcionalidad_todos('A');
		$this->data['Combo_Funcionalidad'] = set_dropdown($comboFuncionalidad, 'fun_codigo', 'fun_nombre');

        $this->data['result'] = $result;
		
        $this->session->set_userdata('Session_formFUNAlias', $result->fun_alias);

        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('funcionalidad/editar', $this->data);
    }
	
	function editarsave(){        
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_FUNCIONALIDAD,GL_PERMISO_ACCION)) acceso_denegado(current_url());
        
        $this->form_validation->set_rules('fun_codigo', '', 'trim|xss_clean');	
		$this->form_validation->set_rules('fun_nombre', 'Nombre', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fun_codigo_padre', 'Agrupador', 'trim|xss_clean'); 
		$this->form_validation->set_rules('fun_orden', 'Orden', 'trim|required|xss_clean');  
		$this->form_validation->set_rules('fun_alias', 'Alias', 'trim|required|xss_clean|callback_verificarBD');
		$this->form_validation->set_rules('fun_url', 'Dirección URL', 'trim|xss_clean'); 
		$this->form_validation->set_rules('fun_orden', 'Orden', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fun_tipo', 'Tipo', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fun_estado', 'Estado', 'trim|required|xss_clean');
		
        if ($this->form_validation->run() == false)
        {
			$comboFuncionalidad = $this->funcionalidadModel->consulta_funcionalidad_todos('A');
			$this->data['Combo_Funcionalidad'] = set_dropdown($comboFuncionalidad, 'fun_codigo', 'fun_nombre');

            $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
			$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
            $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		    $this->data['nav'] = $this->load->view('template/nav', null, true);
		    $this->data['footer'] = $this->load->view('template/footer', null, true);
            $this->load->view('funcionalidad/editar', $this->data);
        } else
        {                            
            $data = array(
				'fun_nombre' => $this->input->post('fun_nombre'),
				'fun_alias' => $this->input->post('fun_alias'),
				'fun_codigo_padre' => set_null($this->input->post('fun_codigo_padre')),
				'fun_url' => set_null($this->input->post('fun_url')),
				'fun_orden' => $this->input->post('fun_orden'),
				'fun_tipo' => $this->input->post('fun_tipo'),
				'fun_estado' => $this->input->post('fun_estado')
            );
           
			if ($this->hitaloagroModel->edit('funcionalidad', $data, array('fun_codigo'=>$this->input->post('fun_codigo'))) == TRUE)
			{
				$aud_descripcion = 'Se modificó el registro '.$this->input->post('fun_codigo');
				$data2 = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_FUNCIONALIDAD, 'acc_codigo' => ACC_MODIFICACION, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
				$this->hitaloagroModel->add('auditoria', $data2);
			
                $this->data['mensaje_exito'] = 'Se grabó correctamente los datos de la funcionalidad';
                $this->data['pagina_retorno'] = base_url().'funcionalidad/index';
				
				$comboFuncionalidad = $this->funcionalidadModel->consulta_funcionalidad_todos('A');
				$this->data['Combo_Funcionalidad'] = set_dropdown($comboFuncionalidad, 'fun_codigo', 'fun_nombre');
		
                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
		        $this->data['informacion'] = $this->load->view('template/informacion', null, true);
		        $this->load->view('funcionalidad/editar', $this->data);
			}
			else
			{	
				$comboFuncionalidad = $this->funcionalidadModel->consulta_funcionalidad_todos('A');
				$this->data['Combo_Funcionalidad'] = set_dropdown($comboFuncionalidad, 'fun_codigo', 'fun_nombre');

                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
				$this->data['mensaje_error'] = 'Hubo problemas al grabar los datos de la funcionalidad';
				$this->load->view('funcionalidad/editar', $this->data);
			}
		}
    }
	
	function eliminar(){        
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_FUNCIONALIDAD,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		
		$fun_codigo =  $this->uri->segment(3);
		if($this->hitaloagroModel->edit('funcionalidad', array('fun_estado'=>'X'), array('fun_codigo'=>$fun_codigo)) == TRUE){
			$aud_descripcion = 'Se eliminó el registro '.$fun_codigo;
			$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_FUNCIONALIDAD, 'acc_codigo' => ACC_ELIMINACION, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
			$this->hitaloagroModel->add('auditoria', $data);
			
		    redirect(base_url().'funcionalidad/index');
        }else{
            $this->data['mensaje_error'] = 'Hubo problemas al eliminar la funcionalidad';
			$this->load->view('funcionalidad/index', $this->data);
        }
    }

    public function detalle(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_FUNCIONALIDAD,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		
        $fun_codigo =  $this->uri->segment(3);			
		$result = $this->funcionalidadModel->consulta_funcionalidad_codfun($fun_codigo);
        if(!$result){
            $this->session->set_userdata('Session_MensajeError', 'Error. La funcionalidad que está consultando no existe');
            redirect('error/index', 'refresh');
        }

        $this->data['result'] = $result;

        $aud_descripcion = 'Se ingresó al detalle del registro '.$fun_codigo;
		$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_FUNCIONALIDAD, 'acc_codigo' => ACC_DETALLE, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
		$this->hitaloagroModel->add('auditoria', $data);
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('funcionalidad/detalle', $this->data);
    }
	
	public function exportar(){
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_FUNCIONALIDAD,GL_PERMISO_ACCION)) acceso_denegado(current_url());
			
		$fun_nombre = sies_vacio($this->input->post('fun_nombre'),'');
		$bdsortfield = 'codigo_orden';
		$bdorder = 'asc';

		$result = $this->funcionalidadModel->listado_funcionalidad_todos($fun_nombre, 0, $this->input->post('h_tot_listado'), $bdsortfield, $bdorder);
		if(!$result){ return false; }

		$this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $sheet = $this->excel->getActiveSheet();
        $sheet->setTitle('Funcionalidades');			 
		
		setCellValueH($sheet, 'A1', 'Código');
        setCellValueH($sheet, 'B1', 'Nombre');
        setCellValueH($sheet, 'C1', 'Alias');
        setCellValueH($sheet, 'D1', 'Agrupador');
        setCellValueH($sheet, 'E1', 'Tipo');
        setCellValueH($sheet, 'F1', 'Estado');
		
		$fila = 2;
		foreach($result as $row){
			setCellValueI($sheet, 'A'.$fila, $row->fun_codigo, 'C');
			setCellValueI($sheet, 'B'.$fila, $row->fun_nombre, 'L');
			setCellValueI($sheet, 'C'.$fila, $row->fun_alias, 'C');
			setCellValueI($sheet, 'D'.$fila, $row->fun_nombrepadre, 'L');
			setCellValueI($sheet, 'E'.$fila, settext_estado5($row->fun_tipo), 'C');	
			setCellValueI($sheet, 'F'.$fila, settext_estado($row->fun_estado), 'C');	
			$fila += 1;		
		}
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setAutoSize(true);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);

		//-------------------------- 
		while (ob_get_level() > 0) {
			ob_end_clean();
        }
		
        $filename = 'Listado_de_Funcionalidades.xlsx';
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
        
		$this->session->set_userdata('Session_formFUNFunnombre', $this->input->post('fun_nombre'));
	}

    function verificarBD($fun_alias){
        if($this->session->userdata('Session_formFUNAlias') == $fun_alias) return TRUE;

        $result = $this->funcionalidadModel->verifica_funcionalidad_alias($fun_alias);
        if($result){
            $this->form_validation->set_message('verificarBD', 'El nombre Alias ya existe');
            return FALSE;
        }else{            
			return TRUE;
        }
    }

}
?>