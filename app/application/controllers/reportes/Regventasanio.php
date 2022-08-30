<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regventasanio extends CI_Controller {

	public function index()
	{
		$data['anios'] = $this->db->from('tb_cobro')
											->select('YEAR(fecha_cobro) as anio')
											->group_by('YEAR(fecha_cobro)')
											->get()->result();
	$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('reports/reportventanio',$data); 
	// $this->load->view('reports/reportcompranio',$data);     
    $this->load->view('layouts/footer');
	}

	public function jsonVentas()
	{
		$anio = $this->input->get('anio');
		$this->db->from('tb_cobro');
		$this->db->select("
		CASE 
			WHEN MONTH(fecha_cobro) = 1 THEN 'Enero'
			WHEN MONTH(fecha_cobro) = 2 THEN 'Febrero'
			WHEN MONTH(fecha_cobro) = 3 THEN 'Marzo'
			WHEN MONTH(fecha_cobro) = 4 THEN 'Abril'
			WHEN MONTH(fecha_cobro) = 5 THEN 'Mayo'
			WHEN MONTH(fecha_cobro) = 6 THEN 'Junio'
			WHEN MONTH(fecha_cobro) = 7 THEN 'Julio'
			WHEN MONTH(fecha_cobro) = 8 THEN 'Agosto'
			WHEN MONTH(fecha_cobro) = 9 THEN 'Septiembre'
			WHEN MONTH(fecha_cobro) = 10 THEN 'Octubre'
			WHEN MONTH(fecha_cobro) = 11 THEN 'Noviembre'
			WHEN MONTH(fecha_cobro) = 12 THEN 'Diciembre'
			END as mes
			, SUM(monto_cobro) as monto",NULL);
		if (isset($_GET['Contado'])) {
			$this->db->or_where('tipo_cobro','Contado');
		}
		if (isset($_GET['Credito'])) {
			$this->db->or_where('tipo_cobro','Credito');
		}
		
		$this->db->group_by('YEAR(fecha_cobro), MONTH(fecha_cobro)');
		$this->db->where('YEAR(fecha_cobro)',$anio);
		$query = $this->db->get()->result();

		echo json_encode($query);
	}
	public function Compras()
	{
		$data['anios'] = $this->db->from('tb_compra')
											->select('YEAR(fecha_comp) as anio')
											->group_by('YEAR(fecha_comp)')
											->get()->result();
	$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('reports/reportcompranio',$data); 
	// $this->load->view('reports/reportcompranio',$data);     
    $this->load->view('layouts/footer');
	}

	public function jsonCompras()
	{
		$anio = $this->input->get('anio');
		$this->db->from('tb_compra');
		$this->db->select("
		CASE 
			WHEN MONTH(fecha_comp) = 1 THEN 'Enero'
			WHEN MONTH(fecha_comp) = 2 THEN 'Febrero'
			WHEN MONTH(fecha_comp) = 3 THEN 'Marzo'
			WHEN MONTH(fecha_comp) = 4 THEN 'Abril'
			WHEN MONTH(fecha_comp) = 5 THEN 'Mayo'
			WHEN MONTH(fecha_comp) = 6 THEN 'Junio'
			WHEN MONTH(fecha_comp) = 7 THEN 'Julio'
			WHEN MONTH(fecha_comp) = 8 THEN 'Agosto'
			WHEN MONTH(fecha_comp) = 9 THEN 'Septiembre'
			WHEN MONTH(fecha_comp) = 10 THEN 'Octubre'
			WHEN MONTH(fecha_comp) = 11 THEN 'Noviembre'
			WHEN MONTH(fecha_comp) = 12 THEN 'Diciembre'
			END as mes
			, SUM(total_comp) as monto",NULL);
		if (isset($_GET['CO'])) {
			$this->db->or_where('pago_comp','CO');
		}
		if (isset($_GET['CRE'])) {
			$this->db->or_where('pago_comp','CRE');
		}
		
		$this->db->group_by('YEAR(fecha_comp), MONTH(fecha_comp)');
		$this->db->where('YEAR(fecha_comp)',$anio);
		$query = $this->db->get()->result();

		echo json_encode($query);
	}

}

/* End of file Regventasanio.php */
/* Location: ./application/controllers/reportes/Regventasanio.php */