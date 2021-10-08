<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */

class Regusuario extends CI_Controller
{
  private $permisos;
  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata("login")) {
      redirect(base_url());
    }
    $this->load->model('usuario_model');
    $this->load->model('modelgeneral');
    $this->load->helper('general');
    $this->permisos = $this->backend_lib->control();
  }

  public function index()
  {
    $data['permisos'] = $this->permisos;
    $data['usuario'] = $this->modelgeneral->getTable('tb_usuario');
    $data['grupos'] = $this->modelgeneral->getTable('tb_grupo');

    $data['perfiles'] = $this->modelgeneral->getTable('tb_perfil');
    $data['puntos'] = $this->modelgeneral->getTable('tb_puntoventa');
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/usuario/listgetusuario', $data);
    $this->load->view('layouts/footer');
  }

  public function jsonUsuario()
  {
    $data['start'] = $this->input->get_post('start', true);
    $data['length'] = $this->input->get_post('length', true);
    $data['sEcho']  = $this->input->get_post('_', true);
    $columns = array('cod_usu', 'NombreUsuario', 'login_usu', 'fecha_visita', 'grupo', 'perfil');
    $orderCampo = $this->input->get_post('order', true);
    $orderCampo = $orderCampo[0]['column'];
    $orderCampo = $columns[$orderCampo];
    $orderDireccion = $this->input->get_post('order', true);
    $orderDireccion = $orderDireccion[0]['dir'];
    $data['orderCampo'] = $orderCampo;
    $data['orderDireccion'] = $orderDireccion;
    $desde = $this->input->get_post('desde');
    $hasta = $this->input->get_post('hasta');
    $tb_usuario = $this->input->get_post('tb_usuario');
    $tb_grupo = $this->input->get_post('tb_grupo');
    if ($desde != '' and $hasta != '') {
      $data['desde'] = $desde;
      $data['hasta'] = $hasta;
    }
    if ($tb_usuario != '') {
      $data['tb_usuario'] = $tb_usuario;
    }

    if ($tb_grupo != '') {
      $data['tb_grupo'] = $tb_grupo;
    }
    $datos = $this->usuario_model->getUsuario($data);
    header('content-type: application/json; charset=utf-8');
    echo json_encode($datos);
  }

  public function nuevo()
  {

    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/grupo/addgrupo');
    $this->load->view('layouts/footer');
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


  function agregarUsuario()
  {
    $this->form_validation->set_rules('apellido', '', 'required');
    $this->form_validation->set_rules('nombre', '', 'required');
    $this->form_validation->set_rules('direccion', '', 'required');
    $this->form_validation->set_rules('telefono', '', 'required');
    $this->form_validation->set_rules('documento', '', 'required');
    $this->form_validation->set_rules('email', '', 'required|valid_email|is_unique[tb_usuario.email_usu]');
    $this->form_validation->set_rules('login', '', 'required|trim|is_unique[tb_usuario.login_usu]');
    $this->form_validation->set_rules('passwoord', '', 'required');
    $this->form_validation->set_rules('grupo', '', 'required');
    $this->form_validation->set_rules('perfil', '', 'required');
    $this->form_validation->set_rules('estado', '', 'required');
    $this->form_validation->set_rules('fnacimiento','','required');
    if ($this->form_validation->run() == TRUE) {

      $data['apell_usu'] = $this->input->post('apellido');
      $data['nomb_usu'] = $this->input->post('nombre');
      $data['direcc_usu'] = $this->input->post('direccion');
      $data['telf_usu'] = $this->input->post('telefono');
      $data['docum_usu'] = $this->input->post('documento');
      $data['email_usu'] = $this->input->post('email');
      $data['login_usu'] = $this->input->post('login');
      $data['passwoord_usu'] =  sha1($this->input->post('passwoord'));
      $data['fecha_registro'] = date("Y-m-d H:i:s");
      $data['fecha_modificacion'] = date("Y-m-d H:i:s");
      $data['fecha_visita'] = date("Y-m-d H:i:s");
      $data['cod_grupo'] = $this->input->post('grupo');
      $data['cod_perfil'] = $this->input->post('perfil');
      $data['estado_usuario'] =  $this->input->post('estado');
      $data['fena_usu']=  $this->input->post('fnacimiento');
      $insert = $this->modelgeneral->insertRegist('tb_usuario', $data);
      $resp = [];
      if (!is_null($insert)) {

        $punto = $this->modelgeneral->getTableWhereRow('tb_puntoventa', ['pordefecto_puntoventa' => 1]); //POR DEFECTO DEL SISTEMA
        $this->db->set('cod_puntoventa', $punto->cod_puntoventa)
          ->set('cod_usu', $insert)
          ->set('pordefecto', 1)
          ->insert('tb_usuario_puntoventa'); //INSERTAR PUNTO DE VENTA A NUEVO USUARIO
        $resp['success'] = true;
      } else {
        $resp['success'] = false;
      }
      echo json_encode($resp);
    }
  }

  function getUsuario()
  {
    $id = $this->input->get('id');
    $grupo = $this->modelgeneral->getTableWhereRow('tb_usuario', ['cod_usu' => $id]);
    echo json_encode($grupo);
  }


  function editUsuario()
  {
    $this->form_validation->set_rules('id', '', 'required');
    $this->form_validation->set_rules('apellido', '', 'required');
    $this->form_validation->set_rules('nombre', '', 'required');
    $this->form_validation->set_rules('direccion', '', 'required');
    $this->form_validation->set_rules('telefono', '', 'required');
    $this->form_validation->set_rules('documento', '', 'required');
    $this->form_validation->set_rules('email', '', 'required|min_length[3]|valid_email|trim');
    $this->form_validation->set_rules('login', '', 'required');
    $this->form_validation->set_rules('grupo', '', 'required');
    $this->form_validation->set_rules('perfil', '', 'required');
    $this->form_validation->set_rules('estado','','required');
    if ($this->form_validation->run() == TRUE) {

      $data['apell_usu'] = $this->input->post('apellido');
      $data['nomb_usu'] = $this->input->post('nombre');
      $data['direcc_usu'] = $this->input->post('direccion');
      $data['telf_usu'] = $this->input->post('telefono');
      $data['docum_usu'] = $this->input->post('documento');
      $data['email_usu'] = $this->input->post('email');
      $data['login_usu'] = $this->input->post('login');
      if (isset($_POST['passwoord'])) {
        $data['passwoord_usu'] = sha1($this->input->post('passwoord'));
      }
      $data['fecha_modificacion'] = date("Y-m-d H:i:s");
      $data['cod_grupo'] = $this->input->post('grupo');
      $data['cod_perfil'] = $this->input->post('perfil');
      $data['estado_usuario']= $this->input->post('estado');
      $data['fena_usu']= $this->input->post('fnacimiento');
      $where['cod_usu'] = $this->input->post('id');
      $edit = $this->modelgeneral->editRegist('tb_usuario', $where, $data);
      $resp = [];
      if (!is_null($edit)) {
        $resp['success'] = true;
      } else {
        $resp['success'] = false;
      }
      echo json_encode($resp);
    }
  }

  function anularUsuario()
  {
    $data['estado_usuario'] = 2; //ANULAR	
    $where['cod_usu'] = $this->input->get('id');

    $edit = $this->modelgeneral->editRegist('tb_usuario', $where, $data);
    $resp = [];
    if ($edit) {
      $resp['success'] = true;
    } else {
      $resp['success'] = false;
    }
    echo json_encode($resp);
  }

  public function getCajaDocumento()
  {
    $usuario = $this->input->get('id');
    $query = $this->db->from('tb_usuario_documento')
      ->join('tb_caja', 'tb_usuario_documento.cod_caja = tb_caja.cod_caja')
      ->join('tb_tipodocumento', 'tb_usuario_documento.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
      ->where('cod_usu', $usuario)
      ->get()->result();
    echo json_encode($query);
  }

  public function getCajas()
  {
    $usuario = $this->input->get('usuario');
    $punto = $this->input->get('punto');

    $query = $this->db->from('tb_puntoventa_caja')
      ->join('tb_caja', 'tb_puntoventa_caja.cod_caja = tb_caja.cod_caja')
      ->where('tb_puntoventa_caja.cod_puntoventa', $punto)
      ->get()->result();
    echo json_encode($query);
  }

  public function getTalonarios()
  {
    $usuario = $this->input->get('usuario');
    $punto = $this->input->get('punto');
    $caja = $this->input->get('caja');

    $query = $this->db->from('tb_usuario_documento')
      ->select('cod_tipdocu')
      ->where('cod_caja', $caja)
      ->where('cod_usu', $usuario)
      ->get()->result();
    $array = [];
    foreach ($query as $q) {
      $array[] = $q->cod_tipdocu;
    }

    $this->db->from('tb_talonario');
    $this->db->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
    $this->db->where('tb_talonario.cod_puntoventa', $punto);
    if (count($array) > 0) {
      //$this->db->where_not_in('tb_talonario.cod_tipdocu',$array);
    }
    $documentos = $this->db->get()->result();

    echo json_encode($documentos);
  }

  public function agregarCajaDocumento()
  {
    $data['cod_usu'] = $this->input->post('usuario');
    $data['cod_caja'] = $this->input->post('caja');
    $data['cod_tipdocu'] = $this->input->post('documento');
    $data['serie_usudoc'] = $this->input->post('serie');
    $data['type_formt'] = $this->input->post('formato');

    $insert = $this->modelgeneral->insertRegist('tb_usuario_documento', $data);

    $resp = [];
    if (!is_null($insert)) {
      $resp['success'] = true;
    } else {
      $resp['success'] = false;
    }
    echo json_encode($resp);
  }

  public function quitarCajaDocumento()
  {
    $where['cod_usudoc'] = $this->input->get('id');
    $delete = $this->modelgeneral->deleteRegist('tb_usuario_documento', $where);

    $resp = [];
    if ($delete) {
      $resp['success'] = true;
    } else {
      $resp['success'] = false;
    }

    echo json_encode($resp);
  }

  public function updateSerie()
  {

    if ($this->input->get('formato') !== null) {
      $data['type_formt'] = $this->input->get('formato');
    } else {
      $data['serie_usudoc'] = $this->input->get('serie');
    }

    $where['cod_usudoc'] = $this->input->get('id');
  
    $edit = $this->modelgeneral->editRegist('tb_usuario_documento', $where, $data);

    $resp = [];
    if ($edit) {
      $resp['success'] = true;
    } else {
      $resp['success'] = false;
    }

    echo json_encode($resp);
  }
}
