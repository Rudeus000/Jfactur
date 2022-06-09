<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

class Regcuenta extends CI_Controller
{
 private $permisos;
 public function __construct()
 {
   parent::__construct();
   if(!$this->session->userdata("login")){
     redirect(base_url());
   }
   $this->load->model('cuenta_model');
   $this->load->model('modelgeneral');
   $this->load->helper('general');
   $this->permisos = $this->backend_lib->control();
 }

 public function index()
 {
  $data['permisos'] =$this->permisos;
  $data['cuenta'] = $this->modelgeneral->getTable('tb_cuenta');
  $data['tipo'] = $this->modelgeneral->getTable('tipo_cuenta');
  $this->load->view('layouts/header');
  $this->load->view('layouts/aside');
  $this->load->view('admin/cuenta/listcuenta', $data);    
  $this->load->view('layouts/footer');
}

public function jsonCuentaasignar()
{
  $data['start'] = $this->input->get_post('start', true);
  $data['length'] = $this->input->get_post('length', true);
  $data['sEcho']  = $this->input->get_post('_', true);
  $columns= array('id_cuenta');
  $orderCampo = $this->input->get_post('order', true);
  $orderCampo = $orderCampo[0]['column'];
  $orderCampo = $columns[$orderCampo];
  $orderDireccion = $this->input->get_post('order', true);
  $orderDireccion = $orderDireccion[0]['dir'];
  $data['orderCampo'] = $orderCampo;
  $data['orderDireccion'] = $orderDireccion;

  $tipo_cuenta= $this->input->get_post('tipo_cuenta');
    $data['tb_cuenta'] = $this->input->get_post('tb_cuenta');
   if ($tipo_cuenta!='') {
      $data['tipo_cuenta'] = $tipo_cuenta;
    }
  $datos = $this->cuenta_model->getcuentaasignar($data);
 header('content-type: application/json; charset=utf-8');
 echo json_encode($datos);
}



    //  public function validaEmail()
    // {
    //     if ($this->input->is_ajax_request()) {
    //         $email = $this->input->get('email');
    //         $verifica = $this->modelgeneral->verificaUnico('tb_usuario','email_usu',$email);
    //         if ($verifica) {
    //             echo 'true';
    //         }else{
    //             echo 'false';
    //         }
    //     }
    // }


      function agregarCuent()
       {
            $this->form_validation->set_rules('tipo','','required');
            $this->form_validation->set_rules('nombre','','required');
            $this->form_validation->set_rules('orden','','required'); 
               if($this->form_validation->run() == TRUE){

                 $data['id_tipcuenta'] = $this->input->post('tipo');
                 $data['nomb_cuenta'] = $this->input->post('nombre');
                 $data['orden_cuenta']= $this->input->post('orden');
                 $insert = $this->modelgeneral->insertRegist('tb_cuenta',$data);
                 $resp =[];
                 if (!is_null($insert)) {
                      $resp['success'] = true;
                    }else{
                      $resp['success'] = false;
                    }

                    echo json_encode($resp);

        }
      }

         function getCuentas()
         {
          $id = $this->input->get('id');
          $cuenta = $this->modelgeneral->getTableWhereRow('tb_cuenta',['id_cuenta'=>$id]);
          echo json_encode($cuenta);
        }


        function editCuenta()
        {
         $this->form_validation->set_rules('id','','required');
         $this->form_validation->set_rules('tipo','','required');
         $this->form_validation->set_rules('nombre','','required');
         $this->form_validation->set_rules('orden','','required'); 
        
       //   $this->form_validation->set_rules('estado','','required');
         if($this->form_validation->run() == TRUE){

          $data['id_tipcuenta'] = $this->input->post('tipo');
          $data['nomb_cuenta'] = $this->input->post('nombre');
          $data['orden_cuenta']= $this->input->post('orden');
          $where['id_cuenta'] = $this->input->post('id');
          $edit = $this->modelgeneral->editRegist('tb_cuenta',$where,$data);
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
    $data['estado_cuenta'] = 2; //ANULAR	
    $where['id_cuenta'] = $this->input->get('id');  

    $edit = $this->modelgeneral->editRegist('tb_cuenta',$where,$data);
    $resp = [];
    if ($edit) {
     $resp['success'] = true;
   }else{
     $resp['success'] = false;
   }
   echo json_encode($resp);
  }

  



}