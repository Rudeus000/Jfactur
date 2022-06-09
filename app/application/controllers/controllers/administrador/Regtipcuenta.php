<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regtipcuenta extends CI_Controller
 {
     private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('tipocuenta_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();

     }

     public function index()
         {
             $data['permisos'] =$this->permisos; 
             $data['tipo'] = $this->modelgeneral->getTable('tipo_cuenta');
            $this->load->view('layouts/header');
            $this->load->view('layouts/aside');
            $this->load->view('admin/tipocuenta/listipcuenta',$data);    
            $this->load->view('layouts/footer');
         }

    public function jsonTipcuenta()
         {
            $data['start'] = $this->input->get_post('start', true);
            $data['length'] = $this->input->get_post('length', true);
            $data['sEcho']  = $this->input->get_post('_', true);
            $columns= array('id_tipcuenta','abrev_tipcuenta');
            $orderCampo = $this->input->get_post('order', true);
            $orderCampo = $orderCampo[0]['column'];
            $orderCampo = $columns[$orderCampo];
            $orderDireccion = $this->input->get_post('order', true);
            $orderDireccion = $orderDireccion[0]['dir'];
            $data['orderCampo'] = $orderCampo;
            $data['orderDireccion'] = $orderDireccion;
            $tipo_cuenta = $this->input->get_post('tipo_cuenta');
            if ($tipo_cuenta!='') {
                $data['tipo_cuenta'] = $tipo_cuenta;
            }
            $datos = $this->tipocuenta_model->getipocuent($data);
            header('content-type: application/json; charset=utf-8');
            echo json_encode($datos);
         }

  
       function agregarTipcuenta()
       {

         $this->form_validation->set_rules('simbolo','','required'); 
         $this->form_validation->set_rules('descripcion','','required|trim|is_unique[tipo_cuenta.nomb_tipcuenta]');
         $this->form_validation->set_rules('orden','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['abrev_tipcuenta'] = $this->input->post('simbolo');            
             $data['nomb_tipcuenta'] = $this->input->post('descripcion');
             $data['orden_tipcuenta']=  $this->input->post('orden');
             $data['estado_tipcuenta']=  1;
             $insert = $this->modelgeneral->insertRegist('tipo_cuenta',$data);
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
 


     function getipcuenta()
	  {
		$id = $this->input->get('id');
		$tipo = $this->modelgeneral->getTableWhereRow('tipo_cuenta',['id_tipcuenta'=>$id]);
		echo json_encode($tipo);
	  }


     function editTipoCuenta()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('simbolo','','required'); 
         $this->form_validation->set_rules('descripcion','','required');
          $this->form_validation->set_rules('orden','','required');
         
         if($this->form_validation->run() == TRUE){

             $data['abrev_tipcuenta'] = $this->input->post('simbolo');
             $data['nomb_tipcuenta'] = $this->input->post('descripcion');
             $data['orden_tipcuenta'] = $this->input->post('orden');
             $where['id_tipcuenta'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tipo_cuenta',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularCuenta()
	  {
        $data['estado_tipcuenta'] = 2; //ANULAR	
        $where['id_tipcuenta'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tipo_cuenta',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }