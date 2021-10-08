<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tiparticulo_model extends CI_Model
{

	 function getTiparticulo($data)
    {
        $this->db->from('tb_tiparticulo');
	$queryTotal= $this->db->get();

	$this->db->from('tb_tiparticulo');
		
	if (isset($data['tb_tiparticulo'])) {
			$this->db->having("nomb_tiparticulo LIKE '%".$data['tb_tiparticulo']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_tiparticulo');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_tiparticulo'])) {
			$this->db->having("nomb_tiparticulo LIKE '%".$data['tb_tiparticulo']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_tiparticulo=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_tiparticulo=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_tiparticulo.'" class="editar-tipo on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarTiparticulo" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_tiparticulo.'" class="anular-tipo on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

         if ($q->stock_tiparticulo=='S') {
				$tipo_articulo = '<label class="label label-purple">Si gestion stock</label>';
		}elseif($q->stock_tiparticulo=='N'){
				$tipo_articulo = '<label class="label label-danger">No gestion stock</label>';
		}
		
			$row[] = [$q->cod_tiparticulo,$q->nomb_tiparticulo,$tipo_articulo,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}