<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regrupo extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('grupos_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos;
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/grupo/listgetgrupo',$data);    
        $this->load->view('layouts/footer');
     }

     public function jsonGrupo()
     {

        $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_grupo','nombre_grupo');
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$tb_grupo = $this->input->get_post('tb_grupo');
		if ($tb_grupo!='') {
			$data['tb_grupo'] = $tb_grupo;
		}
		$datos = $this->grupos_model->getGrupo($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
     }

     public function nuevo()
     {
         
             $this->load->view('layouts/header');
             $this->load->view('layouts/aside');
             $this->load->view('admin/grupo/addgrupo');
             $this->load->view('layouts/footer');
 
     }
 
 
     public function guardar()
     {
         $this->form_validation->set_rules('descripcion','','requerid');
         $this->form_validation->set_rules('estado','','requerid');
     
 
         $data = array(
             
             'nombre_grupo' => $this->input->post('descripcion'),
             'estado_grupo' => $this->input->post('estado')
         );
 
         $insert = $this->grupos_model->agregarGrupo($data);
             if(!is_null($insert)){
                 $this->session->set_flashdata('success', 'Te has registrado correctamente en nuestro sistema.<br>Hemos enviado un código de verificación a ');
                 $resp['success']=true;
             }
         echo json_encode(array("status" => TRUE));
 
         redirect(base_url().'administrador/regrupo');
      }
 

     function getGrupo()
	  {
		$id = $this->input->get('id');
		$grupo = $this->modelgeneral->getTableWhereRow('tb_grupo',['cod_grupo'=>$id]);
		echo json_encode($grupo);
	  }


     function editGrupo()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('descripcion','','required');
         if($this->form_validation->run() == TRUE){
 
             $data['nombre_grupo'] = $this->input->post('descripcion');
             $where['cod_grupo'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_grupo',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularGrupo()
	  {
        $data['estado_grupo'] = 2; //ANULAR	
        $where['cod_grupo'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_grupo',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }
 }