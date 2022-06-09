<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regcategoria extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('categoria_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        $data['permisos'] =$this->permisos;
        $data['categoria'] = $this->modelgeneral->getTable('tb_categoria');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/categoria/listgetcategoria',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonCategoria()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_categoria','nomb_categoria');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_categoria = $this->input->get_post('tb_categoria');
        if ($tb_categoria!='') {
            $data['tb_categoria'] = $tb_categoria;
        }
        $datos = $this->categoria_model->getcategoria($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function agregarCategoria()
       {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_categoria.nomb_categoria]');
         if($this->form_validation->run() == TRUE){
            
             $data['nomb_categoria'] = $this->input->post('descripcion');
             $data['est_categoria']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_categoria',$data);
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
 


     function getCategoria()
	  {
		$id = $this->input->get('id');
		$categoria = $this->modelgeneral->getTableWhereRow('tb_categoria',['cod_categoria'=>$id]);
		echo json_encode($categoria);
	  }


     function editCategoria()
      {
        $this->form_validation->set_rules('id','','required');
        $this->form_validation->set_rules('descripcion','','required');
        $this->form_validation->set_rules('estado','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['nomb_categoria'] = $this->input->post('descripcion');
             $data['est_categoria'] = $this->input->post('estado');
             $where['cod_categoria'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_categoria',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularCategoria()
	  {
        $data['est_categoria'] = 2; //ANULAR	
        $where['cod_categoria'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_categoria',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }