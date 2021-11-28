<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuentaspagar_model extends CI_Model {

	function getCuentas($data)
	{
		$this->db->from('tb_compra');
		$this->db->select('tb_proveedor_nom,tb_proveedor_doc,SUM(saldo_comp) as monto');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
		$this->db->group_by('tb_compra.tb_proveedor_id');
		$this->db->where('estado_comp',1);
		$this->db->where('pendiente_comp > ',0);
		$queryTotal = $this->db->get();

		$this->db->from('tb_compra');
		$this->db->select('tb_proveedor_nom,tb_proveedor_doc,SUM(saldo_comp) as monto');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
		$this->db->group_by('tb_compra.tb_proveedor_id');
		$this->db->where('estado_comp',1);
		$this->db->where('pendiente_comp > ',0);
		if ($data['proveedor']!='') {
			$this->db->like('tb_proveedor_nom',$data['proveedor']);
		}
		$queryLike = $this->db->get();

		$this->db->from('tb_compra');
		$this->db->select('tb_compra.tb_proveedor_id,tb_proveedor_nom,tb_proveedor_doc,SUM(saldo_comp) as monto');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
		$this->db->group_by('tb_compra.tb_proveedor_id');
		$this->db->where('estado_comp',1);
		$this->db->where('pendiente_comp > ',0);
		if ($data['proveedor']!='') {
			$this->db->like('tb_proveedor_nom',$data['proveedor']);
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
		$result['iTotalRecords'] = $queryTotal->num_rows();
		$result['iTotalDisplayRecords'] = $query->num_rows();

		$row = [];
		foreach ($query->result() as $q) {
			$abono = $this->getAbonos($q->tb_proveedor_id);
			$saldo = $q->monto - $abono;
			$buttons = '<a class="btn btn-xs btn-success" href="'.base_url('administrador/regcuentaspagar/detalle/'.$q->tb_proveedor_id).'"><i class="fa fa-plus"></i> Detalle</a>';
			$row[] = [$q->tb_proveedor_nom,$q->tb_proveedor_doc,$q->monto,$abono,$saldo,$buttons];
		}

		$result['aaData'] = $row;
		return $result;
	}

	function getAbonos($proveedor)
	{
		return $this->db->from('tb_pago')
		->select('SUM(monto_pago) as abono')
		->join('tb_compra','tb_pago.cod_comp = tb_compra.cod_comp')
		->where('tb_proveedor_id',$proveedor)
		->where('tipo_pago','CRE')
		->group_by('tb_proveedor_id')
		->get()->row()->abono;
	}

}

/* End of file Cuentaspagar_model.php */
/* Location: ./application/models/Cuentaspagar_model.php */