<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Traspasos_model extends CI_Model
{

  function getTraspasos($data)
  {
    $this->db->from('tb_traspasos');
    $this->db->select('tb_traspasos.cod_tras, fecha_tras, origen.nomb_almacen as origen, destino.nomb_almacen as destino, observacion_tras, usuario.nomb_usu as usuario, validador.nomb_usu as usuario_valida,tb_traspasos.estado_tras');
    $this->db->join('tb_almacen origen', 'tb_traspasos.origen_tras = origen.cod_almacen');
    $this->db->join('tb_almacen destino', 'tb_traspasos.destino_tras = destino.cod_almacen');
    $this->db->join('tb_usuario usuario', 'tb_traspasos.cod_usu = usuario.cod_usu');
    $this->db->join('tb_usuario validador', 'tb_traspasos.cod_usu_recibe = validador.cod_usu', 'left'); // Usuario que valida
    $this->db->where('fecha_tras >= ', $data['desde']);
    $this->db->where('fecha_tras <=', $data['hasta']);


    if ($data['origen'] != '') {
      $this->db->like('origen.cod_almacen', $data['origen']);
    }
    if ($data['destino'] != '') {
      $this->db->like('destino.cod_almacen', $data['destino']);
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
    $result['iTotalRecords'] = $query->num_rows();
    $result['iTotalDisplayRecords'] = $query->num_rows();

    $row = [];
    foreach ($query->result() as $q) {
      $detalle = $this->getDetalle($q->cod_tras);
      $btn_update = '<div class="btn-footer text-center">
            <a class="btn btn-sm btn-info" href="' . base_url('administrador/regtraspasos/editar/' . $q->cod_tras) . '" title="Editar Traspaso"><i class="fa fa-edit"></i></a>
        </div>';

      $boton_detalle = '<button class="btn btn-icon waves-effect waves-light btn-success" ><span class="fas fa-arrow-alt-circle-down"></span></button>';

      $boton_impresion = '<a class="btn btn-sm btn-danger" href="' . base_url('administrador/regtraspasos/generarPDF/' . $q->cod_tras) . '" title="Imprimir Traspaso" target="_blank"><i class="fa fa-print"></i></a>';

      $boton_estado = '';
      if ($q->estado_tras == 0) { // Pendiente
          $boton_estado = '<button class="btn btn-warning" onclick="abrirModalValidacion(' . $q->cod_tras .')">Pendiente</button>';
      } elseif ($q->estado_tras == 1) { // Aceptado
          $boton_estado = '<span class="badge badge-success">Aceptado</span>';
      } elseif ($q->estado_tras == 2) { // Rechazado
          $boton_estado = '<span class="badge badge-danger">Rechazado</span>';
      }
      

      // Mostrar solo un detalle de producto (el primero)
      $firstDetalle = isset($detalle[0]) ? $detalle[0] : null;

      $row[] = [$boton_detalle, $q->cod_tras, $q->fecha_tras, $q->origen, $q->destino, $firstDetalle->nomb_product, $firstDetalle->cant_trasdet, $q->usuario, $boton_estado,$q->usuario_valida, $q->observacion_tras, $btn_update, $boton_impresion, '', json_encode($detalle)];
    }

    $result['aaData'] = $row;
    return $result;
  }



  function getDetalle($id)
  {
    return $this->db->from('tb_traspasos_detalles')
      ->select('nomb_product, cant_trasdet, serie_trasdet')
      ->join('tb_producto', 'tb_traspasos_detalles.cod_producto = tb_producto.cod_producto')
      ->where('cod_tras', $id)
      ->get()->result();
  }

  function getTraspasosDetalle($cod_tras)
  {
    // Obtener información del traspaso
    $this->db->from('tb_traspasos');
    $this->db->select('tb_traspasos.cod_tras, fecha_tras, origen.nomb_almacen as origen, destino.nomb_almacen as destino, observacion_tras, usuario.nomb_usu as usuario,validador.nomb_usu as usuario_valida');
    $this->db->join('tb_almacen origen', 'tb_traspasos.origen_tras = origen.cod_almacen');
    $this->db->join('tb_almacen destino', 'tb_traspasos.destino_tras = destino.cod_almacen');
    $this->db->join('tb_usuario usuario', 'tb_traspasos.cod_usu = usuario.cod_usu');
    $this->db->join('tb_usuario validador', 'tb_traspasos.cod_usu_recibe = validador.cod_usu', 'left'); // Usuario que valida
    $this->db->where('tb_traspasos.cod_tras', $cod_tras);
    $query = $this->db->get();

    $traspaso = $query->row();

    // Obtener detalles de productos transferidos
    $this->db->from('tb_traspasos_detalles');
    $this->db->select('tb_producto.nomb_product, cant_trasdet, serie_trasdet');
    $this->db->join('tb_producto', 'tb_traspasos_detalles.cod_producto = tb_producto.cod_producto');
    $this->db->where('cod_tras', $cod_tras);
    $query = $this->db->get();

    $detalles = $query->result();

    return array('traspaso' => $traspaso, 'detalles' => $detalles);
  }

}

/* End of file traspasos_model.php */
/* Location: ./application/models/traspasos_model.php */
