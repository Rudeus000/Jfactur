<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipmoneda_model extends CI_Model
{

	 function getmoneda($data)
    {
        $this->db->from('tipo_moneda');
	$queryTotal= $this->db->get();

	$this->db->from('tipo_moneda');
		
	if (isset($data['tipo_moneda'])) {
			$this->db->having("mon_moneda LIKE '%".$data['tipo_moneda']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tipo_moneda');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tipo_moneda'])) {
			$this->db->having("mon_moneda LIKE '%".$data['tipo_moneda']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->mon_estado=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->mon_estado=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->id_moneda.'" class="editar-moneda on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarMoneda" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->id_moneda.'" class="anular-moneda on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

         if ($q->tipo_moneda=='S') {
				$tipo_moneda = '<label class="label label-purple">Soles</label>';
		}elseif($q->tipo_moneda=='D'){
				$tipo_moneda = '<label class="label label-danger">Dolar</label>';
		}
		elseif($q->tipo_moneda=='E'){
				$tipo_moneda = '<label class="label label-warning">Euros</label>';
            }
			$row[] = [$q->id_moneda,$q->mon_simbolo,$q->mon_moneda, $tipo_moneda,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}