<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Impresora_model extends CI_Model
{

	 function getImpresora($data)
    {
        $this->db->from('tb_impresora');
	$queryTotal= $this->db->get();

	$this->db->from('tb_impresora');
		
	if (isset($data['tb_impresora'])) {
			$this->db->having("nom_impresora LIKE '%".$data['tb_impresora']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_impresora');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_impresora'])) {
			$this->db->having("nom_impresora LIKE '%".$data['tb_impresora']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estad_impresora=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estad_impresora=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_impresora.'" class="editar-impresora on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarImpresora" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_impresora.'" class="anular-impresora on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

     
			$row[] = [$q->nom_impresora,$q->nomlocal_impresora,$q->serie_impresora,$q->url_impresora,$q->ip_impresora,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}