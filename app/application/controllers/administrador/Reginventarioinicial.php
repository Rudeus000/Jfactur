<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reginventarioinicial extends CI_Controller
{

	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventarioinicial_model');
		$this->load->model('modelgeneral');
		$this->load->helper('general');
		$this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['permisos'] = $this->permisos;
		$data['almacenes'] = $this->modelgeneral->getTableWhere('tb_almacen', ['est_almacen' => 1]);
		$data['categorias'] = $this->modelgeneral->getTable('tb_categoria');
		$data['marcas'] = $this->modelgeneral->getTable('tb_marca');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/inventarioinicial/listgetinventario', $data);
		$this->load->view('layouts/footer');
	}

	public function jsonInventarioInicial()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho'] = $this->input->get_post('_', true);

		$columns = ['nomb_product', 'nomb_marca'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		// Verificar si se seleccionó un almacén
		$almacen = $this->input->get_post('almacen');
		if ($almacen != '') {
			$data['almacen'] = $almacen;
		} else {
			// Si no se seleccionó un almacén, establece $data['almacen'] como null
			$data['almacen'] = null;
		}

		$producto = $this->input->get_post('producto');
		if ($producto != '') {
			$data['producto'] = $producto;
		}

		$categoria = $this->input->get_post('categoria');
		if ($categoria != '') {
			$data['categoria'] = $categoria;
		}

		$marca = $this->input->get_post('marca');
		if ($marca != '') {
			$data['marca'] = $marca;
		}

		$datos = $this->inventarioinicial_model->getProductos($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}


	public function guardarStockInicial()
	{
		$producto = $this->input->get('producto');
		$data['cod_producto'] = $this->input->get('producto');

		// Obtener el código de almacén para "almacen central" si no se proporciona uno
		$selectedAlmacen = $this->input->get('almacen');
		if (empty($selectedAlmacen)) {
			$almacenCentral = $this->db->get_where('tb_almacen', ['nomb_almacen' => 'almacen central', 'est_almacen' => 1])->row();
			if ($almacenCentral) {
				$data['cod_almacen'] = $almacenCentral->cod_almacen;
			} else {
				// Handle the case where "almacen central" is not found
				$data['cod_almacen'] = 1;
			}
		} else {
			$data['cod_almacen'] = $selectedAlmacen;
		}

		$data['stock_inicial'] = $this->input->get('stock');
		$data['stock'] = $this->input->get('stock');

		$insert = $this->modelgeneral->insertRegist('tb_producto_stock', $data);


		$seriesJSON = $this->input->get('series');
		$seriesData = json_decode($seriesJSON, true);

		// Extract series for the specific product
		$productoID = $this->input->get('producto');
		$series = [];

		if (isset($seriesData['producto-' . $productoID])) {
			// If it's an array of series, use it directly
			if (is_array($seriesData['producto-' . $productoID])) {
				$series = $seriesData['producto-' . $productoID];
			} else {
				// If it's a single serie, convert it to an array
				$series = [$seriesData['producto-' . $productoID]];
			}
		}

		if (!empty($series)) {
			foreach ($series as $serie) {
				// Here, you can process each serie and save it accordingly
				// For example:
				$dataSerie = array(
					'serie_descripcion' => $serie,
					'cod_producto' => $data['cod_producto'],
					'cod_almacen' => $data['cod_almacen']
				);
				$this->modelgeneral->insertRegist('tb_producto_serie', $dataSerie);
			}
		}
		$resp = [];
		if (!is_null($insert)) {
			$resp['success'] = true;
		} else {
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}


	public function verificarSerieUnico()
	{
		$serie = $this->input->post('serie');
		$query = $this->db->from('tb_producto_serie')
			->where('serie_descripcion', $serie)
			->join('tb_producto', 'tb_producto_serie.cod_producto = tb_producto.cod_producto')
			->get();
		$resp = [];
		if ($query->num_rows() == 0) {
			$resp['success'] = true;
		} else {
			$resp['success'] = false;
			$resp['serie'] = $serie;
		}
		echo json_encode($resp);
	}
	public function guardarSeries($series, $producto, $almacen)
	{
		// $num = 1;
		// foreach ($series as $key => $value) {
		if (is_array($series)) {
			$num = 1;
			foreach ($series as $key => $value) {
				$data['cod_producto'] = $producto;
				$data['cod_almacen'] = $almacen;
				$data['serie_descripcion'] = $value;
				$data['cod_comp'] = null;
				$data['cod_vent'] = null;
				$data['serie_estado'] = 'D';
				$data['histcompstock_serie'] = $num;
				$this->modelgeneral->insertRegist('tb_producto_serie', $data);
				$num++;
			}
		} else {
			$data['cod_producto'] = $producto;
			$data['cod_almacen'] = $almacen;
			$data['serie_descripcion'] = $series;
			$data['cod_comp'] = null;
			$data['cod_vent'] = null;
			$data['serie_estado'] = 'D';
			$data['histcompstock_serie'] = 1;
			$this->modelgeneral->insertRegist('tb_producto_serie', $data);
			// $num++;


		}
	}
	public function reportePdf()
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4',
			'orientation' => 'L',
			'margin_left' => 10,
			'margin_right' => 10,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_header' => 10,
			'margin_footer' => 10
		]);

		$data['almacen'] = $this->modelgeneral->getTableWhereRow('tb_almacen', ['cod_almacen' => $this->input->get('almacen')]);
		$data['datos'] = $this->getInventarioInicialReporte();
		$html = $this->load->view('admin/inventarioinicial/reporte_pdf', $data, TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css, 1);
		$this->mpdf->writeHTML($html, 2);
		$this->mpdf->Output('Reporte', 'I');
	}

	function reporteExcel()
	{
		$data['datos'] = $this->getInventarioInicialReporte();
		$this->load->view('admin/inventarioinicial/reporte_excel', $data);
	}

	function getInventarioInicialReporte()
	{
		$this->db->from('tb_producto');
		$this->db->select('tb_producto.*, nomb_marca, nomb_categoria, nomb_unid, tb_producto_stock.stock_inicial, stock, tb_producto_stock.cod_almacen, nomb_almacen,fecha_registro, est_product');
		$this->db->join('tb_marca', 'tb_producto.cod_marca = tb_marca.cod_marca');
		$this->db->join('tb_categoria', 'tb_producto.cod_categoria = tb_categoria.cod_categoria');
		$this->db->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid');
		$this->db->join('tb_producto_stock', 'tb_producto.cod_producto = tb_producto_stock.cod_producto', 'left');
		$this->db->join('tb_almacen', 'tb_producto_stock.cod_almacen = tb_almacen.cod_almacen', 'left'); // Join para obtener nomb_almacen

		// Filtrar por almacén si se ha seleccionado uno
		$almacen = $this->input->get('almacen');
		if ($almacen !== null) {
			$this->db->like('tb_producto_stock.cod_almacen', $almacen);
		}

		if ($this->input->get('producto') != '') {
			$this->db->like('nomb_product', $this->input->get('producto'));
		}
		if ($this->input->get('categoria') != '') {
			$this->db->where('tb_categoria.cod_categoria', $this->input->get('categoria'));
		}
		if ($this->input->get('marca') != '') {
			$this->db->where('tb_marca.cod_marca', $this->input->get('marca'));
		}

		return $this->db->get()->result();
	}



	function getSeriesInventario()
	{
		$producto = $this->input->get('producto');
		$almacen = $this->input->get('almacen');

		$query = $this->db->from('tb_producto_serie')
			->where('cod_producto', $producto)
			->where('cod_almacen', $almacen)
			->get()->result();
		header('content-type: application/json; charset=utf-8');
		echo json_encode($query);
	}

	public function actualizarSerieInventario()
	{
		// Obtener los datos enviados desde la solicitud AJAX
		$serieId = $this->input->post('serie_id');
		$nuevaDescripcion = $this->input->post('nueva_descripcion');
		$nuevoEstado = $this->input->post('nuevo_estado');

		// Realizar la actualización en la base de datos
		$datosActualizar = array(
			'serie_descripcion' => $nuevaDescripcion,
			'serie_estado' => $nuevoEstado
		);

		$this->db->where('serie_id', $serieId);
		$this->db->update('tb_producto_serie', $datosActualizar);

		// Devolver la respuesta al cliente (puede ser un mensaje de éxito/error)
		$respuesta = array('mensaje' => 'Actualización exitosa');
		header('Content-Type: application/json');
		echo json_encode($respuesta);
	}




	function reporteExcelSeries()
	{
		$data['datos'] = $this->getInventarioInicialReporteseries();
		// Imprimir los datos para depuración
		// foreach ($data['datos'] as $d) {
		// 	echo $d->cod_producto . " | " . $d->nomb_product . " | " . $d->serie_estado . " | " . $d->fecha_ventas . "<br>";
		// 	exit();
		// }

		$this->load->view('admin/inventarioinicial/reporte_excel_series', $data);
	}

	function getInventarioInicialReporteseries()
	{
		$this->db->select('tb_producto.*, nomb_almacen, serie_descripcion, cod_comp, cod_vent, serie_estado, histcompstock_serie,fecha_registro,prec_costo,prec_venta,fecha_venta');
		$this->db->from('tb_producto');
		$this->db->join('tb_marca', 'tb_producto.cod_marca = tb_marca.cod_marca');
		$this->db->join('tb_categoria', 'tb_producto.cod_categoria = tb_categoria.cod_categoria');
		$this->db->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid');
		$this->db->join('tb_producto_serie', 'tb_producto.cod_producto = tb_producto_serie.cod_producto');
		$this->db->join('tb_almacen', 'tb_producto_serie.cod_almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_producto_stock', 'tb_producto.cod_producto = tb_producto_stock.cod_producto AND tb_producto_serie.cod_almacen = tb_producto_stock.cod_almacen', 'left');
		if ($this->input->get('producto') != '') {
			$this->db->like('nomb_product', $this->input->get('producto'));
		}

		if ($this->input->get('categoria') != '') {
			$this->db->where('tb_categoria.cod_categoria', $this->input->get('categoria'));
		}

		if ($this->input->get('marca') != '') {
			$this->db->where('tb_marca.cod_marca', $this->input->get('marca'));
		}

		// Filtrar por almacén si se ha seleccionado uno
		$almacen = $this->input->get('almacen');
		if ($almacen !== null) {
			$this->db->like('tb_producto_stock.cod_almacen', $almacen);
		}

		$this->db->distinct();

		return $this->db->get()->result();
	}

	public function getFechasProducto()
	{
		$query = $this->db->from('tb_producto_fecha')
			->select('tb_producto_fecha.*')
			->join('tb_producto', 'tb_producto_fecha.cod_producto = tb_producto.cod_producto')
			->join('tb_almacen', 'tb_producto_fecha.cod_almacen = tb_almacen.cod_almacen')
			->where('tb_producto_fecha.cod_producto', $this->input->post('producto'))
			->where('tb_producto_fecha.cod_almacen', $this->input->post('almacen'))
			->where('cantidad_prodfec !=', '0')
			->get()->result();

		header('content-type: application/json; charset=utf-8');
		echo json_encode($query);
	}

	public function productoFechaGuardar()
	{
		$data['cantidad_prodfec'] = $this->input->post('cantidad');
		$data['cantidad_inicial_prodfec'] = $this->input->post('cantidad');
		$data['fecha_produccion_prodfec'] = $this->input->post('fecha_produccion');
		$data['fecha_vencimiento_prodfec'] = $this->input->post('fecha_vencimiento');
		$data['fecha_alerta_prodfec'] = $this->input->post('fecha_alerta');
		$data['cod_producto'] = $this->input->post('producto');
		$data['cod_almacen'] = $this->input->post('almacen');
		$insert = $this->modelgeneral->insertRegist('tb_producto_fecha', $data);
		$resp = [];
		if (!is_null($insert)) {
			$resp['success'] = true;
		} else {
			$resp['success'] = false;
		}
		echo json_encode($resp);
	}

	public function productoFechaEliminar()
	{
		$this->db->where('cod_prodfec', $this->input->get('id'))
			->delete('tb_producto_fecha');

		$response = [];
		$response['success'] = true;
		header('content-type: application/json; charset=utf-8');
		echo json_encode($response);
	}
}
/* End of file Reginventarioinicial.php */
/* Location: ./application/controllers/administrador/Reginventarioinicial.php */
