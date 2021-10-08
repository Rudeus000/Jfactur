<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regasignpuntoventa extends CI_Controller {

	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('asignpuntoventa_model');
		$this->load->model('modelgeneral');
		$this->load->helper('general');
        $this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['permisos'] =$this->permisos;
		$data['grupos'] = $this->modelgeneral->getTable('tb_grupo');
		$this->load->view('layouts/header');
   	$this->load->view('layouts/aside');
   	$this->load->view('admin/asignar_puntoventa/panel',$data);    
   	$this->load->view('layouts/footer');
	}

	public function jsonAsignarPuntoVenta()
	{
	  $data['start'] = $this->input->get_post('start', true);
	  $data['length'] = $this->input->get_post('length', true);
	  $data['sEcho']  = $this->input->get_post('_', true);
	  $columns= array('tb_usuario.cod_usu','nomb_usu','apell_usu','nombre_grupo','nomb_puntoventa');
	  $orderCampo = $this->input->get_post('order', true);
	  $orderCampo = $orderCampo[0]['column'];
	  $orderCampo = $columns[$orderCampo];
	  $orderDireccion = $this->input->get_post('order', true);
	  $orderDireccion = $orderDireccion[0]['dir'];
	  $data['orderCampo'] = $orderCampo;
	  $data['orderDireccion'] = $orderDireccion;
	  $data['grupo'] = $this->input->get_post('grupo');
	  $datos = $this->asignpuntoventa_model->getUsuarios($data);
	  header('content-type: application/json; charset=utf-8');
	  echo json_encode($datos);
	}

	public function getPuntos()
	{
		$usuario = $this->input->get('id');
		$query = $this->db->from('tb_usuario_puntoventa')
		->where('tb_usuario_puntoventa.cod_usu',$usuario)
		->join('tb_puntoventa','tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa.cod_puntoventa')
		->get()->result();
		echo json_encode($query);
	}

	public function cambiarPuntoVentaDefecto()
	{
		$punto = $this->input->get('punto');
		$usuario = $this->input->get('usuario');
		$this->modelgeneral->editRegist('tb_usuario_puntoventa',['cod_usu'=>$usuario],['pordefecto'=>0]);
		$edit = $this->modelgeneral->editRegist('tb_usuario_puntoventa',['cod_usu'=>$usuario,'cod_puntoventa'=>$punto],['pordefecto'=>1]);

		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	public function quitarPuntoVenta()
	{
		$where['cod_puntoventa'] = $this->input->get('punto');
		$where['cod_usu'] = $this->input->get('usuario');
		$delete = $this->modelgeneral->deleteRegist('tb_usuario_puntoventa',$where);
		
		$resp = [];
		if ($delete) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	function getPuntosVentasParaAgregar()
	{
		$usuario = $this->input->get('usuario');
		$query = $this->db->from('tb_usuario_puntoventa')
		->join('tb_puntoventa','tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa.cod_puntoventa AND tb_usuario_puntoventa.cod_usu = '.$usuario,'right')
		->where('tb_usuario_puntoventa.cod_puntoventa IS NULL',NULL)
		->get()->result();
		echo json_encode($query);
	}

	function agregarPuntoVenta()
	{
		$data['cod_puntoventa'] = $this->input->post('punto');
		$data['cod_usu'] = $this->input->post('usuario');

		$query = $this->modelgeneral->getTableWhere('tb_usuario_puntoventa',['cod_usu'=>$this->input->post('usuario')]);
		if (empty($query)) {
			$data['pordefecto'] = 1;
		}
		$insert = $this->modelgeneral->insertRegist('tb_usuario_puntoventa',$data);

		$resp = [];
		if (!is_null($insert)) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	}

}

/* End of file Regasignpuntoventa.php */
/* Location: ./application/controllers/administrador/Regasignpuntoventa.php */