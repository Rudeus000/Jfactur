<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Regtraspasos extends CI_Controller
{
	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('empresa_model');
		$this->load->model('traspasos_model');
		$this->load->model('modelgeneral');
		$this->load->helper('general');
		$this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['permisos'] = $this->permisos;
		$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/traspasos/panel', $data);
		$this->load->view('layouts/footer');
	}

	public function agregar()
	{

		$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/traspasos/agregar', $data);
		$this->load->view('layouts/footer');
	}

	public function getDestinos()
	{
		$origen = $this->input->get('origen');
		$query = $this->db->from('tb_almacen')
			->where_not_in('cod_almacen', [$origen])
			->get()->result();
		echo json_encode($query);
	}

	public function jsonTraspasos()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho'] = $this->input->get_post('_', true);
		$columns = ['fecha_tras'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['desde'] = $this->input->get_post('desde');
		$data['hasta'] = $this->input->get_post('hasta');
		$data['origen'] = $this->input->get_post('origen');
		$data['destino'] = $this->input->get_post('destino');

		$datos = $this->traspasos_model->getTraspasos($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	public function getProductoBusqueda()
	{
		$queryLike = $this->input->get('producto');
		$almacen = $this->input->get('origen');
		// $producto = $this->input->get('producto');
		$result = $this->db->from('tb_producto')
			->select('tb_producto.cod_producto as id,nomb_product as nombre,prec_costo as costo,prec_venta as venta,nomb_unid as unidad')
			->select('tb_producto.cod_producto as id,nomb_product as nombre,prec_costo as costo,prec_venta as venta,nomb_unid as unidad,(CASE WHEN stock > stockmin_product THEN 1 ELSE 0 END) as estado,cod_tiparticulo,stock')
			->join('tb_producto_stock', 'tb_producto_stock.cod_producto = tb_producto.cod_producto')
			->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
			->where('est_product', 1)
			// ->like('nomb_product',$producto)
			// ->get()->result();
			->where('cod_tiparticulo', 1)
			->where('(tb_producto_stock.cod_almacen = "' . $almacen . '" AND (nomb_product LIKE "%' . $queryLike
				. '%" OR barra_product LIKE "%' . $queryLike . '%"))', NULL)
			->where('(nomb_product LIKE "%' . $queryLike
				. '%" OR barra_product LIKE "%' . $queryLike . '%")', NULL)

			->get()->result_array();
		echo json_encode($result);
	}

	public function verificaCantidadTraspaso()
	{
		$origen = $this->input->get('origen');
		$destino = $this->input->get('destino');
		$producto = $this->input->get('producto');
		$cantidad = $this->input->get('cantidad');
		$query = $this->db->from('tb_producto')
			->join('tb_producto_stock', 'tb_producto.cod_producto = tb_producto_stock.cod_producto')
			->where('cod_almacen', $origen)
			->where('tb_producto.cod_producto', $producto)
			->get()->row();

		$queryDestino = $this->db->from('tb_producto')
			->join('tb_producto_stock', 'tb_producto.cod_producto = tb_producto_stock.cod_producto')
			->where('cod_almacen', $destino)
			->where('tb_producto.cod_producto', $producto)
			->get()->row();


		$resp = [];
		$resp['success'] = false;
		$resp['destino'] = true;
		$resp['mensaje_destino'] = 'El producto no existe en el almacen de destino';
		if (!is_null($queryDestino)) {
			$resp['destino'] = true;
		}
		if (!is_null($query)) {
			if (!is_null($query->stock)) {
				if ($query->stock >= $cantidad) {
					$stockTemp = $query->stock - $cantidad;
					if ($stockTemp >= $query->stockmin_product) {
						$resp['series'] = (isset($_GET['series'])) ? $this->getSeries($origen, $producto, $_GET['series']) : [];
						$resp['success'] = true;
						$resp['stock'] = $query->stock;
						$resp['producto'] = $this->getProducto($producto);
						$resp['actual'] = $stockTemp;
					} else {
						$resp['mensaje'] = 'La cantidad ingresada supera el stock mínimo.';
					}
				} else {
					$resp['mensaje'] = 'El producto no tiene stock suficiente';
				}
			} else {
				$resp['mensaje'] = 'El producto no tiene stock disponible';
			}
		} else {
			$resp['mensaje'] = 'El producto no esta disponible en el almacen de origen';
		}

		echo json_encode($resp);
	}

	function getSeries($almacen, $producto, $series)
	{
		$verificados = [];
		foreach ($series as $key => $value) {
			$query = $this->db->from('tb_producto_serie')
				->where('cod_almacen', $almacen)
				->where('cod_producto', $producto)
				->where('serie_descripcion', $value)
				->where('serie_estado', 'D')
				->get();
			if ($query->num_rows() > 0) {
				$verificados[] = $value;
			}
		}

		return $verificados;
	}

	function getProducto($producto)
	{
		return $this->db->from('tb_producto')
			->where('cod_producto', $producto)
			->join('tb_marca', 'tb_producto.cod_marca = tb_marca.cod_marca')
			->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
			->get()->row();
	}
	function agregarTraspaso()
	{
		$data['origen_tras'] = $this->input->post('origen');
		$data['destino_tras'] = $this->input->post('destino');
		$data['fecha_tras'] = $this->input->post('fecha');
		$data['observacion_tras'] = $this->input->post('observacion');
		$data['cod_usu'] = $this->session->userdata('cod_usu');
		$data['estado_tras'] = 0; // Estado pendiente

		// Insertar el traspaso en la tabla tb_traspasos con estado pendiente
		$insert = $this->modelgeneral->insertRegist('tb_traspasos', $data);

		$resp = [];
		if (!is_null($insert)) {
			// Insertar los detalles del traspaso sin actualizar el stock ni las series
			foreach ($_POST['id_producto'] as $key => $value) {
				$detalle['cod_tras'] = $insert;
				$detalle['cod_producto'] = $_POST['id_producto'][$key];
				$detalle['cant_trasdet'] = $_POST['cant_producto'][$key];

				// Asegurarse de que la función traspasarSerie se ejecute incluso si la cantidad es 1
				if (isset($_POST['serie_producto'][$value])) {
					if (is_array($_POST['serie_producto'][$value])) {
						// Iterar por cada serie y crear una nueva fila para cada serie
						foreach ($_POST['serie_producto'][$value] as $serie) {
							// Crear una nueva fila para cada serie
							$detalle['serie_trasdet'] = $serie;
							$this->modelgeneral->insertRegist('tb_traspasos_detalles', $detalle);
						}

						// No actualizamos el stock ni traspasamos las series aún
						// Se hará cuando se valide el traspaso (aceptación o rechazo)
					} else {
						// Si solo hay una serie, agregar una fila con esa serie
						$detalle['serie_trasdet'] = $_POST['serie_producto'][$value];
						$this->modelgeneral->insertRegist('tb_traspasos_detalles', $detalle);
						// No traspasamos la serie aún
					}
				} else {
					// Manejar productos sin series o con una sola unidad
					$detalle['serie_trasdet'] = ''; // Serie vacía para productos sin serie
					$this->modelgeneral->insertRegist('tb_traspasos_detalles', $detalle);
				}
			}

			// Devolver respuesta de éxito
			$resp['success'] = true;
		} else {
			// Si hubo error al insertar, devolver respuesta de error
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	public function validarTraspaso()
	{

		// Obtener los parámetros desde la solicitud POST
		$cod_traspaso = $this->input->post('cod_traspaso');
		$estado = $this->input->post('estado');
		$motivoRechazo = $this->input->post('motivoRechazo');
		$password = $this->input->post('password');

		// Validar que los parámetros no sean nulos
		if (is_null($cod_traspaso) || is_null($estado) || empty($password)) {
			echo json_encode(['success' => false, 'message' => 'Parámetros insuficientes para validar el traspaso.']);
			return;
		}

		// Obtener al usuario autenticado desde la sesión
		$usuario_actual = $this->session->userdata('cod_usu');

		// Obtener el traspaso
		$traspaso = $this->db->get_where('tb_traspasos', ['cod_tras' => $cod_traspaso])->row_array();

		if (!$traspaso) {
			echo json_encode(['success' => false, 'message' => 'Traspaso no encontrado.']);
			return;
		}
		// Verificar que el usuario que valida no sea el mismo que transfiere
		if ($traspaso['cod_usu'] === $usuario_actual) {
			echo json_encode(['success' => false, 'message' => 'No puedes validar tu propio traspaso.']);
			return;
		}

		// Verificar la contraseña del usuario autenticado
		$usuario = $this->db->get_where('tb_usuario', ['cod_usu' => $usuario_actual])->row_array();

		if (!$usuario || sha1($password) !== $usuario['passwoord_usu']) {
			echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta.']);
			return;
		}

		// Cambiar el estado del traspaso a aceptado o rechazado
		$actualizar_data = [
			'estado_tras' => $estado,
			'cod_usu_recibe' => $usuario_actual // Guardar el usuario que valida
		];
		if ($estado == 2 && !empty($motivoRechazo)) {
			$actualizar_data['motivo_rechazo'] = $motivoRechazo;
		}

		$this->db->where('cod_tras', $cod_traspaso);
		$this->db->update('tb_traspasos', $actualizar_data);

		// Si el traspaso es aceptado, actualizar stock y series
		if ($estado == 1) {
			$detalles = $this->db->get_where('tb_traspasos_detalles', ['cod_tras' => $cod_traspaso])->result_array();

			foreach ($detalles as $det) {
				$cantidadTraspasada = (int) $det['cant_trasdet']; // Cantidad del traspaso
			
				// Procesar las series si existen
				if (!empty($det['serie_trasdet'])) {
					$series = is_array($det['serie_trasdet']) ? $det['serie_trasdet'] : [$det['serie_trasdet']];
			
					// Asegurar que la cantidad traspasada coincida con el número de series
					$cantidadTraspasada = count($series);
			
					foreach ($series as $serie) {
						// Mover cada serie individualmente
						$this->traspasarSerie($det['cod_producto'], $serie, $traspaso['origen_tras'], $traspaso['destino_tras']);
					}
				}
			
				// Disminuir el stock en el almacén de origen (una sola vez para todo el lote)
				$this->db->set('stock', 'stock - ' . $cantidadTraspasada, FALSE)
					->where('cod_almacen', $traspaso['origen_tras'])
					->where('cod_producto', $det['cod_producto'])
					->update('tb_producto_stock');
			
				// Verificar si el producto existe en el almacén de destino
				$queryDestino = $this->db->get_where('tb_producto_stock', [
					'cod_almacen' => $traspaso['destino_tras'],
					'cod_producto' => $det['cod_producto']
				]);
			
				if ($queryDestino->num_rows() > 0) {
					// Sumar el stock en el almacén de destino
					$this->db->set('stock', 'stock + ' . $cantidadTraspasada, FALSE)
						->where('cod_almacen', $traspaso['destino_tras'])
						->where('cod_producto', $det['cod_producto'])
						->update('tb_producto_stock');
				} else {
					// Crear un nuevo registro de stock en el almacén de destino
					$productoStock = [
						'cod_producto' => $det['cod_producto'],
						'cod_almacen' => $traspaso['destino_tras'],
						'stock' => $cantidadTraspasada,
						'stock_inicial' => 0
					];
					$this->modelgeneral->insertRegist('tb_producto_stock', $productoStock);
				}
			}
			
			
		}

		// Responder con éxito
		echo json_encode(['success' => true, 'message' => 'Traspaso validado correctamente.']);
	}



	function agregarTraspasoso()
	{
		$data['origen_tras'] = $this->input->post('origen');
		$data['destino_tras'] = $this->input->post('destino');
		$data['fecha_tras'] = $this->input->post('fecha');
		$data['observacion_tras'] = $this->input->post('observacion');
		$data['cod_usu'] = $this->session->userdata('cod_usu');
		$insert = $this->modelgeneral->insertRegist('tb_traspasos', $data);

		$resp = [];
		if (!is_null($insert)) {
			// Actualizar stock en el almacén de origen
			foreach ($_POST['id_producto'] as $key => $value) {
				$detalle['cod_tras'] = $insert;
				$detalle['cod_producto'] = $_POST['id_producto'][$key];
				$detalle['cant_trasdet'] = $_POST['cant_producto'][$key];

				// Asegurarse de que la función traspasarSerie se ejecute incluso si la cantidad es 1
				if (isset($_POST['serie_producto'][$value])) {
					if (is_array($_POST['serie_producto'][$value])) {
						// Iterate through each serie and create a new row for each serie
						foreach ($_POST['serie_producto'][$value] as $serie) {
							// Create a new detalle row for each serie
							$detalle['serie_trasdet'] = $serie;
							$this->modelgeneral->insertRegist('tb_traspasos_detalles', $detalle);
						}

						// Traspasar series al almacén destino
						$this->traspasarSerie($_POST['id_producto'][$key], $_POST['serie_producto'][$value], $data['origen_tras'], $data['destino_tras']);
					} else {
						// If only one serie, add a single row with that serie
						$detalle['serie_trasdet'] = $_POST['serie_producto'][$value];
						$this->modelgeneral->insertRegist('tb_traspasos_detalles', $detalle);
						// Traspasar la serie al almacén destino
						$this->traspasarSerie($_POST['id_producto'][$key], $_POST['serie_producto'][$value], $data['origen_tras'], $data['destino_tras']);
					}
				} else {
					// Handle products without series or with a single unit
					$detalle['serie_trasdet'] = ''; // Empty serie for products without series
					$this->modelgeneral->insertRegist('tb_traspasos_detalles', $detalle);
				}

				// Actualizar stock en el almacén de origen para este producto
				$this->db->query("UPDATE tb_producto_stock SET stock = stock - " . $_POST['cant_producto'][$key] . " WHERE cod_almacen = " . $data['origen_tras'] . " AND cod_producto = " . $_POST['id_producto'][$key]);

				// Verificar si el producto existe en el almacén de destino y actualizar o insertar según corresponda
				$queryDestino = $this->db->from('tb_producto_stock')
					->where('cod_almacen', $data['destino_tras'])
					->where('cod_producto', $value)
					->get();

				if ($queryDestino->num_rows() > 0) {
					// Actualizar stock en el almacén de destino para este producto
					$this->db->query("UPDATE tb_producto_stock SET stock = stock + " . $_POST['cant_producto'][$key] . " WHERE cod_almacen = " . $data['destino_tras'] . " AND cod_producto = " . $value);
				} else {
					// Crear un nuevo registro de producto en el almacén de destino
					$productoStock['cod_producto'] = $value;
					$productoStock['cod_almacen'] = $data['destino_tras'];
					$productoStock['stock'] = $_POST['cant_producto'][$key];
					$productoStock['stock_inicial'] = 0;
					$this->modelgeneral->insertRegist('tb_producto_stock', $productoStock);
				}
			}

			$resp['success'] = true;
		} else {
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}


	function traspasarSerie($producto, $serie, $origen, $destino)
	{
		if (is_array($serie)) {
			// Iterate through each serie
			foreach ($serie as $key => $value) {
				// Actualizar la ubicación de la serie al almacén destino
				$this->db->where('cod_producto', $producto)
					->where('serie_descripcion', $value)
					->where('cod_almacen', $origen)
					->set('cod_almacen', $destino)
					->update('tb_producto_serie');
			}
		} else {
			// Actualizar la ubicación de la serie al almacén destino
			$this->db->where('cod_producto', $producto)
				->where('serie_descripcion', $serie)
				->where('cod_almacen', $origen)
				->set('cod_almacen', $destino)
				->update('tb_producto_serie');
		}
	}



	public function editar($id)
	{
		$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
		$data['traspaso'] = $this->modelgeneral->getTableWhereRow('tb_traspasos', ['cod_tras' => $id]);
		$data['detalles'] = $this->db->from('tb_traspasos_detalles')
			->join('tb_producto', 'tb_traspasos_detalles.cod_producto = tb_producto.cod_producto')
			->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
			->where('cod_tras', $id)
			->get()->result();
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/traspasos/editar', $data);
		$this->load->view('layouts/footer');
	}

	public function editarGuardar()
	{
		$data['fecha_tras'] = $this->input->post('fecha');
		$data['observacion_tras'] = $this->input->post('observacion');
		$where['cod_tras'] = $this->input->post('id');
		$edit = $this->modelgeneral->editRegist('tb_traspasos', $where, $data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
			$resp['redirect'] = 'administrador/regtraspasos';
		} else {
			$resp['success'] = false;
		}
		echo json_encode($resp);
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
		$data['datos'] = $this->getTraspasosReporte();
		$html = $this->load->view('admin/traspasos/reporte_pdf', $data, TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css, 1);
		$this->mpdf->writeHTML($html, 2);
		$this->mpdf->Output('Compras', 'I');
	}

	public function generarPDF($cod_tras)
	{
		// Cargar el modelo Traspasos_model
		$this->load->model('traspasos_model');
		//$this->load->model('empresa_model');

		// Obtener los detalles del traspaso
		$traspasoData = $this->traspasos_model->getTraspasosDetalle($cod_tras);
		$traspaso = $traspasoData['traspaso'];
		$detalles = $traspasoData['detalles'];

		// Obtener información de la empresa
		$data = array();  // Define $data as an array
		$data['empresa'] = $this->empresa_model->getEmpresa($data);

		// Pasar datos a la vista
		$data['traspaso'] = $traspaso;
		$data['detalles'] = $detalles;

		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4',
			'orientation' => 'L',

		]);

		// Comenzar a agregar contenido al PDF
		$html = $this->load->view('admin/traspasos/imprimir_pdf', $data, true);
		$css = file_get_contents(APP_PATH . 'assets/styles_pdf.css');
		$mpdf->WriteHTML($css, 1);
		$mpdf->WriteHTML($html, 2);

		// Generar el PDF
		$mpdf->Output();
	}



	function reporteExcel()
	{
		$data['datos'] = $this->getTraspasosReporte();
		$this->load->view('admin/traspasos/reporte_excel', $data);
	}

	public function getTraspasosReporte()
	{
		$desde = $this->input->get('desde');
		$hasta = $this->input->get('hasta');
		$origen = $this->input->get('origen');
		$destino = $this->input->get('destino');
		$this->db->from('tb_traspasos');
		$this->db->select('tb_traspasos.cod_tras,detallet.cod_tras,fecha_tras,origen.nomb_almacen as origen, destino.nomb_almacen as destino,observacion_tras,nomb_product,cant_trasdet,serie_trasdet,tb_usuario.nomb_usu as usuario');
		$this->db->join('tb_almacen origen', ' tb_traspasos.origen_tras = origen.cod_almacen');
		$this->db->join('tb_almacen destino', ' tb_traspasos.destino_tras = destino.cod_almacen');
		$this->db->join('tb_traspasos_detalles detallet', 'tb_traspasos.cod_tras = detallet.cod_tras');
		$this->db->join('tb_producto', 'detallet.cod_producto = tb_producto.cod_producto');
		$this->db->join('tb_usuario', 'tb_traspasos.cod_usu = tb_usuario.cod_usu');

		$this->db->where('fecha_tras >= ', $desde);
		$this->db->where('fecha_tras <=', $hasta);
		if ($origen != '') {
			$this->db->like('origen.cod_almacen', $origen);
		}
		if ($destino != '') {
			$this->db->like('destino.cod_almacen', $desino);
		}
		return $this->db->get()->result();

	}

}

/* End of file Regtraspasos.php */
/* Location: ./application/controllers/administrador/Regtraspasos.php */
