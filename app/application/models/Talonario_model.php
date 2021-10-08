<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Talonario_model extends CI_Model
{

	 function gettalonario($data)
    {
    $this->db->from('tb_talonario');
	$queryTotal= $this->db->get();

	$this->db->from('tb_talonario');
	$this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
	$this->db->join('tb_puntoventa','tb_talonario.cod_puntoventa = tb_puntoventa.cod_puntoventa');
	$this->db->join('tb_impresora','tb_talonario.cod_impresora = tb_impresora.cod_impresora');
    $this->db->select('tb_talonario.* , nom_tipdocumento as documento, nomb_puntoventa as punto, nom_impresora as impresora, 
        serie, talonario_ini, talonario_fin, correlativo_actual');
		

	if (isset($data['tb_tipodocumento'])) {
			$this->db->where('tb_tipodocumento.cod_tipdocu',$data['tb_tipodocumento']);
		}


	$queryLike = $this->db->get();

	$this->db->from('tb_talonario');
	$this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
    $this->db->join('tb_puntoventa','tb_talonario.cod_puntoventa = tb_puntoventa.cod_puntoventa');
	$this->db->join('tb_impresora','tb_talonario.cod_impresora = tb_impresora.cod_impresora');
    $this->db->select('tb_talonario.* , nom_tipdocumento as documento, nomb_puntoventa as punto, nom_impresora as impresora, 
        serie, talonario_ini, talonario_fin, correlativo_actual');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}
    if (isset($data['tb_tipodocumento'])) {
			$this->db->where('tb_tipodocumento.cod_tipdocu',$data['tb_tipodocumento']);
		}


	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_talonario=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_talonario=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_talonario.'" class="editar-talonario on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarTalonario" data-placement="top" ><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_talonario.'" class="anular-talonario on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

		$siglastalonarios ="";

		if ($q->siglas_talonario=='FC') {
				$siglastalonarios = '<label class="label label-primary">Documentos de movimiento sunat</label>';
		}elseif($q->siglas_talonario=='PC'){
				$siglastalonarios = '<label class="label label-danger">Cotizaciones - Proformas</label>';
		}elseif($q->siglas_talonario=='TK'){
			$siglastalonarios = '<label class="label label-warning">Documento - Interno </label>';
	}
     
			$row[] = [$q->documento,$q->punto,$q->impresora,$q->serie,$q->talonario_ini,$q->talonario_fin,$q->correlativo_actual,$siglastalonarios,$estado,$botones];
			
		}
		$result['aaData'] = $row;
		return $result;

	}
}