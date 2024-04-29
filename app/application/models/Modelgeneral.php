<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modelgeneral extends CI_Model {

	function getTable($table,$select=NULL,$orderCamp=NULL,$order=NULL)
	{
		if (!is_null($select)) {
			$this->db->select($select);
		}
		if (!is_null($orderCamp)) {
			$this->db->order_by($orderCamp,$order);
		}
		$query = $this->db->get($table);
		return $query->result();
	}

	function getTableLocacionesAeropuerto()
	{
		return $this->db->from('locaciones')
		->join('aeropuertos','locaciones.AerId = aeropuertos.AerId')
		->get()
		->result();
	}

	function getTableWhere($table,$wheres=NULL,$select=NULL)
	{
		if (!is_null($select)) {
			$this->db->select($select);
		}
		$this->db->where($wheres);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->result();
	}

	function getTableWhereRow($table,$wheres=NULL,$select=NULL)
	{
		if (!is_null($select)) {
			$this->db->select($select);
		}
		$this->db->where($wheres);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->row();
	}

	function getTableWhereRowArray($table,$wheres=NULL,$select=NULL)
	{
		if (!is_null($select)) {
			$this->db->select($select);
		}
		$this->db->where($wheres);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->row_array();
	}

	function getTableLikeRow($table,$likes=NULL,$select=NULL)
	{
		if (!is_null($select)) {
			$this->db->select($select);
		}
		$this->db->like($likes);
		$this->db->from($table);
		$query = $this->db->get();
		return $query->row();
	}

	function getTableLimit($table,$cant,$indice,$id=NULL,$orden=NULL)
	{
		if ($id  != '' and $orden  != '') {
			$this->db->order_by($id,$orden);
		}

		$this->db->limit($cant,$indice);
		$query = $this->db->get($table);
		return $query->result();
	}

	function getTableLimitWhere($table,$cant,$indice,$campo,$valor,$id=NULL,$orden=NULL)
	{
		if ($id != '' and $orden != '') {
			$this->db->order_by($id,$orden);
		}
		if ($campo != '' and $valor != '') {
			$this->db->where($campo,$valor);
		}

		$this->db->limit($cant,$indice);
		$query = $this->db->get($table);
		return $query->result();
	}

	function getTotalRows($table)
	{
		$query = $this->db->get($table);
		$rows = $query->num_rows();
		return $rows;
	}

	function getTotalRowsWhere($table,$campo,$valor)
	{
		$this->db->where($campo,$valor);
		$query = $this->db->get($table);
		$rows = $query->num_rows();
		return $rows;
	}

	function insertRegist($table,$data)
	{
		$this->db->insert($table,$data);
		if ($this->db->affected_rows()===1) {
			return $this->db->insert_id();
		}
		return NULL;
	}

	function editRegist($table,$wheres,$data)
	{
		$this->db->where($wheres);
		$this->db->update($table,$data);
		if ($this->db->affected_rows()===1 || $this->db->affected_rows()===0) {
			return TRUE;
		}else{
			return NULL;
		}
	}

	function deleteRegist($table,$wheres)
	{
		$this->db->where($wheres);
		$this->db->delete($table);
		if ($this->db->affected_rows()===1) {
			return TRUE;
		}else{
			return NULL;
		}
	}

	function getTableWhereNotIn($table,$campo,$array)
	{
		$this->db->from($table);
		$this->db->or_where_not_in($campo,$array);
		$query = $this->db->get();
		return $query->result();
	}

	function getAdministrador()
	{
		return $this->db->from('usuarios')
		->where('EmpId',$this->session->userdata('IdEmpresa'))
		->where('(usuarios.UsrTipo = '.SUPER_USUARIO.' OR usuarios.UsrTipo = '.ADMINISTRADOR.' OR usuarios.UsrTipo = '.SUPERVISOR_GENERAL.')',NULL)
		->get()
		->result();
	}
	function obtenerPermiso($campo)
	{
		$query = $this->db->from('permisos')
		->select($campo)
		->where('UsrId',$this->session->userdata('IdUsuario'))
		->get()
		->row();
		return $query->$campo;
	}

	function verificaUnico($tabla,$campo,$valor,$idCampo=NULL,$idValor=NULL)
	{
		$this->db->from($tabla);
		$this->db->where($campo,$valor);
		if (!is_null($idCampo)) {
			$this->db->where_not_in($idCampo,$idValor);
		}
		$query = $this->db->get();
		if ($query->num_rows()==0) {
			return true;
		} else {
			return false;
		}

	}

	function getSecuencia($table,$campo,$whereCampo=NULL,$whereValor=NULL)
	{
		$this->db->select_max($campo);
		$this->db->from($table);
		if (!is_null($whereCampo)) {
			$this->db->where($whereCampo,$whereValor);
		}
		$query = $this->db->get();

		if($query->num_rows() > 0){
			return $query->row($campo) + 1;
		}else{
			return 1;
		}
	}

	function getSecuenciaNotas($serie,$tipo)
	{
		$query = $this->db->from('tb_nota')
		->where('seriecomp_nota',$serie)
		->where('tiponota_nota',$tipo)
		->limit(1)
		->order_by('numcomp_nota','desc')
		->get();
		if($query->num_rows() > 0){
			return $query->row('numcomp_nota') + 1;
		}else{
			return 1;
		}
	}
	public function insertarToken($tabla, $data)
    {
        $this->db->insert($tabla, $data);
        return $this->db->insert_id(); // Devolver el ID del último registro insertado
    }



}

/* End of file Modelgeneral.php */
/* Location: ./application/models/Modelgeneral.php */
