<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportventasproductos_model extends CI_Model {

	function getreportventproductos($data)
	{
		$this->db->from('tb_venta_detalle as tb');
		$this->db->select('tb.cod_producto, p.nomb_product, m.nomb_marca,ca.nomb_categoria, u.nomb_unid, SUM(tb.cant_ventdet) as cantidad, tb.precunit_ventdet as precio, SUM(tb.subtotal_ventdet) as total');

		$this->db->join('tb_venta as v','tb.cod_vent = v.cod_vent');
		$this->db->join('tb_producto as p','tb.cod_producto = p.cod_producto');
		$this->db->join('tb_marca as m','p.cod_marca = m.cod_marca');
		$this->db->join('tb_categoria ca','p.cod_categoria = ca.cod_categoria');
		$this->db->join('tb_unidades u','p.cod_unid = u.cod_unid');
		$this->db->group_by('tb.cod_producto');
	//	$this->db->where('c.estado_comp',1);
		$this->db->where('p.est_product',1);
    	//$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('v.fecha_vent >=',$data['desde']);
			$this->db->where('v.fecha_vent <=',$data['hasta']);
		}

		if ($data['producto']!='') {
        $this->db->like('p.nomb_product',$data['producto']);
         }

         if ($data['estado']=='G') {
			$this->db->where('v.estado_vent','G');
		}
		if($data['estado']=='A'){
			$this->db->where('v.estado_vent','A');
		}

		if ($data['punto']!='') {
         $this->db->where('v.cod_puntoventa',$data['punto']);
         }

        if (isset($data['tb_marca'])) {
  		$this->db->where('m.cod_marca',$data['tb_marca']);
        }
        if (isset($data['tb_categoria'])) {
  		$this->db->where('ca.cod_categoria',$data['tb_categoria']);
         }
  	
		$queryLike = $this->db->get();

		$total = 0;
		$totalesprecios = 0;
		$totales = 0;

		foreach ($queryLike->result() as $r) {
			$total += $r->cantidad;
			$totalesprecios += $r->precio;
			$totales += $r->total;
		}

	   $this->db->from('tb_venta_detalle as tb');
	   $this->db->select('tb.cod_producto, p.nomb_product, m.nomb_marca,ca.nomb_categoria, u.nomb_unid, SUM(tb.cant_ventdet) as cantidad, tb.precunit_ventdet as precio, SUM(tb.subtotal_ventdet) as total');

		$this->db->join('tb_venta as v','tb.cod_vent = v.cod_vent');
		$this->db->join('tb_producto as p','tb.cod_producto = p.cod_producto');
		$this->db->join('tb_marca as m','p.cod_marca = m.cod_marca');
		$this->db->join('tb_categoria ca','p.cod_categoria = ca.cod_categoria');
		$this->db->join('tb_unidades u','p.cod_unid = u.cod_unid');
		$this->db->group_by('tb.cod_producto');
	//	$this->db->where('c.estado_comp',1);
		$this->db->where('p.est_product',1);
    	//$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('v.fecha_vent >=',$data['desde']);
			$this->db->where('v.fecha_vent <=',$data['hasta']);
		}

		if ($data['producto']!='') {
        $this->db->like('p.nomb_product',$data['producto']);
         }

         if ($data['estado']=='G') {
			$this->db->where('v.estado_vent','G');
		}
		if($data['estado']=='A'){
			$this->db->where('v.estado_vent','A');
		}

		if ($data['punto']!='') {
         $this->db->where('v.cod_puntoventa',$data['punto']);
         }

        if (isset($data['tb_marca'])) {
  		$this->db->where('m.cod_marca',$data['tb_marca']);
        }
        if (isset($data['tb_categoria'])) {
  		$this->db->where('ca.cod_categoria',$data['tb_categoria']);
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
		$result['totales'] = $totales;
		$result['totalesprecios'] = $totalesprecios;
		$row = [];
		foreach ($query->result() as $q) {
		
			$row[] = [$q->cod_producto,$q->nomb_product,$q->nomb_marca,$q->nomb_categoria,$q->nomb_unid,$q->cantidad,$q->precio,$q->total];
		}

		$result['aaData'] = $row;
		return $result;
	}


// Exportar a excel reporte ventas por producto
	function getreportventproductexcel($data){
		$this->db->from('tb_venta_detalle as tb');
	   $this->db->select('tb.cod_producto, p.nomb_product, m.nomb_marca,ca.nomb_categoria, u.nomb_unid, SUM(tb.cant_ventdet) as cantidad, tb.precunit_ventdet as precio, SUM(tb.subtotal_ventdet) as total');

		$this->db->join('tb_venta as v','tb.cod_vent = v.cod_vent');
		$this->db->join('tb_producto as p','tb.cod_producto = p.cod_producto');
		$this->db->join('tb_marca as m','p.cod_marca = m.cod_marca');
		$this->db->join('tb_categoria ca','p.cod_categoria = ca.cod_categoria');
		$this->db->join('tb_unidades u','p.cod_unid = u.cod_unid');
		$this->db->group_by('tb.cod_producto');
	//	$this->db->where('c.estado_comp',1);
		$this->db->where('p.est_product',1);
    	//$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('v.fecha_vent >=',$data['desde']);
			$this->db->where('v.fecha_vent <=',$data['hasta']);
		}

		if ($data['producto']!='') {
        $this->db->like('p.nomb_product',$data['producto']);
         }

         if ($data['estado']=='G') {
			$this->db->where('v.estado_vent','G');
		}
		if($data['estado']=='A'){
			$this->db->where('v.estado_vent','A');
		}

		if ($data['punto']!='') {
         $this->db->where('v.cod_puntoventa',$data['punto']);
         }

        if (isset($data['tb_marca'])) {
  		$this->db->where('m.cod_marca',$data['tb_marca']);
        }
        if (isset($data['tb_categoria'])) {
  		$this->db->where('ca.cod_categoria',$data['tb_categoria']);
         }
  		
		

		if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}
		if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

		return $this->db->get()->result();
	}

	

  }


/* End of file Cuentascobrar_model.php */
/* Location: ./application/models/Cuentascobrar_model.php */
