

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportcoticlientes_model extends CI_Model {


function getreportcoticlientes($data)
	{
		// $this->db->from('tb_cotizacion as co');
		// $this->db->select('co.fecha_cot, CONCAT(td.nom_tipdocumento," - ",t.serie) as documento,co.numero_cot,c.doc_cliente,c.nomb_cliente,co.pago_cot,co.moneda_cat, co.total_cot');
		// $this->db->join('tb_cliente as c','co.id_cliente = c.id_cliente','left');
		// $this->db->join('tb_talonario as t','co.cod_talonario = t.cod_talonario','left');
		// $this->db->join('tb_tipodocumento as td','t.cod_tipdocu = td.cod_tipdocu','left');

		$this->db->from('tb_cotizacion');
		$this->db->select('fecha_cot,doc_cliente,nomb_cliente,pago_cot,total_cot as monto');
		$this->db->join('tb_cliente','tb_cotizacion.id_cliente = tb_cliente.id_cliente');
		$this->db->join('tb_talonario as t','tb_cotizacion.cod_talonario = t.cod_talonario','left');
		$this->db->join('tb_tipodocumento as td','t.cod_tipdocu = td.cod_tipdocu','left');
    //	$this->db->group_by('tb_cotizacion.id_cliente');
    	$this->db->where('estado_cliente',1);
    	$this->db->where('estado_cot','G');
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_cot >=',$data['desde']);
			$this->db->where('fecha_cot <=',$data['hasta']);
		}

		if ($data['tb_cliente']!='') {
        $this->db->like('tb_cliente.nomb_cliente',$data['tb_cliente']);
         }

		  if ($data['pago_cot']=='CO') {
			$this->db->where('pago_cot','CO');
		}
		if($data['pago_cot']=='CRE'){
			$this->db->where('pago_cot','CRE');
		}

		$queryLike = $this->db->get();

		$total = 0;
		foreach ($queryLike->result() as $r) {
			$total += $r->monto;
		}

	    $this->db->from('tb_cotizacion');
		$this->db->select('fecha_cot,doc_cliente,nomb_cliente,pago_cot,total_cot as monto');
		$this->db->join('tb_cliente','tb_cotizacion.id_cliente = tb_cliente.id_cliente');
    	//$this->db->group_by('tb_cotizacion.id_cliente');
    	$this->db->where('estado_cliente',1);
        $this->db->where('estado_cot','G');

        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_cot >=',$data['desde']);
			$this->db->where('fecha_cot <=',$data['hasta']);
		}

		if ($data['tb_cliente']!='') {
        $this->db->like('tb_cliente.nomb_cliente',$data['tb_cliente']);
         }

		  if ($data['pago_cot']=='CO') {
			$this->db->where('pago_cot','CO');
		}
		if($data['pago_cot']=='CRE'){
			$this->db->where('pago_cot','CRE');
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
		 if ($q->pago_cot== 'CO') {
				$pago = '<label class="label label-success">Contado</label>';
			}elseif($q->pago_cot== 'CRE'){
				$pago = '<label class="label label-info">Credito</label>';
			}

		$row[] = [$q->fecha_cot,$q->doc_cliente,$q->nomb_cliente,$pago,$q->monto];
		}

		$result['aaData'] = $row;
		return $result;
	}
}