<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente_model extends CI_Model {

function getCliente($data)
    {
    $this->db->from('tb_cliente'); 
     $queryTotal = $this->db->get();


      $this->db->from('tb_cliente');
     
      
    
      if (isset($data['tipo'])) {
      	$this->db->where('tb_cliente_tip',$data['tipo']);
      }
      if (isset($data['nombre'])) {
      	$this->db->like('nomb_cliente',$data['nombre']);
      }
      $queryLike = $this->db->get();
      

      $this->db->from('tb_cliente');
     
     
   
      if (isset($data['tipo'])) {
      	$this->db->where('tb_cliente_tip',$data['tipo']);
      }
       if (isset($data['nombre'])) {
      	$this->db->like('nomb_cliente',$data['nombre']);
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
		if ($q->estado_cliente=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estado_cliente=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->id_cliente.'" class="editar-cliente on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarCliente" data-placement="top" title data-original-title="Edit"><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->id_cliente.'" class="anular-cliente on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';


			$row[] = [$q->id_cliente,$q->nomb_cliente,$q->fena_pac,$q->doc_cliente,$q->direc_cliente,$q->contac_cliente,$q->telf_cliente,$q->email_cliente,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}

}