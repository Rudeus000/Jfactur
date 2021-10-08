<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regcaja extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('caja_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos;
        $data['caja'] = $this->modelgeneral->getTable('tb_caja');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/caja/listgetcaja',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonCaja()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_caja','nomb_caja');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_caja = $this->input->get_post('tb_caja');
        if ($tb_caja!='') {
            $data['tb_caja'] = $tb_caja;
        }
        $datos = $this->caja_model->getCaja($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function agregarCaja()
       {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_caja.nomb_caja]');
         $this->form_validation->set_rules('tipo','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['nomb_caja'] = $this->input->post('descripcion');
             $data['tipo_caja']=  $this->input->post('tipo');
             $data['est_caja']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_caja',$data);
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
 


     function getCaja()
	  {
		$id = $this->input->get('id');
		$tipo = $this->modelgeneral->getTableWhereRow('tb_caja',['cod_caja'=>$id]);
		echo json_encode($tipo);
	  }


     function editCaja()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('descripcion','','required');
          $this->form_validation->set_rules('tipo','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['nomb_caja'] = $this->input->post('descripcion');
             $data['tipo_caja'] = $this->input->post('tipo');
             $where['cod_caja'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_caja',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularCaja()
	  {
        $data['est_caja'] = 2; //ANULAR	
        $where['cod_caja'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_caja',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }