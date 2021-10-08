<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportcompras_model extends CI_Model {

	function getreportcompras($data)
	{
		$this->db->from('tb_compra');
		$this->db->select('fecha_comp, CONCAT(documento_comp," ",numdocumento_comp) as documento,tb_proveedor_doc,tb_proveedor_nom, total_comp');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id','left');
    	$this->db->order_by('tb_compra.fecha_comp','desc');			
        
    if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_comp >=',$data['desde']);
			$this->db->where('fecha_comp <=',$data['hasta']);			
		}

		if ($data['proveedor']!='') {
			$this->db->like('tb_proveedor_nom',$data['proveedor']);
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
			$total += $r->total_comp;
		}

		$this->db->from('tb_compra');
		$this->db->select('tb_compra.cod_comp,fecha_comp, CONCAT(documento_comp," ",numdocumento_comp) as documento,tb_proveedor_doc,tb_proveedor_nom,total_comp');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id','left');
    	$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_comp >=',$data['desde']);
			$this->db->where('fecha_comp <=',$data['hasta']);
		}

		if ($data['proveedor']!='') {
			$this->db->like('tb_proveedor_nom',$data['proveedor']);
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
			
			$queryPagos = $this->getPagos($q->cod_comp);

			$pagos = "<table class='table table-bordered'
									<tr>
										<th class='bg-primary'>Caja</th>
										<th class='bg-primary'>Monto</th>
									</tr>";
			foreach ($queryPagos as $p) {
				$pagos .= "
				<tr>
					<td>{$p->nomb_caja}</td>
					<td>{$p->monto_pago}</td>
				</tr>
				";
			}

			$pagos .= "</table>";
			$row[] = [$q->fecha_comp,$q->documento,$q->tb_proveedor_doc,$q->tb_proveedor_nom,$q->total_comp,$pagos];
		}

		$result['aaData'] = $row;
		return $result;
	}


	function getPagos($id)
	{
		return $this->db->from('tb_pago')
		->join('tb_caja','tb_pago.cod_caja = tb_caja.cod_caja')
		->where('cod_comp',$id)
		->get()->result();
	}
	

 }


/* End of file Cuentascobrar_model.php */
/* Location: ./application/models/Cuentascobrar_model.php */