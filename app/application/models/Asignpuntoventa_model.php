<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asignpuntoventa_model extends CI_Model {

	function getUsuarios($data)
	{
		$this->db->from('tb_usuario');
		$this->db->join('tb_grupo','tb_usuario.cod_grupo = tb_grupo.cod_grupo');
		$this->db->join('tb_usuario_puntoventa','tb_usuario.cod_usu = tb_usuario_puntoventa.cod_usu AND pordefecto = 1','left');
		$this->db->join('tb_puntoventa','tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa.cod_puntoventa','left');
		if ($data['grupo']!='') {
			$this->db->where('tb_usuario.cod_grupo',$data['grupo']);
		}
		$queryTotal = $this->db->get();

		$this->db->from('tb_usuario');
		$this->db->join('tb_grupo','tb_usuario.cod_grupo = tb_grupo.cod_grupo');
		$this->db->join('tb_usuario_puntoventa','tb_usuario.cod_usu = tb_usuario_puntoventa.cod_usu AND pordefecto = 1','left');
		$this->db->join('tb_puntoventa','tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa.cod_puntoventa','left');
		if ($data['grupo']!='') {
			$this->db->where('tb_usuario.cod_grupo',$data['grupo']);
		}

		$queryLike = $this->db->get();


		$this->db->from('tb_usuario');
		$this->db->select('tb_usuario.cod_usu, nomb_usu,apell_usu,nombre_grupo,nomb_puntoventa');
		$this->db->join('tb_grupo','tb_usuario.cod_grupo = tb_grupo.cod_grupo');
		$this->db->join('tb_usuario_puntoventa','tb_usuario.cod_usu = tb_usuario_puntoventa.cod_usu  AND pordefecto = 1','left');
		$this->db->join('tb_puntoventa','tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa.cod_puntoventa','left');
		if ($data['grupo']!='') {
			$this->db->where('tb_usuario.cod_grupo',$data['grupo']);
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
			$asignar = '<button data-id="'.$q->cod_usu.'" class="btn btn-primary btn-sm asignarPuntoVenta" data-toggle="modal" data-target="#ModalAsignarPuntoVenta">Asignar</button>';
			$row[] = [$q->cod_usu,$q->nomb_usu,$q->apell_usu,$q->nombre_grupo,$q->nomb_puntoventa,$asignar];
		}
		$result['aaData'] = $row;
		return $result;
	}

}

/* End of file asignpuntoventa_model.php */
/* Location: ./application/models/asignpuntoventa_model.php */