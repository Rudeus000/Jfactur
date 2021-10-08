<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
/**
* 
*/

/**
 * 
 */
class Reportdashboard_model extends CI_Model
{
	
	public function years()
	{
		$this->db->select("YEAR(fecha_vent) as year");
		$this->db->from('tb_venta');
		$this->db->group_by("year");
		$this->db->order_by("year","desc");
		$resultados = $this->db->get();
		return $resultados->result(); 
	}

       public function GeTventasTotales($year){
		$this->db->select("MONTH(fecha_vent) as mes, sum(total_vent) as montos");
		$this->db->from('tb_venta');
		$this->db->where("fecha_vent >=",$year."-01-01");
		$this->db->where("fecha_vent <=",$year."-12-31");
		$this->db->where('estado_vent','G');
	//	$this->db->where('estado_tra','1');
		$this->db->group_by("mes");
		$this->db->order_by("mes");
			if ($this->session->userdata('puntoventa_reportes')!='admin') {
			$this->db->where('cod_puntoventa',$this->session->userdata('puntoventa_reportes'));
		}
		$resultados = $this->db->get();
		return $resultados->result();
	}

	public function rowCountVentasContado()
	{
		$this->db->where('pago_vent','CO');
	    $this->db->from('tb_venta');
	    $resultados = $this->db->get();
		return $resultados->num_rows();
	}

	public function rowCountVentasDia()
	{
		
		$this->db->select("SUM(total_vent) as totalDia");
	    $this->db->from('tb_venta');
	    $this->db->where('fecha_vent',date("Y-m-d"));
	    $this->db->where('pago_vent','CO');
	     // $this->db->where('estado_vent','G');
	     	 $this->db->where('estado_vent','G');
			if ($this->session->userdata('puntoventa_reportes')!='admin') {
				$this->db->where('cod_puntoventa',$this->session->userdata('puntoventa_reportes'));
			}
	    $resultados = $this->db->get();
		return $resultados->result()[0]->totalDia;
	}

	public function rowCountVentasCredito()
	{
		
		$this->db->select("SUM(total_vent) as totalDia");
	    $this->db->from('tb_venta');
	    $this->db->where('fecha_vent',date("Y-m-d"));
	    $this->db->where('pago_vent','CRE');
	    $this->db->where('estado_vent','G');	    
			if ($this->session->userdata('puntoventa_reportes')!='admin') {
				$this->db->where('cod_puntoventa',$this->session->userdata('puntoventa_reportes'));
			}
	    $resultados = $this->db->get();
		return $resultados->result()[0]->totalDia;
	}

	public function rowCountClientes()
	{
		$this->db->where('estado_cliente','1');
	    $this->db->from('tb_cliente');
	    $resultados = $this->db->get();
		return $resultados->num_rows();
	}

	public function rowCountComprasDia()
	{
		
		$this->db->select("SUM(total_comp) as totalDia");
	    $this->db->from('tb_compra');
	    $this->db->where('fecha_comp',date("Y-m-d"));
	    $this->db->where('estado_comp','1');
	    $resultados = $this->db->get();
		return $resultados->result()[0]->totalDia;
	}


	public function rowSumproductvent()
	{
		$this->db->select("YEAR(fecha_vent) as year");
		$this->db->from('tb_venta');
		$this->db->group_by("year");
		$this->db->order_by("year","desc");
		$resultados = $this->db->get();
		return $resultados->result(); 
	}

}