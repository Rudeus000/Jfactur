<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regreportedetallado extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reportedetallado_model');
	}
	
	public function compras()
	{
		$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('reports/comprasdetalladas');    
    $this->load->view('layouts/footer');
	}

	public function jsonCompras()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
	    $data['sEcho']  = $this->input->get_post('_', true);
	    $columns= ['fecha_comp','fecha_comp'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$desde = $this->input->get_post('desde');
		$hasta = $this->input->get_post('hasta');
		$proveedor = $this->input->get_post('proveedor');
		$almacen = $this->input->get_post('almacen');

		if ($desde!='' AND $hasta!='') {
			$data['desde'] = $desde;
			$data['hasta'] = $hasta;
		}
		// if ($proveedor!='') {
		// 	$data['proveedor'] = $proveedor;
		// }
		// if ($almacen!='') {
		// 	$data['almacen'] = $almacen;
		// }
		$data['proveedor'] = $proveedor;
		$data['almacen'] = $almacen;
	
		$datos = $this->reportedetallado_model->getCompras($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	function comprasDetalladasExcel()
	{
		$data['desde'] = $this->input->get('desde');
		$data['hasta'] = $this->input->get('hasta');
		$data['proveedor'] = $this->input->get('proveedor');
		$data['almacen'] = $this->input->get('almacen');
		$data['datos'] = $this->reportedetallado_model->getComprasDetalladasExcel($data);
		//var_dump($data['datos']);
		$this->load->view('reports/comprasdetalladasexcel',$data);
	}

	public function ventas()
	{
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('reports/ventasdetalladas');    
		$this->load->view('layouts/footer');
	}

	function jsonVentas()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
	    $data['sEcho']  = $this->input->get_post('_', true);
	    $columns= ['fecha_vent','fecha_vent'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$desde = $this->input->get_post('desde');
		$hasta = $this->input->get_post('hasta');
		$almacen = $this->input->get_post('almacen');
		$cliente = $this->input->get_post('cliente');
		$vendedor = $this->input->get_post('vendedor');

		if ($desde!='' AND $hasta!='') {
			$data['desde'] = $desde;
			$data['hasta'] = $hasta;
		}
		// if ($cliente!='') {
		// 	$data['cliente'] = $cliente;
		// }
		// if ($vendedor!='') {
		// 	$data['vendedor'] = $vendedor;
		// }
		// if ($almacen!='') {
		// 	$data['almacen'] = $almacen;
		// }
		$data['cliente'] = $cliente;
		$data['vendedor'] = $vendedor;
		$data['almacen'] = $almacen;
	
		$datos = $this->reportedetallado_model->getVentas($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	function ventasDetalladasExcel()
	{
		$data['desde'] = $this->input->get('desde');
		$data['hasta'] = $this->input->get('hasta');
		$data['cliente'] = $this->input->get('cliente');
		$data['vendedor'] = $this->input->get('vendedor');
		$data['almacen'] = $this->input->get('almacen');
		$data['datos'] = $this->reportedetallado_model->getVentasDetalladasExcel($data);
		$this->load->view('reports/ventasdetalladasexcel',$data);
	}

}

/* End of file Regreportedetallado.php */
