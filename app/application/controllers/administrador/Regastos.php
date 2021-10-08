<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

 class Regastos extends CI_Controller
 {
     private $permisos;
     
     public function __construct()
     {
         parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
        
         $this->load->model('gastos_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         //$this->permisos = $this->backend_lib->control();
     }

     public function index()
     {
        //$data['permisos'] =$this->permisos;
        $data['tipogastos'] = $this->modelgeneral->getTable('tb_tipo_gastos');
        $data['gastos'] = $this->modelgeneral->getTable('tb_gastos');
        $data['banco'] = $this->modelgeneral->getTable('tb_banco');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/gastos/lisgetgastos', $data);    
        $this->load->view('layouts/footer');
     }

     public function jsonGastos()
     {
        $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= array('cod_gastos', 'descripcion');
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$desde = $this->input->get_post('desde');
		$hasta = $this->input->get_post('hasta');
        $tb_gastos = $this->input->get_post('tb_gastos');
        $tb_tipo_gastos = $this->input->get_post('tb_tipo_gastos');
		if ($desde!='' AND $hasta!='') {
			$data['desde'] = $desde;
			$data['hasta'] = $hasta;
		}
		if ($tb_gastos!='') {
			$data['tb_gastos'] = $tb_gastos;
         }
  
        if ($tb_tipo_gastos!='') {
			$data['tb_tipo_gastos'] = $tb_tipo_gastos;
		}
        $data['estado'] = $this->input->get_post('estado');
		$datos = $this->gastos_model->getGastos($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
     }

   
 
 
     function addGastos()
     {
        date_default_timezone_set("America/Lima"); 
         $this->form_validation->set_rules('tipogastos','','required');
         $this->form_validation->set_rules('banco','','required');
         $this->form_validation->set_rules('nombre','','required');
         $this->form_validation->set_rules('total','','required');
         if($this->form_validation->run() == TRUE){
            
             $data['cod_tipgastos'] = $this->input->post('tipogastos');
             $data['cod_ban'] = $this->input->post('banco');
              $data['fecha_registro'] = date("Y-m-d H:i:s");
             $data['nomb_gastos']= $this->input->post('nombre');
             $data['cuenta_gastos']= $this->input->post('cuenta');
             $data['persona_gastos']= $this->input->post('persona');
             $data['oper_gastos']= $this->input->post('operacion');
             $data['documento_gastos']= $this->input->post('documentos');
             $data['total_gastos']=  $this->input->post('total');
             $data['observacion_gastos']= $this->input->post('observacion');
             $data['est_gastos']=  1;
             $insert = $this->modelgeneral->insertRegist('tb_gastos',$data);
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

     function getGastos()
	{
		$id = $this->input->get('id');
		$gastos = $this->modelgeneral->getTableWhereRow('tb_gastos',['cod_gastos'=>$id]);
		echo json_encode($gastos);
	}


     function editGastos()
     {
         $this->form_validation->set_rules('tipogastos','','required');
         $this->form_validation->set_rules('banco','','required');
         $this->form_validation->set_rules('nombre','','required');
         $this->form_validation->set_rules('total','','required');
         $this->form_validation->set_rules('estado','','required');
         if($this->form_validation->run() == TRUE){
 
            $data['cod_tipgastos'] = $this->input->post('tipogastos');
            $data['cod_ban'] = $this->input->post('banco');
            $data['nomb_gastos']= $this->input->post('nombre');
            $data['cuenta_gastos']= $this->input->post('cuenta');
            $data['persona_gastos']= $this->input->post('persona');
            $data['oper_gastos']= $this->input->post('operacion');
            $data['documento_gastos']= $this->input->post('documento');
            $data['total_gastos']=  $this->input->post('total');
            $data['observacion_gastos']= $this->input->post('observacion');
            $data['est_gastos']= $this->input->post('estado');
            $where['cod_gastos'] = $this->input->post('id');
            $edit = $this->modelgeneral->editRegist('tb_gastos',$where,$data);
             $resp =[];
             if(!is_null($edit)){
                 $resp['success'] = true;
             }else{
                 $resp['success'] = false;
             }
             echo json_encode($resp);
         }
     }

     function anularGastos()
	  {
        $data['est_gastos'] = 2; //ANULAR	
        $where['cod_gastos'] = $this->input->get('id');  
		
		$edit = $this->modelgeneral->editRegist('tb_gastos',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
	  }


       public function reportegastosPdf()
        {
                    $this->mpdf = new \Mpdf\Mpdf([
                            'mode' => 'utf-8',
                            'format' => 'A4',
                            'orientation' => 'L',
                            'margin_left' => 10,
                            'margin_right' => 10,
                            'margin_top' => 10,
                            'margin_bottom' => 10,
                            'margin_header' => 10,
                            'margin_footer' => 10
                        ]); 
                      $data['datos'] = $this->getGastosReporte();
                        $html = $this->load->view('admin/gastos/reportegastos_pdf',$data,TRUE);
                        $css = $css = file_get_contents('assets/styles_pdf.css');
                        $this->mpdf->SetTitle('Gastos');
                        $this->mpdf->writeHTML($css,1);
                        $this->mpdf->writeHTML($html,2);
                        $this->mpdf->Output('Gastos','I');
         }


       function getGastosReporte()
          {
            $this->db->from('tb_gastos');
            $this->db->select("tb_gastos.*,nomb_gastos,tb_tipo_gastos.descripcion,tb_banco.nomb_ban,fecha_registro,cuenta_gastos,persona_gastos,
                oper_gastos,documento_gastos,total_gastos,observacion_gastos,CASE est_gastos WHEN '1' THEN 'Gastado' ELSE 'Anulado' END as estado");
            $this->db->join('tb_tipo_gastos','tb_gastos.cod_tipgastos = tb_tipo_gastos.cod_tipgastos');
            $this->db->join('tb_banco','tb_gastos.cod_ban = tb_banco.cod_ban','left');
            $this->db->where('fecha_registro >= ',$this->input->get('desde'));
            $this->db->where('fecha_registro <=',$this->input->get('hasta'));
            if ($this->input->get('tb_gastos')!='') {
              $this->db->like('nomb_gastos',$this->input->get('tb_gastos'));
            }
            if ($this->input->get('tb_tipo_gastos')!='') {
                $this->db->where('tb_tipo_gastos.cod_tipgastos',$this->input->get('tb_tipo_gastos'));
            }
            if ($this->input->get('banco')!='') {
                $this->db->where('tb_banco.cod_ban',$this->input->get('banco'));
            }
           
            if ($this->input->get('estado')!='') {
                  $this->db->where('tb_gastos.est_gastos',$this->input->get('estado'));
             }
              return $this->db->get()->result();


          }
 }