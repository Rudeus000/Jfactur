<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class perfil extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata("login")){
			redirect(base_url());
		}
		$this->load->model('dashboard_model');
		$this->load->model('usuario_model');
	}
	
	public function index()
	{
    $data['usuarios'] = $this->modelgeneral->getTableWhereRow('tb_usuario',['cod_usu'=>$this->session->userdata('cod_usu')]);
    $data['perfil'] = $this->modelgeneral->getTableWhereRow('tb_perfil',['cod_perfil'=>$this->session->userdata('perfil')]);  
		//$data['sucursales'] = $this->modelgeneral->getTableWhere('tb_puntoventa',['estad_pto'=>1]);
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('home/inicio',$data);
		//$this->load->view('home/inicio',$data);
		$this->load->view('layouts/footer');
	}

	// 	public function setPuntoVenta($punto)
	// {
	// 	if($punto=='admin' AND $this->session->userdata('perfil')!='1'){
	// 		redirect(base_url().'reportes/regdashboard');
	// 	}
	// 	$this->session->set_userdata('puntoventa_reportes',$punto);
	// 	redirect(base_url().'reportes/regdashboard');
	// }


	public function kardex()
	{
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('home/kardex');
		$this->load->view('layouts/footer');
	}

	public function jsonKardex()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
    $data['sEcho']  = $this->input->get_post('_', true);
    $columns= ['nomb_puntoventa','nomb_product','nomb_tiparticulo'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$datos = $this->dashboard_model->getKardex($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	 public function getPerfil()
  {
    $id = $this->input->get('id');
    $grupo = $this->modelgeneral->getTableWhereRow('tb_usuario', ['cod_usu' => $id]);
    echo json_encode($grupo);
  }

	  function editPerfil()
  {
    $this->form_validation->set_rules('id', '', 'required');
    $this->form_validation->set_rules('email', '', 'required|min_length[3]|valid_email|trim');
    $this->form_validation->set_rules('login', '', 'required');  
    if ($this->form_validation->run() == TRUE) {

      $data['email_usu'] = $this->input->post('email');
      $data['login_usu'] = $this->input->post('login');
      if (isset($_POST['passwoord'])) {
        $data['passwoord_usu'] = sha1($this->input->post('passwoord'));
      }     
      $where['cod_usu'] = $this->input->post('id');
      $edit = $this->modelgeneral->editRegist('tb_usuario', $where, $data);
      $resp = [];
      if (!is_null($edit)) {
        $resp['success'] = true;
        $resp['redirect'] = 'perfil';
        // $resp['usuarios'] = $this->modelgeneral->getTableWhereRow('tb_usuario',['cod_usu'=>$this->session->userdata('cod_usu')]);
      } else {
        $resp['success'] = false;
      }
      echo json_encode($resp);
    }
  }

	
}
