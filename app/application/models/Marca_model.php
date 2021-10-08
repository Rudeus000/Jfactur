<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marca_model extends CI_Model
{

	 function getMarca($data)
    {
        $this->db->from('tb_marca');
	$queryTotal= $this->db->get();

	$this->db->from('tb_marca');
		
	if (isset($data['tb_marca'])) {
			$this->db->having("nomb_marca LIKE '%".$data['tb_marca']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_marca');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_marca'])) {
			$this->db->having("nomb_marca LIKE '%".$data['tb_marca']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_marca=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_marca=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_marca.'" class="editar-marca on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarMarca" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_marca.'" class="anular-marca on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

		
			$row[] = [$q->cod_marca,$q->nomb_marca,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}