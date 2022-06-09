<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */

/**
 * 
 */
class Permisos extends CI_Controller
{
	private $permisos;
	public function __construct()
	{
		parent::__construct();
		
		if(!$this->session->userdata("login")){
			redirect(base_url());
		}
		
		$this->load->model('permisos_model');
		$this->load->model('modelgeneral');
		 $this->load->helper('general');
		// $this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['permisos'] =$this->permisos;
		$data = array(
			
			'perfil' =>$this->modelgeneral->getTableWhere('tb_perfil',['estado_perfil' =>'1']),
			'menus' =>$this->modelgeneral->getTable('menus')
		);

		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/permisos/listar',$data);
		$this->load->view('layouts/footer');
	}


	public function jsonPermisos()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho']  = $this->input->get_post('_', true);

		$columns = array('id_permiso','NombreMenu','NombrePerfil');
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$menus = $this->input->get_post('menus');
		$tb_perfil = $this->input->get_post('tb_perfil');

		if ($menus!='') {
			$data['menus'] = $menus;
		}

		if ($tb_perfil!='') {
			$data['tb_perfil'] = $tb_perfil;
		}
			
		$datos = $this->permisos_model->getPerm($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}


	public function add(){

		$data = array(
			'perfil' =>  $this->modelgeneral->getTable('tb_perfil'),
			'menus' => $this->permisos_model->getMenus(),

		);

		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/permisos/add',$data);
		$this->load->view('layouts/footer');
	}


	public function store(){
		$menu =$this->input->post('menu');
		$perfil = $this->input->post('perfil');
		$read= $this->input->post('read');
		$insert = $this->input->post('insert');
		$update = $this->input->post('update');
		$delete = $this->input->post('delete');

		$data = array(

		'id_menu' => $menu,
		'cod_perfil' => $perfil,
		'read' => $read,
		'insert' =>$insert,
		'update' =>$update,
		'delete' =>$delete,	
		);

		if($this->permisos_model->save($data)){
			redirect(base_url().'administrador/permisos');
		}else{
			$this->session->set_flashdata('error','no se pudo guardar la informacion');
			redirect(base_url().'administrador/permisos/add');
		}
	}

	public function edit($id){
		$data = array(
			'perfil' => $this->modelgeneral->getTable('tb_perfil'),
			'menus' => $this->permisos_model->getMenus(),
			'permiso' => $this->permisos_model->getPermiso($id),
		);

		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/permisos/edit',$data);
		$this->load->view('layouts/footer');


	}

	public function update(){
		$idpermiso = $this->input->post('idpermiso');
		$menu =$this->input->post('menu');
		$perfil = $this->input->post('perfil');
		$read= $this->input->post('read');
		$insert = $this->input->post('insert');
		$update = $this->input->post('update');
		$delete = $this->input->post('delete');

			$data = array(
		'read' => $read,
		'insert' =>$insert,
		'update' =>$update,
		'delete' =>$delete,	
		);

			if($this->permisos_model->update($idpermiso,$data)){
			redirect(base_url().'administrador/permisos');
		}else{
			$this->session->set_flashdata('error','no se pudo guardar la informacion');
			redirect(base_url().'administrador/permisos/edit');
		}

	}

	public function quitarpermiso()
	{
		$where['id_permiso'] = $this->input->get('id');
		$delete = $this->modelgeneral->deleteRegist('permisos',$where);
		
		$resp = [];
		if ($delete) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}
}