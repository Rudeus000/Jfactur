<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regunidad extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('unidad_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
         $data['permisos'] =$this->permisos;
         $data['unidad'] = $this->modelgeneral->getTable('tb_unidades');
         $data['tipounidad'] = $this->modelgeneral->getTable('tb_tipounidad');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/unidad/listgetunidad',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonUnidad()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_unid','abreviatura_unid','>nomb_unid','fact_unid','tipounidad','nomb_unidad');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_unidades = $this->input->get_post('tb_unidades');
        $tb_tipounidad = $this->input->get_post('tb_tipounidad');
        if ($tb_unidades!='') {
            $data['tb_unidades'] = $tb_unidades;
        }
        if ($tb_tipounidad!='') {
            $data['tb_tipounidad'] = $tb_tipounidad;
        }
        $datos = $this->unidad_model->getUnidad($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function insertUmedida()
       {
        $this->form_validation->set_rules('abreviatura','','required|trim|is_unique[tb_unidades.abreviatura_unid]');
        $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_unidades.nomb_unid]');
        $this->form_validation->set_rules('factor','','required');
        $this->form_validation->set_rules('tipounidad','','required');
        if($this->form_validation->run() == TRUE){
            
             $data['abreviatura_unid'] = $this->input->post('abreviatura');
             $data['nomb_unid'] = $this->input->post('descripcion');
             $data['fact_unid'] = $this->input->post('factor');
             $data['cod_tipunidad'] = $this->input->post('tipounidad');
             $data['est_unidad']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_unidades',$data);
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
 


     function getUmedida()
	  {
		$id = $this->input->get('id');
		$umedida = $this->modelgeneral->getTableWhereRow('tb_unidades',['cod_unid'=>$id]);
		echo json_encode($umedida);
	  }


     function editUmedida()
      {
        $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('abreviatura','','required');
        $this->form_validation->set_rules('descripcion','','required');
        $this->form_validation->set_rules('factor','','required');
        $this->form_validation->set_rules('tipounidad','','required');
         $this->form_validation->set_rules('estado','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['abreviatura_unid'] = $this->input->post('abreviatura');
             $data['nomb_unid'] = $this->input->post('descripcion');
             $data['fact_unid'] = $this->input->post('factor');
             $data['cod_tipunidad'] = $this->input->post('tipounidad');
             $data['est_unidad'] = $this->input->post('estado');
             $where['cod_unid'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_unidades',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularUmedida()
	  {
        $data['est_unidad'] = 2; //ANULAR	
        $where['cod_unid'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_unidades',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }