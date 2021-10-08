<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipodocumento_model extends CI_Model
{

	 function getDocumento($data)
    {
        $this->db->from('tb_tipodocumento');
	$queryTotal= $this->db->get();

	$this->db->from('tb_tipodocumento');
		
	if (isset($data['tb_tipodocumento'])) {
			$this->db->having("nom_tipdocumento LIKE '%".$data['tb_tipodocumento']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_tipodocumento');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_tipodocumento'])) {
			$this->db->having("nom_tipdocumento LIKE '%".$data['tb_tipodocumento']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_tipdocum=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_tipdocum=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_tipdocu.'" class="editar-documento on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarDocumento" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_tipdocu.'" class="anular-documento on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

     
			$row[] = [$q->cod_tipdocu,$q->nom_tipdocumento,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}