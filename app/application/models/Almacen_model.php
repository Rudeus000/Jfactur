<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Almacen_model extends CI_Model
{
    function getAlmacen($data)
    {
        $this->db->from('tb_almacen');
	$queryTotal= $this->db->get();

	$this->db->from('tb_almacen');
		
	if (isset($data['tb_almacen'])) {
			$this->db->having("nomb_almacen LIKE '%".$data['tb_almacen']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_almacen');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_almacen'])) {
			$this->db->having("nomb_almacen LIKE '%".$data['tb_almacen']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_almacen=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_almacen=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<button data-id="'.$q->cod_almacen.'" class="editar-almacen btn btn-success waves-effect waves-light" 
		data-toggle="modal" data-target="#ModalEditarAlmacen" style="padding:4px 8px;margin:0px 4px"><i class="fas fa-pencil-alt"></i></button>';                                      
		$botones .= '<a></a> <button data-id="'.$q->cod_almacen.'" class="anular-almacen btn btn-danger waves-effect waves-light" style="padding:4px 8px;margin:0px 4px"><i class="fa fa-trash"></i></button>';

         if ($q->disp_venta=='1') {
				$estadoventa = '<label class="label label-purple">Si venta</label>';
		}elseif($q->disp_venta=='2'){
				$estadoventa = '<label class="label label-danger">No venta</label>';
		}
            
			$row[] = [$q->cod_almacen,$q->nomb_almacen,$estadoventa,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
	
	
	 function getTipoAlmacen($data)
    {
        $this->db->from('tb_tipoalmacen');
	$queryTotal= $this->db->get();

	$this->db->from('tb_tipoalmacen');
		
	if (isset($data['tb_tipoalmacen'])) {
			$this->db->having("nomb_tipoalm	 LIKE '%".$data['tb_tipoalmacen']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_tipoalmacen');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_tipoalmacen'])) {
			$this->db->having("nomb_tipoalm LIKE '%".$data['tb_tipoalmacen']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->est_almacen=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->est_almacen=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_tipoalm.'" class="editar-tipo on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarTipAlmacen" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;<a data-id="'.$q->cod_tipoalm.'" class="anular-tipalmacen on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"> <i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

         if ($q->tip_tipoalm=='I') {
				$estadotipo = '<label class="label label-purple">Ingreso Stock</label>';
		}elseif($q->tip_tipoalm=='S'){
				$estadotipo = '<label class="label label-danger">Salida Stock</label>';
		}
            
			$row[] = [$q->cod_tipoalm,$q->nomb_tipoalm,$estadotipo,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
	




    
}
?>