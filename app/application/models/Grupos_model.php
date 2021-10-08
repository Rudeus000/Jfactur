<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupos_model extends CI_Model
{
    function getGrupo($data)
    {
        $this->db->from('tb_grupo');
	$queryTotal= $this->db->get();

	$this->db->from('tb_grupo');
		
	if (isset($data['tb_grupo'])) {
			$this->db->having("nombre_grupo LIKE '%".$data['tb_grupo']."%'");
		}

	$queryLike = $this->db->get();

	$this->db->from('tb_grupo');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_grupo'])) {
			$this->db->having("nombre_grupo LIKE '%".$data['tb_grupo']."%'");
		}

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estado_grupo=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estado_grupo=='2'){
				$estado = '<label class="label label-info">Inactivo</label>';
		}	


		$botones = '<div class="btn-footer text-center">
		<button data-id="'.$q->cod_grupo.'" class="editar-grupo btn btn-success waves-effect waves-light" 
		data-toggle="modal" data-target="#ModalEditarGrupo" style="padding:4px 8px;margin:0px 4px"><i class="fas fa-pencil-alt"></i></button>';                                      
		$botones .= '<a></a> <button data-id="'.$q->cod_grupo.'" class="anular-grupo btn btn-danger waves-effect waves-light" style="padding:4px 8px;margin:0px 4px"><i class="fa fa-trash"></i></button>';
            
			$row[] = [$q->cod_grupo,$q->nombre_grupo,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}
	
	public function agregarGrupo($data)
	{
			$this->db->insert('tb_grupo',$data);
			return $this->db->insert_id();
	}

    
}
?>