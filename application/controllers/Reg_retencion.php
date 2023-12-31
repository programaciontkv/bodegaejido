<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_retencion extends CI_Controller {

	private $permisos;

	function __construct(){
		parent:: __construct();
		if(!$this->session->userdata('s_login')){
			redirect(base_url());
		}
		$this->load->library('backend_lib');
		$this->load->model('backend_model');
		$this->permisos=$this->backend_lib->control();
		$this->load->library('form_validation');
		$this->load->model('emisor_model');
		$this->load->model('factura_model');
		$this->load->model('reg_retencion_model');
		$this->load->model('cliente_model');
		$this->load->model('vendedor_model');
		$this->load->model('impuesto_model');
		$this->load->model('auditoria_model');
		$this->load->model('menu_model');
		$this->load->model('estado_model');
		$this->load->model('configuracion_model');
		$this->load->model('caja_model');
		$this->load->model('opcion_model');
		$this->load->model('cheque_model');
		$this->load->model('ctasxcobrar_model');			
		$this->load->library('html2pdf');
		$this->load->library('export_excel');
	}

	public function _remap($method, $params = array()){
    
	    if(!method_exists($this, $method))
	      {
	       $this->index($method, $params);
	    }else{
	      return call_user_func_array(array($this, $method), $params);
	    }
  	}


	public function menus()
	{
		$menu=array(
					'menus' =>  $this->menu_model->lista_opciones_principal('1',$this->session->userdata('s_idusuario')),
					'sbmopciones' =>  $this->menu_model->lista_opciones_submenu('1',$this->session->userdata('s_idusuario'),$this->permisos->sbm_id),
					'actual'=>$this->permisos->men_id,
					'actual_sbm'=>$this->permisos->sbm_id,
					'actual_opc'=>$this->permisos->opc_id
				);
		return $menu;
	}
	

	public function index($opc_id){

		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

		///buscador 
		if($_POST){
			$text= $this->input->post('txt');
			$ids= $this->input->post('tipo');
			$f1= $this->input->post('fec1');
			$f2= $this->input->post('fec2');	
			$cns_retenciones=$this->reg_retencion_model->lista_retencion_buscador($text,$f1,$f2,$rst_cja->emp_id);
		}else{
			$text= '';
			$f1= date('Y-m-d');
			$f2= date('Y-m-d');
			$cns_retenciones=$this->reg_retencion_model->lista_retencion_buscador($text,$f1,$f2,$rst_cja->emp_id);
		}
		$data=array(
					'permisos'=>$this->permisos,
					'rentenciones'=>$cns_retenciones,
					'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
					'opc_id'=>$rst_opc->opc_id,
					'buscar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'txt'=>$text,
					'fec1'=>$f1,
					'fec2'=>$f2,
				);
		$this->load->view('layout/header',$this->menus());
		$this->load->view('layout/menu',$this->menus());
		$this->load->view('reg_retencion/lista',$data);
		$modulo=array('modulo'=>'reg_retencion');
		$this->load->view('layout/footer',$modulo);
	}


	public function nuevo($opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_insertar){
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_impuestos'=>$this->impuesto_model->lista_impuestos_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=> (object) array(
											'rgr_fec_registro'=>date('Y-m-d'),
											'rgr_fecha_emision'=>date('Y-m-d'),
											'rgr_fec_autorizacion'=>date('Y-m-d'),
											'rgr_fec_caducidad'=>date('Y-m-d'),
											'rgr_numero'=>'',
											'rgr_autorizacion'=>'',
											'rgr_num_comp_retiene'=>'',
											'fac_id'=>'0',
					                        'cli_id'=>'',
					                        'rgr_identificacion'=>'',
					                        'rgr_nombre'=>'',
					                        'rgr_direccion'=>'',
					                        'rgr_telefono'=>'',
					                        'rgr_email'=>'',
					                        'rgr_total_valor'=>'0',
					                        'emp_id'=>$rst_cja->emp_id,
					                        'rgr_id'=>'',
					                        'fac_subtotal'=>'0',
					                        'fac_total_iva'=>'0',
										),
						'cns_det'=>'',
						'action'=>base_url().'reg_retencion/guardar/'.$opc_id
						);
			$this->load->view('reg_retencion/form',$data);
			$modulo=array('modulo'=>'reg_retencion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}
	}

	public function guardar($opc_id){
		
		$fac_id= $this->input->post('fac_id');
		$rgr_numero= $this->input->post('rgr_numero');
		$rgr_autorizacion= $this->input->post('rgr_autorizacion');
		$rgr_num_comp_retiene= $this->input->post('rgr_num_comp_retiene');
		$rgr_fec_registro= $this->input->post('rgr_fec_registro');
		$rgr_fecha_emision= $this->input->post('rgr_fecha_emision');
		$rgr_fec_autorizacion= $this->input->post('rgr_fec_autorizacion');
		$rgr_fec_caducidad= $this->input->post('rgr_fec_caducidad');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('rgr_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('rgr_num_comp_retiene','Factura No','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');
		if($this->form_validation->run()){
		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'fac_id'=>$fac_id,
							'rgr_denominacion_comp'=>'1',
							'rgr_fecha_emision'=>$rgr_fecha_emision,
							'rgr_fec_registro'=>$rgr_fec_registro,
							'rgr_fec_autorizacion'=>$rgr_fec_autorizacion,
							'rgr_fec_caducidad'=>$rgr_fec_caducidad,
							'rgr_numero'=>$rgr_numero, 
							'rgr_autorizacion'=>$rgr_autorizacion, 
							'rgr_nombre'=>$nombre, 
							'rgr_identificacion'=>$identificacion, 
							'rgr_num_comp_retiene'=>$rgr_num_comp_retiene, 
							'rgr_total_valor'=>$total_valor,
							'rgr_estado'=>'4'
		    );


		    $rgr_id=$this->reg_retencion_model->insert($data);
		    if(!empty($rgr_id)){
		    	$n=0;
		    	while($n<$count_det){
		    		$n++;
		    		if($this->input->post("drr_base_imponible$n")!=null){
		    			$por_id = $this->input->post("por_id$n");
		    			$drr_ejercicio_fiscal = $this->input->post("drr_ejercicio_fiscal$n");
		    			$drr_base_imponible = $this->input->post("drr_base_imponible$n");
		    			$drr_codigo_impuesto = $this->input->post("drr_codigo_impuesto$n");
		    			$drr_procentaje_retencion = $this->input->post("drr_procentaje_retencion$n");
		    			$drr_valor = $this->input->post("drr_valor$n");
		    			$drr_tipo_impuesto = $this->input->post("drr_tipo_impuesto$n");
		    			$dt_det=array(
		    							'rgr_id'=>$rgr_id,
	                                    'por_id'=>$por_id,
	                                    'drr_ejercicio_fiscal'=>$drr_ejercicio_fiscal,
	                                    'drr_base_imponible'=>$drr_base_imponible,
	                                    'drr_codigo_impuesto'=>$drr_codigo_impuesto,
	                                    'drr_procentaje_retencion'=>$drr_procentaje_retencion,
	                                    'drr_valor'=>$drr_valor,
	                                    'drr_tipo_impuesto'=>$drr_tipo_impuesto,
		    						);
		    			$this->reg_retencion_model->insert_detalle($dt_det);
		    		}

		    				$data_chq=array(	
				    				'emp_id'=>$emp_id,
				    				'cli_id'=>$cli_id,
				    				'chq_recepcion'=>date('Y-m-d'),
									'chq_fecha'=>$rgr_fecha_emision,
									'chq_tipo_doc'=>'7', 
									'chq_nombre'=>'RETENCION', 
									'chq_concepto'=>'RETENCION',
									'chq_banco'=>'',
									'chq_numero'=>$rgr_numero,
									'chq_monto'=>$total_valor,
									'chq_estado'=>'9',
									'chq_estado_cheque'=>'11',
									'doc_id'=>$rgr_id
		    				);
		    				$chq_id=$this->cheque_model->insert($data_chq);

		    				///ctasxcobrar
		    				$ctaxcob=array(	
			    				'com_id'=>$fac_id,
			    				'cta_fecha_pago'=>$rgr_fecha_emision,
								'cta_forma_pago'=>'7',
								'num_documento'=>$rgr_numero, 
								'cta_concepto'=>'RETENCION', 
								'cta_monto'=>$total_valor,
								'cta_fecha'=>date('Y-m-d'),
								'chq_id'=>$chq_id,
								'cta_estado'=>'1'
						    );
							
						    $cta_id=$this->ctasxcobrar_model->insert($ctaxcob);	
		    	
		    	}

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO RETENCION',
								'adt_accion'=>'INSERTAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$pro_codigo,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'reg_retencion/show_frame/'. $rgr_id.'/'.$opc_id);
			
			}else{
				$this->session->set_flashdata('error','No se pudo guardar');
				redirect(base_url().'reg_retencion/nuevo/'.$opc_id);
			}
		}else{
			$this->nuevo($opc_id);
		}	

	}

	public function editar($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
		if($permisos->rop_actualizar){
			$data=array(
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'estados'=>$this->estado_model->lista_estados_modulo($this->permisos->opc_id),
						'cns_impuestos'=>$this->impuesto_model->lista_impuestos_estado('1'),
						'cns_clientes'=>$this->cliente_model->lista_clientes_estado('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=>$this->reg_retencion_model->lista_una_retencion($id),
						'cns_det'=>$this->reg_retencion_model->lista_detalle_retencion($id),
						'action'=>base_url().'reg_retencion/actualizar/'.$opc_id
						);
			
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('reg_retencion/form',$data);
			$modulo=array('modulo'=>'reg_retencion');
			$this->load->view('layout/footer',$modulo);
		}else{
			redirect(base_url().'inicio');
		}	
	}

	public function actualizar($opc_id){
			
		$id = $this->input->post('rgr_id');
		$fac_id= $this->input->post('fac_id');
		$rgr_numero= $this->input->post('rgr_numero');
		$rgr_autorizacion= $this->input->post('rgr_autorizacion');
		$rgr_num_comp_retiene= $this->input->post('rgr_num_comp_retiene');
		$rgr_fec_registro= $this->input->post('rgr_fec_registro');
		$rgr_fecha_emision= $this->input->post('rgr_fecha_emision');
		$rgr_fec_autorizacion= $this->input->post('rgr_fec_autorizacion');
		$rgr_fec_caducidad= $this->input->post('rgr_fec_caducidad');
		$identificacion = $this->input->post('identificacion');
		$nombre = $this->input->post('nombre');
		$cli_id = $this->input->post('cli_id');
		$total_valor = $this->input->post('total_valor');
		$emp_id = $this->input->post('emp_id');
		$count_det=$this->input->post('count_detalle');
		
		$this->form_validation->set_rules('rgr_fecha_emision','Fecha de Emision','required');
		$this->form_validation->set_rules('rgr_num_comp_retiene','Factura No','required');
		$this->form_validation->set_rules('identificacion','Identificacion','required');
		$this->form_validation->set_rules('nombre','Nombre','required');
		$this->form_validation->set_rules('total_valor','Total Valor','required');

		if($this->form_validation->run()){
		    $data=array(	
		    				'emp_id'=>$emp_id,
							'cli_id'=>$cli_id, 
							'fac_id'=>$fac_id,
							'rgr_denominacion_comp'=>'1',
							'rgr_fecha_emision'=>$rgr_fecha_emision,
							'rgr_fec_registro'=>$rgr_fec_registro,
							'rgr_fec_autorizacion'=>$rgr_fec_autorizacion,
							'rgr_fec_caducidad'=>$rgr_fec_caducidad,
							'rgr_numero'=>$rgr_numero, 
							'rgr_autorizacion'=>$rgr_autorizacion, 
							'rgr_nombre'=>$nombre, 
							'rgr_identificacion'=>$identificacion, 
							'rgr_num_comp_retiene'=>$rgr_num_comp_retiene, 
							'rgr_total_valor'=>$total_valor,
							'rgr_estado'=>'4'
		    );

			if($this->reg_retencion_model->update($id,$data)){
				if($this->reg_retencion_model->delete_detalle($id)){
			    	$n=0;
			    	while($n<$count_det){
			    		$n++;
			    		if($this->input->post("drr_base_imponible$n")!=null){
			    			$por_id = $this->input->post("por_id$n");
			    			$drr_ejercicio_fiscal = $this->input->post("drr_ejercicio_fiscal$n");
			    			$drr_base_imponible = $this->input->post("drr_base_imponible$n");
			    			$drr_codigo_impuesto = $this->input->post("drr_codigo_impuesto$n");
			    			$drr_procentaje_retencion = $this->input->post("drr_procentaje_retencion$n");
			    			$drr_valor = $this->input->post("drr_valor$n");
			    			$drr_tipo_impuesto = $this->input->post("drr_tipo_impuesto$n");
			    			$dt_det=array(
			    							'rgr_id'=>$id,
		                                    'por_id'=>$por_id,
		                                    'drr_ejercicio_fiscal'=>$drr_ejercicio_fiscal,
		                                    'drr_base_imponible'=>$drr_base_imponible,
		                                    'drr_codigo_impuesto'=>$drr_codigo_impuesto,
		                                    'drr_procentaje_retencion'=>$drr_procentaje_retencion,
		                                    'drr_valor'=>$drr_valor,
		                                    'drr_tipo_impuesto'=>$drr_tipo_impuesto,
			    						);
		    				$this->reg_retencion_model->insert_detalle($dt_det);
		    			}
			    	
			    	}
		    	}			
		    				//anular ctasxcobrar
		    				$cheque=$this->cheque_model->lista_cheque_retencion($id);
		    				$dt_cta=array('cta_estado'=>'3');
		    				$this->ctasxcobrar_model->update_ctascob_cheque($cheque->chq_id,$dt_cta);

		    				//anular cheque
		    				$dt_chq=array('chq_estado'=>'3',
										  'chq_estado_cheque'=>'3',);
		    				$this->cheque_model->update_chq_retencion($id,$dt_chq);

		    				$data_chq=array(	
				    				'emp_id'=>$emp_id,
				    				'cli_id'=>$cli_id,
				    				'chq_recepcion'=>date('Y-m-d'),
									'chq_fecha'=>$rgr_fecha_emision,
									'chq_tipo_doc'=>'7', 
									'chq_nombre'=>'RETENCION', 
									'chq_concepto'=>'RETENCION',
									'chq_banco'=>'',
									'chq_numero'=>$rgr_numero,
									'chq_monto'=>$total_valor,
									'chq_estado'=>'9',
									'chq_estado_cheque'=>'11',
									'doc_id'=>$id
		    				);
		    				$chq_id=$this->cheque_model->insert($data_chq);

		    				///ctasxcobrar

		    				$ctaxcob=array(	
			    				'com_id'=>$fac_id,
			    				'cta_fecha_pago'=>$rgr_fecha_emision,
								'cta_forma_pago'=>'7',
								'num_documento'=>$rgr_numero, 
								'cta_concepto'=>'RETENCION', 
								'cta_monto'=>$total_valor,
								'cta_fecha'=>date('Y-m-d'),
								'chq_id'=>$chq_id,
								'cta_estado'=>'1'
						    );
							
						    $cta_id=$this->ctasxcobrar_model->insert($ctaxcob);	

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO RETENCION',
								'adt_accion'=>'MODIFICAR',
								'adt_campo'=>json_encode($this->input->post()),
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$rst_fac->fac_numero,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
				redirect(base_url().strtolower($rst_opc->opc_direccion).$opc_id);
				// redirect(base_url().'reg_retencion/show_frame/'. $id.'/'.$opc_id);
			}else{
				$this->session->set_flashdata('error','No se pudo editar');
				redirect(base_url().'reg_retencion/editar'.$id.'/'.$opc_id);
			}
		}else{
			$this->editar($id,$opc_id);
		}	
	}

	public function visualizar($id){
		if($this->permisos->rop_reporte){
			$data=array(
						'producto'=>$this->factura_model->lista_un_producto($id)
						);
			$this->load->view('factura/visualizar',$data);
		}else{
			redirect(base_url().'inicio');
		}	
	}


	public function anular($id,$num,$opc_id){
		if($this->permisos->rop_eliminar){
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		    $up_dtf=array('rgr_estado'=>3);
			if($this->reg_retencion_model->update($id,$up_dtf)){
				//anular ctasxcobrar
		    	$cheque=$this->cheque_model->lista_cheque_retencion($id);
		    	$dt_cta=array('cta_estado'=>'3');
		    	$this->ctasxcobrar_model->update_ctascob_cheque($cheque->chq_id,$dt_cta);

		    	//anular cheque
		    	$dt_chq=array('chq_estado'=>'3',
							  'chq_estado_cheque'=>'3',);
		    	$this->cheque_model->update_chq_retencion($id,$dt_chq);

				$data_aud=array(
								'usu_id'=>$this->session->userdata('s_idusuario'),
								'adt_date'=>date('Y-m-d'),
								'adt_hour'=>date('H:i'),
								'adt_modulo'=>'REGISTRO RETENCION',
								'adt_accion'=>'ANULAR',
								'adt_ip'=>$_SERVER['REMOTE_ADDR'],
								'adt_documento'=>$num,
								'usu_login'=>$this->session->userdata('s_usuario'),
								);
				$this->auditoria_model->insert($data_aud);
				$data=array(
								'estado'=>0,
								'url'=>strtolower($rst_opc->opc_direccion).$opc_id,
							);
				echo json_encode($data);
			}

		}else{
			redirect(base_url().'inicio');
		}	
	}

    public function traer_cliente($id){
		$rst=$this->cliente_model->lista_un_cliente($id);
		if(!empty($rst)){
			$data=array(
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_parroquia'=>$rst->cli_parroquia,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_canton'=>$rst->cli_canton,
						'cli_email'=>$rst->cli_email,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_pais'=>$rst->cli_pais,
						);
			echo json_encode($data);
		}else{
			echo "";
		}
	}

	public function load_impuesto($id){

		$rst=$this->impuesto_model->lista_un_impuesto_cod($id);
		if(empty($rst)){
			$rst=$this->impuesto_model->lista_un_impuesto($id);
		}
		if(!empty($rst)){
			$data=array(
						'por_id'=>$rst->por_id,
						'por_descripcion'=>$rst->por_descripcion,
						'por_codigo'=>$rst->por_codigo,
						'por_porcentage'=>$rst->por_porcentage,
						'por_siglas'=>$rst->por_siglas,
						);
			echo json_encode($data);
		}else{
			echo "";
		}

	}

	
	
	public function show_frame($id,$opc_id){
		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
		$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);
    	if($permisos->rop_reporte){
    		$data=array(
					'titulo'=>'Registro Retencion '.ucfirst(strtolower($rst_cja->emp_nombre)),
					'regresar'=>strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
					'direccion'=>"reg_retencion/show_pdf/$id/$opc_id",
				);
			$this->load->view('layout/header',$this->menus());
			$this->load->view('layout/menu',$this->menus());
			$this->load->view('pdf/frame',$data);
			$modulo=array('modulo'=>'reg_retencion');
			$this->load->view('layout/footer',$modulo);
		}
    	
    }

    
    public function show_pdf($id,$opc_id){
    		$permisos=$this->backend_model->get_permisos($opc_id,$this->session->userdata('s_rol'));
			$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
			$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

			$data=array(
						'ambiente'=>$this->configuracion_model->lista_una_configuracion('5'),
						'dec'=>$this->configuracion_model->lista_una_configuracion('2'),
						'dcc'=>$this->configuracion_model->lista_una_configuracion('1'),
						'titulo'=>ucfirst(strtolower($rst_cja->emp_nombre)),
						'cancelar'=>base_url().strtolower($rst_opc->opc_direccion).$rst_opc->opc_id,
						'retencion'=>$this->reg_retencion_model->lista_una_retencion($id),
						'cns_det'=>$this->reg_retencion_model->lista_detalle_retencion($id),
						);
			// $this->load->view('pdf/pdf_reg_retencion', $data);
			$this->html2pdf->filename('reg_retencion.pdf');
			$this->html2pdf->paper('a4', 'portrait');
    		$this->html2pdf->html(utf8_decode($this->load->view('pdf/pdf_reg_retencion', $data, true)));
    		$this->html2pdf->output(array("Attachment" => 0));
		
    }


	public function traer_facturas($num,$emp){
		$rst=$this->factura_model->lista_factura_num_empresa($num,$emp);
		echo json_encode($rst);
	}

	public function load_factura($id,$dec,$dcc){
		$rst=$this->factura_model->lista_una_factura($id);

			$data= array(
						'fac_id'=>$rst->fac_id,
						'cli_id'=>$rst->cli_id,
						'cli_raz_social'=>$rst->cli_raz_social,
						'cli_ced_ruc'=>$rst->cli_ced_ruc,
						'cli_calle_prin'=>$rst->cli_calle_prin,
						'cli_telefono'=>$rst->cli_telefono,
						'cli_email'=>$rst->cli_email,
						'fac_fecha_emision'=>$rst->fac_fecha_emision,
						'fac_numero'=>$rst->fac_numero,
						'fac_subtotal'=>$rst->fac_subtotal,
						'fac_total_iva'=>$rst->fac_total_iva,
						);	

		echo json_encode($data);
	} 

	public function doc_duplicado($id,$num){
		$rst=$this->reg_retencion_model->lista_doc_duplicado($id,$num);
		if(!empty($rst)){
			echo $rst->rgr_id;
		}else{
			echo "";
		}
	}

	public function excel($opc_id,$fec1,$fec2){
    	$rst_opc=$this->opcion_model->lista_una_opcion($opc_id);
		$rst_cja=$this->caja_model->lista_una_caja($rst_opc->opc_caja);

    	$titulo='Registro Retencion '.ucfirst(strtolower($rst_cja->emi_nombre)).' '.ucfirst(strtolower($rst_cja->cja_nombre));
    	$file="reg_retenciones".date('Ymd');
    	$data=$_POST['datatodisplay'];
    	$this->export_excel->to_excel($data,$file,$titulo,$fec1,$fec2);
    }
	
}
