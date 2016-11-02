<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Persona extends CI_Controller {
	public $menu;
	private $ENT_PERSONA = 2;
	private $FUN_A_PERSONA = "MTOPER";

    public function __construct(){
        parent::__construct();
		if(!$this->session->userdata('logged_in')) redirect('account/index', 'refresh');		
		if(!acceso_bloqueado('PER')) redirect('home/seleccionar', 'refresh');
		$this->load->library(array('encrypt'));
		$this->load->model('rolModel','',TRUE);
		$this->load->model('personaModel','',TRUE);	
		$this->load->model('usuarioModel','',TRUE);
		$this->load->model('tipo_personaModel','',TRUE);
		$this->load->model('estado_civilModel','',TRUE);
		$this->load->model('tipo_documentoModel','',TRUE);
		$this->load->model('cargoModel','',TRUE);		
		$this->load->model('seguroModel','',TRUE);
		$this->load->model('bancoModel','',TRUE);
		$this->load->model('ubigeoModel','',TRUE);
		$this->load->model('nivel_estudiosModel','',TRUE);
    }

    public function index(){    
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_PERSONA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		$this->session->set_userdata('Session_formPERPaginicio', current_url());
		
		$per_nombre = sies_vacio($this->input->post('per_nombre'),'');

		$num_pagina = 1;
		$segmento3 = $this->uri->segment(3);
		if($segmento3 == 'page'){
			$num_pagina = $this->uri->segment(4);
			if(empty($num_pagina)) $num_pagina = 1;
		}

		$bdsortfield = 'per_codigo';
		$bdorder = 'asc';
		$segmento5 = $this->uri->segment(5);
		$segmento6 = $this->uri->segment(6);
		if(!empty($segmento5)){
			$bdsortfield = $segmento5;
			if(!empty($segmento6)) $bdorder = $segmento6;
		}

		$aud_descripcion = 'Se realizó la consulta';
		$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_PERSONA, 'acc_codigo' => ACC_CONSULTA, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
		$this->hitaloagroModel->add('auditoria', $data);

		$pagreferrer = $this->agent->referrer();
		if(strrpos($pagreferrer, "persona") > -1){
			$Session_per_nombre = obtiene_ValorSession('Session_formPERPernombre');
			if(!empty($Session_per_nombre)) $per_nombre = $Session_per_nombre;
		}

		$bdini_reg = ($num_pagina - 1) * GL_CANTIDAD_PAGINA;
		$bdnum_reg = GL_CANTIDAD_PAGINA;

		$this->data['Listado_Entidad'] = $this->personaModel->listado_persona_todos($per_nombre, $bdini_reg, $bdnum_reg, $bdsortfield, $bdorder);
        $this->data['Total_ListEntidad'] = $this->personaModel->listado_persona_cantidad($per_nombre);
		$this->data['per_nombre'] = $per_nombre;

	    $config['total_rows'] = $this->data['Total_ListEntidad'];
	    $this->pagination->initialize($config);
	    $str_links = $this->pagination->create_links();
		$this->data["links"] = explode('&nbsp;',$str_links );
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
		$this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
	    $this->load->view('persona/index', $this->data);
    }

    public function registrar(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_PERSONA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		
        $this->session->set_userdata('Session_formUSULogin', '');

        $comboDepartamento = $this->ubigeoModel->consulta_ubigeo_departamento();
		$this->data['Combo_Departamento'] = set_dropdown($comboDepartamento, 'ubi_coddep', 'ubi_departamento', 1);

		$comboEstadocivil = $this->estado_civilModel->consulta_estado_civil_todos('A');
		$this->data['Combo_Estadocivil'] = set_dropdown($comboEstadocivil, 'esc_codigo', 'esc_nombre', 1);

		$comboCargo = $this->cargoModel->consulta_cargo_todos();
		$this->data['comboCargo'] = set_dropdown($comboCargo, 'car_codigo', 'cargo', 1);

		$comboBanco = $this->bancoModel->consulta_banco_todos();
		$this->data['comboBanco'] = set_dropdown($comboBanco, 'ban_codigo', 'ban_banco', 1);

		$comboNivelEstudios = $this->nivel_estudiosModel->consulta_nivel_estudios_todos();
		$this->data['comboNivelEstudios'] = set_dropdown($comboNivelEstudios, 'nie_codigo', 'nie_estudios', 1);		
		
		$comboSeguro = $this->seguroModel->consulta_seguro_todos();
		$this->data['comboSeguro'] = set_dropdown($comboSeguro, 'seg_codigo', 'seg_seguro', 1);

		$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('persona/registrar', $this->data);
    }
	
	function registrarsave(){		
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_PERSONA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
        
        $this->form_validation->set_rules('per_nombres', 'Nombres', 'trim|xss_clean');		
        $this->form_validation->set_rules('per_apellidos', 'Apellidos', 'trim|xss_clean');
        $this->form_validation->set_rules('per_direccion', 'Direccion', 'trim|xss_clean');
        $this->form_validation->set_rules('per_fecha_nacimiento', 'Fecha de Nacimiento', 'trim|xss_clean');
        $this->form_validation->set_rules('per_dni', 'Numero de DNI', 'trim|xss_clean');
        $this->form_validation->set_rules('per_telefono_fijo', 'Telefono Fijo', 'trim|xss_clean');
        $this->form_validation->set_rules('per_telefono_movil', 'Telefono Movil', 'trim|xss_clean');
        $this->form_validation->set_rules('seg_codigo', 'Codigo de Seguro', 'trim|xss_clean');
        $this->form_validation->set_rules('per_fecha_ingreso', 'Fecha de Ingreso', 'trim|xss_clean');
        $this->form_validation->set_rules('car_codigo', 'Codigo de Cargo', 'trim|xss_clean');
        $this->form_validation->set_rules('ubi_codigo', 'Codigo de Ubigeo', 'trim|xss_clean');
        $this->form_validation->set_rules('per_codigo_empleado', 'Codigo de Empleado', 'trim|xss_clean');
        $this->form_validation->set_rules('ban_codigo', 'Codigo de Banco', 'trim|xss_clean');
        $this->form_validation->set_rules('per_cuenta_banco', 'Cuenta de Banco', 'trim|xss_clean');
        $this->form_validation->set_rules('nie_codigo', 'Nivel de Estudios', 'trim|xss_clean');
        $this->form_validation->set_rules('per_lugar_estudios', 'Lugar de Estudios', 'trim|xss_clean');
        $this->form_validation->set_rules('per_ultrabajo_empresa', 'Ultimo Trabajo Empresa', 'trim|xss_clean');
        $this->form_validation->set_rules('per_ultrabajo_cargo', 'Ultimo Trabajo Cargo', 'trim|xss_clean');
        $this->form_validation->set_rules('per_ultrabajo_telefono', 'Ultimo Trabajo Telefono', 'trim|xss_clean');
		$this->form_validation->set_rules('esc_codigo', 'Estado Civil', 'trim|xss_clean');
		$this->form_validation->set_rules('per_nro_hijos', 'Numero de hijos', 'trim|xss_clean');
		$this->form_validation->set_rules('per_casoemer_nombres', 'Caso de Emergencia Nombres', 'trim|xss_clean');
		$this->form_validation->set_rules('per_casoemer_apellidos', 'Caso de Emergencia Apellidos', 'trim|xss_clean');
		$this->form_validation->set_rules('per_casoemer_telefono', 'Caso de Emergencia Telefono', 'trim|xss_clean');
		$this->form_validation->set_rules('per_recomendado_nombres', 'Recomendado por Nombres', 'trim|xss_clean');
		$this->form_validation->set_rules('per_recomendado_apellidos', 'Recomendado por Apellidos', 'trim|xss_clean');
		$this->form_validation->set_rules('per_recomendado_telefono', 'Recomendado por Telefono', 'trim|xss_clean');
		$this->form_validation->set_rules('h_per_foto', 'Foto', 'trim|xss_clean');
		$this->form_validation->set_rules('per_tiene_acceso', 'Acceso al Sistema', 'trim|required|xss_clean');
		$this->form_validation->set_rules('usu_login', 'Login de Acceso', 'trim|xss_clean|callback_verificarBD');
		$this->form_validation->set_rules('usu_password', 'Contraseña', 'trim|xss_clean');
		$this->form_validation->set_rules('per_estado', 'Estado', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false)
        {
        	$comboDepartamento = $this->ubigeoModel->consulta_ubigeo_departamento();
			$this->data['Combo_Departamento'] = set_dropdown($comboDepartamento, 'ubi_coddep', 'ubi_departamento', 1);

			$comboEstadocivil = $this->estado_civilModel->consulta_estado_civil_todos('A');
			$this->data['Combo_Estadocivil'] = set_dropdown($comboEstadocivil, 'esc_codigo', 'esc_nombre', 1);

			$comboCargo = $this->cargoModel->consulta_cargo_todos();
			$this->data['comboCargo'] = set_dropdown($comboCargo, 'car_codigo', 'cargo', 1);

			$comboBanco = $this->bancoModel->consulta_banco_todos();
			$this->data['comboBanco'] = set_dropdown($comboBanco, 'ban_codigo', 'ban_banco', 1);

			$comboNivelEstudios = $this->nivel_estudiosModel->consulta_nivel_estudios_todos();
			$this->data['comboNivelEstudios'] = set_dropdown($comboNivelEstudios, 'nie_codigo', 'nie_estudios', 1);		
			
			$comboSeguro = $this->seguroModel->consulta_seguro_todos();
			$this->data['comboSeguro'] = set_dropdown($comboSeguro, 'seg_codigo', 'seg_seguro', 1);

			$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');

            $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
			$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
            $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		    $this->data['nav'] = $this->load->view('template/nav', null, true);
		    $this->data['footer'] = $this->load->view('template/footer', null, true);
            $this->load->view('persona/registrar', $this->data);
        } else
        {      
        	$ubi_codigo = str_replace('00','',$this->input->post('ubi_coddep').$this->input->post('h_ubi_codprov').$this->input->post('h_ubi_coddis'));
            $data = array(
				'per_nombres' => set_null($this->input->post('per_nombres')),
				'per_apellidos' => set_null($this->input->post('per_apellidos')),
				'per_direccion' => set_null($this->input->post('per_direccion')),
				'per_fecha_nacimiento' => dateFormat($this->input->post('per_fecha_nacimiento')),
				'per_dni' => set_null($this->input->post('per_dni')),
				'per_telefono_fijo' => set_null($this->input->post('per_telefono_fijo')),
				'per_telefono_movil' => set_null($this->input->post('per_telefono_movil')),
				'seg_codigo' => set_null($this->input->post('seg_codigo')),
				'per_fecha_ingreso' => dateFormat($this->input->post('per_fecha_ingreso')),
				'car_codigo' => set_null($this->input->post('car_codigo')),
				'ubi_codigo' => set_null($ubi_codigo),
				'per_codigo_empleado' => set_null($this->input->post('per_codigo_empleado')),
				'ban_codigo' => $this->input->post('ban_codigo'),
				'per_cuenta_banco' => set_null($this->input->post('per_cuenta_banco')),
				'nie_codigo' => set_null($this->input->post('nie_codigo')),
				'per_lugar_estudios' => set_null($this->input->post('per_lugar_estudios')),
				'per_ultrabajo_empresa' => set_null($this->input->post('per_ultrabajo_empresa')),
				'per_ultrabajo_cargo' => set_null($this->input->post('per_ultrabajo_cargo')),
				'per_ultrabajo_telefono' => set_null($this->input->post('per_ultrabajo_telefono')),
				'esc_codigo' => set_null($this->input->post('esc_codigo')),
				'per_nro_hijos' => set_null($this->input->post('per_nro_hijos')),
				'per_casoemer_nombres' => set_null($this->input->post('per_casoemer_nombres')),
				'per_casoemer_apellidos' => set_null($this->input->post('per_casoemer_apellidos')),
				'per_casoemer_telefono' => set_null($this->input->post('per_casoemer_telefono')),
				'per_recomendado_nombres' => set_null($this->input->post('per_recomendado_nombres')),
				'per_recomendado_apellidos' => set_null($this->input->post('per_recomendado_apellidos')),
				'per_recomendado_telefono' => set_null($this->input->post('per_recomendado_telefono')),
				'per_foto' => $this->input->post('h_per_foto'),
				'per_estado' => $this->input->post('per_estado'),
				'per_usuario_registro' => $session_data['Session_IdUsuario'],
				'per_fecha_registro' => date("Y-m-d H:i:s")
            );
           
			$per_codigo = $this->hitaloagroModel->add_return('persona',$data);
            if(isset($per_codigo)){
            	$refp_referencias = $this->input->post('refp_referencia');
		    	$refp_edad = $this->input->post('refp_edad');
		    	$refp_telefono = $this->input->post('refp_telefono');

            	for($i = 0; $i < 2; $i++) {
            		if($refp_referencias[$i] == '' && $refp_edad[$i] == '' && $refp_telefono[$i] == '') {
            			break;
            		} else {
            			$data_referencias_personales = array(
	            			'per_codigo' => $per_codigo,
	            			'refp_referencia' => $refp_referencias[$i],
	            			'refp_edad' => $refp_edad[$i],
	            			'refp_telefono' => $refp_telefono[$i]
	            		);
			    		$this->hitaloagroModel->add('det_referencias_personales',$data_referencias_personales);            			
            		}
		    	}

		    	$hic_nombres = $this->input->post('hic_nombres');
		    	$hic_apellidos = $this->input->post('hic_apellidos');
		    	$hic_edad = $this->input->post('hic_edad');

		    	for($i = 0; $i < 4; $i++) {
            		if($hic_nombres[$i] == '' && $hic_apellidos[$i] == '' && $hic_edad[$i] == '') {
            			break;
            		} else {
            			$data_hijos_conyuge = array(
	            			'per_codigo' => $per_codigo,
	            			'hic_nombres' => $hic_nombres[$i],
	            			'hic_apellidos' => $hic_apellidos[$i],
	            			'hic_edad' => $hic_edad[$i]
	            		);
			    		$this->hitaloagroModel->add('det_hijos_conyuge',$data_hijos_conyuge);	
            		}
		    	}

        		if($this->input->post('per_tiene_acceso') == 'S'){
        			$data2 = array(
						'per_codigo' => $per_codigo,
						'usu_login' => $this->input->post('usu_login'),
						'usu_password' => $this->encrypt->encode($this->input->post('usu_password')),
						'usu_estado' => $this->input->post('per_estado'),
						'usu_usuario_registro' => $session_data['Session_IdUsuario'],
						'usu_fecha_registro' => date("Y-m-d H:i:s")
		            );
		            $usu_codigo = $this->hitaloagroModel->add_return('usuario',$data2);
		            if(isset($usu_codigo)){
	        			$listado_roles = $this->input->post('h_listado_roles');
		            	if($listado_roles != ''){
		            		$listado_rolesarr = explode(',', $listado_roles);
							foreach($listado_rolesarr as $rol_codigo){
								$data3 = array(
									'usu_codigo' => $usu_codigo,
									'rol_codigo' => $rol_codigo
					            );
					            $this->hitaloagroModel->add('usuario_rol',$data3);
							}
		            	}
		            }
        		}	

				$aud_descripcion = 'Se ingresó el registro '.$per_codigo;
				$data4 = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_PERSONA, 'acc_codigo' => ACC_NUEVO, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
				$this->hitaloagroModel->add('auditoria', $data4);
				
                $this->data['mensaje_exito'] = 'Se grabó correctamente los datos de la persona';
                $this->data['pagina_retorno'] = base_url().'persona/index';
		
                $this->session->set_userdata('Session_formUSULogin', '');

                $comboDepartamento = $this->ubigeoModel->consulta_ubigeo_departamento();
				$this->data['Combo_Departamento'] = set_dropdown($comboDepartamento, 'ubi_coddep', 'ubi_departamento', 1);

				$comboEstadocivil = $this->estado_civilModel->consulta_estado_civil_todos('A');
				$this->data['Combo_Estadocivil'] = set_dropdown($comboEstadocivil, 'esc_codigo', 'esc_nombre', 1);

				$comboCargo = $this->cargoModel->consulta_cargo_todos();
				$this->data['comboCargo'] = set_dropdown($comboCargo, 'car_codigo', 'cargo', 1);

				$comboBanco = $this->bancoModel->consulta_banco_todos();
				$this->data['comboBanco'] = set_dropdown($comboBanco, 'ban_codigo', 'ban_banco', 1);

				$comboNivelEstudios = $this->nivel_estudiosModel->consulta_nivel_estudios_todos();
				$this->data['comboNivelEstudios'] = set_dropdown($comboNivelEstudios, 'nie_codigo', 'nie_estudios', 1);		
				
				$comboSeguro = $this->seguroModel->consulta_seguro_todos();
				$this->data['comboSeguro'] = set_dropdown($comboSeguro, 'seg_codigo', 'seg_seguro', 1);

				$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');

                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
		        $this->data['informacion'] = $this->load->view('template/informacion', null, true);
		        $this->load->view('persona/registrar', $this->data);
			}
			else
			{
				$comboDepartamento = $this->ubigeoModel->consulta_ubigeo_departamento();
				$this->data['Combo_Departamento'] = set_dropdown($comboDepartamento, 'ubi_coddep', 'ubi_departamento', 1);

				$comboEstadocivil = $this->estado_civilModel->consulta_estado_civil_todos('A');
				$this->data['Combo_Estadocivil'] = set_dropdown($comboEstadocivil, 'esc_codigo', 'esc_nombre', 1);

				$comboCargo = $this->cargoModel->consulta_cargo_todos();
				$this->data['comboCargo'] = set_dropdown($comboCargo, 'car_codigo', 'cargo', 1);

				$comboBanco = $this->bancoModel->consulta_banco_todos();
				$this->data['comboBanco'] = set_dropdown($comboBanco, 'ban_codigo', 'ban_banco', 1);

				$comboNivelEstudios = $this->nivel_estudiosModel->consulta_nivel_estudios_todos();
				$this->data['comboNivelEstudios'] = set_dropdown($comboNivelEstudios, 'nie_codigo', 'nie_estudios', 1);		
				
				$comboSeguro = $this->seguroModel->consulta_seguro_todos();
				$this->data['comboSeguro'] = set_dropdown($comboSeguro, 'seg_codigo', 'seg_seguro', 1);

				$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');

                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
				$this->data['mensaje_error'] = 'Hubo problemas al grabar los datos de la persona';
				$this->load->view('persona/registrar', $this->data);
			}
		}		   
    }	
	
	function eliminar(){        
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_PERSONA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		
		$per_codigo =  $this->uri->segment(3);
		$per_tiene_acceso =  $this->uri->segment(4);
		if($this->hitaloagroModel->edit('persona', array('per_estado'=>'X'), array('per_codigo'=>$per_codigo)) == TRUE){
			if($per_tiene_acceso == 'S'){
				$result = $this->usuarioModel->consulta_usuario_codper($per_codigo);
				$this->hitaloagroModel->delete('usuario_rol', array('usu_codigo'=>$result->usu_codigo));
				$this->hitaloagroModel->edit('usuario', array('usu_estado'=>'X'), array('usu_codigo'=>$result->usu_codigo));
			}
				
			$aud_descripcion = 'Se eliminó el registro '.$per_codigo;
			$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_PERSONA, 'acc_codigo' => ACC_ELIMINACION, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
			$this->hitaloagroModel->add('auditoria', $data);
			
		    redirect(base_url().'persona/index');
        }else{
            $this->data['mensaje_error'] = 'Hubo problemas al eliminar la persona';
			$this->load->view('persona/index', $this->data);
        }
    }

    public function editar(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_PERSONA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
        
        $per_codigo =  $this->uri->segment(3);			
		$result = $this->personaModel->consulta_persona_codper($per_codigo);
        if(!$result){
            $this->session->set_userdata('Session_MensajeError', 'Error. La persona que está consultando no existe');
            redirect('error/index', 'refresh');
        }

        $this->data['result_dhc'] = $this->personaModel->consulta_det_hijos_conyuge_perCod($per_codigo);
        $this->data['result_drp'] = $this->personaModel->consulta_det_referencias_personales_perCod($per_codigo);

        $this->data['result'] = $result;
        $this->session->set_userdata('Session_formUSULogin', $result->usu_login);

        $comboDepartamento = $this->ubigeoModel->consulta_ubigeo_departamento();
		$this->data['Combo_Departamento'] = set_dropdown($comboDepartamento, 'ubi_coddep', 'ubi_departamento', 1);

		$comboEstadocivil = $this->estado_civilModel->consulta_estado_civil_todos('A');
		$this->data['Combo_Estadocivil'] = set_dropdown($comboEstadocivil, 'esc_codigo', 'esc_nombre', 1);

		$comboCargo = $this->cargoModel->consulta_cargo_todos();
		$this->data['comboCargo'] = set_dropdown($comboCargo, 'car_codigo', 'cargo', 1);

		$comboBanco = $this->bancoModel->consulta_banco_todos();
		$this->data['comboBanco'] = set_dropdown($comboBanco, 'ban_codigo', 'ban_banco', 1);

		$comboNivelEstudios = $this->nivel_estudiosModel->consulta_nivel_estudios_todos();
		$this->data['comboNivelEstudios'] = set_dropdown($comboNivelEstudios, 'nie_codigo', 'nie_estudios', 1);		
		
		$comboSeguro = $this->seguroModel->consulta_seguro_todos();
		$this->data['comboSeguro'] = set_dropdown($comboSeguro, 'seg_codigo', 'seg_seguro', 1);

		$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');
		
        $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('persona/editar', $this->data);
    }
	
	function editarsave(){        
		$session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_PERSONA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
        
        $this->form_validation->set_rules('per_nombres', 'Nombres', 'trim|xss_clean');		
        $this->form_validation->set_rules('per_apellidos', 'Apellidos', 'trim|xss_clean');
        $this->form_validation->set_rules('per_direccion', 'Direccion', 'trim|xss_clean');
        $this->form_validation->set_rules('per_fecha_nacimiento', 'Fecha de Nacimiento', 'trim|xss_clean');
        $this->form_validation->set_rules('per_dni', 'Numero de DNI', 'trim|xss_clean');
        $this->form_validation->set_rules('per_telefono_fijo', 'Telefono Fijo', 'trim|xss_clean');
        $this->form_validation->set_rules('per_telefono_movil', 'Telefono Movil', 'trim|xss_clean');
        $this->form_validation->set_rules('seg_codigo', 'Codigo de Seguro', 'trim|xss_clean');
        $this->form_validation->set_rules('per_fecha_ingreso', 'Fecha de Ingreso', 'trim|xss_clean');
        $this->form_validation->set_rules('car_codigo', 'Codigo de Cargo', 'trim|xss_clean');
        $this->form_validation->set_rules('ubi_codigo', 'Codigo de Ubigeo', 'trim|xss_clean');
        $this->form_validation->set_rules('per_codigo_empleado', 'Codigo de Empleado', 'trim|xss_clean');
        $this->form_validation->set_rules('ban_codigo', 'Codigo de Banco', 'trim|xss_clean');
        $this->form_validation->set_rules('per_cuenta_banco', 'Cuenta de Banco', 'trim|xss_clean');
        $this->form_validation->set_rules('nie_codigo', 'Nivel de Estudios', 'trim|xss_clean');
        $this->form_validation->set_rules('per_lugar_estudios', 'Lugar de Estudios', 'trim|xss_clean');
        $this->form_validation->set_rules('per_ultrabajo_empresa', 'Ultimo Trabajo Empresa', 'trim|xss_clean');
        $this->form_validation->set_rules('per_ultrabajo_cargo', 'Ultimo Trabajo Cargo', 'trim|xss_clean');
        $this->form_validation->set_rules('per_ultrabajo_telefono', 'Ultimo Trabajo Telefono', 'trim|xss_clean');
		$this->form_validation->set_rules('esc_codigo', 'Estado Civil', 'trim|xss_clean');
		$this->form_validation->set_rules('per_nro_hijos', 'Numero de hijos', 'trim|xss_clean');
		$this->form_validation->set_rules('per_casoemer_nombres', 'Caso de Emergencia Nombres', 'trim|xss_clean');
		$this->form_validation->set_rules('per_casoemer_apellidos', 'Caso de Emergencia Apellidos', 'trim|xss_clean');
		$this->form_validation->set_rules('per_casoemer_telefono', 'Caso de Emergencia Telefono', 'trim|xss_clean');
		$this->form_validation->set_rules('per_recomendado_nombres', 'Recomendado por Nombres', 'trim|xss_clean');
		$this->form_validation->set_rules('per_recomendado_apellidos', 'Recomendado por Apellidos', 'trim|xss_clean');
		$this->form_validation->set_rules('per_recomendado_telefono', 'Recomendado por Telefono', 'trim|xss_clean');
		$this->form_validation->set_rules('h_per_foto', 'Foto', 'trim|xss_clean');
		$this->form_validation->set_rules('per_tiene_acceso', 'Acceso al Sistema', 'trim|required|xss_clean');
		$this->form_validation->set_rules('usu_login', 'Login de Acceso', 'trim|xss_clean|callback_verificarBD');
		$this->form_validation->set_rules('usu_password', 'Contraseña', 'trim|xss_clean');
		$this->form_validation->set_rules('per_estado', 'Estado', 'trim|required|xss_clean');
		
        if ($this->form_validation->run() == false)
        {
        	$this->data['result_dhc'] = $this->personaModel->consulta_det_hijos_conyuge_perCod($this->input->post('per_codigo'));
		    $this->data['result_drp'] = $this->personaModel->consulta_det_referencias_personales_perCod($this->input->post('per_codigo'));

		    $comboDepartamento = $this->ubigeoModel->consulta_ubigeo_departamento();
			$this->data['Combo_Departamento'] = set_dropdown($comboDepartamento, 'ubi_coddep', 'ubi_departamento', 1);

			$comboEstadocivil = $this->estado_civilModel->consulta_estado_civil_todos('A');
			$this->data['Combo_Estadocivil'] = set_dropdown($comboEstadocivil, 'esc_codigo', 'esc_nombre', 1);

			$comboCargo = $this->cargoModel->consulta_cargo_todos();
			$this->data['comboCargo'] = set_dropdown($comboCargo, 'car_codigo', 'cargo', 1);

			$comboBanco = $this->bancoModel->consulta_banco_todos();
			$this->data['comboBanco'] = set_dropdown($comboBanco, 'ban_codigo', 'ban_banco', 1);

			$comboNivelEstudios = $this->nivel_estudiosModel->consulta_nivel_estudios_todos();
			$this->data['comboNivelEstudios'] = set_dropdown($comboNivelEstudios, 'nie_codigo', 'nie_estudios', 1);		
			
			$comboSeguro = $this->seguroModel->consulta_seguro_todos();
			$this->data['comboSeguro'] = set_dropdown($comboSeguro, 'seg_codigo', 'seg_seguro', 1);

			$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');

            $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
			$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
            $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		    $this->data['nav'] = $this->load->view('template/nav', null, true);
		    $this->data['footer'] = $this->load->view('template/footer', null, true);
            $this->load->view('persona/editar', $this->data);
        } else
        {                            
            $ubi_codigo = str_replace('00','',$this->input->post('ubi_coddep').$this->input->post('h_ubi_codprov').$this->input->post('h_ubi_coddis'));
            $data = array(
				'per_nombres' => set_null($this->input->post('per_nombres')),
				'per_apellidos' => set_null($this->input->post('per_apellidos')),
				'per_direccion' => set_null($this->input->post('per_direccion')),
				'per_fecha_nacimiento' => dateFormat($this->input->post('per_fecha_nacimiento')),
				'per_dni' => set_null($this->input->post('per_dni')),
				'per_telefono_fijo' => set_null($this->input->post('per_telefono_fijo')),
				'per_telefono_movil' => set_null($this->input->post('per_telefono_movil')),
				'seg_codigo' => set_null($this->input->post('seg_codigo')),
				'per_fecha_ingreso' => dateFormat($this->input->post('per_fecha_ingreso')),
				'car_codigo' => set_null($this->input->post('car_codigo')),
				'ubi_codigo' => $ubi_codigo,
				'per_codigo_empleado' => set_null($this->input->post('per_codigo_empleado')),
				'ban_codigo' => $this->input->post('ban_codigo'),
				'per_cuenta_banco' => set_null($this->input->post('per_cuenta_banco')),
				'nie_codigo' => set_null($this->input->post('nie_codigo')),
				'per_lugar_estudios' => set_null($this->input->post('per_lugar_estudios')),
				'per_ultrabajo_empresa' => set_null($this->input->post('per_ultrabajo_empresa')),
				'per_ultrabajo_cargo' => set_null($this->input->post('per_ultrabajo_cargo')),
				'per_ultrabajo_telefono' => set_null($this->input->post('per_ultrabajo_telefono')),
				'esc_codigo' => set_null($this->input->post('esc_codigo')),
				'per_nro_hijos' => set_null($this->input->post('per_nro_hijos')),
				'per_casoemer_nombres' => set_null($this->input->post('per_casoemer_nombres')),
				'per_casoemer_apellidos' => set_null($this->input->post('per_casoemer_apellidos')),
				'per_casoemer_telefono' => set_null($this->input->post('per_casoemer_telefono')),
				'per_recomendado_nombres' => set_null($this->input->post('per_recomendado_nombres')),
				'per_recomendado_apellidos' => set_null($this->input->post('per_recomendado_apellidos')),
				'per_recomendado_telefono' => set_null($this->input->post('per_recomendado_telefono')),
				'per_foto' => $this->input->post('h_per_foto'),
				'per_estado' => $this->input->post('per_estado')
            );
           
			if ($this->hitaloagroModel->edit('persona', $data, array('per_codigo'=>$this->input->post('per_codigo'))) == TRUE)
			{
				$this->personaModel->transaction_det_personas_eliminar($this->input->post('per_codigo'));

				$refp_referencias = $this->input->post('refp_referencia');
		    	$refp_edad = $this->input->post('refp_edad');
		    	$refp_telefono = $this->input->post('refp_telefono');

            	for($i = 0; $i < 2; $i++) {
            		if($refp_referencias[$i] == '' && $refp_edad[$i] == '' && $refp_telefono[$i] == '') {
            			break;
            		} else {
            			$data_referencias_personales = array(
	            			'per_codigo' => $this->input->post('per_codigo'),
	            			'refp_referencia' => $refp_referencias[$i],
	            			'refp_edad' => $refp_edad[$i],
	            			'refp_telefono' => $refp_telefono[$i]
	            		);
			    		$this->hitaloagroModel->add('det_referencias_personales',$data_referencias_personales);            			
            		}
		    	}

		    	$hic_nombres = $this->input->post('hic_nombres');
		    	$hic_apellidos = $this->input->post('hic_apellidos');
		    	$hic_edad = $this->input->post('hic_edad');

		    	for($i = 0; $i < 4; $i++) {
            		if($hic_nombres[$i] == '' && $hic_apellidos[$i] == '' && $hic_edad[$i] == '') {
            			break;
            		} else {
            			$data_hijos_conyuge = array(
	            			'per_codigo' => $this->input->post('per_codigo'),
	            			'hic_nombres' => $hic_nombres[$i],
	            			'hic_apellidos' => $hic_apellidos[$i],
	            			'hic_edad' => $hic_edad[$i]
	            		);
			    		$this->hitaloagroModel->add('det_hijos_conyuge',$data_hijos_conyuge);	
            		}
		    	}

				if(($this->input->post('h_per_tiene_acceso')=='N') && ($this->input->post('per_tiene_acceso')=='S')){
					$data2 = array(
						'per_codigo' => $this->input->post('per_codigo'),
						'usu_login' => $this->input->post('usu_login'),
						'usu_password' => $this->encrypt->encode($this->input->post('usu_password')),
						'usu_estado' => $this->input->post('per_estado'),
						'usu_usuario_registro' => $session_data['Session_IdUsuario'],
						'usu_fecha_registro' => date("Y-m-d H:i:s")
		            );
		            $usu_codigo = $this->hitaloagroModel->add_return('usuario',$data2);
		            if(isset($usu_codigo)){
	        			$listado_roles = $this->input->post('h_listado_roles');
	            		$listado_rolesarr = explode(',', $listado_roles);
						foreach($listado_rolesarr as $rol_codigo){
							$data3 = array(
								'usu_codigo' => $usu_codigo,
								'rol_codigo' => $rol_codigo
				            );
				            $this->hitaloagroModel->add('usuario_rol',$data3);
						}		            	
		            }
				}else if(($this->input->post('h_per_tiene_acceso')=='S') && ($this->input->post('per_tiene_acceso')=='S')){
					$data2 = array(
						'usu_login' => $this->input->post('usu_login'),
						'usu_password' => $this->encrypt->encode($this->input->post('usu_password')),
						'usu_estado' => $this->input->post('per_estado')
		            );
		            $this->hitaloagroModel->edit('usuario', $data2, array('usu_codigo'=>$this->input->post('usu_codigo')));

					if($this->input->post('per_listado_roles') != $this->input->post('h_listado_roles')){
						if($this->hitaloagroModel->delete('usuario_rol', array('usu_codigo'=>$this->input->post('usu_codigo'))) == TRUE){
		                    $listado_roles = $this->input->post('h_listado_roles');			            	
		            		$listado_rolesarr = explode(',', $listado_roles);
							foreach($listado_rolesarr as $rol_codigo){
								$data3 = array(
									'usu_codigo' => $this->input->post('usu_codigo'),
									'rol_codigo' => $rol_codigo
					            );
					            $this->hitaloagroModel->add('usuario_rol',$data3);
							}
		                }
					}		            
				}

				$aud_descripcion = 'Se modificó el registro '.$this->input->post('per_codigo');
				$data4 = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_PERSONA, 'acc_codigo' => ACC_MODIFICACION, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
				$this->hitaloagroModel->add('auditoria', $data4);
			
                $this->data['mensaje_exito'] = 'Se grabó correctamente los datos de la persona';
                $this->data['pagina_retorno'] = base_url().'persona/index';

            	$this->data['result_dhc'] = $this->personaModel->consulta_det_hijos_conyuge_perCod($this->input->post('per_codigo'));
		        $this->data['result_drp'] = $this->personaModel->consulta_det_referencias_personales_perCod($this->input->post('per_codigo'));

		        $comboDepartamento = $this->ubigeoModel->consulta_ubigeo_departamento();
				$this->data['Combo_Departamento'] = set_dropdown($comboDepartamento, 'ubi_coddep', 'ubi_departamento', 1);

				$comboEstadocivil = $this->estado_civilModel->consulta_estado_civil_todos('A');
				$this->data['Combo_Estadocivil'] = set_dropdown($comboEstadocivil, 'esc_codigo', 'esc_nombre', 1);

				$comboCargo = $this->cargoModel->consulta_cargo_todos();
				$this->data['comboCargo'] = set_dropdown($comboCargo, 'car_codigo', 'cargo', 1);

				$comboBanco = $this->bancoModel->consulta_banco_todos();
				$this->data['comboBanco'] = set_dropdown($comboBanco, 'ban_codigo', 'ban_banco', 1);

				$comboNivelEstudios = $this->nivel_estudiosModel->consulta_nivel_estudios_todos();
				$this->data['comboNivelEstudios'] = set_dropdown($comboNivelEstudios, 'nie_codigo', 'nie_estudios', 1);		
				
				$comboSeguro = $this->seguroModel->consulta_seguro_todos();
				$this->data['comboSeguro'] = set_dropdown($comboSeguro, 'seg_codigo', 'seg_seguro', 1);

				$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');
		
                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
		        $this->data['informacion'] = $this->load->view('template/informacion', null, true);
		        $this->load->view('persona/editar', $this->data);
			}
			else
			{	
				$this->data['result_dhc'] = $this->personaModel->consulta_det_hijos_conyuge_perCod($this->input->post('per_codigo'));
			    $this->data['result_drp'] = $this->personaModel->consulta_det_referencias_personales_perCod($this->input->post('per_codigo'));

			    $comboDepartamento = $this->ubigeoModel->consulta_ubigeo_departamento();
				$this->data['Combo_Departamento'] = set_dropdown($comboDepartamento, 'ubi_coddep', 'ubi_departamento', 1);

				$comboEstadocivil = $this->estado_civilModel->consulta_estado_civil_todos('A');
				$this->data['Combo_Estadocivil'] = set_dropdown($comboEstadocivil, 'esc_codigo', 'esc_nombre', 1);

				$comboCargo = $this->cargoModel->consulta_cargo_todos();
				$this->data['comboCargo'] = set_dropdown($comboCargo, 'car_codigo', 'cargo', 1);

				$comboBanco = $this->bancoModel->consulta_banco_todos();
				$this->data['comboBanco'] = set_dropdown($comboBanco, 'ban_codigo', 'ban_banco', 1);

				$comboNivelEstudios = $this->nivel_estudiosModel->consulta_nivel_estudios_todos();
				$this->data['comboNivelEstudios'] = set_dropdown($comboNivelEstudios, 'nie_codigo', 'nie_estudios', 1);		
				
				$comboSeguro = $this->seguroModel->consulta_seguro_todos();
				$this->data['comboSeguro'] = set_dropdown($comboSeguro, 'seg_codigo', 'seg_seguro', 1);

				$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');

                $this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
				$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
                $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		        $this->data['nav'] = $this->load->view('template/nav', null, true);
		        $this->data['footer'] = $this->load->view('template/footer', null, true);
				$this->data['mensaje_error'] = 'Hubo problemas al grabar los datos de la persona';
				$this->load->view('persona/editar', $this->data);
			}
		}
    }

    public function detalle(){
	    $session_data = $this->session->userdata('logged_in');
		if(!$this->rol_funcionalidad_permisoModel->verifica_rol_funcionalidad_permiso_acceso($session_data['Session_RolUsuario'],$this->FUN_A_PERSONA,GL_PERMISO_ACCION)) acceso_denegado(current_url());
		
        $per_codigo =  $this->uri->segment(3);
		$result = $this->personaModel->consulta_persona_codPer($per_codigo);
        if(!$result){
            $this->session->set_userdata('Session_MensajeError', 'Error. La persona que está consultando no existe');
            redirect('error/index', 'refresh');
        }

        $aud_descripcion = 'Se ingresó al detalle del registro '.$per_codigo;
		$data = array('usu_codigo' => $session_data['Session_IdUsuario'], 'ent_codigo' => $this->ENT_PERSONA, 'acc_codigo' => ACC_DETALLE, 'aud_descripcion' => $aud_descripcion, 'aud_fecha_registro' => date('Y-m-d H:i:s'), 'aud_ip_acceso' => $session_data['Session_IPUsuario']);
		$this->hitaloagroModel->add('auditoria', $data);

		$this->data['result_dhc'] = $this->personaModel->consulta_det_hijos_conyuge_perCod($per_codigo);
		$this->data['result_drp'] = $this->personaModel->consulta_det_referencias_personales_perCod($per_codigo);
        $this->data['result'] = $result;	
       	$this->data['Listado_Entidad'] = $this->rolModel->consulta_rol_todos('A');

 		$this->dataH['Session_NombreUsuario'] = $session_data['Session_NombreUsuario'];
		$this->dataH['Session_NombreResponsabilidad'] = $session_data['Session_NombreResponsabilidad'];
        $this->data['header'] = $this->load->view('template/header', $this->dataH, true);
		$this->data['nav'] = $this->load->view('template/nav', null, true);
		$this->data['footer'] = $this->load->view('template/footer', null, true);
		$this->load->view('persona/detalle', $this->data);
    }
		
	//---------------------------------
	
	function grabafiltros_ajax(){
		$session_data = $this->session->userdata('logged_in');
		
		$this->session->set_userdata('Session_formPERPernombre', $this->input->post('per_nombre'));
	}
	
	function verificarBD($usu_login){
        if($this->session->userdata('Session_formUSULogin') == $usu_login) return TRUE;

        $result = $this->usuarioModel->verifica_usuario_login_existe($usu_login);
        if($result){
            $this->form_validation->set_message('verificarBD', 'El Login de Acceso ya existe');
            return FALSE;
        }else{            
			return TRUE;
        }
    }

    function lista_ajax(){
		$session_data = $this->session->userdata('logged_in');
		
		$per_nombres_completo = $this->input->post('per_nombres_completo');			
		$result = $this->personaModel->consulta_persona_nombres($tir_codigo, $per_nombres_completo);
        if($result){
        	foreach ($result as $row){
        		$per_nombres_completo = $row->per_nombres_completo;
        		$per_documento_identidad = $row->per_documento_identidad;
        		if($per_nombres_completo == "") {
        			$per_nombres_completo = $row->per_razon_social;
        			$per_documento_identidad = $row->per_nro_ruc;
        		}
				$new_row['label']=stripslashes($per_nombres_completo);
				$new_row['value']=stripslashes($row->per_codigo);
				$new_row['per_direccion_domicilio']=stripslashes($row->per_direccion_completo);
				$new_row['per_documento_identidad']=stripslashes($per_documento_identidad);
				$new_row['ubi_coddep_llegada']= substr($row->ubi_codigo, 0, 2);
				$new_row['ubi_codprov_llegada']=substr($row->ubi_codigo, 2, 2);
				$new_row['ubi_coddis_llegada']=substr($row->ubi_codigo, 4, 2);
				$new_row['gur_direccion_llegada']=$row->per_direccion_domicilio;				
				$row_set[] = $new_row; //build an array
			}
			echo json_encode($row_set); //format the array into json data
        }else{
        	echo json_encode("");
        }
	}

	function lista_productor_ajax(){
		$session_data = $this->session->userdata('logged_in');
		
		$per_nombres_completo = $this->input->post('per_nombres_completo');
		$zod_codigo = $this->input->post('zod_codigo');			
		$tir_codigo = $this->input->post('tir_codigo');

		if(($zod_codigo == '') || ($zod_codigo == '0')){
			$result = $this->personaModel->consulta_persona_nombres($tir_codigo,$per_nombres_completo);
		}else{
			$result = $this->personaModel->consulta_persona_nombres2($per_nombres_completo,$zod_codigo,$tir_codigo);
			$result2 = $this->compras_distribucion_stockModel->consulta_compras_distribucion_stock_reporte($zod_codigo);
		}		
        if($result){
        	foreach ($result as $row){
        		if($result2) {
        			foreach ($result2 as $stock) {
        				$new_row['malla'] = $stock->envase_2;
        				$new_row['rafia'] = $stock->envase_3;
        				$new_row['aguja'] = $stock->envase_4;
        				$new_row['saconegro'] = $stock->envase_5;
        				$new_row['sacorojo'] = $stock->envase_6;
        			}
        		} else {
        			$new_row['malla'] = '0 Un.';
        			$new_row['rafia'] = '0 Kg.';
        			$new_row['aguja'] = '0 Un.';
        			$new_row['saconegro'] = '0 Un.';
        			$new_row['sacorojo'] = '0 Un.';
        		}
        		$per_nombres_completo = $row->per_nombres_completo;
        		$per_documento_identidad = $row->per_documento_identidad;
        		if($per_nombres_completo == "") {
        			$per_nombres_completo = $row->per_razon_social;
        			$per_documento_identidad = $row->per_nro_ruc;
        		}
				$new_row['label']=stripslashes($per_nombres_completo);
				$new_row['value']=stripslashes($row->per_codigo);
				$new_row['per_direccion_domicilio']=stripslashes($row->per_direccion_completo);
				$new_row['per_documento_identidad']=stripslashes($per_documento_identidad);
				$row_set[] = $new_row; //build an array
			}
			echo json_encode($row_set); //format the array into json data
        }else{
        	echo json_encode("");
        }
	}

    function uploadfile_ajax(){
		$session_data = $this->session->userdata('logged_in');
        if(!$session_data) exit;

        $status = "";
        $value = "";
        $id = "";
        $file_element_name = $this->input->post('filename');

        $config['upload_path'] = FCPATH.'files/';
        $config['allowed_types'] = 'jpg|png';
        $config['max_size']  = 1024 * 2;
        $config['encrypt_name'] = FALSE;
 
        $this->load->library('upload', $config);
         
        if (!$this->upload->do_upload($file_element_name))
        {
            $status = 'error';
            $value = $this->upload->display_errors('', '');
        }
        else
        {
            $data = $this->upload->data();
            $status = "success";
            $value = $data['file_name'];
        }
        @unlink($_FILES[$file_element_name]);

        echo json_encode(array('status'=>$status, 'value'=>$value));
    }

    function deletefile_ajax(){
        $session_data = $this->session->userdata('logged_in');
        if(!$session_data) exit;

        $full_path = FCPATH.'files/'.$this->input->post('file_name');
        @unlink($full_path);
        $status = 'success';

        echo json_encode(array('status'=>$status));
    }
}
?>