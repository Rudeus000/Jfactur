<?php 
@session_start();
class Motivorecepcion_model extends CI_Model{
	 function __construct()
	 { 
		 parent::__construct();
	 } 
	 public function fillallmotivorecepcion($params=NULL){
		 $store=$this->prepareStore('call fillallmotivorecepcion',$params);
		 $consulta = $this->db->query($store);
		 return $consulta->result_array();
		 } 
	 public function Rmvmotivorecepcion($params=NULL){
		 $store=$this->prepareStore('call Eliminar_motivorecepcion',$params);
		 $consulta = $this->db->query($store);
		 return $consulta->result_array();
	 } 
	 public function Findmotivorecepcion($params=NULL){
		 $store=$this->prepareStore('call Buscar_alm_motivorecepcion',$params);
		 $consulta = $this->db->query($store);
		 return $consulta->result_array();
	 } 
	 public function Insmotivorecepcion($params=NULL){
		 $store=$this->prepareStore('call Insertar_alm_motivorecepcion',$params);
		 $consulta = $this->db->query($store);
	 } 
	 public function Editmotivorecepcion($params=NULL){
		 $store=$this->prepareStore('call Editar_alm_motivorecepcion',$params);
		 $consulta = $this->db->query($store);
	 } 
	}
?>