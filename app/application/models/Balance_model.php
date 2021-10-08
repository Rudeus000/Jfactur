<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Balance_model extends CI_Model {
	
  public function getBalance($data)
  {
		$this->db->from('balance');
    $this->db->where('fecha >=',$data['desde']);
    $this->db->where('fecha <=',$data['hasta']);
		if($data['sede']!='Todos'){
			$this->db->where('cod_sede',$data['sede']);
		}
		$queryTotal = $this->db->get();

    $ingresos = 0;
    $egresos = 0;
    foreach ($queryTotal->result() as $t) {
      $ingresos += ($t->tipo=='Ingreso')?$t->total:0;
      $egresos += ($t->tipo=='Egreso')?$t->total:0;
    }
    
    $totales = [];
    $totales['ingresos'] = $ingresos;
    $totales['egresos'] = $egresos;
    $totales['balance'] = $ingresos - $egresos;

		$this->db->reset_query();
		$this->db->from('balance');
    $this->db->where('fecha >=',$data['desde']);
    $this->db->where('fecha <=',$data['hasta']);
		if($data['sede']!='Todos'){
			$this->db->where('cod_sede',$data['sede']);
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
		$result['iTotalDisplayRecords'] = $queryTotal->num_rows();
		$result['totales'] = $totales;

		$row = [];
		foreach ($query->result() as $q) {
			$row[] = [$q->tipo,$q->fecha,$q->nombre_sede,$q->categoria,$q->descripcion,$q->total];
		}
		$result['aaData'] = $row;
		return $result;
	}

}

/* End of file Balance_model_model.php */
/* Location: ./application/models/Balance_model.php */