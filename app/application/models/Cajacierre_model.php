<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cajacierre_model extends CI_Model
{

  function getCajasDestinos()
  {
    return $this->db->from('tb_caja')
      ->where('est_caja', 1)
      ->where_in('tipo_caja', [2, 3])
      ->get()->result();
  }

  function getCajasAperturas()
  {
    return $this->db->from('tb_usuario_puntoventa')
      ->select('nomb_caja,tb_caja.cod_caja')
      ->join('tb_puntoventa', 'tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa.cod_puntoventa')
      ->join('tb_puntoventa_caja', 'tb_puntoventa.cod_puntoventa = tb_puntoventa_caja.cod_puntoventa')
      ->join('tb_caja', 'tb_puntoventa_caja.cod_caja = tb_caja.cod_caja')
      ->where('tb_usuario_puntoventa.cod_usu', $this->session->userdata('cod_usu'))
      ->where('tb_usuario_puntoventa.cod_puntoventa', $this->session->userdata('puntoventa'))
      ->where('est_caja', 1)
      ->get()->result();
  }

  function getCierre($data)
  {
    $this->db->from('tb_caja_apertura');
    $this->db->select('tb_caja_apertura.cod_apertura');
    $this->db->join('tb_caja as origen', 'tb_caja_apertura.cod_caja = origen.cod_caja');
    $this->db->join('tb_caja as destino', 'tb_caja_apertura.destino_apertura = destino.cod_caja');
    $this->db->join('tb_usuario', 'tb_caja_apertura.cod_usu = tb_usuario.cod_usu');
    $this->db->where('estado_apertura', 'C');
    $queryTotal = $this->db->get();

    $this->db->from('tb_caja_apertura');
    $this->db->select('tb_caja_apertura.*, CONCAT(apell_usu, " ", nomb_usu) as NombreUsuario, origen.nomb_caja as origen,destino.nomb_caja as destino');
    $this->db->join('tb_caja as origen', 'tb_caja_apertura.cod_caja = origen.cod_caja');
    $this->db->join('tb_caja as destino', 'tb_caja_apertura.destino_apertura = destino.cod_caja');
    $this->db->join('tb_usuario', 'tb_caja_apertura.cod_usu = tb_usuario.cod_usu');
    $this->db->where('fecha_apertura >= ', $data['desde']);
    $this->db->where('fecha_apertura <=', $data['hasta']);
    $this->db->where('estado_apertura', 'C');
    if ($data['caja'] != '') {
      $this->db->like('nomb_caja', $data['caja']);
    }
    if ($data['usuario'] != '') {
      $this->db->having("NombreUsuario LIKE '%" . $data['usuario'] . "%'");
    }
    $queryLike = $this->db->get();

    $efectivo_apertura = 0;
    $totalEgresos = 0;
    $totalCierre = 0;

    foreach ($queryLike->result() as $r) {
      $efectivo_apertura += $r->efectivo_apertura;
      $totalEgresos += $r->monto_movimiento;
      $totalCierre += $r->bonos_apertura;
    }


    $this->db->from('tb_caja_apertura');
    $this->db->select('tb_caja_apertura.*, CONCAT(apell_usu, " ", nomb_usu) as NombreUsuario, origen.nomb_caja as origen,destino.nomb_caja as destino');
    $this->db->join('tb_caja as origen', 'tb_caja_apertura.cod_caja = origen.cod_caja');
    $this->db->join('tb_caja as destino', 'tb_caja_apertura.destino_apertura = destino.cod_caja');
    $this->db->join('tb_usuario', 'tb_caja_apertura.cod_usu = tb_usuario.cod_usu');
    $this->db->where('fecha_apertura >= ', $data['desde']);
    $this->db->where('fecha_apertura <=', $data['hasta']);
    $this->db->where('estado_apertura', 'C');

    if ($data['caja'] != '') {
      $this->db->like('nomb_caja', $data['caja']);
    }
    if ($data['usuario'] != '') {
      $this->db->having("NombreUsuario LIKE '%" . $data['usuario'] . "%'");
    }
    if ($data['length'] != -1) {
      $this->db->limit($data['length'], $data['start']);
    }
    if (isset($data['orderCampo'])) {
      $this->db->order_by($data['orderCampo'], $data['orderDireccion']);
    }
    $query = $this->db->get();

    $result = array();
    $result['sEcho'] = $data['sEcho'];
    $result['iTotalRecords'] = $queryTotal->num_rows();
    $result['iTotalDisplayRecords'] = $query->num_rows();
    $result['efectivo_apertura'] = $efectivo_apertura;
    $result['totalEgresos'] = $totalEgresos;
    $result['totalCierre'] = $efectivo_apertura - $totalEgresos;

    $row = [];
    foreach ($query->result() as $q) {

      $turnos = ['M' => 'Mañana', 'T' => 'Tarde', 'N' => 'Noche'];
      $buttons = '
      <div class="btn-group">
     <a href="' . base_url('administrador/regcajacierre/imprimirCierrecaja/' . $q->cod_apertura) . '" target="_blank" class="btn btn-sm btn-success" data-toggle="tooltip" title="Imprimir Ticket"><i class="far fa-file-alt"></i></a>';
      $row[] = [$q->cod_apertura, $q->fechacierre_apertura, $q->origen, $q->NombreUsuario, $q->destino, $q->obs_movimiento, $q->monto_movimiento, $q->efectivo_apertura, $q->tarjeta_apertura, $q->bonos_apertura, $q->credito_apertura, $q->total_apertura, $buttons];
    }

    $result['aaData'] = $row;
    return $result;
  }
}

/* End of file Cajacierre_model.php */
/* Location: ./application/models/Cajacierre_model.php */
