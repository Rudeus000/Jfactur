<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RegNuevo extends CI_Controller {

	public function index()
	{
		$data['datos'] = $this->db->from('tb_nuevo')
										->order_by('id','desc')
										->get()->result();
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/wysiwyg/panel',$data);    
		$this->load->view('layouts/footer');
	}

	public function agregar()
	{
		$data['titulo'] = $this->input->post('titulo');
		$data['contenido'] = $this->input->post('contenido');
		$data['fecha'] = date('Y-m-d H:i:s');
		$insert = $this->modelgeneral->insertRegist('tb_nuevo',$data);
		$resp =[];
		if(!is_null($insert)){
			$resp['success'] = true;
			$resp['redirect'] = 'administrador/regnuevo';
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	}

	public function getNuevo()
	{
		$query = $this->modelgeneral->getTableWhereRow('tb_nuevo',['id'=>$this->input->get('id')]);
		echo json_encode($query);
	}

	public function editar()
	{
		$data['titulo'] = $this->input->post('titulo');
		$data['contenido'] = $this->input->post('contenido');
		$where['id'] = $this->input->post('id');
		$edit = $this->modelgeneral->editRegist('tb_nuevo',$where,$data);
		$resp =[];
		if(!is_null($edit)){
				$resp['success'] = true;
				$resp['redirect'] = 'administrador/regnuevo';
		}else{
				$resp['success'] = false;
		}
		echo json_encode($resp);
	}

	public function eliminar()
	{
		$where['id'] = $this->input->get('id');  
		$eliminar = $this->modelgeneral->deleteRegist('tb_nuevo',$where);
		$resp = [];
		if ($eliminar) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	}

}

/* End of file RegNuevo.php */
