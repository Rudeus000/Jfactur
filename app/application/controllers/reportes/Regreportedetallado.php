<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Regreportedetallado extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->model('reportedetallado_model');
		$this->permisos = $this->backend_lib->control();
	}
	private $permisos;
	public function compras()
	{
		$data['permisos'] = $this->permisos;
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('reports/comprasdetalladas');
		$this->load->view('layouts/footer');
	}

	public function jsonCompras()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho'] = $this->input->get_post('_', true);
		$columns = ['fecha_comp', 'fecha_comp'];
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

		if ($desde != '' and $hasta != '') {
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
		$this->load->view('reports/comprasdetalladasexcel', $data);
	}

	public function ventas()
	{
		$data['vendedores'] = $this->reportedetallado_model->getVendedores();
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('reports/ventasdetalladas', $data);
		$this->load->view('layouts/footer');
	}

	function jsonVentas()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho'] = $this->input->get_post('_', true);
		$columns = ['fecha_vent', 'fecha_vent'];
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
		// $vendedor = $this->input->get_post('vendedor');

		if ($desde != '' and $hasta != '') {
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
		// $data['vendedor'] = $vendedor;

		$datos = $this->reportedetallado_model->getVentas($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	function ventasDetalladasExcel()
	{
		$data['desde'] = $this->input->get('desde');
		$data['hasta'] = $this->input->get('hasta');
		$data['cliente'] = $this->input->get('cliente');
		if ($data['vendedorcod'] = $this->input->get('vendedorcod')) {
			$data['vendedorcod'] = $this->input->get('vendedorcod');

		} else {
			$data['vendedorcod'] = $this->input->get('vendedorcod');

		}
		$data['vendedor'] = $this->input->get('vendedor');
		$data['almacen'] = $this->input->get('almacen');
		$data['datos'] = $this->reportedetallado_model->getVentasDetalladasExcel($data);
		$this->load->view('reports/ventasdetalladasexcel', $data);
	}


	public function compararExcel() {
		if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
			$rutaTemporal = $_FILES['archivo']['tmp_name'];
			$nombreArchivo = $_FILES['archivo']['name'];
			$extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
	
			// Obtén las fechas de inicio y fin desde la solicitud POST
			$fechaInicio = $this->input->post('fecha_inicio');
			$fechaFin = $this->input->post('fecha_fin');
	
			try {
				if ($extension === 'xlsx') {
					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
				} elseif ($extension === 'xls') {
					$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
				} else {
					throw new \Exception("Formato de archivo no compatible. Usa un archivo .xlsx o .xls");
				}
	
				// Cargar el archivo
				$spreadsheet = $reader->load($rutaTemporal);
				$worksheet = $spreadsheet->getSheetByName('Sheet 0');
	
				if ($worksheet === null) {
					throw new \Exception("La hoja 'Sheet 0' no se encuentra en el archivo.");
				}
	
				$isdnNoEncontrados = [];
	
				foreach ($worksheet->getRowIterator(8) as $row) {
					$isdnCell = $worksheet->getCell("Q" . $row->getRowIndex())->getValue();
	
					// Verifica que el valor de la celda ISDN no esté vacío
					if (empty($isdnCell)) {
						continue; // Salta a la siguiente fila si está vacía
					}
	
					$codigoTienda = $worksheet->getCell("D" . $row->getRowIndex())->getValue();
					$tipoCanal = $worksheet->getCell("E" . $row->getRowIndex())->getValue();
					$tipoTransaccion = $worksheet->getCell("S" . $row->getRowIndex())->getValue();
					$cantidad = $worksheet->getCell("O" . $row->getRowIndex())->getValue();
	
					// Verificar si el ISDN existe en la base de datos dentro del rango de fechas
					if (!$this->reportedetallado_model->verificarISDNConFecha($isdnCell, $fechaInicio, $fechaFin)) {
						$isdnNoEncontrados[] = [
							'isdn' => $isdnCell,
							'codigoTienda' => $codigoTienda,
							'tipoCanal' => $tipoCanal,
							'tipoTransaccion' => $tipoTransaccion,
							'cantidad' => $cantidad
						];
					}
				}
	
				if (!empty($isdnNoEncontrados)) {
					echo json_encode([
						'mensaje' => 'Existen operaciones no registradas  en el sistema en el rango de fechas especificado',
						'isdnData' => $isdnNoEncontrados,
						'total' => count($isdnNoEncontrados)
					]);
				} else {
					echo json_encode([
						'mensaje' => 'Todos las operaciones fueron regitrados en el sistema en el rango de fechas especificado.',
						'isdnData' => [],
						'total' => 0
					]);
				}
	
			} catch (\Exception $e) {
				echo json_encode([
					'mensaje' => '<p style="color:red;">Error al procesar el archivo: ' . $e->getMessage() . '</p>'
				]);
			}
		} else {
			echo json_encode([
				'mensaje' => '<p style="color:red;">Error al subir el archivo. Verifica e inténtalo nuevamente.</p>'
			]);
		}
	}
	
	

}

/* End of file Regreportedetallado.php */
