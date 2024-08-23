<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa_model extends CI_Model
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

      

		$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_puntoventa.'" class="editar-punto on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarPventa" data-placement="top" ><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_puntoventa.'" class="anular-punto on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';

     
			$row[] = [$q->punto,$q->almacen,$q->caja,$q->impresora,$q->sede,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;

	}

	 function getEmpresa($data=null){
		$this->db->from('tb_empresa');
		$this->db->where('cod_empresa',1);
		$empresa = $this->db->get()->row();
		return $empresa;
	}

	 function get_fechavence_emp() {
		// Calcular la fecha de vencimiento y la fecha de corte
		$this->db->select("
			cod_empresa,
			fecha_emp,
			CURDATE() as fecha_actual, 
			DATE_ADD(fecha_emp, INTERVAL 1 MONTH) as fecha_vencimiento,
			DATE_ADD(DATE_ADD(fecha_emp, INTERVAL 1 MONTH), INTERVAL 1 DAY) as fecha_corte,
			DATEDIFF(DATE_ADD(DATE_ADD(fecha_emp, INTERVAL 1 MONTH), INTERVAL 1 DAY), CURDATE()) as dias_restantes
		");
		$this->db->from('tb_empresa');
		// $this->db->having('fecha_vencimiento <=', date('Y-m-d'));
		// $this->db->having('fecha_corte >=', date('Y-m-d'));
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	
	
	public function desactivarPermisos() {
		$this->db->set('read', '0');
		$this->db->where('read', '1');
		$this->db->update('permisos');
	}
	

}
