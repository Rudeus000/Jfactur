

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportcomprov_model extends CI_Model {


function getreportcomproveedor($data)
	{
		$this->db->from('tb_compra');
		$this->db->select('tb_proveedor_doc,tb_proveedor_nom,SUM(total_comp) as monto');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
    	$this->db->group_by('tb_compra.tb_proveedor_id');
    	$this->db->where('tb_proveedor_xac',1);
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_comp >=',$data['desde']);
			$this->db->where('fecha_comp <=',$data['hasta']);
		}

		if ($data['tb_proveedor']!='') {
        $this->db->like('tb_proveedor_nom',$data['tb_proveedor']);
         }

		if ($data['estado']=='1') {
			$this->db->where('estado_comp','1');
		}
		if($data['estado']=='2'){
			$this->db->where('estado_comp','2');
		}

		$queryLike = $this->db->get();

		$total = 0;
		foreach ($queryLike->result() as $r) {
			$total += $r->monto;
		}

		$this->db->from('tb_compra');
		$this->db->select('tb_proveedor_doc,tb_proveedor_nom,SUM(total_comp) as monto');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
    	$this->db->group_by('tb_compra.tb_proveedor_id');
    	$this->db->where('tb_proveedor_xac',1);
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_comp >=',$data['desde']);
			$this->db->where('fecha_comp <=',$data['hasta']);
		}

		if ($data['tb_proveedor']!='') {
        $this->db->like('tb_proveedor_nom',$data['tb_proveedor']);
         }

		if ($data['estado']=='1') {
			$this->db->where('estado_comp','1');
		}
		if($data['estado']=='2'){
			$this->db->where('estado_comp','2');
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
			// $abono = $this->getAbonos($q->tb_proveedor_id);
			// $saldo = $q->monto - $abono;
			// $buttons = '<a class="btn btn-xs btn-success" href="'.base_url('administrador/regcuentascobrar/detalle/'.$q->tb_proveedor_id).'"><i class="fa fa-plus"></i> Detalle</a>';
			$row[] = [$q->tb_proveedor_doc,$q->tb_proveedor_nom,$q->monto];
		}

		$result['aaData'] = $row;
		return $result;
	}
}