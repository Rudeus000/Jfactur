<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caja_model extends CI_Model
{

	 function getCaja($data)
    {
        $this->db->from('tb_caja');
	$queryTotal= $this->db->get();

	$this->db->from('tb_caja');
		
	if (isset($data['tb_caja'])) {
			$this->db->having("nomb_caja LIKE '%".$data['tb_caja']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_caja');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_caja'])) {
			$this->db->having("nomb_caja LIKE '%".$data['tb_caja']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_caja=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_caja=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_caja.'" class="editar-caja on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarCaja" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_caja.'" class="anular-caja on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

         if ($q->tipo_caja=='1') {
				$tipo_caja = '<label class="label label-purple">Venta</label>';
		}elseif($q->tipo_caja=='2'){
				$tipo_caja = '<label class="label label-danger">Central</label>';
		}
		elseif($q->tipo_caja=='3'){
				$tipo_caja = '<label class="label label-warning">Recaudadora</label>';
            }
         elseif($q->tipo_caja=='4'){
				$tipo_caja = '<label class="label label-success">Compras</label>';
            }
			$row[] = [$q->cod_caja,$q->nomb_caja,$tipo_caja,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}