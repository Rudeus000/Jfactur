<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tutoria extends CI_Controller {

  private $permisos;
  public function __construct()
	{
		parent::__construct();
		
		if(!$this->session->userdata("login")){
				redirect(base_url());
		}
		//$this->permisos = $this->backend_lib->control();
		$this->load->model('modelgeneral');
		// $this->load->model('confempresa_model');

	}

  public function index()
  {
		$data['permisos'] =$this->permisos;
		$this->load->helper('url');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');		
		$data['empresa'] = $this->modelgeneral->getTableWhereRow('tb_empresa',['cod_empresa'=>1]);
		$this->load->view('tutoria',$data);
		$this->load->view('layouts/footer');
	}
	
	

	
		
	

}