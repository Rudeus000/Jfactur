<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regclientecobertura extends CI_Controller {
	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('clientecobertura_model');
		$this->load->model('modelgeneral');
		$this->load->helper('general');
        $this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
	$data['permisos'] =$this->permisos;
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/cobertura/panel',$data);    
    $this->load->view('layouts/footer');
	}

	public function jsonCobertura()
	{
	  $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
    $data['sEcho']  = $this->input->get_post('_', true);
    $columns= ['id_cobertura','nomb_cliente'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		
		$data['cliente'] = $this->input->get_post('cliente');

		$datos = $this->clientecobertura_model->getClientes($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	 }

	 public function getClientes()
	 {
	 		$q = $this->input->get_post('q');
	 		$query = $this->db->from('tb_cliente')
	 		->select('nomb_cliente as text, id_cliente as id')
	 		->like('nomb_cliente',$q)
	 		->get()->result();
	 		echo json_encode($query);
	 }

	 public function agregar()
	 {
	 		$data['id_cliente'] = $this->input->post('cliente');
	 		$data['inicio_cobertura'] = $this->input->post('inicio');
	 		$data['limite_cobertura'] = $this->input->post('limite');
	 		$data['monto_cobertura'] = $this->input->post('monto');
	 		if ($this->input->post('cobertura')=='on') {
	 			$data['amplicacion_cobertura'] = 1;
	 		}else{
	 			$data['amplicacion_cobertura'] = 0;
	 		}

	 		$insert = $this->modelgeneral->insertRegist('tb_cliente_cobertura',$data);

	 		$resp = [];
	 		if (!is_null($insert)) {
	 			$resp['success'] = true;
	 		}else{
	 			$resp['success'] = false;
	 		}

	 		echo json_encode($resp);
	 }

	function getCobertura()
	{
		$id = $this->input->get('id');
		$query = $this->db->from('tb_cliente_cobertura')
		->select('tb_cliente_cobertura.*,tb_cliente.nomb_cliente')
		->join('tb_cliente','tb_cliente_cobertura.id_cliente = tb_cliente.id_cliente')
		->where('id_cobertura',$id)
		->get()->row();
		echo json_encode($query);
	}

	public function editar()
	{
 		$data['inicio_cobertura'] = $this->input->post('inicio');
 		$data['limite_cobertura'] = $this->input->post('limite');
 		$data['monto_cobertura'] = $this->input->post('monto');
 		if ($this->input->post('cobertura')=='on') {
 			$data['amplicacion_cobertura'] = 1;
 		}else{
 			$data['amplicacion_cobertura'] = 0;
 		}
 		$where['id_cobertura'] = $this->input->post('id');

 		$edit = $this->modelgeneral->editRegist('tb_cliente_cobertura',$where,$data);

 		$resp = [];
 		if ($edit) {
 			$resp['success'] = true;
 		}else{
 			$resp['success'] = false;
 		}

 		echo json_encode($resp);
	}

	public function eliminar()
	{
		$where['id_cobertura'] = $this->input->get('id');
		$delete = $this->modelgeneral->deleteRegist('tb_cliente_cobertura',$where);
		$resp = [];
 		if ($delete) {
 			$resp['success'] = true;
 		}else{
 			$resp['success'] = false;
 		}

 		echo json_encode($resp);
	}

}

/* End of file Regclientecobertura.php */
/* Location: ./application/controllers/administrador/Regclientecobertura.php */