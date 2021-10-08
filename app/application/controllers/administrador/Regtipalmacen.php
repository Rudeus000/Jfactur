<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regtipalmacen extends CI_Controller
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
        $data['tipo'] = $this->modelgeneral->getTable('tb_tipoalmacen');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/almacen/listgetipalmacen',$data);    
        $this->load->view('layouts/footer');
     }

        public function jsonTipAlmacen()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_tipoalm','nomb_tipoalm');
        $orderCampo = $this->input->get_post('order', true);
        $orderCampo = $orderCampo[0]['column'];
        $orderCampo = $columns[$orderCampo];
        $orderDireccion = $this->input->get_post('order', true);
        $orderDireccion = $orderDireccion[0]['dir'];
        $data['orderCampo'] = $orderCampo;
        $data['orderDireccion'] = $orderDireccion;
        $tb_tipoalmacen = $this->input->get_post('tb_tipoalmacen');
        if ($tb_tipoalmacen!='') {
            $data['tb_tipoalmacen'] = $tb_tipoalmacen;
        }
        $datos = $this->almacen_model->getTipoAlmacen($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
     }

  
       function agregarTipAlmacen()
     {
        
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tb_tipoalmacen.nomb_tipoalm]');
         $this->form_validation->set_rules('tipo','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['nomb_tipoalm '] = $this->input->post('descripcion');
             $data['tip_tipoalm']=  $this->input->post('tipo');
             $data['est_almacen']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_tipoalmacen',$data);
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
 


     function getTipAlmacen()
	  {
		$id = $this->input->get('id');
		$tipo = $this->modelgeneral->getTableWhereRow('tb_tipoalmacen',['cod_tipoalm'=>$id]);
		echo json_encode($tipo);
	  }


     function editTipAlmacen()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('descripcion','','required');
          $this->form_validation->set_rules('tipo','','required');
         
         if($this->form_validation->run() == TRUE){
 
             $data['nomb_tipoalm '] = $this->input->post('descripcion');
             $data['tip_tipoalm'] = $this->input->post('tipo');
             $where['cod_tipoalm'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_tipoalmacen',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularTipAlmacen()
	  {
        $data['est_almacen'] = 2; //ANULAR	
        $where['cod_tipoalm'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_tipoalmacen',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }