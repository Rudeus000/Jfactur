<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipocuenta_model extends CI_Model
{

	 function getipocuent($data)
    {
    $this->db->from('tipo_cuenta');
	$queryTotal= $this->db->get();

	$this->db->from('tipo_cuenta');
		
	if (isset($data['tipo_cuenta'])) {
			$this->db->having("nomb_tipcuenta LIKE '%".$data['tipo_cuenta']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tipo_cuenta');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tipo_cuenta'])) {
			$this->db->having("nomb_tipcuenta LIKE '%".$data['tipo_cuenta']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estado_tipcuenta=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estado_tipcuenta=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->id_tipcuenta.'" class="editar-tipocuenta on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarTipoCuenta" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->id_tipcuenta.'" class="anular-tipocuenta on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

        
			$row[] = [$q->id_tipcuenta,$q->abrev_tipcuenta,$q->nomb_tipcuenta, $q->orden_tipcuenta,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}