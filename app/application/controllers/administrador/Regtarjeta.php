<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regtarjeta extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('tarjeta_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
         {
            $data['permisos'] =$this->permisos; 
            $data['tarjeta'] = $this->modelgeneral->getTable('tb_tarjeta');
            $this->load->view('layouts/header');
            $this->load->view('layouts/aside');
            $this->load->view('admin/tarjeta/listarjeta',$data);    
            $this->load->view('layouts/footer');
         }

    public function jsontarjeta()
         {
            $data['start'] = $this->input->get_post('start', true);
            $data['length'] = $this->input->get_post('length', true);
            $data['sEcho']  = $this->input->get_post('_', true);
            $columns=['cod_tarj','nomb_tarj'];
            $orderCampo = $this->input->get_post('order', true);
            $orderCampo = $orderCampo[0]['column'];
            $orderCampo = $columns[$orderCampo];
            $orderDireccion = $this->input->get_post('order', true);
            $orderDireccion = $orderDireccion[0]['dir'];
            $data['orderCampo'] = $orderCampo;
            $data['orderDireccion'] = $orderDireccion;
            $tb_tarjeta = $this->input->get_post('tb_tarjeta');
            if ($tb_tarjeta!='') {
            $data['tb_tarjeta'] = $tb_tarjeta;
        }
            $datos = $this->tarjeta_model->getarjeta($data);
            header('content-type: application/json; charset=utf-8');
            echo json_encode($datos);
         }

  
       function agregarTarjeta()
       {

         $this->form_validation->set_rules('nombre','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['nomb_tarj'] = $this->input->post('nombre');            
             $data['est_tarj']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_tarjeta',$data);
             $resp =[];
             if(!is_null($insert)){
            
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
            echo json_encode($resp);
          
          }
 
       }
 


     function getarj()
	  {
		$id = $this->input->get('id');
		$tarjeta = $this->modelgeneral->getTableWhereRow('tb_tarjeta',['cod_tarj'=>$id]);
		echo json_encode($tarjeta);
	  }


     function editTarjeta()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('nombre','','required'); 
       
         
         if($this->form_validation->run() == TRUE){

             $data['nomb_tarj'] = $this->input->post('nombre');
             $where['cod_tarj'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_tarjeta',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularTarjeta()
	  {
        $data['est_tarj'] = 2; //ANULAR	
        $where['cod_tarj'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_tarjeta',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }