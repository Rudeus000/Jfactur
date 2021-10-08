<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unidad_model extends CI_Model
{

	 function getUnidad($data)
    {
        $this->db->from('tb_unidades');
	$queryTotal= $this->db->get();

	$this->db->from('tb_unidades');
	$this->db->join('tb_tipounidad','tb_unidades.cod_tipunidad = tb_tipounidad.cod_tipunidad');
    $this->db->select('tb_unidades.* , cod_unid as codigo, abreviatura_unid as abreviatura, nomb_unid as unidad, fact_unid as factor, nomb_tipunidad as tipounidad');
		
	if (isset($data['tb_unidades'])) {
			$this->db->having("nomb_unid LIKE '%".$data['tb_unidades']."%'");
		}
	if (isset($data['tb_tipounidad'])) {
			$this->db->where('tb_tipounidad.cod_tipunidad',$data['tb_tipounidad']);
		}


	$queryLike = $this->db->get();

	$this->db->from('tb_unidades');
	$this->db->join('tb_tipounidad','tb_unidades.cod_tipunidad = tb_tipounidad.cod_tipunidad');
    $this->db->select('tb_unidades.* , cod_unid as codigo, abreviatura_unid as abreviatura, nomb_unid as unidad, fact_unid as factor, nomb_tipunidad as tipounidad');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_unidades'])) {
			$this->db->having("nomb_unid LIKE '%".$data['tb_unidades']."%'");
		}
	if (isset($data['tb_tipounidad'])) {
			$this->db->where('tb_tipounidad.cod_tipunidad',$data['tb_tipounidad']);
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_unidad=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_unidad=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_unid.'" class="editar-umedida on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarUmedida" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_unid.'" class="anular-umedida on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

		
			$row[] = [$q->cod_unid,$q->abreviatura_unid,$q->nomb_unid,$q->fact_unid,$q->tipounidad,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}