<?php

class Regdashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata("login")){
			redirect(base_url());
		}
		$this->load->model('reportdashboard_model');
	}
	
	public function index()
	{

		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('reports/reportdashboard', $data);
		$this->load->view('layouts/footer');


 }
}