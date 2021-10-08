<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class sede_model extends CI_Model
{

	 function getsede($data)
    {
        $this->db->from('sede');
	$queryTotal= $this->db->get();

	$this->db->from('sede');
		
	if (isset($data['sede'])) {
			$this->db->having("sede_nombre LIKE '%".$data['sede']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('sede');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['sede'])) {
			$this->db->having("sede_nombre LIKE '%".$data['sede']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->sede_estado=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->sede_estado=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_sede.'" class="editar-sede on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarSede" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_sede.'" class="anular-sede on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

		
			$row[] = [$q->cod_sede,$q->sede_nombre,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}