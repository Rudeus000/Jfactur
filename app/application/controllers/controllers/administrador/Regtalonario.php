<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regtalonario extends CI_Controller
 {
    private $permisos;
    public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('talonario_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
         
     }

    public function index()
     {
          $data['permisos'] =$this->permisos;
         $data['talonario'] = $this->modelgeneral->getTable('tb_talonario');
         $data['documento'] = $this->modelgeneral->getTableWhere('tb_tipodocumento',['est_tipdocum'=>1]);
         $data['punto'] = $this->modelgeneral->getTable('tb_puntoventa');
         $data['impresora'] = $this->modelgeneral->getTable('tb_impresora');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/talonario/listgetalonario',$data);    
        $this->load->view('layouts/footer');
     }

    public function jsonTalonario()
     {
        $data['start'] = $this->input->get_post('start', true);
        $data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('documento,punto,impresora, serie, talonario_ini, talonario_fin, correlativo_actual');
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
        $datos = $this->talonario_model->getTalonario($data);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($datos);
       }

  
       function agregarTalonario()
       {
        
         $this->form_validation->set_rules('documento','','required');
         $this->form_validation->set_rules('punto','','required');
         $this->form_validation->set_rules('impresora','','required');
         $this->form_validation->set_rules('serie','','required|trim|is_unique[tb_talonario.serie]'); 
         $this->form_validation->set_rules('inicio','','required');  
         $this->form_validation->set_rules('fin','','required');
         $this->form_validation->set_rules('actual','','required'); 
         $this->form_validation->set_rules('siglas','','required'); 
         if($this->form_validation->run() == TRUE){
            
             $data['cod_tipdocu'] = $this->input->post('documento');
             $data['cod_puntoventa'] = $this->input->post('punto');
             $data['cod_impresora'] = $this->input->post('impresora');
             $data['serie'] = $this->input->post('serie');
             $data['talonario_ini']=  $this->input->post('inicio');
             $data['talonario_fin']=  $this->input->post('fin');
             $data['correlativo_actual']=  $this->input->post('actual');
             $data['fecha_registro'] = date("Y-m-d H:i:s");
             $data['fecha_modificacion'] = date("Y-m-d H:i:s");
             $data['est_talonario']=  1;
             $data['siglas_talonario']=  $this->input->post('siglas');
             if($this->input->post('doccli_dni')=='on'){
                $data['docclidni_talonario']=  '1';
             }else{
                $data['docclidni_talonario']=  '0';
             }
             if($this->input->post('doccli_ruc')=='on'){
                $data['doccliruc_talonario']=  '1';
             }else{
                $data['doccliruc_talonario']=  '0';
             }
             $insert = $this->modelgeneral->insertRegist('tb_talonario',$data);
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
 


     function getTalonario()
	  {
		$id = $this->input->get('id');
		$talonario = $this->modelgeneral->getTableWhereRow('tb_talonario',['cod_talonario'=>$id]);
		echo json_encode($talonario);
	  }


     function editTalonario()
      {
         $this->form_validation->set_rules('id','','required');      
         $this->form_validation->set_rules('documento','','required');
         $this->form_validation->set_rules('punto','','required');
         $this->form_validation->set_rules('impresora','','required');
         $this->form_validation->set_rules('serie','','required'); 
         $this->form_validation->set_rules('inicio','','required');  
         $this->form_validation->set_rules('fin','','required');
         $this->form_validation->set_rules('actual','','required');    
          $this->form_validation->set_rules('estado','','required');
          $this->form_validation->set_rules('siglas','','required');
         if($this->form_validation->run() == TRUE){
 
             $data['cod_tipdocu'] = $this->input->post('documento');
             $data['cod_puntoventa'] = $this->input->post('punto');
             $data['cod_impresora'] = $this->input->post('impresora');
             $data['serie'] = $this->input->post('serie');
             $data['talonario_ini']=  $this->input->post('inicio');
             $data['talonario_fin']=  $this->input->post('fin');
             $data['correlativo_actual']=  $this->input->post('actual');
             $data['fecha_modificacion'] = date("Y-m-d H:i:s");
             $data['est_talonario']=  $this->input->post('estado');
             $data['siglas_talonario']=  $this->input->post('siglas');
             if($this->input->post('doccli_dni')=='on'){
                $data['docclidni_talonario']=  '1';
             }else{
                $data['docclidni_talonario']=  '0';
             }
             if($this->input->post('doccli_ruc')=='on'){
                $data['doccliruc_talonario']=  '1';
             }else{
                $data['doccliruc_talonario']=  '0';
             }
             $where['cod_talonario'] = $this->input->post('id');
             $edit = $this->modelgeneral->editRegist('tb_talonario',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularTalonario()
	  {
        $data['est_talonario'] = 2; //ANULAR	
        $where['cod_talonario'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_talonario',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }

  
 }