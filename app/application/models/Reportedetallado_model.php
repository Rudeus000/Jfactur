<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportedetallado_model extends CI_Model {

	function getCompras($data)
	{
		$this->db->from('tb_compra_detalle');
		$this->db->select("IFNULL(CONCAT(tb_producto.cod_producto,'-',serie_descripcion),CONCAT(tb_producto.cod_producto,'-null')) as code,fecha_comp,tb_compra.cod_almacen,nomb_almacen,tb_compra.tb_proveedor_id,tb_proveedor_nom,documento_comp,numdocumento_comp,tb_compra_detalle.cod_producto,nomb_product,cant_compdet,serie_descripcion,
		precunit_compdet,subtotal_compdet,
		CASE 
			WHEN serie_descripcion IS NULL THEN SUM(cant_compdet)
			WHEN serie_descripcion IS NOT NULL THEN '1'
    END as ingreso,
    CASE 
      WHEN histcompstock_serie IS NULL THEN historialstock_compdet
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'D' THEN 1 
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'N' THEN 0
    END as stock,
		CASE 
			WHEN serie_descripcion IS NULL THEN SUM(subtotal_compdet)
			WHEN serie_descripcion IS NOT NULL THEN precunit_compdet 
		END as subtotal
		",FALSE);
		$this->db->join('tb_compra','tb_compra_detalle.cod_comp = tb_compra.cod_comp');
		$this->db->join('tb_almacen','tb_compra.cod_almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
		$this->db->join('tb_producto','tb_compra_detalle.cod_producto = tb_producto.cod_producto');
		$this->db->join('tb_producto_serie','tb_compra_detalle.cod_comp = tb_producto_serie.cod_comp AND tb_producto.cod_producto = tb_producto_serie.cod_producto','left');
		$this->db->where('fecha_comp >= ',$data['desde']);
		$this->db->where('fecha_comp <=',$data['hasta']);
		$this->db->where('estado_comp',1);		
		$this->db->group_by('code');

		if ($data['proveedor']!='') {
		$this->db->like('tb_proveedor_nom',$data['proveedor']);
			}
			if ($data['almacen']!='') {
		$this->db->like('nomb_almacen',$data['almacen']);
		}
		$queryLike = $this->db->get();
		
		$total = 0;
		foreach ($queryLike->result() as $ql) {
			$total += $ql->subtotal;
		}


		$this->db->from('tb_compra_detalle');
		$this->db->select("IFNULL(CONCAT(tb_producto.cod_producto,'-',serie_descripcion),CONCAT(tb_producto.cod_producto,'-null')) as code,fecha_comp,tb_compra.cod_almacen,nomb_almacen,tb_compra.tb_proveedor_id,tb_proveedor_nom,documento_comp,numdocumento_comp,tb_compra_detalle.cod_producto,nomb_product,cant_compdet,serie_descripcion,
		precunit_compdet,subtotal_compdet,
		CASE 
			WHEN serie_descripcion IS NULL THEN SUM(cant_compdet)
			WHEN serie_descripcion IS NOT NULL THEN '1'
    END as ingreso,
    CASE 
      WHEN histcompstock_serie IS NULL THEN historialstock_compdet
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'D' THEN 1 
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'N' THEN 0
		END as stock,
		CASE 
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'D' THEN 0 
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'N' THEN 1
    END as ventas,
		CASE 
			WHEN serie_descripcion IS NULL THEN SUM(subtotal_compdet)
			WHEN serie_descripcion IS NOT NULL THEN precunit_compdet 
		END as subtotal
		",FALSE);
		$this->db->join('tb_compra','tb_compra_detalle.cod_comp = tb_compra.cod_comp');
		$this->db->join('tb_almacen','tb_compra.cod_almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
		$this->db->join('tb_producto','tb_compra_detalle.cod_producto = tb_producto.cod_producto');
		$this->db->join('tb_producto_serie','tb_compra_detalle.cod_comp = tb_producto_serie.cod_comp AND tb_producto.cod_producto = tb_producto_serie.cod_producto','left');
		$this->db->where('fecha_comp >= ',$data['desde']);
		$this->db->where('fecha_comp <=',$data['hasta']);
		$this->db->where('estado_comp',1);
		$this->db->group_by('code');
		if ($data['proveedor']!='') {
		$this->db->like('tb_proveedor_nom',$data['proveedor']);
			}
			if ($data['almacen']!='') {
		$this->db->like('nomb_almacen',$data['almacen']);
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
		$result['total'] = $total;

    $row = [];
    foreach ($query->result() as $q) {

			$boton_detalle = '';
			$q->historial = null;
			if(is_null($q->serie_descripcion)){
				$q->historial = $this->getHistorialCompras($q->cod_almacen,$q->tb_proveedor_id,$q->cod_producto,$data['desde'],$data['hasta']);
				$q->historial = json_encode($q->historial);
				$q->ventas = $this->getVentasEnCompra($q->cod_producto,$data['desde'],$data['hasta']);
				$q->stock =  $q->ingreso - $q->ventas;
				$boton_detalle = '<button class="btn btn-icon waves-effect waves-light btn-success" ><span class="fa fa-caret-right"></span></button>';
			}
			
			$row[] = [$boton_detalle,$q->fecha_comp,$q->nomb_almacen,$q->tb_proveedor_nom,$q->documento_comp,$q->numdocumento_comp,$q->nomb_product,$q->serie_descripcion,$q->ingreso,$q->stock,$q->ventas,$q->precunit_compdet,$q->subtotal,$q->historial];
		}

		$result['aaData'] = $row;
		return $result;
	}

	function getHistorialCompras($almacen,$proveedor,$producto,$desde,$hasta)
	{
		return $this->db->from('tb_compra')
		->select('tb_compra.cod_comp,fecha_comp,cant_compdet')
		->join('tb_compra_detalle','tb_compra.cod_comp = tb_compra_detalle.cod_comp')
		->join('tb_producto','tb_compra_detalle.cod_producto = tb_producto.cod_producto')
		->join('tb_producto_serie','tb_compra_detalle.cod_comp = tb_producto_serie.cod_comp AND tb_producto.cod_producto = tb_producto_serie.cod_producto','left')
		->where('fecha_comp >= ',$desde)
		->where('fecha_comp <= ',$hasta)
		->where('tb_compra.cod_almacen',$almacen)
		->where('tb_compra.tb_proveedor_id',$proveedor)
		->where('tb_compra_detalle.cod_producto',$producto)
		->where('tb_producto_serie.serie_descripcion IS NULL')
		->get()->result();
	}

	function getVentasEnCompra($producto,$desde,$hasta)
	{
		$query = $this->db->from('tb_venta')
		->select('tb_venta.cod_vent,fecha_vent,cant_ventdet,serie_descripcion,SUM(cant_ventdet) as ventas')
		->join('tb_venta_detalle','tb_venta.cod_vent = tb_venta_detalle.cod_vent')
		->join('tb_producto','tb_venta_detalle.cod_producto = tb_producto.cod_producto')
		->join('tb_producto_serie','tb_venta_detalle.cod_vent = tb_producto_serie.cod_vent AND tb_producto.cod_producto = tb_producto_serie.cod_producto','left')
		->where('fecha_vent >= ',$desde)
		->where('fecha_vent <= ',$hasta)
		->where('tb_venta_detalle.cod_producto',$producto)
		->where('tb_producto_serie.serie_descripcion IS NULL')
		->group_by('tb_producto.cod_producto')
		->get();

		if($query->num_rows()>0){
			return $query->row()->ventas;
		}else{
			return 0;
		}
	}
	
	function getComprasDetalladasExcel($data)
	{$this->db->from('tb_compra_detalle');
		$this->db->select("IFNULL(CONCAT(tb_producto.cod_producto,'-',serie_descripcion),CONCAT(tb_producto.cod_producto,'-null')) as code,fecha_comp,tb_compra.cod_almacen,nomb_almacen,tb_compra.tb_proveedor_id,tb_proveedor_nom,documento_comp,numdocumento_comp,tb_compra_detalle.cod_producto,nomb_product,cant_compdet,serie_descripcion,
		precunit_compdet,subtotal_compdet,
		CASE 
			WHEN serie_descripcion IS NULL THEN SUM(cant_compdet)
			WHEN serie_descripcion IS NOT NULL THEN '1'
    END as ingreso,
    CASE 
      WHEN histcompstock_serie IS NULL THEN historialstock_compdet
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'D' THEN 1 
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'N' THEN 0
		END as stock,
		CASE 
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'D' THEN 0 
      WHEN histcompstock_serie IS NOT NULL AND serie_Estado = 'N' THEN 1
    END as ventas,
		CASE 
			WHEN serie_descripcion IS NULL THEN SUM(subtotal_compdet)
			WHEN serie_descripcion IS NOT NULL THEN precunit_compdet 
		END as subtotal
		",FALSE);
		$this->db->join('tb_compra','tb_compra_detalle.cod_comp = tb_compra.cod_comp');
		$this->db->join('tb_almacen','tb_compra.cod_almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
		$this->db->join('tb_producto','tb_compra_detalle.cod_producto = tb_producto.cod_producto');
		$this->db->join('tb_producto_serie','tb_compra_detalle.cod_comp = tb_producto_serie.cod_comp AND tb_producto.cod_producto = tb_producto_serie.cod_producto','left');
		$this->db->where('fecha_comp >= ',$data['desde']);
		$this->db->where('fecha_comp <=',$data['hasta']);
		$this->db->where('estado_comp',1);
		$this->db->group_by('code');
		if ($data['proveedor']!='') {
		$this->db->like('tb_proveedor.tb_proveedor_nom',$data['proveedor']);
			}
			if ($data['almacen']!='') {
		$this->db->like('nomb_almacen',$data['almacen']);
			}
			
		$query = $this->db->get()->result();

		foreach ($query as $q) {
			$boton_detalle = '';
			if(is_null($q->serie_descripcion)){
				$q->historial = $this->getHistorialCompras($q->cod_almacen,$q->tb_proveedor_id,$q->cod_producto,$data['desde'],$data['hasta']);
				$q->ventas = $this->getVentasEnCompra($q->cod_producto,$data['desde'],$data['hasta']);
				$q->stock =  $q->ingreso - $q->ventas;
				$boton_detalle = '<button class="btn btn-icon waves-effect waves-light btn-success" ><span class="fa fa-caret-right"></span></button>';
			}
		}

		return $query;
	}

	function getVentas($data)
	{
		$this->db->from('tb_venta_detalle');
		$this->db->select("fecha_vent,nomb_almacen,nomb_puntoventa,doc_cliente,nomb_cliente,nom_tipdocucli,doc_cliente,nom_tipdocumento,serie,numero_vent,CONCAT(apell_usu, ' ', nomb_usu) as nombre_apellido,producto_ventdet,precunit_ventdet,descuento_ventdet,(precunit_ventdet - descuento_ventdet) as precunit_con_descuento,cant_ventdet,subtotal_ventdet,serie_ventdetserie,
		CASE 
			WHEN serie_ventdetserie IS NULL THEN cant_ventdet
			WHEN serie_ventdetserie IS NOT NULL THEN '1'
		END as cantidad,
		CASE 
			WHEN serie_ventdetserie IS NULL THEN subtotal_ventdet
			WHEN serie_ventdetserie IS NOT NULL THEN (precunit_ventdet - descuento_ventdet) 
		END as subtotal
		
		",FALSE);
		$this->db->join('tb_venta','tb_venta_detalle.cod_vent = tb_venta.cod_vent');
		$this->db->join('tb_almacen','tb_venta.cod_almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_puntoventa','tb_venta.cod_puntoventa = tb_puntoventa.cod_puntoventa');
		$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
		$this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		$this->db->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli');
		$this->db->join('tb_usuario','tb_venta.cod_usu = tb_usuario.cod_usu');
		$this->db->join('tb_venta_detalle_serie','tb_venta_detalle.cod_ventdet = tb_venta_detalle_serie.cod_ventdet','left');
		$this->db->where('fecha_vent >= ',$data['desde']);
		$this->db->where('fecha_vent <=',$data['hasta']);
		$this->db->where('estado_vent','G');
		if ($this->session->userdata('puntoventa_reportes')!='admin') {
			$this->db->where('tb_venta.cod_puntoventa',$this->session->userdata('puntoventa_reportes'));
		}
		if ($data['almacen']!='') {
      $this->db->like('nomb_almacen',$data['almacen']);
		}
		if ($data['cliente']!='') {
      $this->db->like('nomb_cliente',$data['cliente']);
		}
		if ($data['vendedor'] != '') {
			$this->db->where('tb_venta.cod_usu', $data['vendedor']);
		  }
	// 	if($data['vendedor']!=''){
    //   $this->db->having("nombre_apellido LIKE '%".$data['vendedor']."%'");
    // }
		$queryLike = $this->db->get();
		
		$total = 0;
		foreach ($queryLike->result() as $ql) {
			$total += $ql->subtotal;
		}


		$this->db->from('tb_venta_detalle');
		$this->db->select("fecha_vent,nomb_almacen,nomb_puntoventa,nomb_cliente,nom_tipdocucli,doc_cliente,nom_tipdocumento,serie,numero_vent,CONCAT(apell_usu, ' ', nomb_usu) as nombre_apellido,producto_ventdet,producto_isdn,precunit_ventdet,descuento_ventdet,(precunit_ventdet - descuento_ventdet) as precunit_con_descuento,cant_ventdet,subtotal_ventdet,serie_ventdetserie,
		CASE 
			WHEN serie_ventdetserie IS NULL THEN cant_ventdet
			WHEN serie_ventdetserie IS NOT NULL THEN '1'
		END as cantidad,
		CASE 
			WHEN serie_ventdetserie IS NULL THEN subtotal_ventdet
			WHEN serie_ventdetserie IS NOT NULL THEN (precunit_ventdet - descuento_ventdet) 
		END as subtotal
		
		",FALSE);
		$this->db->join('tb_venta','tb_venta_detalle.cod_vent = tb_venta.cod_vent');
		$this->db->join('tb_almacen','tb_venta.cod_almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_puntoventa','tb_venta.cod_puntoventa = tb_puntoventa.cod_puntoventa');
		$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
		$this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		$this->db->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli');
		$this->db->join('tb_usuario','tb_venta.cod_usu = tb_usuario.cod_usu');
		$this->db->join('tb_venta_detalle_serie','tb_venta_detalle.cod_ventdet = tb_venta_detalle_serie.cod_ventdet','left');
		$this->db->where('fecha_vent >= ',$data['desde']);
		$this->db->where('fecha_vent <=',$data['hasta']);
		$this->db->where('estado_vent','G');
		if ($this->session->userdata('puntoventa_reportes')!='admin') {
			$this->db->where('tb_venta.cod_puntoventa',$this->session->userdata('puntoventa_reportes'));
		}
		if ($data['almacen']!='') {
		$this->db->like('nomb_almacen',$data['almacen']);
			}
			if ($data['cliente']!='') {
		$this->db->like('nomb_cliente',$data['cliente']);
			}
			if ($data['vendedor'] != '') {
				$this->db->where('tb_venta.cod_usu', $data['vendedor']);
			  }
		// 	if($data['vendedor']!=''){
		// $this->db->having("nombre_apellido LIKE '%".$data['vendedor']."%'");
   		//  }

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
		$result['total'] = $total;
		
		$row = [];
    foreach ($query->result() as $q) {
			$row[] = [$q->fecha_vent,$q->nomb_almacen,$q->nomb_puntoventa,$q->doc_cliente,$q->nomb_cliente,$q->nom_tipdocumento.'-'.$q->serie.'-'.$q->numero_vent,$q->nombre_apellido,$q->producto_ventdet,$q->producto_isdn,$q->serie_ventdetserie,$q->precunit_ventdet,$q->descuento_ventdet,$q->precunit_con_descuento,$q->cantidad,$q->subtotal];
		}

		$result['aaData'] = $row;
		return $result;
	}
	function getVendedores()
	{
	  return $this->db->from('tb_usuario')
		->get()->result();
	}

	function getVentasDetalladasExcel($data)
	{
		$this->db->from('tb_venta_detalle');
		$this->db->select("fecha_vent,nomb_almacen,nomb_puntoventa,nomb_cliente,nom_tipdocucli,doc_cliente,nom_tipdocumento,serie,numero_vent,CONCAT(apell_usu, ' ', nomb_usu) as nombre_apellido,producto_ventdet,producto_isdn,precunit_ventdet,descuento_ventdet,(precunit_ventdet - descuento_ventdet) as precunit_con_descuento,cant_ventdet,subtotal_ventdet,serie_ventdetserie,estado_vent,observacion_vent,
		CASE 
			WHEN serie_ventdetserie IS NULL THEN cant_ventdet
			WHEN serie_ventdetserie IS NOT NULL THEN '1'
		END as cantidad,
		CASE 
			WHEN serie_ventdetserie IS NULL THEN subtotal_ventdet
			WHEN serie_ventdetserie IS NOT NULL THEN (precunit_ventdet - descuento_ventdet) 
		END as subtotal
		
		",FALSE);
		$this->db->join('tb_venta','tb_venta_detalle.cod_vent = tb_venta.cod_vent');
		$this->db->join('tb_almacen','tb_venta.cod_almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_puntoventa','tb_venta.cod_puntoventa = tb_puntoventa.cod_puntoventa');
		$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
		$this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		$this->db->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli');
		$this->db->join('tb_usuario','tb_venta.cod_usu = tb_usuario.cod_usu');
		$this->db->join('tb_venta_detalle_serie','tb_venta_detalle.cod_ventdet = tb_venta_detalle_serie.cod_ventdet','left');
		$this->db->where('fecha_vent >= ',$data['desde']);
		$this->db->where('fecha_vent <=',$data['hasta']);
		$this->db->where_in('estado_vent',['G','A']);		
		if ($this->session->userdata('puntoventa_reportes')!='admin') {
			$this->db->where('tb_venta.cod_puntoventa',$this->session->userdata('puntoventa_reportes'));
		}
		if ($data['almacen']!='') {
      $this->db->like('nomb_almacen',$data['almacen']);
		}
		if ($data['cliente']!='') {
      $this->db->like('nomb_cliente',$data['cliente']);
		}
		if ($data['vendedor'] != '') {
			$this->db->where('tb_venta.cod_usu', $data['vendedor']);
		  }
	// 	if($data['vendedor']!=''){
    //   $this->db->having("nombre_apellido LIKE '%".$data['vendedor']."%'");
    // }
		return $this->db->get()->result();
	}

}

/* End of file ModelName.php */
