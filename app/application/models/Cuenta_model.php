<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuenta_model extends CI_Model
{

	 function getcuentaasignar($data)
    {
    $this->db->from('tb_cuenta');
	$queryTotal= $this->db->get();

	$this->db->from('tb_cuenta');
	$this->db->join('tipo_cuenta','tb_cuenta.id_tipcuenta = tipo_cuenta.id_tipcuenta');
    $this->db->select('tb_cuenta.* , nomb_cuenta as cuenta, nomb_tipcuenta as tipocuenta');
		
	
	if (isset($data['tipo_cuenta'])) {
			$this->db->where('tipo_cuenta.id_tipcuenta',$data['tipo_cuenta']);
		}

   if (isset($data['tb_cuenta'])) {
			$this->db->having("nomb_cuenta LIKE '%".$data['tb_cuenta']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_cuenta');
	$this->db->join('tipo_cuenta','tb_cuenta.id_tipcuenta = tipo_cuenta.id_tipcuenta');
     $this->db->select('tb_cuenta.* , nomb_cuenta as cuenta, nomb_tipcuenta as tipocuenta');
	

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	

	if (isset($data['tipo_cuenta'])) {
			$this->db->where('tipo_cuenta.id_tipcuenta',$data['tipo_cuenta']);
		}
    
    if (isset($data['tb_cuenta'])) {
			$this->db->having("nomb_cuenta LIKE '%".$data['tb_cuenta']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estado_cuenta=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estado_cuenta=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<button data-id="'.$q->id_cuenta.'" class="editar-cuenta btn btn-sm btn-info" 
		data-toggle="modal" data-target="#ModalEditarCuenta" title="Editar cuenta" > <i class="fas fa-pencil-alt"></i></button>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<button data-id="'.$q->id_cuenta.'" class="anular-cuenta btn btn-sm btn-pink" title="Anular cuenta"> <i class="far fa-trash-alt"></i></button>';

     
			$row[] = [$q->id_cuenta,$q->cuenta,$q->tipocuenta,$q->orden_cuenta,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
}