<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regsede extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('sede_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos;
        $data['sede'] = $this->modelgeneral->getTable('sede');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/sede/listgetsede',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonsede()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_sede','sede_nombre');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $sede = $this->input->get_post('sede');
        if ($sede!='') {
            $data['sede'] = $sede;
        }
        $datos = $this->sede_model->getsede($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function agregarsede()
       {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[sede.sede_nombre]');
         if($this->form_validation->run() == TRUE){
            
             $data['sede_nombre'] = $this->input->post('descripcion');
             $data['sede_estado']=  1;
             $insert = $this->modelgeneral->insertRegist('sede',$data);
             $resp =[];
             if(!is_null($insert)){
              //  $insert = $this->modelgeneral->insertRegist('tb_usuario',$data);
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
            echo json_encode($resp);
          
          }
 
       }
 


     function getsede()
	  {
		$id = $this->input->get('id');
		$sede = $this->modelgeneral->getTableWhereRow('sede',['cod_sede'=>$id]);
		echo json_encode($sede);
	  }


     function editsede()
      {
        $this->form_validation->set_rules('id','','required');
        $this->form_validation->set_rules('descripcion','','required');
        $this->form_validation->set_rules('estado','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['sede_nombre'] = $this->input->post('descripcion');
             $data['sede_estado'] = $this->input->post('estado');
             $where['cod_sede'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('sede',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularsede()
	  {
        $data['sede_estado'] = 2; //ANULAR	
        $where['cod_sede'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('sede',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }