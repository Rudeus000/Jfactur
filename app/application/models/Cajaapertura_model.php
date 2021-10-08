<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cajaapertura_model extends CI_Model {

	function getApertura($data)
	{
		$this->db->from('tb_caja_apertura');
		$this->db->join('tb_caja','tb_caja_apertura.cod_caja = tb_caja.cod_caja');
    $this->db->where('estado_apertura','A');
		$queryTotal = $this->db->get();

		$this->db->from('tb_caja_apertura');
		$this->db->select('tb_caja_apertura.*, CONCAT(apell_usu, " ", nomb_usu) as NombreUsuario, nomb_caja');
		$this->db->join('tb_caja','tb_caja_apertura.cod_caja = tb_caja.cod_caja');
		$this->db->join('tb_usuario','tb_caja_apertura.cod_usu = tb_usuario.cod_usu');
		$this->db->join('tb_puntoventa_caja','tb_caja.cod_caja = tb_puntoventa_caja.cod_caja');
    $this->db->where('estado_apertura','A');
    if ($data['caja']!='') {
    	$this->db->like('nomb_caja',$data['caja']);
    }
    if($data['usuario']!=''){
      $this->db->having("NombreUsuario LIKE '%".$data['usuario']."%'");
    }
   	$queryLike = $this->db->get();


   	$this->db->from('tb_caja_apertura');
   	$this->db->select('tb_caja_apertura.*, CONCAT(apell_usu, " ", nomb_usu) as NombreUsuario, nomb_caja');
		$this->db->join('tb_caja','tb_caja_apertura.cod_caja = tb_caja.cod_caja');
		$this->db->join('tb_usuario','tb_caja_apertura.cod_usu = tb_usuario.cod_usu');
		$this->db->join('tb_puntoventa_caja','tb_caja.cod_caja = tb_puntoventa_caja.cod_caja');
    $this->db->where('estado_apertura','A');
    if ($data['caja']!='') {
    	$this->db->like('nomb_caja',$data['caja']);
    }
		if($data['usuario']!=''){
      $this->db->having("NombreUsuario LIKE '%".$data['usuario']."%'");
    }
	  if ($data['length']!=-1) {
	  	$this->db->limit($data['length'],$data['start']);
	  }
	  if (isset($data['orderCampo'])) {
	    $this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}
		$this->db->where('tb_puntoventa_caja.cod_puntoventa',$this->session->userdata('puntoventa'));
  	$query = $this->db->get();

    $result = array();
    $result['sEcho'] = $data['sEcho'];
    $result['iTotalRecords'] = $queryLike->num_rows();
    $result['iTotalDisplayRecords'] = $queryTotal->num_rows();

    $row = [];
    foreach ($query->result() as $q) {
      $botones = '<div class="btn-group m-b-10">
        <button type="button" data-id="'.$q->cod_apertura.'" class="editarApertura btn btn-warning btn-sm waves-effect"><i class="fas fa-edit"></i></button>
        <button type="button" data-id="'.$q->cod_apertura.'" class="eliminar btn btn-danger btn-sm waves-effect"><i class="fas fa-trash"></i></button>
        
    		</div>';

    	$turnos = ['C'=>'Completo','M'=>'Mañana','T'=>'Tarde','N'=>'Noche'];
      $row[] = [$q->cod_apertura,$q->nomb_caja,$q->NombreUsuario,$q->monto_apertura,$turnos[$q->turno_apertura],$q->fecha_apertura,$botones];
     }

     $result['aaData'] = $row;
     return $result;
	}

  function getCajasAperturas()
  {
    return $this->db->from('tb_usuario_puntoventa')
    ->select('nomb_caja,tb_caja.cod_caja')
    ->join('tb_puntoventa','tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa.cod_puntoventa')
    ->join('tb_puntoventa_caja','tb_puntoventa.cod_puntoventa = tb_puntoventa_caja.cod_puntoventa')
    ->join('tb_caja','tb_puntoventa_caja.cod_caja = tb_caja.cod_caja')
    ->where('tb_usuario_puntoventa.cod_usu',$this->session->userdata('cod_usu'))
    ->where('tb_usuario_puntoventa.cod_puntoventa',$this->session->userdata('puntoventa'))
    ->get()->result();
  }

}

/* End of file Cajaapertura_model.php */
/* Location: ./application/models/Cajaapertura_model.php */
