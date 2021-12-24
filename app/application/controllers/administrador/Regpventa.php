<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */

class Regpventa extends CI_Controller
{
  private $permisos;
  public function __construct()
  {
   parent::__construct();
   if(!$this->session->userdata("login")){
     redirect(base_url());
   }
   $this->load->model('pventa_model');
   $this->load->model('modelgeneral');
   $this->load->helper('general');
   $this->permisos = $this->backend_lib->control();
 }

  public function index()
  {
    $data['permisos'] =$this->permisos;
    $data['punto'] = $this->modelgeneral->getTable('tb_puntoventa');
    $data['almacen'] = $this->modelgeneral->getTable('tb_almacen');
    $data['caja'] = $this->modelgeneral->getTable('tb_caja');
    $data['impresora'] = $this->modelgeneral->getTable('tb_impresora');
    $data['sede'] = $this->modelgeneral->getTable('sede');
    $data['ubigeos'] = $this->ubigeo();
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/punto/listgetpventa',$data);    
    $this->load->view('layouts/footer');  
  }

 public function jsonPventa()
 {
  $data['start'] = $this->input->get_post('start', true);
  $data['length'] = $this->input->get_post('length', true);
  $data['sEcho']  = $this->input->get_post('_', true);
  $columns= array('punto','almacen','caja','impresora','sede');
  $orderCampo = $this->input->get_post('order', true);
  $orderCampo = $orderCampo[0]['column'];
  $orderCampo = $columns[$orderCampo];
  $orderDireccion = $this->input->get_post('order', true);
  $orderDireccion = $orderDireccion[0]['dir'];
  $data['orderCampo'] = $orderCampo;
  $data['orderDireccion'] = $orderDireccion;
  $tb_puntoventa = $this->input->get_post('tb_puntoventa');
  $tb_almacen = $this->input->get_post('tb_almacen');
  $sede = $this->input->get_post('sede');
  if ($tb_puntoventa!='') {
    $data['tb_puntoventa'] = $tb_puntoventa;
  }
  if ($tb_almacen!='') {
    $data['tb_almacen'] = $tb_almacen;
  }
  if ($sede!='') {
    $data['sede'] = $sede;
  }
  $datos = $this->pventa_model->getPventa($data);
  header('content-type: application/json; charset=utf-8');
  echo json_encode($datos);
}


function agregarPventa()
{
  
 $this->form_validation->set_rules('punto','','required|trim|is_unique[tb_puntoventa.nomb_puntoventa]');
 $this->form_validation->set_rules('almacen','','required');
 $this->form_validation->set_rules('caja','',''); 
 $this->form_validation->set_rules('impresora','','');
 $this->form_validation->set_rules('sede','','required');  
 if($this->form_validation->run() == TRUE){
  
   $data['nomb_puntoventa'] = $this->input->post('punto');
   $data['cod_almacen'] = $this->input->post('almacen');
   $data['cod_caja'] = $this->input->post('caja');
   $data['cod_impresora'] = $this->input->post('impresora');   
   $data['cod_sede']=  $this->input->post('sede');
   $data['ubigeo_puntoventa']=  $this->input->post('ubigeo');
   $data['telefono_puntoventa']=  $this->input->post('telefono');
   $data['direccion_puntoventa']=  $this->input->post('direccion');
   $data['email_puntoventa']=  $this->input->post('email');
   $data['codigosunat_puntoventa']=  $this->input->post('codigo');
   $data['estad_pto']=  1;
   $insert = $this->modelgeneral->insertRegist('tb_puntoventa',$data);
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



function getPventa()
{
  $id = $this->input->get('id');
  $pventa = $this->modelgeneral->getTableWhereRow('tb_puntoventa',['cod_puntoventa'=>$id]);
  echo json_encode($pventa);
}


function editPventa()
{
 $this->form_validation->set_rules('id','','required');      
 $this->form_validation->set_rules('punto','','required');
 $this->form_validation->set_rules('almacen','','required');
 $this->form_validation->set_rules('caja','',''); 
 $this->form_validation->set_rules('impresora','','');
 $this->form_validation->set_rules('sede','','required');   
 
 if($this->form_validation->run() == TRUE){
   
   $data['nomb_puntoventa'] = $this->input->post('punto');
   $data['cod_almacen'] = $this->input->post('almacen');
   $data['cod_caja'] = $this->input->post('caja');
   $data['cod_impresora'] = $this->input->post('impresora');
   $data['cod_sede']=  $this->input->post('sede');
   $data['ubigeo_puntoventa']=  $this->input->post('ubigeo');
   $data['telefono_puntoventa']=  $this->input->post('telefono');
   $data['direccion_puntoventa']=  $this->input->post('direccion');
   $data['email_puntoventa']=  $this->input->post('email');
   $data['codigosunat_puntoventa']=  $this->input->post('codigo');
   $data['talonario_defecto']=  $this->input->post('comp_elect_defecto');
   $data['cliente_defecto']=  $this->input->post('cliente_defecto');
   $where['cod_puntoventa'] = $this->input->post('id');
   $edit = $this->modelgeneral->editRegist('tb_puntoventa',$where,$data);
   $resp =[];
   if(!is_null($edit)){
     $resp['success'] = true;
   }else{
     $resp['success'] = false;
   }
   echo json_encode($resp);
 }
}

  function anularPventa()
  {
      $data['estad_pto'] = 2; //ANULAR	
      $where['cod_puntoventa'] = $this->input->get('id');  
      
      $edit = $this->modelgeneral->editRegist('tb_puntoventa',$where,$data);
      $resp = [];
      if ($edit) {
       $resp['success'] = true;
     }else{
       $resp['success'] = false;
     }
     echo json_encode($resp);
  }

  function asignarPorDefecto()
  {
    $id = $this->input->get('id');
    $this->db->set('pordefecto_puntoventa',0)->update('tb_puntoventa');
    $edit = $this->modelgeneral->editRegist('tb_puntoventa',['cod_puntoventa'=>$id],['pordefecto_puntoventa'=>1]);
    $resp = [];
    if ($edit) {
      $resp['success'] = true;
    }else{
      $resp['success'] = false;
    }

    echo json_encode($resp);
  }

  public function getCajas()
  {
    $punto = $this->input->get('id');
    $query = $this->db->from('tb_puntoventa_caja')
    ->where('cod_puntoventa',$punto)
    ->join('tb_caja','tb_puntoventa_caja.cod_caja = tb_caja.cod_caja')
    ->get()->result();
    echo json_encode($query);
  }

  public function getCajasParaAgregar()
  {
    $punto = $this->input->get('punto');
    $query = $this->db->from('tb_puntoventa_caja')
    ->join('tb_caja','tb_puntoventa_caja.cod_caja = tb_caja.cod_caja AND tb_puntoventa_caja.cod_puntoventa = '.$punto,'right')
    ->where('tb_puntoventa_caja.cod_caja IS NULL',NULL)
    ->where('tipo_caja',1)
    ->where('est_caja',1)
    ->get()->result();
    echo json_encode($query);
  }

  public function agregarCaja()
  {
    $data['cod_caja'] = $this->input->post('caja');
    $data['cod_puntoventa'] = $this->input->post('puntoVenta');
    $insert = $this->modelgeneral->insertRegist('tb_puntoventa_caja',$data);

    $resp = [];
    if (!is_null($insert)) {
      $resp['success'] = true;
    }else{
      $resp['success'] = false;
    }
    echo json_encode($resp);
  }

  public function quitarCaja()
  {
    $where['cod_puntoventa'] = $this->input->get('punto');
    $where['cod_caja'] = $this->input->get('caja');
    $delete = $this->modelgeneral->deleteRegist('tb_puntoventa_caja',$where);
    
    $resp = [];
    if ($delete) {
      $resp['success'] = true;
    }else{
      $resp['success'] = false;
    }

    echo json_encode($resp);
  }




  public function getAlmacenes()
  {
    $punto = $this->input->get('id');
    $query = $this->db->from('tb_puntoventa_almacen')
    ->select('tb_puntoventa_almacen.*,tb_almacen.nomb_almacen')
    ->where('cod_puntoventa',$punto)
    ->join('tb_almacen','tb_puntoventa_almacen.cod_almacen = tb_almacen.cod_almacen')
    ->get()->result();
    echo json_encode($query);
  }

  public function getAlmacenesParaAgregar()
  {
    $punto = $this->input->get('punto');
    $query = $this->db->from('tb_puntoventa_almacen')
    ->join('tb_almacen','tb_puntoventa_almacen.cod_almacen = tb_almacen.cod_almacen AND tb_puntoventa_almacen.cod_puntoventa = '.$punto,'right')
    ->where('tb_puntoventa_almacen.cod_almacen IS NULL',NULL)
    ->get()->result();
    echo json_encode($query);
  }

  public function agregarAlmacen()
  {
    $query = $this->db->from('tb_puntoventa_almacen')
    ->where('cod_puntoventa',$this->input->post('puntoVenta'))
    ->get();

    if ($query->num_rows()==0) {
      $data['pordefecto'] = 1;
    }

    $data['cod_almacen'] = $this->input->post('almacen');
    $data['cod_puntoventa'] = $this->input->post('puntoVenta');
    $insert = $this->modelgeneral->insertRegist('tb_puntoventa_almacen',$data);

    $resp = [];
    if (!is_null($insert)) {
      $resp['success'] = true;
    }else{
      $resp['success'] = false;
    }
    echo json_encode($resp);
  }

  public function quitarAlmacen()
  {
    $where['cod_puntoventa'] = $this->input->get('punto');
    $where['cod_almacen'] = $this->input->get('almacen');
    $delete = $this->modelgeneral->deleteRegist('tb_puntoventa_almacen',$where);
    
    $resp = [];
    if ($delete) {
      $resp['success'] = true;
    }else{
      $resp['success'] = false;
    }

    echo json_encode($resp);
  }

  public function cambiarAlmacenDefecto()
  {
    $this->db->set('pordefecto',0)
    ->where('cod_puntoventa',$this->input->get('punto'))
    ->update('tb_puntoventa_almacen');

    $where['cod_almacen'] = $this->input->get('almacen');
    $where['cod_puntoventa'] = $this->input->get('punto');
    $data['pordefecto'] = 1;
    $edit = $this->modelgeneral->editRegist('tb_puntoventa_almacen',$where,$data);

    $resp = [];
    if ($edit) {
      $resp['success'] = true;
    }else{
      $resp['success'] = false;
    }

    echo json_encode($resp);

  }
  public function ubigeo()
  {
    return $this->db->from('ubigeo_distritos')
    ->select('ubigeo_distritos.nombre as distrito,ubigeo_provincias.nombre as provincia, ubigeo_departamentos.nombre as departamento, ubigeo_distritos.id as ubigeo')
    ->join('ubigeo_provincias','ubigeo_provincias.id = ubigeo_distritos.provincia_id')
    ->join('ubigeo_departamentos','ubigeo_departamentos.id = ubigeo_distritos.departamento_id')
    ->get()->result();
  }

  public function getTalonarioPorDefecto()
  {
    $id = $this->input->get('id');
    $talonario = $this->db->from('tb_talonario')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
    ->where('cod_puntoventa',$id)
    ->get()->result();
    header('content-type: application/json; charset=utf-8');
    echo json_encode($talonario);
  }

  public function getClientePorDefecto()
  {
    $id = $this->input->get('id');
    $talonario = $this->db->from('tb_talonario')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
    ->where('cod_talonario',$id)
    ->get()->row();

    $array = [];
    if($talonario->docclidni_talonario==1){
      $array[] = 4;
    }
    if($talonario->doccliruc_talonario==1){
      $array[] = 6;
    }
    $clientes = $this->db->from('tb_cliente')
    ->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
    ->where_in('codsunat_tipdocucli',$array)
    ->order_by('nomb_cliente','asc')
    ->get()->result();

    header('content-type: application/json; charset=utf-8');
    echo json_encode($clientes);


  }


}