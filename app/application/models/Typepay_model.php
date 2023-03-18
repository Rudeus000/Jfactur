<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Typepay_model extends CI_Model
{

	 function gettpay($data)
    {
    $this->db->from('tb_tipo_pago');
	$queryTotal= $this->db->get();

	$this->db->from('tb_tipo_pago');
		
	if (isset($data['tb_tipo_pago'])) {
			$this->db->having("nom_tipopago LIKE '%".$data['tb_tipo_pago']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_tipo_pago');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_tipo_pago'])) {
			$this->db->having("nomb_tipo_pago LIKE '%".$data['tb__tipo_pago']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estado_tipopago=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estado_tipopago=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<button data-id="'.$q->cod_tipopago.'" class="editar-banco btn btn-sm btn-info" 
		data-toggle="modal" data-target="#ModalUpdateTpay" title="Editar banco" > <i class="fas fa-pencil-alt"></i></button>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<button data-id="'.$q->cod_tipopago.'" class="anular-banco btn btn-sm btn-pink" title="Anular cuenta"> <i class="far fa-trash-alt"></i></button>';

        
			$row[] = [$q->cod_tipopago,$q->nom_tipopago,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}