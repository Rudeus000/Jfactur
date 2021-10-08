

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportventasclientes_model extends CI_Model {


function getreportventclientes($data)
	{
		$this->db->from('tb_venta');
		$this->db->select('doc_cliente,nomb_cliente,SUM(total_vent) as monto');
		$this->db->join('tb_cliente','tb_cliente.id_cliente = tb_venta.id_cliente');
    	$this->db->group_by('tb_venta.id_cliente');
    	$this->db->where('estado_cliente',1);
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_vent >=',$data['desde']);
			$this->db->where('fecha_vent <=',$data['hasta']);
		}

		if ($data['tb_cliente']!='') {
        $this->db->like('nomb_cliente',$data['tb_cliente']);
         }

		if ($data['estado']=='G') {
			$this->db->where('estado_vent','G');
		}
		if($data['estado']=='A'){
			$this->db->where('estado_vent','A');
		}

		$queryLike = $this->db->get();

		$total = 0;
		foreach ($queryLike->result() as $r) {
			$total += $r->monto;
		}

		$this->db->from('tb_venta');
		$this->db->select('doc_cliente,nomb_cliente,SUM(total_vent) as monto');
		$this->db->join('tb_cliente','tb_cliente.id_cliente = tb_venta.id_cliente');
    	$this->db->group_by('tb_venta.id_cliente');
    	$this->db->where('estado_cliente',1);
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_vent >=',$data['desde']);
			$this->db->where('fecha_vent <=',$data['hasta']);
		}

		if ($data['tb_cliente']!='') {
        $this->db->like('nomb_cliente',$data['tb_cliente']);
         }

		if ($data['estado']=='G') {
			$this->db->where('estado_vent','G');
		}
		if($data['estado']=='A'){
			$this->db->where('estado_vent','A');
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
			
			$row[] = [$q->doc_cliente,$q->nomb_cliente,$q->monto];
		}

		$result['aaData'] = $row;
		return $result;
	}
}