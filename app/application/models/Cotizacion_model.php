<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cotizacion_model extends CI_Model {

	function getCotizacion($data)
	{
		$this->db->from('tb_cotizacion');
		$this->db->select('cod_cot,nomb_cliente,monto_cot,pago_cot,fecha_cot,estado_cot');
		$this->db->join('tb_cliente','tb_cotizacion.id_cliente = tb_cliente.id_cliente');
		$this->db->where('fecha_cot >= ',$data['desde']);
    $this->db->where('fecha_cot <=',$data['hasta']);
    if ($data['estado']!='') {
      $this->db->where('estado_cot',$data['estado']);
    }
    if ($data['pago']) {
      $this->db->where('pago_cot',$data['pago']);
    }
		$queryLike = $this->db->get();

  	$this->db->from('tb_cotizacion');
  	$this->db->select('cod_cot,nomb_cliente,monto_cot,pago_cot,fecha_cot,estado_cot,serie,nom_tipdocumento,numero_cot');
		$this->db->join('tb_cliente','tb_cotizacion.id_cliente = tb_cliente.id_cliente');
    $this->db->join('tb_talonario','tb_cotizacion.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		$this->db->where('fecha_cot >= ',$data['desde']);
    $this->db->where('fecha_cot <=',$data['hasta']);
    if ($data['estado']!='') {
      $this->db->where('estado_cot',$data['estado']);
    }
    if ($data['pago']) {
      $this->db->where('pago_cot',$data['pago']);
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
  	$tipo = ['CO'=>'Cotización','PRO'=>'Proforma'];
  	$pago = ['CO'=>'Contado','CRE'=>'Crédito'];
    foreach ($query->result() as $q) {
      $detalle = $this->getDetalle($q->cod_cot);

      $buttons = '
      <div class="btn-group">
       
	      <a href="'.base_url('administrador/regcotizacion/editar/'.$q->cod_cot).'" class="btn btn-sm btn-info" title="Editar Compra"><i class="fa fa-edit"></i></a>&nbsp
	      <button type="button" data-id="'.$q->cod_cot.'" class="procesar-cotizacion-venta btn btn-sm btn-purple" title="Procesar a Venta"><i class="fa fa-cart-plus"></i></button>&nbsp
	       <a href="'.base_url('administrador/regcotizacion/imprimirCotizacion/'.$q->cod_cot).'" target="_blank" class="btn btn-sm btn-success" data-toggle="tooltip" title="Imprimir Cotizacion"><i class="far fa-file-alt"></i></a>&nbsp
	      <button data-id="'.$q->cod_cot.'" class="btn btn-sm btn-warning" title="Enviar Correo" ><i class="fa fa-envelope"></i></button>&nbsp
	      <button data-id="'.$q->cod_cot.'" class="anular btn btn-sm btn-pink" title="Anular"><i class="fa fa-trash"></i></button>&nbsp
	      </div>
      ';


      $boton_detalle = '<button class="btn btn-purple waves-effect waves-light" ><span class="fa fa-caret-right"></span></button>';
      $row[] = [$boton_detalle,$q->cod_cot,$q->nomb_cliente,$q->nom_tipdocumento.' - '.$q->serie,$q->numero_cot,$q->monto_cot,$pago[$q->pago_cot],$q->fecha_cot,$buttons,json_encode($detalle)];

     }

     $result['aaData'] = $row;
     return $result;
	}

	function getDetalle($id)
	{
		return $this->db->from('tb_cotizacion_detalle')
		->join('tb_producto','tb_cotizacion_detalle.cod_producto = tb_producto.cod_producto')
		->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
    ->join('tb_marca','tb_marca.cod_marca = tb_producto.cod_marca')
		->where('cod_cot',$id)
		->get()->result();
	}

  function getTipos()
  {
    return $this->db->from('tb_talonario')
    ->select('cod_talonario,siglas_talonario,nom_tipdocumento,serie')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
    ->where('cod_puntoventa',$this->session->userdata('puntoventa'))
    ->where('siglas_talonario','PC')
    ->get()->result();
  }


  function getImpresionCotizacion($id)
  {
    $tb_cotizacion= $this->db->from('tb_cotizacion')
    ->select("tb_cotizacion.*, fecha_cot, nom_tipdocumento, serie, nomb_cliente, direc_cliente, doc_cliente, email_cliente, telf_cliente, contac_cliente")
     ->join('tb_cliente','tb_cotizacion.id_cliente = tb_cliente.id_cliente')
     ->join('tb_talonario','tb_cotizacion.cod_talonario = tb_talonario.cod_talonario')
     ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
    ->where('cod_cot',$id)
    ->get()->row();

      $tb_cotizacion->detalle =  $this->db->from('tb_cotizacion_detalle')
    ->select('tb_cotizacion_detalle.*,tb_producto.cod_producto, tb_producto.nomb_product, tb_unidades.abreviatura_unid')
    ->join('tb_producto','tb_cotizacion_detalle.cod_producto = tb_producto.cod_producto')
    ->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
    ->where('tb_cotizacion_detalle.cod_cot',$id)
    ->get()->result();

    return $tb_cotizacion;

	}
	
	function obtenerCotizacion($id)
	{
		$cotizacion = $this->db->from('tb_cotizacion')
    ->join('tb_cliente','tb_cotizacion.id_cliente = tb_cliente.id_cliente')
		->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
		->where('cod_cot',$id)
    ->get()->row();

		$cotizacion->detalle = $this->db->from('tb_cotizacion_detalle')
    ->join('tb_producto','tb_cotizacion_detalle.cod_producto = tb_producto.cod_producto','left')
    ->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid','left')
    ->join('tb_marca','tb_marca.cod_marca = tb_producto.cod_marca','left')
		->where('cod_cot',$id)
		->get()->result();		

		return $cotizacion;
	}
	
	function getTiposVentas($tipo_documento_activo)
  {
    return $this->db->from('tb_talonario')
    ->select('cod_talonario,siglas_talonario,nom_tipdocumento,serie,docclidni_talonario,doccliruc_talonario')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
    ->where('cod_puntoventa',$this->session->userdata('puntoventa'))
    ->where_in('siglas_talonario',['FC','TK'])
		->where('est_talonario',1)
		->where($tipo_documento_activo,1)
    ->get()->result();
  }

}

/* End of file Cotizacion_model.php */
/* Location: ./application/models/Cotizacion_model.php */
