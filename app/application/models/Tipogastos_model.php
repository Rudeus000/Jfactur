<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipogastos_model extends CI_Model
{

	 function getTipogastos($data)
    {
        $this->db->from('tb_tipo_gastos');
	$queryTotal= $this->db->get();

	$this->db->from('tb_tipo_gastos');
		
	if (isset($data['tb_tipo_gastos'])) {
			$this->db->having("descripcion LIKE '%".$data['tb_tipo_gastos']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_tipo_gastos');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_tipo_gastos'])) {
			$this->db->having("descripcion LIKE '%".$data['tb_tipo_gastos']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estado_tipo=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estado_tipo=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_tipgastos.'" class="editar-tipogastos on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarTipoGastos" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_tipgastos.'" class="anular-marca on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

		
			$row[] = [$q->cod_tipgastos,$q->descripcion,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}