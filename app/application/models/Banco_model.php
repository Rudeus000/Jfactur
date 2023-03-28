<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banco_model extends CI_Model
{

	 function getbanco($data)
    {
    $this->db->from('tb_banco');
	$queryTotal= $this->db->get();

	$this->db->from('tb_banco');
		
	if (isset($data['tb_banco'])) {
			$this->db->having("nomb_ban LIKE '%".$data['tb_banco']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_banco');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_banco'])) {
			$this->db->having("nomb_ban LIKE '%".$data['tb_banco']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_ban=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_ban=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<button data-id="'.$q->cod_ban.'" class="editar-banco btn btn-sm btn-info" 
		data-toggle="modal" data-target="#ModalEditarBanco" title="Editar banco" > <i class="fas fa-pencil-alt"></i></button>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<button data-id="'.$q->cod_ban.'" class="anular-banco btn btn-sm btn-pink" title="Anular cuenta"> <i class="far fa-trash-alt"></i></button>';

        
			$row[] = [$q->cod_ban,$q->moneda_ban,$q->tipo_cuenta_ban,$q->nomb_ban, $q->nomb_titular_ban,$q->nro_cuenta_ban,$q->cci_cuenta_ban,$q->id_entidad_financiera,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}