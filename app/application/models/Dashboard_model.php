<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
	function getKardex($data)
	{
		$this->db->from('tb_venta_detalle');
		$this->db->select('nomb_puntoventa,nomb_product,nomb_tiparticulo,nomb_unid,SUM(prec_ventdet + igv_ventdet) as total_ingresos,SUM(prec_ventdet) as valor_ingresos');
		$this->db->join('tb_producto','tb_venta_detalle.cod_producto = tb_producto.cod_producto','left');
		$this->db->join('tb_tiparticulo','tb_producto.cod_tiparticulo = tb_tiparticulo.cod_tiparticulo');
		$this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid','left');
		$this->db->join('tb_venta','tb_venta.cod_vent = tb_venta_detalle.cod_vent');
		$this->db->join('tb_puntoventa','tb_venta.cod_puntoventa = tb_puntoventa.cod_puntoventa');
		$this->db->where('tb_venta.estado_vent','G');
		$this->db->group_by('tb_venta_detalle.cod_producto,tb_puntoventa.cod_puntoventa');
		$queryTotal = $this->db->get();

		$this->db->from('tb_venta_detalle');
		$this->db->select('nomb_puntoventa,nomb_product,nomb_tiparticulo,nomb_unid,SUM(prec_ventdet + igv_ventdet) as total_ingresos,SUM(prec_ventdet) as valor_ingresos');
		$this->db->join('tb_producto','tb_venta_detalle.cod_producto = tb_producto.cod_producto','left');
		$this->db->join('tb_tiparticulo','tb_producto.cod_tiparticulo = tb_tiparticulo.cod_tiparticulo');
		$this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid','left');
		$this->db->join('tb_venta','tb_venta.cod_vent = tb_venta_detalle.cod_vent');
		$this->db->join('tb_puntoventa','tb_venta.cod_puntoventa = tb_puntoventa.cod_puntoventa');
		$this->db->where('tb_venta.estado_vent','G');
		$this->db->group_by('tb_venta_detalle.cod_producto,tb_puntoventa.cod_puntoventa');
		$queryLike = $this->db->get();


		$this->db->from('tb_venta_detalle');
		$this->db->select('nomb_puntoventa,nomb_product,nomb_tiparticulo,nomb_unid,SUM(prec_ventdet + igv_ventdet) as total_ingresos,SUM(prec_ventdet) as valor_ingresos');
		$this->db->join('tb_producto','tb_venta_detalle.cod_producto = tb_producto.cod_producto','left');
		$this->db->join('tb_tiparticulo','tb_producto.cod_tiparticulo = tb_tiparticulo.cod_tiparticulo');
		$this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid','left');
		$this->db->join('tb_venta','tb_venta.cod_vent = tb_venta_detalle.cod_vent');
		$this->db->join('tb_puntoventa','tb_venta.cod_puntoventa = tb_puntoventa.cod_puntoventa');
		$this->db->where('tb_venta.estado_vent','G');
		$this->db->group_by('tb_venta_detalle.cod_producto,tb_puntoventa.cod_puntoventa');
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
    $result['iTotalDisplayRecords'] = $queryTotal->num_rows();

		$row = [];

    foreach ($query->result() as $q) {
      $row[] = [$q->nomb_puntoventa,$q->nomb_product,$q->nomb_tiparticulo,$q->unidad_ventdet,$q->total_ingresos,$q->valor_ingresos];
		}

		$result['aaData'] = $row;
		return $result;
	}
}
