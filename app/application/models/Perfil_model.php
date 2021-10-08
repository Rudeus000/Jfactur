<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfil_model extends CI_Model
{
    function getPerfil($data)
    {
        $this->db->from('tb_perfil');
	$queryTotal= $this->db->get();

	$this->db->from('tb_perfil');
		
	if (isset($data['tb_perfil'])) {
			$this->db->having("nomb_perfil LIKE '%".$data['tb_perfil']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_perfil');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_perfil'])) {
			$this->db->having("nomb_perfil LIKE '%".$data['tb_perfil']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estado_perfil=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estado_perfil=='2'){
				$estado = '<label class="label label-info">Inactivo</label>';
		}	


		$botones = '<div class="btn-footer text-center">
		<button data-id="'.$q->cod_perfil.'" class="editar-perfil btn btn-success waves-effect waves-light" 
		data-toggle="modal" data-target="#ModalEditarPerfil" style="padding:4px 8px;margin:0px 4px"><i class="fas fa-pencil-alt"></i></button>';                                      
		$botones .= '<a></a> <button data-id="'.$q->cod_perfil.'" class="anular-perfil btn btn-danger waves-effect waves-light" style="padding:4px 8px;margin:0px 4px"><i class="fa fa-trash"></i></button>';
            
			$row[] = [$q->cod_perfil,$q->nomb_perfil,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
	
	public function agregarPerfil($data)
	{
			$this->db->insert('tb_perfil',$data);
			return $this->db->insert_id();
	}

    
}
?>