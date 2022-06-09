<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regperfil extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('perfil_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos;
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/perfil/listgetperfil',$data);    
        $this->load->view('layouts/footer');
     }

     public function jsonPerfil()
     {
        $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_perfil','nomb_perfil');
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$tb_perfil = $this->input->get_post('tb_perfil');
		if ($tb_perfil!='') {
			$data['tb_perfil'] = $tb_perfil;
		}
		$datos = $this->perfil_model->getPerfil($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
     }

     public function nuevo()
     {
         
             $this->load->view('layouts/header');
             $this->load->view('layouts/aside');
             $this->load->view('admin/perfil/addperfil');
             $this->load->view('layouts/footer');
 
     }
 
 
     public function guardar()
     {
         $this->form_validation->set_rules('descripcion','','requerid');
         $this->form_validation->set_rules('estado','','requerid');
     
 
         $data = array(
             
             'nomb_perfil' => $this->input->post('descripcion'),
             'estado_perfil' => $this->input->post('estado')
         );
 
         $insert = $this->perfil_model->agregarPerfil($data);
             if(!is_null($insert)){
                 $this->session->set_flashdata('success', 'Te has registrado correctamente en nuestro sistema.<br>Hemos enviado un código de verificación a ');
                 $resp['success']=true;
             }
         echo json_encode(array("status" => TRUE));
 
         redirect(base_url().'administrador/regperfil');
      }
 

     function getPerfil()
	{
		$id = $this->input->get('id');
		$perfil = $this->modelgeneral->getTableWhereRow('tb_perfil',['cod_perfil'=>$id]);
		echo json_encode($perfil);
	}


     function editPerfil()
     {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('descripcion','','required');
         if($this->form_validation->run() == TRUE){
 
             $data['nomb_perfil'] = $this->input->post('descripcion');
             $where['cod_perfil'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_perfil',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularPerfil()
	  {
        $where['cod_perfil'] = $this->input->get('id');  
		$data['estado_perfil'] = 2; //ANULAR
		
		$edit = $this->modelgeneral->editRegist('tb_perfil',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }
 }