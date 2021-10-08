<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientecobertura_model extends CI_Model {

	function getClientes($data)
	{
		$this->db->from('tb_cliente_cobertura');
		$this->db->join('tb_cliente','tb_cliente_cobertura.id_cliente = tb_cliente.id_cliente');
		$queryTotal = $this->db->get();

		$this->db->from('tb_cliente_cobertura');
		$this->db->join('tb_cliente','tb_cliente_cobertura.id_cliente = tb_cliente.id_cliente');
    if ($data['cliente']!='') {
    	$this->db->like('nomb_cliente',$data['cliente']);
    }
   	$queryLike = $this->db->get();


   	$this->db->from('tb_cliente_cobertura');
		$this->db->join('tb_cliente','tb_cliente_cobertura.id_cliente = tb_cliente.id_cliente');
    if ($data['cliente']!='') {
    	$this->db->like('nomb_cliente',$data['cliente']);
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
    $result['iTotalRecords'] = $queryLike->num_rows();
    $result['iTotalDisplayRecords'] = $queryTotal->num_rows();

    $row = [];
    foreach ($query->result() as $q) {
      $botones = '<div class="btn-group m-b-10">
        <button type="button" data-id="'.$q->id_cobertura.'" class="editarCobertura btn btn-warning btn-sm waves-effect"><i class="fas fa-edit"></i></button>
        <button type="button" data-id="'.$q->id_cobertura.'" class="eliminar btn btn-danger btn-sm waves-effect"><i class="fas fa-trash"></i></button>
        
    </div>';
      $row[] = [$q->id_cobertura,$q->nomb_cliente,$q->inicio_cobertura,$q->limite_cobertura,$q->monto_cobertura,$botones];
     }

     $result['aaData'] = $row;
     return $result;
	}

}

/* End of file Clientecobertura_model.php */
/* Location: ./application/models/Clientecobertura_model.php */