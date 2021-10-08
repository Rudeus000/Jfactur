<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regtiparticulo extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('tiparticulo_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {   $data['permisos'] =$this->permisos;   
         $data['tipo'] = $this->modelgeneral->getTable('tb_tiparticulo');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/articulo/listgetiparticulo',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonTiparticulo()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_tiparticulo','nomb_tiparticulo','tipo_articulo');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_tiparticulo = $this->input->get_post('tb_tiparticulo');
        if ($tb_tiparticulo!='') {
            $data['tb_tiparticulo'] = $tb_tiparticulo;
        }
        $datos = $this->tiparticulo_model->getTiparticulo($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function agregarTiparticulo()
       {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_tiparticulo.nomb_tiparticulo]');
         $this->form_validation->set_rules('stock','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['nomb_tiparticulo'] = $this->input->post('descripcion');
             $data['stock_tiparticulo']=  $this->input->post('stock');
             $data['est_tiparticulo']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_tiparticulo',$data);
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
 


     function getTiparticulo()
	  {
		$id = $this->input->get('id');
		$stock = $this->modelgeneral->getTableWhereRow('tb_tiparticulo',['cod_tiparticulo'=>$id]);
		echo json_encode($stock);
	  }


     function ediTiparticulo()
      {
        $this->form_validation->set_rules('id','','required');
        $this->form_validation->set_rules('descripcion','','required');
        $this->form_validation->set_rules('stock','','required');
        $this->form_validation->set_rules('estado','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['nomb_tiparticulo'] = $this->input->post('descripcion');
             $data['stock_tiparticulo'] = $this->input->post('stock');
             $data['est_tiparticulo'] = $this->input->post('estado');
             $where['cod_tiparticulo'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_tiparticulo',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularTiparticulo()
	  {
        $data['est_tiparticulo'] = 2; //ANULAR	
        $where['cod_tiparticulo'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_tiparticulo',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }