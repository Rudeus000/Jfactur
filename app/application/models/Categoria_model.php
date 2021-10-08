<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria_model extends CI_Model
{

	 function getCategoria($data)
    {
        $this->db->from('tb_categoria');
	$queryTotal= $this->db->get();

	$this->db->from('tb_categoria');
		
	if (isset($data['tb_categoria'])) {
			$this->db->having("nomb_categoria LIKE '%".$data['tb_categoria']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_categoria');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_categoria'])) {
			$this->db->having("nomb_categoria LIKE '%".$data['tb_categoria']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_categoria=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_categoria=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_categoria.'" class="editar-categoria on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarCategoria" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_categoria.'" class="anular-categoria on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

		
			$row[] = [$q->cod_categoria,$q->nomb_categoria,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}