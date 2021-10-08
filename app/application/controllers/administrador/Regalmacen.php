<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regalmacen extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('almacen_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos;   
        $data['almacen'] = $this->modelgeneral->getTable('tb_almacen');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/almacen/listgetalmacen',$data);    
        $this->load->view('layouts/footer');
     }

     public function jsonAlmacen()
     {
        $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_almacen','nomb_almacen');
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$tb_almacen = $this->input->get_post('tb_almacen');
		if ($tb_almacen!='') {
			$data['tb_almacen'] = $tb_almacen;
		}
		$datos = $this->almacen_model->getAlmacen($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
     }

  
       function agregarAlmacen()
     {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_almacen.nomb_almacen]');
         $this->form_validation->set_rules('estadoventa','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['nomb_almacen'] = $this->input->post('descripcion');
             $data['disp_venta']=  $this->input->post('estadoventa');
             $data['est_almacen']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_almacen',$data);
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
 


     function getAlmacen()
	  {
		$id = $this->input->get('id');
		$almacen = $this->modelgeneral->getTableWhereRow('tb_almacen',['cod_almacen'=>$id]);
		echo json_encode($almacen);
	  }


     function editAlmacen()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('descripcion','','required');
          $this->form_validation->set_rules('estadoventa','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['nomb_almacen'] = $this->input->post('descripcion');
             $data['disp_venta'] = $this->input->post('estadoventa');
             $where['cod_almacen'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_almacen',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularAlmacen()
	  {
        $data['est_almacen'] = 2; //ANULAR	
        $where['cod_almacen'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_almacen',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }



 }