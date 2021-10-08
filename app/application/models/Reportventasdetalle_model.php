<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportventasdetalle_model extends CI_Model {

	function getreportventasdetalle($data)
	{
		$this->db->from('tb_venta v');
			$this->db->select('v.fecha_vent, CONCAT(t.serie, " - ", v.numero_vent) as documento,c.doc_cliente,c.nomb_cliente,v.total_vent,v.login_usu,v.estado_vent');
	    $this->db->join('tb_cliente as c','v.id_cliente = c.id_cliente','left');
		$this->db->join('tb_talonario as t','v.cod_talonario = t.cod_talonario','left');
		$this->db->join('tb_tipodocumento as td','t.cod_tipdocu = td.cod_tipdocu','left');
        $this->db->order_by('v.fecha_vent','desc');	
   		 if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('v.fecha_vent >=',$data['desde']);
			$this->db->where('v.fecha_vent <=',$data['hasta']);
		}

		if ($data['cliente']!='') {
        $this->db->like('c.nomb_cliente',$data['cliente']);
         }

         if ($data['vendedor']!='') {
         $this->db->where('v.cod_usu',$data['vendedor']);
         }

          if ($data['punto']!='') {
         $this->db->where('v.cod_puntoventa',$data['punto']);
         }
		
		if ($data['estado']=='G') {
			$this->db->where('v.estado_vent','G');
		}
		if($data['estado']=='A'){
			$this->db->where('v.estado_vent','A');
		}
		$queryLike = $this->db->get();

		$total = 0;
		foreach ($queryLike->result() as $r) {
			$total += $r->total_vent;
		}

		$this->db->from('tb_venta v');
		$this->db->select('v.cod_vent,v.fecha_vent, CONCAT(t.serie, " - ", v.numero_vent) as documento,c.doc_cliente,c.nomb_cliente,v.total_vent,v.login_usu,v.estado_vent');
	    $this->db->join('tb_cliente as c','v.id_cliente = c.id_cliente','left');
		$this->db->join('tb_talonario as t','v.cod_talonario = t.cod_talonario','left');
		$this->db->join('tb_tipodocumento as td','t.cod_tipdocu = td.cod_tipdocu','left');	
	    	$this->db->order_by('v.fecha_vent','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('v.fecha_vent >=',$data['desde']);
			$this->db->where('v.fecha_vent <=',$data['hasta']);
		}

		if ($data['cliente']!='') {
        $this->db->like('c.nomb_cliente',$data['cliente']);
         }

         if ($data['vendedor']!='') {
         $this->db->where('v.cod_usu',$data['vendedor']);
         }

    	 if ($data['punto']!='') {
         $this->db->where('v.cod_puntoventa',$data['punto']);
         }

		if ($data['estado']=='G') {
			$this->db->where('v.estado_vent','G');
		}
		if($data['estado']=='A'){
			$this->db->where('v.estado_vent','A');
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
	
			$queryPagos = $this->getCobrar($q->cod_vent);

			$pagos = "<table class='table table-bordered'
									<tr>
										<th>Fecha</th>
										<th>Caja</th>
										<th>Monto</th>
									</tr>";
			foreach ($queryPagos as $p) {
				$pagos .= "
				<tr>
				    <td>{$p->fecha_cobro}</td>
					<td>{$p->nomb_caja}</td>
					<td>{$p->monto_cobro}</td>
				</tr>
				";
			}
			


			if ($q->estado_vent== 'G') {
				$estado = 'REGISTRADA';
			  }elseif($q->estado_vent== 'A'){
				$estado = 'ANULADA';
			}

			$pagos .= "</table>";
			$row[] = [$q->fecha_vent,$q->documento,$q->nomb_cliente,$q->doc_cliente,$q->login_usu,$estado,$q->total_vent,$pagos];
		}

		$result['aaData'] = $row;
		return $result;
	}


	function getCobrar($id)
	{
		return $this->db->from('tb_cobro')
		->join('tb_caja','tb_cobro.cod_caja = tb_caja.cod_caja')
		->where('cod_vent',$id)
		->get()->result();
	}
	

 }


/* End of file CuentasCobrar_model.php */
/* Location: ./application/models/Cuentascobrar_model.php */