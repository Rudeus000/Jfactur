<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mensaje extends CI_Controller {

	public function index()
	{
		$data['mensaje'] = $this->modelgeneral->getTableWhereRow('mensaje',['id'=>1]);
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/mensaje/formulario',$data);
		$this->load->view('layouts/footer');
	}

	public function guardar()
	{
		$this->form_validation->set_rules('titulo', '', 'required');
		$this->form_validation->set_rules('contenido', '', 'required');
		$this->form_validation->set_rules('activo', '', 'required');
		if ($this->form_validation->run() == TRUE){
			$data['titulo'] = $this->input->post('titulo');
			$data['contenido'] = $this->input->post('contenido');
			$data['activo'] = $this->input->post('activo');
			$edit = $this->modelgeneral->editRegist('mensaje',['id'=>1],$data);

			$resp = [];
			if ($edit) {
				$resp['success'] = true;
			} else {
				$resp['success'] = false;
			}
			echo json_encode($resp);
		}
	}

}

/* End of file Mensaje.php */
/* Location: ./application/controllers/administrador/Mensaje.php */