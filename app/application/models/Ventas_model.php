<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas_model extends CI_Model {

	function getVentas($data)
	{
   	$this->db->from('tb_venta');
    $this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
    $this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
    $this->db->where('fecha_vent >= ',$data['desde']);
    $this->db->where('fecha_vent <=',$data['hasta']);
    // $this->db->where('tb_venta.cod_usu',$data['vendedor']);
    if ($data['cliente']!='') {
      $this->db->like('tb_cliente.nomb_cliente',$data['cliente']);
    }
    if ($data['vendedor']!='') {
      $this->db->where('tb_venta.cod_usu',$data['vendedor']);
    }
    if ($data['punto']!='') {
      $this->db->where('tb_venta.cod_puntoventa',$data['punto']);
    }
    if ($data['estado']!='') {
      $this->db->where('tb_venta.estado_vent',$data['estado']);
    }
   	$queryLike = $this->db->get();


   	$this->db->from('tb_venta');
   	$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
   	$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
    $this->db->where('fecha_vent >= ',$data['desde']);
    $this->db->where('fecha_vent <=',$data['hasta']);
    if ($data['cliente']!='') {
      $this->db->like('tb_cliente.nomb_cliente',$data['cliente']);
    }
    if ($data['vendedor']!='') {
      $this->db->where('tb_venta.cod_usu',$data['vendedor']);
    }
    if ($data['punto']!='') {
      $this->db->where('tb_venta.cod_puntoventa',$data['punto']);
    }
    if ($data['estado']!='') {
      $this->db->where('tb_venta.estado_vent',$data['estado']);
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
      $cobros = $this->getCobros($q->cod_vent);
      $detalle = $this->getDetalle($q->cod_vent);

      if ($q->estado_vent=='G') {
        $estado = '<label class="label label-primary">Generado</label>';
      }elseif($q->estado_vent=='A'){
        $estado = '<label class="label label-danger">Anulado</label>';
      }
      
			$xml = '';
			$id_venta = '';
      if ($q->siglas_talonario=='FC') {
				$xml = '<a title="Archivo XML" class="btn btn-sm btn-success" target="_blank" href="'.base_url_app('facturacion/'.$q->rutaxml_vent.'/'.$q->archivoxml_vent.'.XML').'">XML</a>&nbsp';
				
				$archivoxml = $q->archivoxml_vent;
      }else{
				$archivoxml = $q->noxml_vent;
        
			}

      $buttons = '
      <div class="btn-group">

      <div class="btn-group">
      <button data-id="'.$archivoxml.'" data-email="'.$q->email_cliente.'"  data-cliente="ENVIAR A: '.$q->email_cliente.'"class="btn btn-sm btn-success enviar-email"><i class="fa fa-envelope"></i></button>

      <button data-telefono="'.$q->telf_cliente.'" data-cliente="ENVIAR A: '.$q->nomb_cliente.'" data-id="'.$archivoxml.'"class="btn btn-primary waves-effect waves-light enviar-whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></button>

      <a href="'.base_url('administrador/regventas/editar/'.$q->cod_vent).'" class="btn btn-sm btn-info" data-toggle="tooltip" title="Ver Venta"><i class="fa fa-eye"></i></a>&nbsp

      <button data-id="'.$q->cod_vent.'" class="anular btn btn-sm btn-pink" data-toggle="tooltip" title="Anular Venta"><i class="fa fa-trash"></i></button>&nbsp
      
      '.$xml.'
      
      <a href="'.base_url('administrador/regventas/imprimirVenta/'.$archivoxml).'" target="_blank" class="btn btn-sm btn-success" data-toggle="tooltip" title="Imprimir Venta"><i class="far fa-file-alt"></i></a>&nbsp
      <a href="'.base_url('administrador/regventas/imprimirticketVenta/'.$archivoxml).'" target="_blank" class="btn btn-sm btn-success" data-toggle="tooltip" title="Imprimir Ticket"><i class="far fa-file-alt"></i></a>
      </div>
       ';

      $boton_detalle = '<button class="btn btn-icon waves-effect waves-light btn-success" ><span class="fa fa-caret-right"></span></button>';
      $row[] = [$boton_detalle,$q->nom_tipdocumento.'-'.$q->serie.'-'.$q->numero_vent,$q->fecha_vent,$q->nomb_cliente,$q->doc_cliente,$q->moneda_vent=='S'?'Soles':'Dolares',$q->total_vent,$estado,$cobros,$q->pendiente_vent,$buttons,json_encode($detalle)];
     }

     $result['aaData'] = $row;
     return $result;
	}
	

  function getCobros($venta)
  {
    return $this->db->from('tb_cobro')
    ->select('SUM(monto_cobro) as cobros')
    ->where('cod_vent',$venta)
    ->group_by('cod_vent')
    ->get()->row()->cobros;
  }

  function getDetalle($venta)
  {
    $detalle = $this->db->from('tb_venta_detalle')
    ->join('tb_producto','tb_venta_detalle.cod_producto = tb_producto.cod_producto','left')
    ->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid','left')
    ->join('tb_marca','tb_marca.cod_marca = tb_producto.cod_marca','left')
    ->where('tb_venta_detalle.cod_vent',$venta)
    ->get()->result();

    foreach ($detalle as $d) {
      $d->series = $this->db->from('tb_producto_serie')
      ->where('cod_vent',$venta)
      ->where('cod_producto',$d->cod_producto)
      ->get()->result();
    }

    return $detalle;
  }

  function getTiposVentas()
  {
    return $this->db->from('tb_talonario')
    ->select('cod_talonario,siglas_talonario,nom_tipdocumento,serie,docclidni_talonario,doccliruc_talonario')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
    ->where('cod_puntoventa',$this->session->userdata('puntoventa'))
    ->where_in('siglas_talonario',['FC','TK'])
    ->where('est_talonario',1)
    ->get()->result();
  }

  function getAlmacenesDisponibles()
  {
    return $this->db->from('tb_puntoventa_almacen')
    ->select('tb_puntoventa_almacen.*,tb_almacen.nomb_almacen')
    ->join('tb_almacen','tb_puntoventa_almacen.cod_almacen = tb_almacen.cod_almacen')
    ->where('tb_puntoventa_almacen.cod_puntoventa',$this->session->userdata('puntoventa'))
    ->get()->result();
  }

  function getVendedores()
  {
    return $this->db->from('tb_usuario')
    ->get()->result();
  }

  function getPuntos()
  {
    return $this->db->from('tb_puntoventa')
    ->get()->result();
  }

  function getVenta($id)
  {
    $venta = $this->db->from('tb_venta')
    ->join('tb_tipo_pago','tb_venta.cod_tipopago = tb_tipo_pago.cod_tipopago')
    ->join('tb_tarjeta','tb_venta.cod_tarj = tb_tarjeta.cod_tarj','left')
    ->join('tb_caja','tb_venta.cod_caja = tb_caja.cod_caja')
    ->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')
    ->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
		->join('tb_puntoventa','tb_venta.cod_puntoventa = tb_puntoventa.cod_puntoventa')
		->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
    ->where('cod_vent',$id)
    ->get()->row();

    $venta->detalle = $this->getDetalle($id);
    return $venta;
  }

  function getCajaApertura()
  {
    $query = $this->db->from('tb_caja_apertura')
    ->join('tb_caja','tb_caja_apertura.cod_caja = tb_caja.cod_caja')
    ->join('tb_puntoventa_caja','tb_caja.cod_caja = tb_puntoventa_caja.cod_caja')
    ->where('fecha_apertura',date('Y-m-d'))
    ->where('cod_usu',$this->session->userdata('cod_usu'))
    ->where('horainicio_apertura <=',date('H:i:s'))
    ->where('horafin_apertura >=',date('H:i:s'))
    // ->where('horafin_apertura >=',date('H:i:s'))
    ->where('tb_puntoventa_caja.cod_puntoventa',$this->session->userdata('puntoventa'))
    ->where('estado_apertura','A')
    ->get();

    if ($query->num_rows() > 0) {
      return $query->row();
    }else{
      return false;
    }
  }


    function getImpresionVenta($archivoxml)
      {
        $tb_venta= $this->db->from('tb_venta')
       ->select("tb_venta.*, fecha_vent, nom_tipdocumento, serie, nomb_cliente, direc_cliente, doc_cliente, email_cliente, telf_cliente, contac_cliente,CASE pago_vent WHEN 'CO' THEN 'CONTADO' ELSE 'CREDITO' END as tipopago, nomb_caja,hash_vent,codsunat_tipdocucli,nomb_usu,apell_usu, tb_puntoventa.*,ubigeo_distritos.nombre as distrito,ubigeo_provincias.nombre as provincia, ubigeo_departamentos.nombre as departamento")
				->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')
				->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
				->join('tb_caja','tb_venta.cod_caja = tb_caja.cod_caja')
				->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
				->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
				->join('tb_usuario','tb_venta.cod_usu = tb_usuario.cod_usu')
        ->join('tb_puntoventa','tb_venta.cod_puntoventa = tb_puntoventa.cod_puntoventa')
        ->join('ubigeo_distritos','tb_puntoventa.ubigeo_puntoventa = ubigeo_distritos.id')
        ->join('ubigeo_provincias','ubigeo_provincias.id = ubigeo_distritos.provincia_id')
        ->join('ubigeo_departamentos','ubigeo_departamentos.id = ubigeo_distritos.departamento_id')
				->where('archivoxml_vent',$archivoxml)
				->or_where('noxml_vent',$archivoxml)
        ->get()->row();

          $tb_venta->detalle =  $this->db->from('tb_venta_detalle')
        ->select('tb_venta_detalle.*,tb_producto.cod_producto, tb_producto.nomb_product, tb_unidades.abreviatura_unid')
        ->join('tb_producto','tb_venta_detalle.cod_producto = tb_producto.cod_producto','left')
        ->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid','left')
        ->where('tb_venta_detalle.cod_vent',$tb_venta->cod_vent)
        ->get()->result();

        return $tb_venta;

      }

function getDocumentosCliente()
  {
    return $this->db->from('tb_tipodocumentocliente')
    ->where_in('codsunat_tipdocucli',['1','6'])
    ->get()
    ->result();
  }
}