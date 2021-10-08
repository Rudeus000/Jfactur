<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuentascobrar_model extends CI_Model {

	function getCuentas($data)
	{
		$this->db->from('tb_venta');
		$this->db->select('tb_venta.id_cliente,nomb_cliente,doc_cliente,SUM(saldo_vent) as monto');
		$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->group_by('tb_venta.id_cliente');
		$this->db->where('estado_vent','G');
		$this->db->where('pendiente_vent > ',0);
		$queryTotal = $this->db->get();

		$this->db->from('tb_venta');
		$this->db->select('tb_venta.id_cliente,nomb_cliente,doc_cliente,SUM(saldo_vent) as monto');
		$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->group_by('tb_venta.id_cliente');
		$this->db->where('estado_vent','G');
		$this->db->where('pendiente_vent > ',0);
		if ($data['cliente']!='') {
			$this->db->like('nomb_cliente',$data['cliente']);
		}
		$queryLike = $this->db->get();

		$this->db->from('tb_venta');
		$this->db->select('tb_venta.id_cliente,nomb_cliente,doc_cliente,SUM(saldo_vent) as monto');
		$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->group_by('tb_venta.id_cliente');
		$this->db->where('estado_vent','G');
		$this->db->where('pendiente_vent > ',0);
		if ($data['cliente']!='') {
			$this->db->like('nomb_cliente',$data['cliente']);
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
			$abono = $this->getAbonos($q->id_cliente);
			$saldo = $q->monto - $abono;
			$buttons = '<a class="btn btn-xs btn-success" href="'.base_url('administrador/regcuentascobrar/detalle/'.$q->id_cliente).'"><i class="fa fa-plus"></i> Detalle</a>';
			$row[] = [$q->nomb_cliente,$q->doc_cliente,$q->monto,$abono,$saldo,$buttons];
		}

		$result['aaData'] = $row;
		return $result;
	}

	function getAbonos($cliente)
	{
		return $this->db->from('tb_cobro')
		->select('SUM(monto_cobro) as abono')
		->join('tb_venta','tb_cobro.cod_vent = tb_venta.cod_vent')
		->where('id_cliente',$cliente)
		->where('tipo_cobro','Credito')
		->group_by('id_cliente')
		->get()->row()->abono;
	}


}

/* End of file Cuentascobrar_model.php */
/* Location: ./application/models/Cuentascobrar_model.php */
