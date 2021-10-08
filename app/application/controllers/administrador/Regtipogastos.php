<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regtipogastos extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('tipogastos_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
        // $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
       // $data['permisos'] =$this->permisos;
        $data['tipogastos'] = $this->modelgeneral->getTable('tb_tipo_gastos');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/tipogastos/listgastos',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonTipoGastos()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_tipgastos','descripcion');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_tipo_gastos = $this->input->get_post('tb_tipo_gastos');
        if ($tb_tipo_gastos!='') {
            $data['tb_tipo_gastos'] = $tb_tipo_gastos;
        }
        $datos = $this->tipogastos_model->getTipogastos($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function insertTipoGastos()
       {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_tipo_gastos.descripcion]');
         if($this->form_validation->run() == TRUE){
            
             $data['descripcion'] = $this->input->post('descripcion');
             $data['estado_tipo']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_tipo_gastos',$data);
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
 


     function getTipoGastosad()
	  {
		$id = $this->input->get('id');
		$tipogastos = $this->modelgeneral->getTableWhereRow('tb_tipo_gastos',['cod_tipgastos'=>$id]);
		echo json_encode($tipogastos);
	  }


     function editTipoGastos()
      {
        $this->form_validation->set_rules('id','','required');
        $this->form_validation->set_rules('descripcion','','required');
        $this->form_validation->set_rules('estado','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['descripcion'] = $this->input->post('descripcion');
             $data['estado_tipo'] = $this->input->post('estado');
             $where['cod_tipgastos'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_tipo_gastos',$where,$data);
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