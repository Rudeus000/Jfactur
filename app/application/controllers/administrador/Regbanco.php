<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regbanco extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('banco_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
         {
            $data['permisos'] =$this->permisos;
            $data['tipo'] = $this->modelgeneral->getTable('tipo_cuenta');
            $this->load->view('layouts/header');
            $this->load->view('layouts/aside');
            $this->load->view('admin/banco/listbanco',$data);    
            $this->load->view('layouts/footer');
         }

    public function jsonbanco()
         {
            $data['start'] = $this->input->get_post('start', true);
            $data['length'] = $this->input->get_post('length', true);
            $data['sEcho']  = $this->input->get_post('_', true);
            $columns= array('cod_ban','nomb_ban');
            $orderCampo = $this->input->get_post('order', true);
            $orderCampo = $orderCampo[0]['column'];
            $orderCampo = $columns[$orderCampo];
            $orderDireccion = $this->input->get_post('order', true);
            $orderDireccion = $orderDireccion[0]['dir'];
            $data['orderCampo'] = $orderCampo;
            $data['orderDireccion'] = $orderDireccion;
            $tb_banco = $this->input->get_post('tb_banco');
            if ($tb_banco!='') {
            $data['tb_banco'] = $tb_banco;
        }
            $datos = $this->banco_model->getbanco($data);
            header('content-type: application/json; charset=utf-8');
            echo json_encode($datos);
         }

  
       function agregarBanco()
       {

         $this->form_validation->set_rules('nombre','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['nomb_ban'] = $this->input->post('nombre');            
             $data['est_ban']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_banco',$data);
             $resp =[];
             if(!is_null($insert)){
            
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
            echo json_encode($resp);
          
          }
 
       }
 


     function getBan()
	  {
		$id = $this->input->get('id');
		$banco = $this->modelgeneral->getTableWhereRow('tb_banco',['cod_ban'=>$id]);
		echo json_encode($banco);
	  }


     function editBanco()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('nombre','','required'); 
       
         
         if($this->form_validation->run() == TRUE){

             $data['nomb_ban'] = $this->input->post('nombre');
             $where['cod_ban'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_banco',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularBanco()
	  {
        $data['est_ban'] = 2; //ANULAR	
        $where['cod_ban'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_banco',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }