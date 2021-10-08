<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportventaspagos_model extends CI_Model {

	function getreportventaspago($data)
	{
		$this->db->from('tb_venta as v');
		$this->db->select('v.fecha_vent,CONCAT(t.serie, " - ", v.numero_vent) as documento,c.doc_cliente,c.nomb_cliente,v.pago_vent,v.total_vent,v.estado_vent');
		$this->db->join('tb_cliente as c','v.id_cliente = c.id_cliente','left');
		$this->db->join('tb_talonario as t','v.cod_talonario = t.cod_talonario','left');
		$this->db->join('tb_tipodocumento as td','t.cod_tipdocu = td.cod_tipdocu','left');

		
    //	$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('v.fecha_vent >=',$data['desde']);
			$this->db->where('v.fecha_vent <=',$data['hasta']);
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

		$this->db->from('tb_venta as v');
		$this->db->select('v.fecha_vent,CONCAT(t.serie, " - ", v.numero_vent) as documento,c.doc_cliente,c.nomb_cliente,v.pago_vent,v.total_vent,v.estado_vent');
		$this->db->join('tb_cliente as c','v.id_cliente = c.id_cliente','left');
		$this->db->join('tb_talonario as t','v.cod_talonario = t.cod_talonario','left');
		$this->db->join('tb_tipodocumento as td','t.cod_tipdocu = td.cod_tipdocu','left');
		
    	//$this->db->order_by('tb_compra.fecha_comp','desc');	
        
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('v.fecha_vent >=',$data['desde']);
			$this->db->where('v.fecha_vent <=',$data['hasta']);
		}

		 if ($data['pago']=='CO') {
			$this->db->where('pago_vent','CO');
		}
		if($data['pago']=='CRE'){
			$this->db->where('pago_vent','CRE');
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

		if ($q->pago_vent== 'CO') {
				$pago = 'CONTADO';
			}

		if ($q->pago_vent== 'CRE') {
				$pago = 'CREDITO';
			}

		  if ($q->estado_vent== 'G') {
				$estado = '<label class="label label-success" >Generado</label>';
			}elseif($q->estado_vent== 'A'){
				$estado = '<label class="label label-info text-center">Anulado</label>';
			}	

			// $abono = $this->getAbonos($q->tb_proveedor_id);
			// $saldo = $q->monto - $abono;
			// $buttons = '<a class="btn btn-xs btn-success" href="'.base_url('administrador/regcuentascobrar/detalle/'.$q->tb_proveedor_id).'"><i class="fa fa-plus"></i> Detalle</a>';
			$row[] = [$q->fecha_vent,$q->documento,$q->doc_cliente,$q->nomb_cliente,$pago,$estado,$q->total_vent];
		}

		$result['aaData'] = $row;
		return $result;
	}



	

  }


/* End of file Cuentascobrar_model.php */
/* Location: ./application/models/Cuentascobrar_model.php */