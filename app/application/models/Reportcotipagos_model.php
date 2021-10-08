<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportcotipagos_model extends CI_Model {

	function getreportcotizacion($data)
	{
		$this->db->from('tb_cotizacion as co');
		$this->db->select('co.fecha_cot, CONCAT(td.nom_tipdocumento," - ",t.serie) as documento,co.numero_cot,c.doc_cliente,c.nomb_cliente,co.pago_cot,co.moneda_cat,co.total_cot');
		$this->db->join('tb_cliente as c','co.id_cliente = c.id_cliente','left');
		$this->db->join('tb_talonario as t','co.cod_talonario = t.cod_talonario','left');
		$this->db->join('tb_tipodocumento as td','t.cod_tipdocu = td.cod_tipdocu','left');
		
    //	$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('co.fecha_cot >=',$data['desde']);
			$this->db->where('co.fecha_cot <=',$data['hasta']);
		}

		// if ($data['tb_proveedor']!='') {
        // $this->db->like('tb_proveedor_nom',$data['tb_proveedor']);
        //  }
		if ($data['tb_cliente']!='') {
			$this->db->like('c.nomb_cliente',$data['tb_cliente']);
		  }

         if ($data['pago_cot']=='CO') {
			$this->db->where('pago_cot','CO');
		}
		if($data['pago_cot']=='CRE'){
			$this->db->where('pago_cot','CRE');
		}

		  if ($data['moneda_cat']=='S') {
			$this->db->where('moneda_cat','S');
		}
		if($data['moneda_cat']=='D'){
			$this->db->where('moneda_cat','D');
		}

		 if (isset($data['tb_talonario'])) {
			$this->db->where('tb_talonario.cod_talonario',$data['tb_talonario']);
		}

		if ($data['estado']=='G') {
			$this->db->where('estado_cot','G');
		}
		if($data['estado']=='A'){
			$this->db->where('estado_cot','A');
		}


        
		$queryLike = $this->db->get();
		$total = 0;

		foreach ($queryLike->result() as $r) {
			$total += $r->total_cot;
			
		}

		$this->db->from('tb_cotizacion as co');
		$this->db->select('co.fecha_cot, CONCAT(td.nom_tipdocumento," - ",t.serie) as documento,co.numero_cot,c.doc_cliente,c.nomb_cliente,co.pago_cot,co.moneda_cat, co.total_cot');
		$this->db->join('tb_cliente as c','co.id_cliente = c.id_cliente','left');
		$this->db->join('tb_talonario as t','co.cod_talonario = t.cod_talonario','left');
		$this->db->join('tb_tipodocumento as td','t.cod_tipdocu = td.cod_tipdocu','left');
		
    	//$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('co.fecha_cot >=',$data['desde']);
			$this->db->where('co.fecha_cot <=',$data['hasta']);
		}

		// if ($data['tb_proveedor']!='') {
        // $this->db->like('tb_proveedor_nom',$data['tb_proveedor']);
        //  }
		if ($data['tb_cliente']!='') {
			$this->db->like('c.nomb_cliente',$data['tb_cliente']);
		  }

         if ($data['pago_cot']=='CO') {
			$this->db->where('pago_cot','CO');
		}
		if($data['pago_cot']=='CRE'){
			$this->db->where('pago_cot','CRE');
		}

		  if ($data['moneda_cat']=='S') {
			$this->db->where('moneda_cat','S');
		}
		if($data['moneda_cat']=='D'){
			$this->db->where('moneda_cat','D');
		}

		 if (isset($data['tb_talonario'])) {
			$this->db->where('tb_talonario.cod_talonario',$data['tb_talonario']);
		}

		if ($data['estado']=='G') {
			$this->db->where('estado_cot','G');
		}
		if($data['estado']=='A'){
			$this->db->where('estado_cot','A');
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

		if ($q->moneda_cat== 'S') {
				$moneda = '<label class="label label-success">Soles</label>';
			}

		if ($q->moneda_cat== 'D') {
				$moneda = '<label class="label label-success">Dolares</label>';
			}

		  if ($q->pago_cot== 'CO') {
				$pago = '<label class="label label-success" >Contado</label>';
			}elseif($q->pago_cot== 'CRE'){
				$pago = '<label class="label label-info text-center">Credito</label>';
			}	

			// $abono = $this->getAbonos($q->tb_proveedor_id);
			// $saldo = $q->monto - $abono;
			// $buttons = '<a class="btn btn-xs btn-success" href="'.base_url('administrador/regcuentascobrar/detalle/'.$q->tb_proveedor_id).'"><i class="fa fa-plus"></i> Detalle</a>';
			$row[] = [$q->fecha_cot,$q->documento,$q->numero_cot,$q->doc_cliente,$q->nomb_cliente,$pago,$moneda,$q->total_cot];
		}

		$result['aaData'] = $row;
		return $result;
	}



	

  }


/* End of file Cuentascobrar_model.php */
/* Location: ./application/models/Cuentascobrar_model.php */