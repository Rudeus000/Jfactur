<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regtipodocum extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('tipodocumento_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos; 
        $data['documento'] = $this->modelgeneral->getTable('tb_tipodocumento');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/documento/listgetipdocum',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonDocumento()
         {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_tipdocu','nom_tipdocumento');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_tipodocumento = $this->input->get_post('tb_tipodocumento');
        if ($tb_tipodocumento!='') {
            $data['tb_tipodocumento'] = $tb_tipodocumento;
        }
        $datos = $this->tipodocumento_model->getDocumento($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
        }

  
       function agregarDocum()
       {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_tipodocumento.nom_tipdocumento]'); 
         if($this->form_validation->run() == TRUE){
            
             $data['nom_tipdocumento'] = $this->input->post('descripcion');
             $data['est_tipdocum']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_tipodocumento',$data);
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
 


     function getDocumento()
	  {
		$id = $this->input->get('id');
		$tipo = $this->modelgeneral->getTableWhereRow('tb_tipodocumento',['cod_tipdocu'=>$id]);
		echo json_encode($tipo);
	  }


     function editDocumento()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('descripcion','','required');
          $this->form_validation->set_rules('estado','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['nom_tipdocumento'] = $this->input->post('descripcion');
             $data['est_tipdocum'] = $this->input->post('estado');
             $where['cod_tipdocu'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_tipodocumento',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularDocumento()
	  {
        $data['est_tipdocum'] = 2; //ANULAR	
        $where['cod_tipdocu'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_tipodocumento',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }