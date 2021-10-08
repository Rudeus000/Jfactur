<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportcotiproductos_model extends CI_Model {

	function getreportcotiproductos($data)
	{
		$this->db->from('tb_cotizacion_detalle as tb');
		$this->db->select('tb.cod_producto, p.nomb_product, m.nomb_marca,ca.nomb_categoria, u.nomb_unid, SUM(tb.cant_cotdet) as cantidad, SUM(tb.subtotal_cotdet) as total');

		$this->db->join('tb_cotizacion as c','tb.cod_cot = c.cod_cot');
		$this->db->join('tb_producto as p','tb.cod_producto = p.cod_producto');
		$this->db->join('tb_marca as m','p.cod_marca = m.cod_marca');
		$this->db->join('tb_categoria ca','p.cod_categoria = ca.cod_categoria');
		$this->db->join('tb_unidades u','p.cod_unid = u.cod_unid');
		$this->db->group_by('tb.cod_producto');
		$this->db->where('c.estado_cot','G');
		$this->db->where('p.est_product',1);
    	//$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('c.fecha_cot >=',$data['desde']);
			$this->db->where('c.fecha_cot <=',$data['hasta']);
		}

		if ($data['tb_producto']!='') {
        $this->db->like('p.nomb_product',$data['tb_producto']);
         }

        if (isset($data['tb_marca'])) {
  		$this->db->where('m.cod_marca',$data['tb_marca']);
        }
        if (isset($data['tb_categoria'])) {
  		$this->db->where('ca.cod_categoria',$data['tb_categoria']);
         }
  	
		$queryLike = $this->db->get();

		$total = 0;
		$totales = 0;

		foreach ($queryLike->result() as $r) {
			$total += $r->cantidad;
			$totales += $r->total;
		}

	    $this->db->from('tb_cotizacion_detalle as tb');
		$this->db->select('tb.cod_producto, p.nomb_product, m.nomb_marca,ca.nomb_categoria, u.nomb_unid, SUM(tb.cant_cotdet) as cantidad, SUM(tb.subtotal_cotdet) as total');

		$this->db->join('tb_cotizacion as c','tb.cod_cot = c.cod_cot');
		$this->db->join('tb_producto as p','tb.cod_producto = p.cod_producto');
		$this->db->join('tb_marca as m','p.cod_marca = m.cod_marca');
		$this->db->join('tb_categoria ca','p.cod_categoria = ca.cod_categoria');
		$this->db->join('tb_unidades u','p.cod_unid = u.cod_unid');
		$this->db->group_by('tb.cod_producto');
		$this->db->where('c.estado_cot','G');
		$this->db->where('p.est_product',1);
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('c.fecha_cot >=',$data['desde']);
			$this->db->where('c.fecha_cot <=',$data['hasta']);
		}

		if ($data['tb_producto']!='') {
        $this->db->like('p.nomb_product',$data['tb_producto']);
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
		$row = [];
		foreach ($query->result() as $q) {
			// $abono = $this->getAbonos($q->tb_proveedor_id);
			// $saldo = $q->monto - $abono;
			// $buttons = '<a class="btn btn-xs btn-success" href="'.base_url('administrador/regcuentascobrar/detalle/'.$q->tb_proveedor_id).'"><i class="fa fa-plus"></i> Detalle</a>';
			$row[] = [$q->cod_producto,$q->nomb_product,$q->nomb_marca,$q->nomb_categoria,$q->nomb_unid,$q->cantidad,$q->total];
		}

		$result['aaData'] = $row;
		return $result;
	}



	

  }


/* End of file Cuentascobrar_model.php */
/* Location: ./application/models/Cuentascobrar_model.php */