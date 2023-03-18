<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regtipopay extends CI_Controller
 {
    //  private $permisos;
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('typepay_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
        //  $this->permisos = $this->backend_lib->control();
     }

     public function index()
         {
            // $data['permisos'] =$this->permisos;
            $data['tipo'] = $this->modelgeneral->getTable('tipo_cuenta');
            $this->load->view('layouts/header');
            $this->load->view('layouts/aside');
            $this->load->view('admin/typepay/typepay',$data);    
            $this->load->view('layouts/footer');
         }

    public function jsontpay()
         {
            $data['start'] = $this->input->get_post('start', true);
            $data['length'] = $this->input->get_post('length', true);
            $data['sEcho']  = $this->input->get_post('_', true);
            $columns= array('cod_tipopago','nom_tipopago');
            $orderCampo = $this->input->get_post('order', true);
            $orderCampo = $orderCampo[0]['column'];
            $orderCampo = $columns[$orderCampo];
            $orderDireccion = $this->input->get_post('order', true);
            $orderDireccion = $orderDireccion[0]['dir'];
            $data['orderCampo'] = $orderCampo;
            $data['orderDireccion'] = $orderDireccion;
            $tpay = $this->input->get_post('tpay');
            if ($tpay!='') {
            $data['tb_tipo_pago'] = $tpay;
        }
            $datos = $this->typepay_model->gettpay($data);
            header('content-type: application/json; charset=utf-8');
            echo json_encode($datos);
         }

  
       function addtpay()
       {

         $this->form_validation->set_rules('nombre','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['nom_tipopago'] = $this->input->post('nombre');            
             $data['estado_tipopago']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_tipo_pago',$data);
             $resp =[];
             if(!is_null($insert)){
            
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
            echo json_encode($resp);
          
          }
 
       }
 


     function gettpay()
	  {
		$id = $this->input->get('id');
		$banco = $this->modelgeneral->getTableWhereRow('tb_tipo_pago',['cod_tipopago'=>$id]);
		echo json_encode($banco);
	  }


     function updatetpay()
      {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('nombre','','required'); 
       
         
         if($this->form_validation->run() == TRUE){

             $data['nom_tipopago'] = $this->input->post('nombre');
             $where['cod_tipopago'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_tipo_pago',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function deletetpay()
	  {
        $data['estado_tipopago'] = 2; //ANULAR	
        $where['cod_tipopago'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_tipo_pago',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }