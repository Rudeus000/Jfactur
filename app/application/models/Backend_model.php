<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Backend_model extends CI_Model
{
	public function getID($link){
		$this->db->like("link",$link);
		$resultado = $this->db->get("menus");
		return $resultado->row();

	}

	public function getPermisos($menu,$perfil){
		$this->db->where("id_menu",$menu);
		$this->db->where("cod_perfil",$perfil);
		$resultado = $this->db->get("permisos");
		return $resultado->row();
	}
}