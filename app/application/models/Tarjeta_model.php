<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarjeta_model extends CI_Model
{

	 function getarjeta($data)
    {
    $this->db->from('tb_tarjeta');
	$queryTotal= $this->db->get();

	$this->db->from('tb_tarjeta');
		
	if (isset($data['tb_tarjeta'])) {
			$this->db->having("nomb_tarj LIKE '%".$data['tb_tarjeta']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_tarjeta');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_tarjeta'])) {
			$this->db->having("nomb_tarj LIKE '%".$data['tb_tarjeta']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estado_tarj=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_tarj=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<button data-id="'.$q->cod_tarj.'" class="editar-tarjeta btn btn-sm btn-info" 
		data-toggle="modal" data-target="#ModalEditarTarjeta" title="Editar tarjeta" > <i class="fas fa-pencil-alt"></i></button>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<button data-id="'.$q->cod_tarj.'" class="anular-tarjeta btn btn-sm btn-pink" title="Anular cuenta"> <i class="far fa-trash-alt"></i></button>';

        
			$row[] = [$q->cod_tarj,$q->nomb_tarj,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}