<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regmoneda extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('tipmoneda_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos;
        $data['moneda'] = $this->modelgeneral->getTable('tipo_moneda');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/moneda/listmoneda',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonMoneda()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('id_moneda','mon_simbolo');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tipo_moneda = $this->input->get_post('tipo_moneda');
        if ($tipo_moneda!='') {
            $data['tipo_moneda'] = $tipo_moneda;
        }
        $datos = $this->tipmoneda_model->getmoneda($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function agregarMoneda()
       {

         $this->form_validation->set_rules('simbolo','','required'); 
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tipo_moneda.mon_moneda]');
         $this->form_validation->set_rules('tipo','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['mon_simbolo'] = $this->input->post('simbolo');            
             $data['mon_moneda  '] = $this->input->post('descripcion');
             $data['tipo_moneda ']=  $this->input->post('tipo');
             $data['mon_estado']=  1;
             $insert = $this->modelgeneral->insertRegist('tipo_moneda',$data);
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
 


     function getMon()
	  {
		$id = $this->input->get('id');
		$moneda = $this->modelgeneral->getTableWhereRow('tipo_moneda',['id_moneda'=>$id]);
		echo json_encode($moneda);
	  }


     function editMoneda()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('simbolo','','required'); 
         $this->form_validation->set_rules('descripcion','','required');
          $this->form_validation->set_rules('tipo','','required');
         
         if($this->form_validation->run() == TRUE){

             $data['mon_simbolo'] = $this->input->post('simbolo');
             $data['mon_moneda'] = $this->input->post('descripcion');
             $data['tipo_moneda'] = $this->input->post('tipo');
             $where['id_moneda'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tipo_moneda',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularMoneda()
	  {
        $data['mon_estado'] = 2; //ANULAR	
        $where['id_moneda'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tipo_moneda',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }