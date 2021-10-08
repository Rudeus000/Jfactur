<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pventa_model extends CI_Model
{

	 function getPventa($data)
    {
    $this->db->from('tb_puntoventa');
	$queryTotal= $this->db->get();

	$this->db->from('tb_puntoventa');
	$this->db->join('tb_almacen','tb_puntoventa.cod_almacen = tb_almacen.cod_almacen');
    $this->db->join('tb_caja','tb_puntoventa.cod_caja = tb_caja.cod_caja');
    $this->db->join('tb_impresora','tb_puntoventa.cod_impresora = tb_impresora.cod_impresora');
    $this->db->join('sede','tb_puntoventa.cod_sede = sede.cod_sede');
    $this->db->select('tb_puntoventa.* , nomb_puntoventa as punto, 
        nomb_almacen as almacen, nomb_caja as caja, nom_impresora as impresora, sede_nombre as sede');
		
	if (isset($data['tb_puntoventa'])) {
			$this->db->having("nomb_puntoventa LIKE '%".$data['tb_puntoventa']."%'");
		}
	if (isset($data['tb_almacen'])) {
			$this->db->where('tb_almacen.cod_almacen',$data['tb_almacen']);
		}
	if (isset($data['sede'])) {
			$this->db->where('sede.cod_sede',$data['sede']);
		} 


	$queryLike = $this->db->get();

	$this->db->from('tb_puntoventa');
	$this->db->join('tb_almacen','tb_puntoventa.cod_almacen = tb_almacen.cod_almacen');
    $this->db->join('tb_caja','tb_puntoventa.cod_caja = tb_caja.cod_caja');
    $this->db->join('tb_impresora','tb_puntoventa.cod_impresora = tb_impresora.cod_impresora');
    $this->db->join('sede','tb_puntoventa.cod_sede = sede.cod_sede');
    $this->db->select('tb_puntoventa.* , nomb_puntoventa as punto, 
        nomb_almacen as almacen, nomb_caja as caja, nom_impresora as impresora, sede_nombre as sede');

	if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
		}

	if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		}

	if (isset($data['tb_puntoventa'])) {
			$this->db->having("nomb_puntoventa LIKE '%".$data['tb_puntoventa']."%'");
		}

	if (isset($data['tb_almacen'])) {
			$this->db->where('tb_almacen.cod_almacen',$data['tb_almacen']);
		}
	if (isset($data['sede'])) {
			$this->db->where('sede.cod_sede',$data['sede']);
		} 

	$query = $this->db->get();

	$result = array();
	$result['sEcho'] = $data['sEcho'];
	$result['iTotalRecords'] = $queryTotal->num_rows();
	$result['iTotalDisplayRecords'] = $queryLike->num_rows();


	$row = [];
	foreach ($query->result() as $q) {
		if ($q->estad_pto=='1') {
				$estado = '<label class="label label-success">Activo</label>';
		}elseif($q->estad_pto=='2'){
				$estado = '<label class="label label-danger">Inactivo</label>';
		}

		$defecto = '';
		if ($q->estad_pto==1) {
			
			if ($q->pordefecto_puntoventa) {
				$defecto = '<button title="Por defecto" class="btn btn-icon waves-effect waves-light btn-info btn-sm"><i class="fas fa-star"></i></button>';
			}else{
				$defecto = '<button data-id="'.$q->cod_puntoventa.'" title="Asignar por defecto" class="asignarPorDefecto btn btn-icon waves-effect waves-light btn-default btn-sm"><i class="far fa-star"></i></button>';
			}
		}

    $usuarios = $this->getUsuarios($q->cod_puntoventa);

		$botones = '<div class="btn-footer text-center">
		<button title="Asignar Almacen" data-id="'.$q->cod_puntoventa.'" class="asignar-almacen btn btn-icon"><i class="fas fa-dolly-flatbed"></i></button>
		<button title="Asignar Cajas" data-id="'.$q->cod_puntoventa.'" class="asignar-cajas btn btn-icon"><i class="fas fa-ion ion-ios-cash"></i></button>
		<a data-id="'.$q->cod_puntoventa.'" class="editar-punto on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarPventa" data-placement="top" ><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_puntoventa.'" class="anular-punto on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

    $boton_detalle = '<button class="btn btn-sm btn-icon" ><span class="fa fa-caret-right"></span></button>';
			$row[] = [$boton_detalle,$q->punto,$q->impresora,$q->sede,$estado,$defecto,$botones,json_encode($usuarios)];
		}
		$result['aaData'] = $row;
		return $result;

	}

	function getUsuarios($punto)
	{
		return $this->db->from('tb_usuario_puntoventa')
		->select('apell_usu,nomb_usu,nomb_perfil')
		->join('tb_usuario','tb_usuario_puntoventa.cod_usu = tb_usuario.cod_usu')
		->join('tb_perfil','tb_usuario.cod_perfil = tb_perfil.cod_perfil')
		->where('tb_usuario_puntoventa.cod_puntoventa',$punto)
		->where('tb_usuario_puntoventa.pordefecto',1)
		->get()->result();
	}
}