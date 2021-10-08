<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regmarca extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('marca_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos;
        $data['marca'] = $this->modelgeneral->getTable('tb_marca');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/marca/listgetmarca',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonMarca()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_marca','nomb_marca');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_marca = $this->input->get_post('tb_marca');
        if ($tb_marca!='') {
            $data['tb_marca'] = $tb_marca;
        }
        $datos = $this->marca_model->getMarca($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function insertMarca()
       {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_marca.nomb_marca]');
         if($this->form_validation->run() == TRUE){
            
             $data['nomb_marca'] = $this->input->post('descripcion');
             $data['est_marca']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_marca',$data);
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
 


     function getMarca()
	  {
		$id = $this->input->get('id');
		$marca = $this->modelgeneral->getTableWhereRow('tb_marca',['cod_marca'=>$id]);
		echo json_encode($marca);
	  }


     function editMarca()
      {
        $this->form_validation->set_rules('id','','required');
        $this->form_validation->set_rules('descripcion','','required');
        $this->form_validation->set_rules('estado','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['nomb_marca'] = $this->input->post('descripcion');
             $data['est_marca'] = $this->input->post('estado');
             $where['cod_marca'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_marca',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularMarca()
	  {
        $data['est_marca'] = 2; //ANULAR	
        $where['cod_marca'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_marca',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }