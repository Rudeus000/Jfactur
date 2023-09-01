<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Regempresa extends CI_Controller {

  private $permisos;
  public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Lima");
		if(!$this->session->userdata("login")){
				redirect(base_url());
		}
		//$this->permisos = $this->backend_lib->control();
		$this->load->model('modelgeneral');
		$this->load->model('confempresa_model');
		// Cargar el modelo
		$this->load->model('tokensunat_model');
	
		// Generar el token
		

		

	}

  public function index()
  {
	
		$data['permisos'] =$this->permisos;
		$this->load->helper('url');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$data['ubigeos'] = $this->ubigeo();
		$data['empresa'] = $this->modelgeneral->getTableWhereRow('tb_empresa',['cod_empresa'=>1]);
		$data['regimen'] = $this->modelgeneral->getTable('sunat_tiporegimen');
		$this->load->view('empresa/viewempresa',$data);
		$this->load->view('layouts/footer');
	}

	function token(){
		$token = $this->tokensunat_model->generateToken();
	if ($token) {
		echo 'Token generado: ' . $token;
	} else {
		echo 'Error al generar el token';
	}

	}
	
	function guardarDatos()
	{
		$MovAlmAutomatico='N';
		if(!empty($this->input->post('mov-almacen'))){
			$MovAlmAutomatico='S';	
		}
		//echo $MovAlmAutomatico;exit(0);
		$logo = $this->uploadLogo();
		
		if($logo['success']==true){
			$data['photo'] = $logo['name'];
		}
		$data['ruc_emp'] = $this->input->post('RUC');
		$data['razon_social'] = $this->input->post('razon_social');
		$data['nombre_comercial'] = $this->input->post('nombre_comercial');
		$data['telf_emp'] = $this->input->post('telefono');
		$data['email_emp'] = $this->input->post('email');
		$data['ubigeo_emp'] = $this->input->post('ubigeo');
		$data['urbanizacion_emp'] = $this->input->post('urbanizacion');
		$data['direcc_emp'] = $this->input->post('direccion');
		$data['regimen_emp'] = $this->input->post('regimen');
		$data['restriccion_stock_emp'] = $this->input->post('restriccion_stock');
		$data['MovAlmacenAutomatico'] = $MovAlmAutomatico;
		$data['multialmacen_stock_emp'] = $this->input->post('multialmacen');
		$data['restriccion_precio_minimo_emp'] = $this->input->post('restriccion_precio_minimo');
		$data['usuario_sol_emp'] = $this->input->post('usuario_sol');
		$data['contrasena_sol_emp'] = $this->input->post('contrasena_sol');
		$data['enviar_factura_emp'] = $this->input->post('enviar_factura_emp');
		// $data['user_sol'] = $this->input->post('user_sol');	
		// $data['pass_sol'] = $this->input->post('pass_sol');
		$data['client_id_emp'] = $this->input->post('cliente_id');
		$data['client_secret_emp'] = $this->input->post('cliente_secret');
		$certificado = $this->uploadCertificado();
		if($certificado['success']==true){
			$data['certificado_emp'] = $certificado['name'];
		}

		$data['contrasena_certificado_emp'] = $this->input->post('contrasena_certificado');
		$where['cod_empresa '] = 1;
		$edit = $this->modelgeneral->editRegist('tb_empresa',$where,$data);
		$resp =[];
		if(!is_null($edit)){
				$resp['success'] = true;
				$resp['empresa'] = $this->modelgeneral->getTableWhereRow('tb_empresa',['cod_empresa'=>1]);
		}else{
				$resp['success'] = false;
		}

		
		if (!file_exists(APP_PATH.'facturacion/archivos_xml_sunat/cpe_xml/produccion/'.$data['ruc_emp'])) {
			mkdir(APP_PATH.'facturacion/archivos_xml_sunat/cpe_xml/produccion/'.$data['ruc_emp'], 0777, true);
		}
		if (!file_exists(APP_PATH.'facturacion/archivos_xml_sunat/cpe_xml/beta/'.$data['ruc_emp'])) {
			mkdir(APP_PATH.'facturacion/archivos_xml_sunat/cpe_xml/beta/'.$data['ruc_emp'], 0777, true);
		}
		echo json_encode($resp);
	}

	function uploadCertificado()
	{
		$config['upload_path'] = APP_PATH.'facturacion/archivos_xml_sunat/certificados/produccion';
		$config['allowed_types'] = '*';
		$config['max_size'] = '40000';
		$config['max_width'] = '40000';
		$config['max_height'] = '40000';
		$this->upload->initialize($config);
		$resp = [];
		if ($this->upload->do_upload('certificado')){
			$upload = $this->upload->data();
			$resp['success'] = true;
			$resp['name'] = $upload['file_name'];
		}else{
			$resp['ruta'] = $config['upload_path'];
			$resp['success'] = false;
			$resp['error'] = $this->upload->display_errors();
		}
		return $resp;
	}

	function uploadLogo()
	{
		$config['upload_path'] = APP_PATH.'assets/uploads/logo';
		$config['allowed_types'] = 'png|jpg|jpeg';
		$config['max_size'] = '40000';
		$config['max_width'] = '40000';
		$config['max_height'] = '40000';
		
		$this->upload->initialize($config);
		$resp = [];
		if ($this->upload->do_upload('logo')){
			$upload = $this->upload->data();
			$resp['success'] = true;
			$resp['name'] = $upload['file_name'];
		}else{
			$resp['success'] = false;
			$resp['error'] = $this->upload->display_errors();
		}
		return $resp;
	}
		
	public function ubigeo()
	{
		return $this->db->from('ubigeo_distritos')
		->select('ubigeo_distritos.nombre as distrito,ubigeo_provincias.nombre as provincia, ubigeo_departamentos.nombre as departamento, ubigeo_distritos.id as ubigeo')
		->join('ubigeo_provincias','ubigeo_provincias.id = ubigeo_distritos.provincia_id')
		->join('ubigeo_departamentos','ubigeo_departamentos.id = ubigeo_distritos.departamento_id')
		->get()->result();
	}

	public function movilExpert()
	{
		$estado = $this->input->post('estado');
		$this->modelgeneral->editRegist('tb_empresa',
			['cod_empresa' => 1],
			['movilexpert_emp' => $estado]
		);
		$this->session->set_userdata('movil_expert',$estado);
	}

	function anuncio(){		
		// $data['cumpleano_clin'] = $this->input->post('cumpleano_clin');
		$data['anuncio'] = $this->input->post('anuncio');		
		$where['cod_empresa '] = 1;
		$edit = $this->modelgeneral->editRegist('tb_empresa',$where,$data);
		$resp =[];
		if(!is_null($edit)){
				$resp['success'] = true;
				$resp['empresa'] = $this->modelgeneral->getTableWhereRow('tb_empresa',['cod_empresa'=>1]);
		}else{
				$resp['success'] = false;
		}

		
		
		echo json_encode($resp);
	}

	function cumpleano(){		
		$data['cumpleano_clin'] = $this->input->post('cumpleano_clin');
		// $data['anuncio'] = $this->input->post('anuncio');		
		$where['cod_empresa '] = 1;
		$edit = $this->modelgeneral->editRegist('tb_empresa',$where,$data);
		$resp =[];
		if(!is_null($edit)){
				$resp['success'] = true;
				$resp['empresa'] = $this->modelgeneral->getTableWhereRow('tb_empresa',['cod_empresa'=>1]);
		}else{
				$resp['success'] = false;
		}

		
		
		echo json_encode($resp);
	}

	function apisunat(){		
		// $data['cumpleano_clin'] = $this->input->post('cumpleano_clin');
		// $data['user_sol'] = $this->input->post('user_sol');
		// $data['pass_sol'] = $this->input->post('pass_sol');
		$data['client_id_emp'] = $this->input->post('cliente_id');
		$data['client_secret_emp'] = $this->input->post('cliente_secret');		
		$where['cod_empresa '] = 1;
		$edit = $this->modelgeneral->editRegist('tb_empresa',$where,$data);
		$resp =[];
		if(!is_null($edit)){
				$resp['success'] = true;
				$resp['empresa'] = $this->modelgeneral->getTableWhereRow('tb_empresa',['cod_empresa'=>1]);
		}else{
				$resp['success'] = false;
		}

		
		
		echo json_encode($resp);
	}
	
	
			
	
			
	

}
