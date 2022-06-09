<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regimpresora extends CI_Controller
 {
    private $permisos;
    public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('impresora_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

    public function index()
     {
        $data['permisos'] =$this->permisos;
        $data['impresora'] = $this->modelgeneral->getTable('tb_impresora');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/impresora/listgetimpresora',$data);    
        $this->load->view('layouts/footer');
     }

    public function jsonImpresora()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('nom_impresora','nomlocal_impresora','serie_impresora','url_impresora','ip_impresora');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_impresora = $this->input->get_post('tb_impresora');
        if ($tb_impresora!='') {
            $data['tb_impresora'] = $tb_impresora;
        }
        $datos = $this->impresora_model->getImpresora($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
       }

  
       function agregarimpresora()
       {
        
         $this->form_validation->set_rules('impresora','','required|trim|is_unique[tb_impresora.nom_impresora]');
         $this->form_validation->set_rules('nombrelocal','','required');
         $this->form_validation->set_rules('serie','',''); 
         $this->form_validation->set_rules('url','','');
         $this->form_validation->set_rules('ip','','required');  
         if($this->form_validation->run() == TRUE){
            
             $data['nom_impresora'] = $this->input->post('impresora');
             $data['nomlocal_impresora'] = $this->input->post('nombrelocal');
             $data['serie_impresora'] = $this->input->post('serie');
             $data['url_impresora'] = $this->input->post('url');
             $data['ip_impresora']=  $this->input->post('ip');
             $data['estad_impresora']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_impresora',$data);
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
 


     function getimpresora()
	  {
		$id = $this->input->get('id');
		$impresora = $this->modelgeneral->getTableWhereRow('tb_impresora',['cod_impresora'=>$id]);
		echo json_encode($impresora);
	  }


     function editImpresora()
      {
         $this->form_validation->set_rules('id','','required');
          $this->form_validation->set_rules('impresora','','required');
         $this->form_validation->set_rules('nombrelocal','','required');
         $this->form_validation->set_rules('serie','',''); 
         $this->form_validation->set_rules('url','','');
         $this->form_validation->set_rules('ip','','required');  
         
         if($this->form_validation->run() == TRUE){
 
             $data['nom_impresora'] = $this->input->post('impresora');
             $data['nomlocal_impresora'] = $this->input->post('nombrelocal');
             $data['serie_impresora'] = $this->input->post('serie');
             $data['url_impresora'] = $this->input->post('url');
             $data['ip_impresora']=  $this->input->post('ip');
             $where['cod_impresora'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_impresora',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularImpresora()
	  {
        $data['estad_impresora'] = 2; //ANULAR	
        $where['cod_impresora'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_impresora',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }