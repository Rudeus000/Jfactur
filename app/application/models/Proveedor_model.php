<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor_model extends CI_Model {

function getProveedor($data)
    {
    $this->db->from('tb_proveedor'); 
     $queryTotal = $this->db->get();


      $this->db->from('tb_proveedor');
     
      
    
      if (isset($data['tipo'])) {
      	$this->db->where('tb_proveedor_tip',$data['tipo']);
      }
      if (isset($data['nombre'])) {
      	$this->db->like('tb_proveedor_nom',$data['nombre']);
      }
      $queryLike = $this->db->get();
      

      $this->db->from('tb_proveedor');
     
     
   
      if (isset($data['tipo'])) {
      	$this->db->where('tb_proveedor_tip',$data['tipo']);
      }
       if (isset($data['nombre'])) {
      	$this->db->like('tb_proveedor_nom',$data['nombre']);
      }
      if ($data['length']!=-1) {
  			$this->db->limit($data['length'],$data['start']);
      }
      if (isset($data['orderCampo'])) {
  			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
      }
    
      $query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->tb_proveedor_xac=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->tb_proveedor_xac=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      $tb_proveedor_tip="";

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->tb_proveedor_id.'" class="editar-proveedor on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarProveedor" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->tb_proveedor_id.'" class="anular-proveedor on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

         if ($q->tb_proveedor_tip=='2') {
				$tb_proveedor_tip = '<label class="label label-purple">Ruc</label>';
		}elseif($q->tb_proveedor_tip=='4'){
				$tb_proveedor_tip = '<label class="label label-danger">Dni</label>';
		}
		
			$row[] = [$q->tb_proveedor_id,$tb_proveedor_tip,$q->tb_proveedor_nom,$q->tb_proveedor_doc,$q->tb_proveedor_dir,$q->tb_proveedor_con,$q->tb_proveedor_tel,$q->tb_proveedor_ema,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}

}