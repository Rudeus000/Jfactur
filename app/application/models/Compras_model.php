<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras_model extends CI_Model {

	function getCompras($data)
	{
    $this->db->from('tb_compra');
    $this->db->join('tb_almacen',' tb_compra.cod_almacen = tb_almacen.cod_almacen');
    $this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
    $this->db->where('estado_comp',$data['estado']);
    $this->db->where('fecha_comp >= ',$data['desde']);
    $this->db->where('fecha_comp <=',$data['hasta']);
    if ($data['proveedor']!='') {
     $this->db->like('tb_proveedor_nom',$data['proveedor']);
   }
   if ($data['almacen']!='') {
     $this->db->where('tb_compra.cod_almacen',$data['almacen']);
   }
   $queryLike = $this->db->get();


   $this->db->from('tb_compra');
   $this->db->join('tb_almacen',' tb_compra.cod_almacen = tb_almacen.cod_almacen');
   $this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
   $this->db->where('estado_comp',$data['estado']);
   $this->db->where('fecha_comp >= ',$data['desde']);
   $this->db->where('fecha_comp <=',$data['hasta']);
   if ($data['proveedor']!='') {
     $this->db->like('tb_proveedor_nom',$data['proveedor']);
   }
   if ($data['almacen']!='') {
     $this->db->where('tb_compra.cod_almacen',$data['almacen']);
   }
   if ($data['cod_compra']!='') {
    $this->db->where('tb_compra.cod_comp',$data['cod_compra']);
  }
   if ($data['length']!=-1) {
     $this->db->limit($data['length'],$data['start']);
   }
   if (isset($data['orderCampo'])) {
     $this->db->order_by($data['orderCampo'],$data['orderDireccion']);
   }

   $query = $this->db->get();

    $result = array();
    $result['sEcho'] = $data['sEcho'];
    $result['iTotalRecords'] = $queryLike->num_rows();
    $result['iTotalDisplayRecords'] = $queryLike->num_rows();

    $row = [];
    foreach ($query->result() as $q) {
      $pagos = $this->getPagos($q->cod_comp);
      $detalle = $this->getDetalle($q->cod_comp);
      $buttons = '
      <div class="btn-group">
      <a href="'.base_url('administrador/regcompras/editar/'.$q->cod_comp).'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Editar Compra"><i class="fa fa-eye"></i></a>&nbsp
      <button data-id="'.$q->cod_comp.'" class="anular btn btn-sm btn-pink" data-toggle="tooltip" title="Anular Compra"><i class="fa fa-trash"></i></button>&nbsp
      <a href="'.base_url('administrador/regcompras/imprimirCompra/'.$q->cod_comp).'" target="_blank" class="btn btn-sm btn-success" data-toggle="tooltip" title="Imprimir Compra"><i class="far fa-file-alt"></i></a>
      </div>

      ';

      $boton_detalle = '<button class="btn btn-icon waves-effect waves-light btn-success" ><span class="fa fa-caret-right"></span></button>';
      $row[] = [$boton_detalle,$q->fecha_comp,$q->cod_comp,$q->documento_comp,$q->tb_proveedor_nom,$q->numdocumento_comp,$q->nomb_almacen,$q->total_comp,$pagos,$q->pendiente_comp,$buttons,json_encode($detalle)];
     }

     $result['aaData'] = $row;
     return $result;
  }

  function getPagos($compra)
  {
    return $this->db->from('tb_pago')
    ->select('SUM(monto_pago) as pagos')
    ->where('cod_comp',$compra)
    ->group_by('cod_comp')
    ->get()->row()->pagos;
  }

  function getDetalle($compra)
  {
    return $this->db->from('tb_compra_detalle')
    ->join('tb_producto','tb_compra_detalle.cod_producto = tb_producto.cod_producto')
    ->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
    ->join('tb_marca','tb_marca.cod_marca = tb_producto.cod_marca')
    ->where('tb_compra_detalle.cod_comp',$compra)
    ->get()->result();
  }

  function getImpresionCompras($id)
  {
    $tb_compra = $this->db->from('tb_compra')
    ->select("tb_compra.*,tb_proveedor_nom,documento_comp,tb_proveedor_dir,tb_proveedor_doc,tb_proveedor_con,CASE pago_comp WHEN 'CO' THEN 'Contado' ELSE 'Credito' END as tipopago")
    ->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id')
    ->where('cod_comp',$id)
    ->get()->row();

    $tb_compra->detalle =  $this->db->from('tb_compra_detalle')
    ->select('tb_compra_detalle.*,tb_producto.cod_producto, tb_producto.nomb_product, tb_unidades.abreviatura_unid')
    ->join('tb_producto','tb_compra_detalle.cod_producto = tb_producto.cod_producto')
    ->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
    ->where('tb_compra_detalle.cod_comp',$id)
    ->get()->result();

    return $tb_compra;
  }

}

/* End of file Compras_model.php */
/* Location: ./application/models/Compras_model.php */