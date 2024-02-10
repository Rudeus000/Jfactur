<?php
defined('BASEPATH') or exit('No direct script access allowed');
require(APP_TENANTPATH . 'config.php');

class Regventas extends CI_Controller
{

	private $permisos;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ventas_model');
		$this->load->model('empresa_model');
		$this->load->model('modelgeneral');
		$this->load->model('notaunidad_model');
		$this->load->model('notavalorizado_model');
		// $this->load->model('modelgeneral');

		$this->load->helper('general');
		$this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['permisos'] = $this->permisos;
		$data['vendedores'] = $this->ventas_model->getVendedores();
		$data['puntos'] = $this->ventas_model->getPuntos();
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/ventas/listgetventas', $data);
		$this->load->view('layouts/footer');
	}

	public function jsonVentas()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho']  = $this->input->get_post('_', true);
		$columns = ['fecha_vent', 'nom_tipdocumento', 'fecha_vent', 'nomb_cliente'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['desde'] = $this->input->get_post('desde');
		$data['hasta'] = $this->input->get_post('hasta');
		$cliente = $this->input->get_post('cliente');
		$vendedor = $this->input->get_post('vendedor');
		$punto = $this->input->get_post('punto');
		$estado = $this->input->get_post('estado');

		// if ($cliente!='') {
		// $data['cliente'] = $cliente;
		// }
		// $data['vendedor'] = $this->input->get_post('vendedor');
		// $data['punto'] = $this->input->get_post('punto');
		// $data['estado'] = $this->input->get_post('estado');


		$data['cliente'] = $cliente;
		$data['vendedor'] = $vendedor;
		$data['punto'] = $punto;
		$data['estado'] = $estado;

		$datos = $this->ventas_model->getVentas($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	public function verificarCoberturaCliente()
	{
		$saldo = $this->input->get('saldo');
		$cliente = $this->input->get('cliente');
		$query = $this->db->from('tb_cliente_cobertura')
			->where('id_cliente', $cliente)
			->where('inicio_cobertura <=', date('Y-m-d'))
			->where('limite_cobertura >=', date('Y-m-d'))
			->get();
		$resp = [];
		if ($query->num_rows() > 0) {
			$queryCobros = $this->db->from('tb_venta')
				->select('SUM(pendiente_vent) as pendientes')
				->where('id_cliente', $cliente)
				->group_by('id_cliente')
				->get()->row();

			$resp['monto_cobertura'] = $query->row()->monto_cobertura;
			$resp['pendientes'] = $queryCobros->pendientes;

			if ($query->row()->monto_cobertura > $queryCobros->pendientes) {
				$cobertura = $query->row()->monto_cobertura - $queryCobros->pendientes;
				$resp['cobertura'] = $cobertura;
				if ($cobertura >= $saldo) {
					$resp['success'] = true;
				} else {
					$resp['success'] = false;
					$resp['mensaje'] = 'El cliente tiene una cobertura disponible de: ' . $cobertura;
				}
			} else {
				$resp['success'] = false;
				$resp['mensaje'] = 'El cliente ya no tiene cobertura disponible';
			}
		} else {
			$resp['success'] = false;
			$resp['mensaje'] = 'El cliente no tiene cobertura';
		}

		echo json_encode($resp);
	}

	public function agregar()
	{
		$data['cod_medio_pay'] = $this->modelgeneral->getTable('sunat_mediosdepago');
		$data['cod_bien'] = $this->modelgeneral->getTable('sunat_codigodetraccion');
		$data['banco'] = $this->modelgeneral->getTableWhere('tb_banco', ['id_entidad_financiera' => 18]);
		$data['busqueda_general'] = urlencode(json_encode($_GET));
		$data['tipos_pagos'] = $this->modelgeneral->getTableWhere('tb_tipo_pago', ['estado_tipopago' => 1]);
		$data['tipos_tarjetas'] = $this->modelgeneral->getTableWhere('tb_tarjeta', ['estado_tarj' => 1]);
		$data['punto'] = $this->modelgeneral->getTableWhereRow('tb_puntoventa', ['cod_puntoventa' => $this->session->userdata('puntoventa')]);
		$data['almacenes'] = $this->ventas_model->getAlmacenesDisponibles();
		$data['cajas'] = $this->modelgeneral->getTableWhere('tb_caja', ['est_caja' => 1]);
		$data['tipos'] = $this->ventas_model->getTiposVentas();
		$data['cliente'] = $this->ventas_model->getClientePorDefecto();
		$data['dolar'] = $this->modelgeneral->getTableWhereRow('parametros', ['nom_paramt' => 'DOLAR']);
		$data['apertura'] = $this->ventas_model->getCajaApertura();
		$data['doc_clientes'] = $this->ventas_model->getDocumentosCliente();
		$data['unidades'] = $this->modelgeneral->getTableWhere('tb_unidades', ['est_unidad' => 1]);
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/ventas/ventagregar', $data);
		$this->load->view('layouts/footer');
	}

	public function numeracion($tipo = NULL)
	{
		if (is_null($tipo)) {
			$id = $this->input->get('id');
		} else {
			$id = $tipo;
		}
		$query =  $this->db->from('tb_talonario')
			->select('correlativo_actual,serie')
			->where('cod_puntoventa', $this->session->userdata('puntoventa'))
			->where('cod_talonario', $id)
			->get()->row();
		if (is_null($tipo)) {
			echo json_encode($query);
		} else {
			return $query->correlativo_actual;
		}
	}

	private function aumentarNumeracion($tipo, $actual)
	{
		$nuevo = $actual + 1;
		$this->db->set('correlativo_actual', $nuevo)
			->where('cod_puntoventa', $this->session->userdata('puntoventa'))
			->where('cod_talonario', $tipo)
			->update('tb_talonario');
	}

	public function getClientes()
	{
		$q = $this->input->get('q');
		$dni = $this->input->get('dni');
		$ruc = $this->input->get('ruc');
		$ex = $this->input->get('ex');
		$pass = $this->input->get('pass');

		$array = [];
		if ($dni == '1') {
			$array[] = 1;
		}
		if ($ruc == '1') {
			$array[] = 6;
		}
		if ($ex == '1') {
			$array[] = 4;
		}
		if ($pass == '1') {
			$array[] = 7;
		}
		$this->db->from('tb_cliente');
		$this->db->select('id_cliente as id,nomb_cliente as nombre,doc_cliente as ruc, direc_cliente as direccion, precio_cliente');
		$this->db->join('tb_tipodocumentocliente', 'tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli');

		$this->db->where('(nomb_cliente like "%' . $q . '%" OR doc_cliente like "%' . $q . '%")', null);
		$this->db->where_in('codsunat_tipdocucli', $array);
		$result = $this->db->get()->result();

		echo json_encode($result);
	}

	public function getProductoBusqueda()
	{
		$queryLike = $this->input->get('producto');
		$almacen = $this->input->get('almacen');
		$cambio = $this->input->get('cambio');
		$precioCliente = $this->input->get('precio_cliente');

		if ($precioCliente == 'Normal') {
			$precioVenta = 'prec_venta';
		} else if ($precioCliente == 'Mayor') {
			$precioVenta = 'prec_mayor_venta';
		} else if ($precioCliente == 'Especial') {
			$precioVenta = 'prec_especial_venta';
		}

		$resultProducto = $this->db->from('tb_producto')
			->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / " . $cambio . ") as costo,(" . $precioVenta . " / " . $cambio . ") as venta,nomb_unid as unidad, (CASE WHEN stock > stockmin_product THEN 1 ELSE 0 END) as estado,peso_product, tb_producto.idTypeAssignmentProduct,stock,cod_tiparticulo,fecha_vencimiento", FALSE)
			->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
			->join('tb_producto_stock', 'tb_producto_stock.cod_producto = tb_producto.cod_producto')
			->where_in('tb_producto.typeAssignmentProduct', array('H', 'N'))
			->where('est_product', 1)
			->where('cod_tiparticulo', 1)
			->where('(tb_producto_stock.cod_almacen = "' . $almacen . '" AND (nomb_product LIKE "%' . $queryLike
				. '%" OR barra_product LIKE "%' . $queryLike . '%"))', NULL)
			->where('(nomb_product LIKE "%' . $queryLike
				. '%" OR barra_product LIKE "%' . $queryLike . '%")', NULL)

			->get()->result_array();

		if (empty($resultProducto)) {
			$resultProducto = $this->db->from('tb_producto')
				->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / " . $cambio . ") as costo,(" . $precioVenta . " / " . $cambio . ") as venta,nomb_unid as unidad, (CASE WHEN stock > stockmin_product THEN 1 ELSE 0 END) as estado,peso_product, tb_producto.idTypeAssignmentProduct,stock,cod_tiparticulo,fecha_vencimiento", FALSE)
				->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
				->join('tb_producto_stock', 'tb_producto_stock.cod_producto = tb_producto.idTypeAssignmentProduct')
				->where_in('tb_producto.typeAssignmentProduct', array('H', 'N'))
				->where('est_product', 1)
				->where('cod_tiparticulo', 1)
				->where('(tb_producto_stock.cod_almacen = "' . $almacen . '" AND (nomb_product LIKE "%' . $queryLike
					. '%" OR barra_product LIKE "%' . $queryLike . '%"))', NULL)
				// ->where('(nomb_product LIKE "%' . $queryLike
				// 	. '%" OR barra_product LIKE "%' . $queryLike . '%")', NULL)

				->get()->result_array();
		}

		$this->db->flush_cache();

		$resultServicio = $this->db->from('tb_producto')
			->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / " . $cambio . ") as costo,(" . $precioVenta . " / " . $cambio . ") as venta,nomb_unid as unidad, '1' as estado,peso_product,cod_tiparticulo,fecha_vencimiento", FALSE)
			->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
			->where_in('tb_producto.typeAssignmentProduct', array('H', 'N'))
			->where('est_product', 1)
			->where('(cod_tiparticulo = 2 AND (nomb_product LIKE "%' . $queryLike
				. '%" OR barra_product LIKE "%' . $queryLike . '%"))', NULL)
			->get()->result_array();

		$productos_array = array_merge($resultProducto, $resultServicio);

		foreach ($productos_array as $k => $p) {
			if ($p['fecha_vencimiento'] == 1) {
				$productos_array[$k]['fechas'] = $this->db->from('tb_producto_fecha')
					->where('cod_producto', $p['id'])
					->where('cod_almacen', $almacen)
					->where('cantidad_prodfec > ', 0)
					->where('fecha_alerta_prodfec <=', date('Y-m-d'))
					->order_by('fecha_vencimiento_prodfec', 'asc')
					->get()->row();
			} else {
				$productos_array[$k]['fechas'] = null;
			}
		}

		header('content-type: application/json; charset=utf-8');
		echo json_encode($productos_array);
	}

	public function getProducto()
	{
		$almacen = $this->input->get('almacen');
		$cambio = $this->input->get('cambio');
		$cantidad = $this->input->get('cantidad');
		$producto = $this->input->get('producto');
		$series = $this->input->get('series');

		$prod = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $producto]);
		if ($prod->typeAssignmentProduct == 'H') {
			$ResultypeAssignmentProduct = array('tb_producto.cod_producto' => $producto, 'tb_producto.idTypeAssignmentProduct' => $prod->idTypeAssignmentProduct);
			$JoinAssignmentProduct = 'tb_producto_stock.cod_producto = tb_producto.idTypeAssignmentProduct';
			$querySeriestypeAssignmentProduct = array('cod_producto' => $prod->idTypeAssignmentProduct);
		} else {
			$ResultypeAssignmentProduct = array('tb_producto.cod_producto' => $producto);
			$JoinAssignmentProduct = 'tb_producto_stock.cod_producto = tb_producto.cod_producto';
			$querySeriestypeAssignmentProduct = array('cod_producto' => $producto);
		}

		$resp = [];
		$resp['tipo'] = $prod->cod_tiparticulo;
		if ($prod->cod_tiparticulo == 1) { //PRODUCTOS

			$result = $this->db->from('tb_producto')
				->select("tb_producto.*,tb_marca.*,tb_unidades.*,tb_producto_stock.*,(prec_costo / " . $cambio . ") as costo,(prec_venta / " . $cambio . ") as venta,(tb_producto_stock.stock - stockmin_product ) as stock_disponible,cod_tiparticulo", FALSE)
				->join('tb_producto_stock', $JoinAssignmentProduct)
				->join('tb_marca', 'tb_producto.cod_marca = tb_marca.cod_marca')
				->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
				->where('tb_producto_stock.cod_almacen', $almacen)
				->where($ResultypeAssignmentProduct)
				->where('cod_tiparticulo', 1)
				->get()->row();

			$querySeries = $this->db->from('tb_producto_serie')
				->where($querySeriestypeAssignmentProduct)
				->where('cod_almacen', $almacen)
				->where('serie_estado', 'N')
				->get();

			if ($result->stock_disponible > $querySeries->num_rows()) {
				$result->stock_disponible -= $querySeries->num_rows();
			}

			$resp['response'] = $result;

			if (!empty($resp['response'])) {
				$resp['response']->cod_producto = $producto;
			}


			if ($result->stock_disponible >= $cantidad) {
				if (is_array($series)) {
					$querySeries = $this->db->from('tb_producto_serie')
						->select('serie_descripcion as id, serie_descripcion as text')
						// ->where('cod_producto',$producto)
						->where($querySeriestypeAssignmentProduct)
						->where('cod_almacen', $almacen)
						->where('serie_estado', 'D')
						->get()->result();
					foreach ($querySeries as $q) {
						if (in_array($q->text, $series)) {
							$q->selected = true;
						} else {
							$q->selected = false;
						}
					}
					$resp['series'] = $querySeries;
				}
				$resp['estado'] = true;
			} else {
				$resp['estado'] = false;
			}


			//OBTENER FECHAS
			$result->fechas = $this->db->from('tb_producto_fecha')
				->where('cod_producto', $producto)
				->where('cod_almacen', $almacen)
				->where('cantidad_prodfec > ', 0)
				->where('fecha_alerta_prodfec <=', date('Y-m-d'))
				->order_by('fecha_vencimiento_prodfec', 'asc')
				->get()->row();
		} else { //SERVICIOS
			$result = $this->db->from('tb_producto')
				->select("tb_producto.*,tb_marca.*,tb_unidades.*,(prec_costo / " . $cambio . ") as costo,(prec_venta / " . $cambio . ") as venta,cod_tiparticulo", FALSE)
				->join('tb_marca', 'tb_producto.cod_marca = tb_marca.cod_marca')
				->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
				// ->where('tb_producto.cod_producto',$producto)
				->where($ResultypeAssignmentProduct)
				->where('cod_tiparticulo', 2)
				->get()->row();

			$querySeries = $this->db->from('tb_producto_serie')
				->select('serie_descripcion as id, serie_descripcion as text')
				// ->where('cod_producto',$producto)
				//->where($ResultypeAssignmentProduct)
				->where('cod_almacen', $almacen)
				->where('serie_estado', 'D')
				->get()->result();
			if (is_array($series)) {
				foreach ($querySeries as $q) {
					if (in_array($q->text, $series)) {
						$q->selected = true;
					} else {
						$q->selected = false;
					}
				}
			}

			$resp['response'] = $result;
		}

		echo json_encode($resp);
	}

	public function getSeriesProducto()
	{
		$producto = $this->input->get('producto');
		$almacen = $this->input->get('almacen');
		$query = $this->db->from('tb_producto_serie')
			->select('serie_descripcion as id, serie_descripcion as text')
			->where('cod_producto', $producto)
			->where('cod_almacen', $almacen)
			->where('serie_estado', 'D')
			->get()->result();

		header('content-type: application/json; charset=utf-8');
		echo json_encode($query);
	}

	public function calcularCuotas()
	{
		$peridiocidad = $this->input->post('periodo');
		$numero = $this->input->post('numero');
		$total = $this->input->post('total');
		$retencion_deuda = $this->input->post('retencion_mont');

		if ($peridiocidad == 'Semanal') {
			$periodo = '+7 day';
		} elseif ($peridiocidad == 'Quincenal') {
			$periodo = '+14 day';
		} elseif ($peridiocidad == 'Mensual') {
			$periodo = '+28 day';
		}

		$montos = $total - $retencion_deuda;
		$monto = $montos / $numero;

		$cuotas = [];
		$fecha = date('Y-m-d');
		for ($i = 1; $i <= $numero; $i++) {
			$fecha = strtotime($periodo, strtotime($fecha));
			$fecha = date('Y-m-d', $fecha);
			$cuotas[] = [
				'fecha' => $fecha,
				'monto' => round($monto, 2)
			];
		}
		header('content-type: application/json; charset=utf-8');
		echo json_encode($cuotas);
	}

	public function agregarVenta()
	{
		$apertura = $this->ventas_model->getCajaApertura();
		if ($apertura == false) {
			return false;
		}

		$data['fecha_vent'] = $this->input->post('fecha');
		$data['hora_vent'] = date('H:i:s');
		$data['id_cliente'] = $this->input->post('cliente');
		$data['cod_almacen'] = $this->input->post('almacen');
		$data['cod_talonario'] = $this->input->post('tipoPedido');
		$talonario = $this->modelgeneral->getTableWhereRow('tb_talonario', ['cod_talonario' => $data['cod_talonario']]);
		$data['numero_vent'] = $this->numeracion($data['cod_talonario']);
		$data['moneda_vent'] = $this->input->post('moneda');
		if ($data['moneda_vent'] == 'S') {
			$data['codmoneda_vent'] = 'PEN';
		} else {
			$data['codmoneda_vent'] = 'USD';
		}
		$data['cambio_vent'] = $this->input->post('tipoCambio');
		$data['monto_vent'] = $this->input->post('monto');
		$data['pago_vent'] = $this->input->post('pago');
		$data['igv_vent'] = null;
		$data['subtotal_vent'] = null;
		$data['total_vent'] = $this->input->post('total');
		$data['montorecibido_vent'] = $this->input->post('montoRecibido');
		$data['vuelto_vent'] = $this->input->post('vuelto');
		$data['pendiente_vent'] = $data['total_vent'] - $data['monto_vent'];
		$data['estado_vent'] = 'G';
		$data['cod_usu'] = $this->session->userdata('cod_usu');
		$printType = $this->modelgeneral->getTableWhere('tb_usuario_documento', ['cod_usu' => $data['cod_usu'], 'serie_usudoc' => $this->input->post('serie')]);
		$data['login_usu'] = $this->session->userdata('login_usu');
		$data['cod_puntoventa'] = $this->session->userdata('puntoventa');
		$data['cod_caja'] = $apertura->cod_caja;
		$data['cod_tipopago'] =   $this->input->post('tipoPago');
		$data['operacion'] =  $this->input->post('operacion');
		if (isset($_POST['tipoTarjeta'])) {
			$data['cod_tarj'] =   $this->input->post('tipoTarjeta');
		}
		$data['cod_apertura'] =  $apertura->cod_apertura;
		if (isset($_POST['dias'])) {
			$data['dias_vent'] = $this->input->post('dias');
			$data['fechavenc_vent'] = $this->input->post('fecVenc');
			$data['saldo_vent'] = $data['total_vent'] - $data['monto_vent'];
		}

		if (isset($_POST['observacion'])) {
			$data['observacion_vent'] = $this->input->post('observacion');
		}

		//DETRACCION
		if ($this->input->post('detraccion-check') == 'on') {
			$data['detraccion_id_mediopago'] = $this->input->post('detraccion_medio_pago');
			$data['detraccion_cuenta'] = $this->input->post('detraccion_cuenta');
			$data['detraccion_iddetraccion'] = $this->input->post('detraccion_bien');
			$data['detraccion_porcentaje'] = $this->input->post('detraccion_porcentaje');
			$data['detraccion_monto'] = $this->input->post('detraccion_monto');
			$data['detraccion_texto'] = $this->input->post('detraccion_informacion');
		}

		//RETENCION
		if ($this->input->post('retencion-check') == 'on') {
			$retencion_porcent = ($this->input->post('retencion_porcentaje') / 100);
			$data['retencion_base_imp'] = $this->input->post('base_monto');
			$data['retencion_porcentaje'] = $retencion_porcent;
			$data['retencion_monto'] = $this->input->post('retencion_monto');;
		}

		$insert = $this->modelgeneral->insertRegist('tb_venta', $data);

		if (is_null($insert)) {
			header('content-type: application/json; charset=utf-8');
			$resp = [];
			$resp['success'] = false;
			echo json_encode($resp);
			exit();
		}

		$venta = $this->db->from('tb_venta')
			->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario')
			->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
			->get()->row();
		$cobro['cod_vent'] = $insert;
		if ($this->input->post('pago') == 'CO') {
			$tipo_cobro = 'Contado';
		} else {
			$tipo_cobro = 'Credito';
		}
		$cobro['tipo_cobro'] = $tipo_cobro;
		$cobro['cod_caja'] = $apertura->cod_caja;
		$cobro['estado_vent'] = 'G';
		$cobro['fecha_cobro'] = $this->input->post('fecha');
		$cobro['detalle_cobro'] = 'PAGO COBRO: ' . $venta->nom_tipdocumento . '-' . $venta->serie . '-' . $venta->numero_vent;
		$cobro['monto_cobro'] = $this->input->post('monto');
		$this->modelgeneral->insertRegist('tb_cobro', $cobro);


		$resp = [];
		if (!is_null($insert)) {
			$this->guardarCuotas($insert);

			$this->aumentarNumeracion($data['cod_talonario'], $data['numero_vent']);

			$igv_acumula = 0;
			$gravada_acumula = 0;
			$cod_art_almacen = "";
			$datos_empresa = $this->modelgeneral->getTableWhereRow('tb_empresa', ['cod_empresa' => 1]);
			foreach ($_POST['id_prod'] as $key => $value) {
				$producto = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $value]);
				$pos = strpos($value, 'ser-');
				if ($pos !== false) {
					$precio_unitario = $_POST['prec_prod'][$value];
					$codigo_producto = null;
					$detalle['cod_servicio'] = substr($value, 4) . '-' . $insert;
				} else {
					$producto = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $value]);
					$codigo_producto = $_POST['id_prod'][$key];
					$precio_unitario = $producto->prec_costo;
				}

				$detalle['cod_vent'] = $insert;
				if (isset($_POST['idTypeAssignmentProduct'][$key])) {
					$idTypeAssignmentProduct = $_POST['idTypeAssignmentProduct'][$key];
					if ($_POST['idTypeAssignmentProduct'][$key] !== "null") {
						$cod_art_almacen = $_POST['idTypeAssignmentProduct'][$key];
						$detalle['cod_father_product'] = $_POST['idTypeAssignmentProduct'][$key];
					} else {
						$detalle['cod_father_product'] = $codigo_producto;
						$cod_art_almacen = $codigo_producto;
					}
				}
				$detalle['cod_producto'] = $codigo_producto;
				// $idTypeAssignmentProduct = $_POST['idTypeAssignmentProduct'][$key];
				$detalle['producto_ventdet'] = $_POST['nombre_prod'][$key];
				$detalle['producto_isdn'] = $_POST['producto_isdn'][$key];
				$detalle['cant_ventdet'] = $_POST['cant_prod'][$key];
				$detalle['precunitcomp_ventdet'] = $precio_unitario;
				$detalle['precunit_ventdet'] = $_POST['prec_prod'][$key];
				if (empty($_POST['desc_prod'][$key])) {
					$descuento = 0;
				} else {
					$descuento = (float)$_POST['desc_prod'][$key];
				}
				$detalle['subtotal_ventdet'] = (($detalle['precunit_ventdet'] - $descuento) * $detalle['cant_ventdet']);
				if ($_POST['tipo_igv'][$key] == '4') {
					$detalle['igv_ventdet'] = 0;
				} else {
					$detalle['igv_ventdet'] = round((($detalle['subtotal_ventdet'])  / 1.18) * 0.18, 2);
					$igv_acumula += $detalle['igv_ventdet'];
					$gravada_acumula += $detalle['subtotal_ventdet'];
				}
				$detalle['prec_ventdet'] = $detalle['subtotal_ventdet'] - $detalle['igv_ventdet'];
				$detalle['descuento_ventdet'] = $descuento;
				$detalle['estado_ventdet'] = 'S';

				$detalle['unidad_ventdet'] = $_POST['unidad_prod'][$key];
				$detalle['unidad_abreviatura_ventdet'] = $_POST['unidad_abreviatura_prod'][$key];
				$detalle['peso_ventdet'] = $_POST['peso_prod'][$key];

				$detalle['tipo_ventdet'] = $_POST['tipo'][$key];
				$insertDetalle = $this->modelgeneral->insertRegist('tb_venta_detalle', $detalle);
				if (!empty($producto)) {


					if (!empty($datos_empresa)) {
						if ($datos_empresa->MovAlmacenAutomatico == "S") {
							/*Poblamos el detalle para la boleta de ingreso */
							$undmed_prod = $this->modelgeneral->getTableWhereRow('tb_unidades', ['cod_unid' => $producto->cod_unid]);
							$arr_det[$value]['nund'] = $_POST['cant_prod'][$key];
							$arr_det[$value]['ccod_undmed'] = $undmed_prod->abreviatura_unid;
							$arr_det[$value]['ccod_art'] = $cod_art_almacen; //$value; 
							$arr_det[$value]['cdsc_art'] = $producto->nomb_product;
							$arr_det[$value]['bind_lote'] = 'N';
							$arr_det[$value]['cnro_lote'] = '';
							/*FIN Poblamos el detalle para la boleta de ingreso */
							/*Poblamos el detalle para la nota de ingreso */
							$arr_detval[$value]['nund'] = $_POST['cant_prod'][$key];
							$arr_detval[$value]['ccod_undmed'] = $undmed_prod->abreviatura_unid;;
							$arr_detval[$value]['ccod_art'] = $cod_art_almacen; //$value; 
							$arr_detval[$value]['cdsc_art'] = $producto->nomb_product;
							//sacamos el costo actual del producto
							$costo_actual_prod = $this->modelgeneral->getTableWhereRow('alm_stkval_actual', ['ccod_art' => $value]);
							if (!empty($costo_actual_prod)) {
								$arr_detval[$value]['ncosto'] = $costo_actual_prod->ncosto;
							} else {
								$arr_detval[$value]['ncosto'] = 0;
							}
							/*FIN Poblamos el detalle para la nota de ingreso */
						}
					}
				}
				if ($pos === false) {
					if ($producto->cod_tiparticulo == 1) { //SI ES PRODUCTO 
						// $this->descontarDeAlmacen($detalle,$data['cod_almacen']); //DESCONTAR STOCK
						$this->descontarDeAlmacen($detalle, $data['cod_almacen'], $idTypeAssignmentProduct); //DESCONTAR STOCK
						// var_dump($_POST);
						if (isset($_POST['series'][$producto->cod_producto])) { //si existe la serie del producto tal

							$series = $_POST['series'][$producto->cod_producto];

							if (is_array($series)) {
								foreach ($series as $key => $value) {
									$whereSeries['cod_almacen'] = $data['cod_almacen'];
									$whereSeries['cod_producto'] = ($producto->typeAssignmentProduct == 'H' and !is_null($producto->idTypeAssignmentProduct)) ? $producto->idTypeAssignmentProduct : $producto->cod_producto;
									$whereSeries['serie_descripcion'] = $value;
									$dataSeries['cod_vent'] = $insert;
									$dataSeries['serie_estado'] = 'N';
									$this->modelgeneral->editRegist('tb_producto_serie', $whereSeries, $dataSeries);

									$dataVentaDetalleSerie['cod_ventdet '] = $insertDetalle;
									$dataVentaDetalleSerie['cod_producto'] = $producto->cod_producto;
									$dataVentaDetalleSerie['serie_ventdetserie '] = $value;
									$this->modelgeneral->insertRegist('tb_venta_detalle_serie', $dataVentaDetalleSerie);
								}
							} else {
								$whereSeries['cod_almacen'] = $data['cod_almacen'];
								$whereSeries['cod_producto'] = ($producto->typeAssignmentProduct == 'H' and !is_null($producto->idTypeAssignmentProduct)) ? $producto->idTypeAssignmentProduct : $producto->cod_producto;
								$whereSeries['serie_descripcion'] = $series;
								$dataSeries['cod_vent'] = $insert;
								$dataSeries['serie_estado'] = 'N';
								$this->modelgeneral->editRegist('tb_producto_serie', $whereSeries, $dataSeries);

								$dataVentaDetalleSerie['cod_ventdet '] = $insertDetalle;
								$dataVentaDetalleSerie['cod_producto'] = $producto->cod_producto;
								$dataVentaDetalleSerie['serie_ventdetserie '] = $series;
								$this->modelgeneral->insertRegist('tb_venta_detalle_serie', $dataVentaDetalleSerie);
							}
						}


						//DESCONTAR FECHAS DE VENCIMIENTO
						if (isset($_POST['producto_fecha'][$codigo_producto]) && $_POST['producto_fecha'][$codigo_producto] != '') {
							// if ($_POST['producto_fecha'][$codigo_producto] != '') {
							$producto_fecha = $this->db->from('tb_producto_fecha')
								->where('cod_prodfec', $_POST['producto_fecha'][$codigo_producto])
								->get()->row();


							//Si la cantidad del producto que el usuario eligió es mayor que la cantidad de fechas de vencimiento de la BD.
							if ($producto_fecha->cantidad_prodfec >= intval($detalle['cant_ventdet'])) {

								$this->db->where('cod_prodfec', $producto_fecha->cod_prodfec)
									->set('cantidad_prodfec', $producto_fecha->cantidad_prodfec - intval($detalle['cant_ventdet']))
									->update('tb_producto_fecha');
							} else {
								//Obtenemos el sobrando de cantidad
								$cantidad = intval($detalle['cant_ventdet']) - $producto_fecha->cod_prodfec;

								//Seteamos a cero la cantidad
								$this->db->where('cod_prodfec', $producto_fecha->cod_prodfec)
									->set('cantidad_prodfec', 0)
									->update('tb_producto_fecha');

								//Obtenemos una nuevo registro de fecha de vencimiento
								$producto_fecha_nuevo = $this->db->from('tb_producto_fecha')
									->where('cod_producto', $codigo_producto)
									->where('cod_almacen', $data['cod_almacen'])
									->where('cantidad_prodfec > ', 0)
									->order_by('fecha_vencimiento_prodfec', 'asc')
									->get();

								if ($producto_fecha_nuevo->num_rows() > 0) {
									$this->db->where('cod_prodfec', $producto_fecha_nuevo->row()->cod_prodfec)
										->set('cantidad_prodfec', $cantidad)
										->update('tb_producto_fecha');
								}
							}
						}
					}
				}
			}


			$this->modelgeneral->editRegist('tb_venta', ['cod_vent' => $insert], [
				'igv_vent' => round((($gravada_acumula)  / 1.18) * 0.18, 2),
				'subtotal_vent' => $this->input->post('total') - round((($gravada_acumula)  / 1.18) * 0.18, 2)
			]);

			$this->calcularGravadaExoneradaDeVenta($insert);
			$flg_continuar = 1;
			if (!empty($arr_det)) {

				if (!empty($datos_empresa)) {
					if ($datos_empresa->MovAlmacenAutomatico == "S") {
						/*poblamos array para boleta de ingreso*/
						$_SESSION['ALM_Kardex_det'] = $arr_det;
						$arr_serie = $this->notaunidad_model->ProxCorrelativoAlmacen(array('tipo' => 'BS', 'codalm' => $this->input->post('almacen')));
						if (sizeof($arr_serie) > 0) {
							$arrboleta['Serie_Nota'] = $arr_serie[0]['serie'];
							$arrboleta['Num_Nota'] = ($arr_serie[0]['correlativo'] + 1);
						} else {
							$resp['success'] = false;
							echo json_encode($resp);
							exit(0);
						}
						$arrboleta['Tipo_Nota'] = 'S';
						$arrboleta['Ruc_Cliente'] = $this->input->post('rucdni');
						$arrboleta['Fecha_Nota'] = date('Y-m-d');
						$arrboleta['Motivo_Recep'] = '4';
						$arrboleta['obs_Nota'] = 'salida desde modulo de ventas';
						$arrboleta['Cod_Almacen'] = $this->input->post('almacen');
						//sacamos el tipo de documento del talonario
						$codtalonario = $this->input->post('tipoPedido');
						//echo 'talonario '.$this->input->post('documento');
						$objTalonario = $this->modelgeneral->getTableWhereRow('tb_talonario', ['cod_talonario' => $codtalonario]);
						//var_export($objTalonario);
						/*
					if (($this->input->post('documento') =="15")) {
						$arrboleta['tip_doc_ref']="01";
					}
					else if ($this->input->post('documento') =="16") {
						$arrboleta['tip_doc_ref']="03";
					}
					else{
						$arrboleta['tip_doc_ref']="00";
					}*/
						$arrboleta['tip_doc_ref'] = $objTalonario->cod_tipdocu;
						$arrboleta['serie_doc_ref'] = $this->input->post('serie');
						$arrboleta['num_doc_ref'] = $this->input->post('correlativo');
						$arrboleta['Estado'] = 'R';
						$arrboleta['usu_reg'] = $this->session->cod_usu;
						$arrboleta['Fec_Reg'] = date('Y-m-d');
						$dins = $this->notaunidad_model->Insnotaunidad($arrboleta, "N");
						if (sizeof($dins) > 0) {
							if ($dins['status'] != "1") {
								$resp['success'] = false;
								$flg_continuar = 0;
							} else {
								$this->notaunidad_model->ActualizarCorrelativoAlmacen(array('tipo' => 'BS', 'codalm' => $this->input->post('almacen'), 'Numero' => $arrboleta['Num_Nota']));
							}
						} else {
							$resp['success'] = false;
							$flg_continuar = 0;
						}
						/*fin poblamos array para boleta de ingreso*/
						/*poblamos array para nota de ingreso*/
						$_SESSION['ALM_Kardexval_det'] = $arr_detval;
						$arr_serie = $this->notaunidad_model->ProxCorrelativoAlmacen(array('tipo' => 'NS', 'codalm' => ''));
						if (sizeof($arr_serie) > 0) {
							$arrnota['Serie_Nota'] = $arr_serie[0]['serie'];
							$arrnota['Num_Nota'] = ($arr_serie[0]['correlativo'] + 1);
						} else {
							$resp['success'] = false;
							echo json_encode($resp);
							exit(0);
						}
						$arrnota['Tipo_Nota'] = 'S';
						$arrnota['Ruc_Cliente'] = $this->input->post('rucdni');
						$arrnota['Fecha_Nota'] = date('Y-m-d');
						$arrnota['CodMotivo'] = '4';
						$arrnota['obs_Nota'] = 'salida desde modulo de ventas';
						$objTalonario = $this->modelgeneral->getTableWhereRow('tb_talonario', ['cod_talonario' => $this->input->post('tipoPedido')]);
						$arrnota['tip_doc_ref'] = $objTalonario->cod_tipdocu;
						/*
					if ($this->input->post('documento') =="15") {
						$arrnota['tip_doc_ref']="01";
					}
					else if ($this->input->post('documento') =="16") {
						$arrnota['tip_doc_ref']="03";
					}
					else{
						$arrnota['tip_doc_ref']="00";
					}*/
						$arrnota['serie_doc_ref'] = $this->input->post('serie');
						$arrnota['num_doc_ref'] = $this->input->post('correlativo');
						$arrnota['ccod_mon'] = 'S';
						$arrnota['nt_cambio'] = '3.50';
						$arrnota['Estado'] = 'R';
						$arrnota['usu_reg'] = $this->session->cod_usu;
						$arrnota['Fec_Reg'] = date('Y-m-d');
						$dins = $this->notavalorizado_model->Insnotaunidad($arrnota, "N");
						if (sizeof($dins) > 0) {
							if ($dins['status'] != "1") {
								$resp['success'] = false;
								$flg_continuar = 0;
							} else {
								$this->notaunidad_model->ActualizarCorrelativoAlmacen(array('tipo' => 'NS', 'codalm' => '', 'Numero' => $arrnota['Num_Nota']));
							}
						} else {
							$resp['success'] = false;
							$flg_continuar = 0;
						}
						/*fin poblamos array para nota de ingreso*/
					}
				}
			}
			if ($flg_continuar == 1) {
				$resp['success'] = true;
				$resp['id'] = $insert;

				//$resp['printType'] = $printType[0]->type_formt;
				$resp['printType'] = 'T';
				if ($talonario->siglas_talonario == 'FC') {
					$resp['xml'] = $this->xmlHash($insert);
				} else {
					$resp['xml']['archivo'] = $this->generarNoXml($insert);
				}
			}
		} else {
			$resp['success'] = false;
		}

		header('content-type: application/json; charset=utf-8');
		echo json_encode($resp);
	}

	public function calcularGravadaExoneradaDeVenta($id)
	{
		$venta = $this->db->from('tb_venta')
			->where('cod_vent', $id)
			->get()->row();


		$venta->detalle = $this->db->from('tb_venta_detalle')
			->where('cod_vent', $id)
			->get()
			->result();


		$acumula_gravada = 0;
		$acumula_exonerada = 0;

		foreach ($venta->detalle as $detalle) {
			if ($detalle->igv_ventdet > 0) {
				$acumula_gravada += $detalle->subtotal_ventdet;
			} else {
				$acumula_exonerada += $detalle->subtotal_ventdet;
			}
		}

		$acumula_gravada -= $venta->igv_vent;
		$this->db->set('exonerada_vent', $acumula_exonerada)
			->set('gravada_vent', $acumula_gravada)
			->where('cod_vent', $venta->cod_vent)
			->update('tb_venta');
	}

	private function guardarCuotas($cod_venta)
	{
		if ($this->input->post('pago') == 'CRE' and $this->input->post('dias_cuotas') == 'on') {
			$this->modelgeneral->editRegist('tb_venta', ['cod_vent' => $cod_venta], ['num_cuotas_vent' => count($_POST['cuotas_fecha'])]);

			$data = [];
			$data['cod_vent'] = $cod_venta;
			foreach ($_POST['cuotas_fecha'] as $key => $value) {
				$data['fecha_ventcuo'] = $_POST['cuotas_fecha'][$key];
				$data['monto_ventcuo'] = $_POST['cuotas_monto'][$key];
				$this->modelgeneral->insertRegist('tb_venta_cuotas', $data);
			}
		}
	}

	private function generarNoXml($id)
	{
		$res = $this->db->from('tb_venta')
			->select('doc_cliente,serie,numero_vent')
			->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente')
			->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario')
			->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
			->where('cod_vent', $id)
			->get()->row();
		$noxml = $res->doc_cliente . '-0-' . $res->serie . '-' . $res->numero_vent;
		$this->db->where('cod_vent', $id)
			->set('noxml_vent', $noxml)
			->update('tb_venta');
		return $noxml;
	}

	// private function descontarDeAlmacen($data,$almacen)
	// private function descontarDeAlmacen($data, $almacen, $idTypeAssignmentProduct)
	// {
	// 	//RESTAR DE ALMACEN
	// 	// $this->db->query("UPDATE tb_producto_stock SET stock = stock - ".$data['cant_ventdet']." WHERE cod_almacen = ".$almacen." AND cod_producto = ".$data['cod_producto']);
	// 	if ($idTypeAssignmentProduct !== "null") {
	// 		$this->db->query("UPDATE tb_producto_stock SET stock = stock - " . $data['cant_ventdet'] . " WHERE cod_almacen = " . $almacen . " AND cod_producto = " . $idTypeAssignmentProduct);
	// 	} else {
	// 		$this->db->query("UPDATE tb_producto_stock SET stock = stock - " . $data['cant_ventdet'] . " WHERE cod_almacen = " . $almacen . " AND cod_producto = " . $data['cod_producto']);
	// 	}
	// }
	private function descontarDeAlmacen($data, $almacen, $idTypeAssignmentProduct)
	{
		// Obtén información del producto
		$productoInfo = $this->db->query("SELECT * FROM tb_producto WHERE cod_producto = " . $data['cod_producto'])->row_array();
		$typeAssignment = $productoInfo['typeAssignmentProducto'];
		// Verificar si $idTypeAssignmentProduct no es nulo
		if ($idTypeAssignmentProduct !== "null") {

			// Restar de almacen
			if ($typeAssignment == 'G') {
				// Si el tipo de asignación es "G", realizar descuento de stock basado en el costo
				$this->db->query("UPDATE tb_producto_stock SET stock = stock - " . $data['precunit_ventdet'] . " WHERE cod_almacen = " . $almacen . " AND cod_producto = " . $idTypeAssignmentProduct);
			} else {
				// De lo contrario, realizar descuento de stock como antes
				$this->db->query("UPDATE tb_producto_stock SET stock = stock - " . $data['cant_ventdet'] . " WHERE cod_almacen = " . $almacen . " AND cod_producto = " . $idTypeAssignmentProduct);
			}
		} else {
			$this->db->query("UPDATE tb_producto_stock SET stock = stock - " . $data['cant_ventdet'] . " WHERE cod_almacen = " . $almacen . " AND cod_producto = " . $data['cod_producto']);
		}
	}


	public function deudasCliente()
	{
		$cliente = $this->input->get('cliente');
		$query = $this->db->from('tb_venta')
			->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente')
			->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario')
			->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
			->where('tb_venta.id_cliente', $cliente)
			->where('pendiente_vent >', 0)
			->get()->result();

		echo json_encode($query);
	}

	public function editar($id)
	{
		$data['venta'] = $this->ventas_model->getVenta($id);
		$data['almacenes'] = $this->ventas_model->getAlmacenesDisponibles();
		$data['cajas'] = $this->modelgeneral->getTableWhere('tb_caja', ['est_caja' => 1]);
		$data['tipos'] = $this->ventas_model->getTiposVentas();
		$data['dolar'] = $this->modelgeneral->getTableWhereRow('parametros', ['nom_paramt' => 'DOLAR']);
		$data['doc_clientes'] = $this->ventas_model->getDocumentosCliente();
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/ventas/venteditar', $data);
		$this->load->view('layouts/footer');
	}

	public function anular()
	{
		$data['estado_vent'] = 'A'; //ANULAR	
		$where['cod_vent'] = $this->input->get('id');

		/*=======================================
		=            SUMAR STOCK            =
		=======================================*/
		$venta = $this->modelgeneral->getTableWhereRow('tb_venta', $where);
		$detalle = $this->modelgeneral->getTableWhere('tb_venta_detalle', $where);

		foreach ($detalle as $d) {
			$whereStock['cod_producto'] = $d->cod_father_product;
			$producto_row = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $d->cod_father_product]);
			if ($producto_row->cod_tiparticulo == 1) { //SI ES DEL TIPO PRODUCTO SE SUMA EL STOCK
				$whereStock['cod_almacen'] = $venta->cod_almacen;
				$productoStock = $this->modelgeneral->getTableWhereRow('tb_producto_stock', $whereStock);

				$nuevoStock = $productoStock->stock + $d->cant_ventdet;
				$edit = $this->modelgeneral->editRegist('tb_producto_stock', $whereStock, ['stock' => $nuevoStock]);

				//REGRESAR DISPONIBILIDAD A SERIE
				$this->db->where('cod_vent', $where['cod_vent'])
					->set('serie_estado', 'D')
					->set('cod_vent', null)
					->update('tb_producto_serie');

				//Cambia estado venta en la tabla cobros
				$this->db->where('cod_vent', $where['cod_vent'])
					->set('estado_vent', 'A')
					->update('tb_cobro');
			}
		}
		/*=====  End of SUMAR STOCK  ======*/

		$edit = $this->modelgeneral->editRegist('tb_venta', $where, $data);
		$resp = [];
		$resp['where'] = $whereStock;
		if ($edit) {
			$resp['success'] = true;
		} else {
			$resp['success'] = false;
		}
		echo json_encode($resp);
	}

	function agregarCliente()
	{
		$this->form_validation->set_rules('tipo', '', 'required');
		$this->form_validation->set_rules('nombre', '', 'required');
		$this->form_validation->set_rules('documento', '', 'required');
		// $this->form_validation->set_rules('telefono','','required');
		// Validación del documento
		if ($this->form_validation->run() == TRUE) {
			$tipo = $this->input->post('tipo');
			$nombre = $this->input->post('nombre');
			$documento = trim($this->input->post('documento'));

			$existingCliente = $this->modelgeneral->getTableWhereRow('tb_cliente', ['doc_cliente' => $documento]);
			if (!is_null($existingCliente)) {
				$resp['success'] = false;
				$resp['message'] = "Ya existe un cliente con este documento en la base de datos.";
				echo json_encode($resp);
				return;
			}

			// Realizar la validación aquí según el tipo de documento seleccionado
			if ($tipo === "2" && !preg_match('/^\d{8}$/', $documento)) {
				$resp['success'] = false;
				$resp['message'] = "El DNI no es válido.";
				echo json_encode($resp);
				return;
			} elseif ($tipo === "4" && !preg_match('/^\d{11}$/', $documento)) {
				$resp['success'] = false;
				$resp['message'] = "El RUC no es válido.";
				echo json_encode($resp);
				return;
			} elseif (($tipo === "3" || $tipo === "5") && !preg_match('/^[0-9a-zA-Z]{12}$/', $documento)) {
				$resp['success'] = false;
				$resp['message'] = "El documento no es válido, recuerda ingresar los datos correctos.";
				echo json_encode($resp);
				return;
			}
		}

		if ($this->form_validation->run() == TRUE) {
			$data['cod_tipdocucli '] = $this->input->post('tipo');
			$data['nomb_cliente'] = $this->input->post('nombre');
			$data['doc_cliente'] = trim($this->input->post('documento'));
			$data['fena_pac'] = $this->input->post('fnacimiento');
			$data['precio_cliente'] = $this->input->post('precio_venta');
			$data['telf_cliente'] = $this->input->post('telefono');
			$data['direc_cliente'] = $this->input->post('direccion');
			$data['contac_cliente'] = $this->input->post('contacto');
			$data['email_cliente'] = $this->input->post('email');
			$insert = $this->modelgeneral->insertRegist('tb_cliente', $data);

			$resp = [];
			if (!is_null($insert)) {
				$resp['cliente'] = $this->modelgeneral->getTableWhereRow('tb_cliente', ['id_cliente' => $insert]);
				$resp['success'] = true;
			} else {
				$resp['success'] = false;
			}

			echo json_encode($resp);
		}
	}

	public function getCliente()
	{
		$id = $this->input->get('id');
		$cliente = $this->modelgeneral->getTableWhereRow('tb_cliente', ['id_cliente' => $id]);
		echo json_encode($cliente);
	}

	public function editarCLiente()
	{
		$this->form_validation->set_rules('id', '', 'required');
		$this->form_validation->set_rules('nombre', '', 'required');
		$this->form_validation->set_rules('documento', '', 'required');
		// $this->form_validation->set_rules('telefono','','required');
		if ($this->form_validation->run() == TRUE) {
			$data['nomb_cliente'] = $this->input->post('nombre');
			$data['doc_cliente'] = $this->input->post('documento');
			$data['fena_pac'] = $this->input->post('fnacimiento');
			$data['precio_cliente'] = $this->input->post('precio_venta');
			$data['telf_cliente'] = $this->input->post('telefono');
			$data['direc_cliente'] = $this->input->post('direccion');
			$data['contac_cliente'] = $this->input->post('contacto');
			$data['email_cliente'] = $this->input->post('email');
			$data['estado_cliente'] = $this->input->post('estado');
			$where['id_cliente'] = $this->input->post('id');
			$editar = $this->modelgeneral->editRegist('tb_cliente', $where, $data);

			$resp = [];
			if ($editar) {
				$resp['success'] = true;
			} else {
				$resp['success'] = false;
			}

			echo json_encode($resp);
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
		$data['datos'] = $this->getVentaReporte();
		$html = $this->load->view('admin/ventas/reporte_pdf', $data, TRUE);
		$css = file_get_contents(APP_PATH . 'assets/styles_pdf.css');
		$this->mpdf->SetTitle('Ventas');
		$this->mpdf->writeHTML($css, 1);
		$this->mpdf->writeHTML($html, 2);
		$this->mpdf->Output('Ventas', 'I');
	}

	public function reporteExcel()
	{
		$data['datos'] = $this->getVentaReporte();
		$this->load->view('admin/ventas/reporte_excel', $data);
	}

	public function getVentaReporte()
	{
		$this->db->from('tb_venta');
		$this->db->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario');
		$this->db->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		$this->db->where('fecha_vent >= ', $this->input->get('desde'));
		$this->db->where('fecha_vent <=', $this->input->get('hasta'));
		if ($this->input->get('cliente') != '') {
			$this->db->like('nomb_cliente', $this->input->get('cliente'));
		}
		if ($this->input->get('vendedor') != '') {
			$this->db->where('tb_venta.cod_usu', $this->input->get('vendedor'));
		}
		if ($this->input->get('punto') != '') {
			$this->db->where('tb_venta.cod_puntoventa', $this->input->get('punto'));
		}
		if ($this->input->get('estado') != '') {
			$this->db->where('tb_venta.estado_vent', $this->input->get('estado'));
		}
		$query = $this->db->get();

		foreach ($query->result() as $q) {
			$q->detalle = $this->ventas_model->getDetalle($q->cod_vent);
		}
		return $query;
	}


	function imprimirVenta($archivoxml, $guardar = NULL)
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8', //MODE
			'format' => 'A4',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 5,
			'margin_bottom' => 5,
			'margin_header' => 10,
			'margin_footer' => 10
		]);
		$data['ventas'] = $this->ventas_model->getImpresionVenta($archivoxml);
		$data['empresa'] = $this->empresa_model->getEmpresa();
		$data['qr'] = $this->getQR($data['ventas']->cod_vent);
		$html = $this->load->view('admin/ventas/impventa', $data, TRUE);
		$css = file_get_contents(APP_PATH . 'assets/styles_pdf.css');
		$this->mpdf->SetTitle('Ventas');
		$this->mpdf->writeHTML($css, 1);
		$this->mpdf->writeHTML($html, 2);

		if (is_null($guardar)) {
			$this->mpdf->Output($data['ventas']->archivoxml_vent . '.pdf', 'I');
		} else {
			$resp = [];
			$this->limpiarComprobantesTemporales();
			$archivo = $archivoxml . '_' . time() . '.pdf';
			$this->mpdf->Output('assets/temporal/whatsapp_email/' . $archivo, 'F');

			$resp['xml'] = '';
			if ($data['ventas']->rutaxml_vent != '') {
				$resp['xml'] = $this->crearXMLTemporal($data['ventas']);
			}

			$resp['success'] = true;
			$resp['archivo'] = $archivo;
			$resp['telefono'] = trim($data['ventas']->telf_cliente);
			$resp['email'] = trim($data['ventas']->email_cliente);
			$resp['cliente'] = $data['ventas']->nomb_cliente;
			header('content-type: application/json; charset=utf-8');
			echo json_encode($resp);
		}
	}

	private function crearXMLTemporal($data)
	{
		$xml = $data->archivoxml_vent . '_' . time() . '.XML';
		copy(APP_PATH . 'facturacion/' . $data->rutaxml_vent . '/' . $data->archivoxml_vent . '.XML', APP_PATH . 'assets/temporal/whatsapp_email/' . $xml);
		return $xml;
	}

	function enviarEmail()
	{
		$empresa = $this->modelgeneral->getTableWhereRow('tb_empresa', ['cod_empresa' => 1]);
		$config['protocol'] = 'mail';
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from($empresa->email_emp, $empresa->nombre_comercial);
		$this->email->to($this->input->post('email'));
		$this->email->subject('Comprobante de Pago');
		$this->email->attach(APP_PATH . 'assets/temporal/whatsapp_email/' . $this->input->post('archivo'));
		if ($this->input->post('xml') != '') {
			$this->email->attach(APP_PATH . 'assets/temporal/whatsapp_email/' . $this->input->post('xml'));
		}
		$this->email->message('Saludos ' . $this->input->post('cliente') . ', adjuntamos el comprobante de pago.');
		$this->email->send();

		$resp = [];
		$resp['success'] = true;
		header('content-type: application/json; charset=utf-8');
		echo json_encode($resp);
	}

	function getQR($id)
	{
		/***** FACTURA: DATOS OBLIGATORIOS PARA EL CÓDIGO QR *****/
		/*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE |*/
		$venta = $this->ventas_model->getVenta($id);
		$empresa = getDatosEmpresa();
		$ruc = $empresa['empresa']->ruc_emp;
		$tipo_documento = $venta->codsunat_tipdocu;
		$serie = $venta->serie;
		$numero = $venta->numero_vent;
		$monto_total_igv = $venta->igv_vent;
		$monto_total = $venta->total_vent;
		$fecha_emision = date('d/m/Y', strtotime($venta->fecha_registro));
		$tipo_doc_cliente = $venta->codsunat_tipdocucli;
		$documento_cliente = $venta->doc_cliente;

		$text_qr = $ruc . '|' . $tipo_documento . '|' . $serie . '|' . $numero . '|' . $monto_total_igv . '|' . $monto_total . '|' . $fecha_emision . '|' . $tipo_doc_cliente . '|' . $documento_cliente . '|';

		return $text_qr;
	}

	function imprimirticketVenta($archivoxml)
	{
		$data['ventas'] = $this->ventas_model->getImpresionVenta($archivoxml);
		$filas = count($data['ventas']->detalle);
		$alturaTicket = 200 + ($filas * 80);

		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8', //MODE
			'format' => [75, $alturaTicket], //FORMAT
			'margin_left' => 2,
			'margin_right' => 2,
			'margin_top' => 2,
			'margin_bottom' => 2,
			'margin_header' => 0,
			'margin_footer' => 0
		]);
		$data['qr'] = $this->getQR($data['ventas']->cod_vent);
		$data['empresa'] = $this->empresa_model->getEmpresa($data);
		$html = $this->load->view('admin/ventas/ticketventa', $data, TRUE);
		$css = file_get_contents(APP_PATH . 'assets/styles_pdf.css');
		$this->mpdf->SetTitle($data['ventas']->archivoxml_vent);
		//$this->mpdf->setHTMLHeader($htmlHeader);
		//$this->mpdf->setHTMLFooter($htmlFooter);
		$this->mpdf->writeHTML($css, 1);
		$this->mpdf->writeHTML($html, 2);
		$this->mpdf->Output($data['ventas']->archivoxml_vent . '.pdf', 'I');
	}

	public function xmlHash($id, $firmar = NULL)
	{
		$res = $this->ventas_model->getVenta($id);
		//var_dump($res);
		//exit();

		// RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
		$ruta = base_url_app() . "/facturacion/api_facturacion/factura_xml.php";

		//se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf
		$tipo_proceso = getTipoProceso();


		$retencion = array();
		$retencion['activo'] = false;

		if ($res->retencion_base_imp != '' and $res->retencion_porcentaje != '') {
			$retencion['activo'] = true;
			$retencion['retencion_base_imp'] = round($res->retencion_base_imp,2);
			$retencion['retencion_porcentaje'] = $res->retencion_porcentaje;
			$retencion['retencion_monto'] = round($res->retencion_monto,2);
		}
		$detraccion = array();
		$detraccion['activo'] = false;

		if ($res->codsunat_tipdocu == '01' and $res->detraccion_id_mediopago != '' and $res->detraccion_cuenta != '' and $res->detraccion_iddetraccion != '') {
			$detraccion['activo'] = true;
			$detraccion['id_mediopago'] = $res->detraccion_id_mediopago;
			$detraccion['cuenta'] = $res->detraccion_cuenta;
			$detraccion['iddetraccion'] = $res->detraccion_iddetraccion;
			$detraccion['porcentaje'] = $res->detraccion_porcentaje;
			$detraccion['monto'] = $res->detraccion_monto;
			$detraccion['texto'] = $res->detraccion_texto;
		}

		$total_reten_cuot = $res->total_vent - $res->retencion_monto;
		$data = array(

			// RETENCION
			"retencion" => $retencion,
			//DETRACION
			"detraccion" => $detraccion,
			//Cabecera del documento
			"tipo_proceso" 					=> $tipo_proceso['tipo_proceso'],
			"tipo_operacion"				=> $detraccion['activo'] == true ? "1001" : "0101", //Venta interna pag 28
			//"total_gravadas"               	=> strval($res->subtotal_vent),
			"total_inafecta"                => "0",
			//"total_exoneradas"				=> "0",
			"total_gratuitas"			    => "0",
			"total_exportacion"		    	=> "0",
			"total_descuento"	    		=> "0",
			"sub_total"              		=> strval($res->subtotal_vent),
			"porcentaje_igv"                => "18.00",
			"total_igv"                     => strval($res->igv_vent),
			"total_isc"                   	=> "0",
			"total_otr_imp"                 => "0",
			"total_retencion_cuot"	=> $total_reten_cuot,
			"total"                  		=> strval($res->total_vent),
			"total_letras"              	=> 'SON ' . strtoupper(convertir(intval($res->total_vent))),
			"nro_guia_remision"             => "",
			"cod_guia_remision"             => "",
			"nro_otr_comprobante"           => "",
			"serie_comprobante"             => $res->serie, //Para Facturas la serie debe comenzar por la letra F, seguido de tres dígitos
			"numero_comprobante"            => (string)$res->numero_vent,
			"fecha_comprobante"             => $res->fecha_vent,
			"fecha_vto_comprobante"         => date('Y-m-d'),
			"cod_tipo_documento"            => strval($res->codsunat_tipdocu),
			"cod_moneda"                    => $res->codmoneda_vent,
			"cuotas" 												=> (!empty($res->cuotas)) ? $res->cuotas : null,

			//Datos del cliente
			"cliente_numerodocumento"       => $res->doc_cliente,
			"cliente_nombre"                => $res->nomb_cliente,
			"cliente_tipodocumento"         => (string)$res->codsunat_tipdocucli,
			"cliente_direccion"             => $res->direc_cliente,
			"cliente_pais"         			=> "PE",
			"cliente_ciudad"				=> "AYACUCHO",
			"cliente_codigoubigeo"          => "050101",
			"cliente_departamento"          => "AYACUCHO",
			"cliente_provincia"         	=> "HUAMANGA",
			"cliente_distrito"              => "AYACUCHO",

			//data de la empresa emisora o contribuyente que entrega el documento electrónico.
			"emisor" => getEmisor()
			//items del documento
		);


		$detalle = [];
		$n = 1;

		$total_gravadas = 0;
		$total_exoneradas = 0;
		foreach ($res->detalle as $d) {
			if ($d->tipo_ventdet == 'V' || $d->tipo_ventdet == 'E') {
				$precio = $d->precunit_ventdet - $d->descuento_ventdet;
				$det['txtITEM'] = $n;
				$det['txtUNIDAD_MEDIDA_DET'] = $d->unidad_abreviatura_ventdet; //NIU = BIENES, ZZ = SERVICIOS
				$det['txtCANTIDAD_DET'] = (string)$d->cant_ventdet;
				$det['txtPRECIO_DET'] = (string)$precio;
				$det['txtSUB_TOTAL_DET'] = (string)$d->prec_ventdet;
				$det['txtPRECIO_TIPO_CODIGO'] = '01';

				$det['txtIGV'] = $d->igv_ventdet;
				$det['txtISC'] = '0';
				$det['txtIMPORTE_DET'] = (string)$d->prec_ventdet;
				$det['txtCOD_TIPO_OPERACION'] = ($d->igv_ventdet > 0) ? '10' : '20';
				$det['txtCODIGO_DET'] = (string)(!is_null($d->cod_producto)) ? $d->cod_producto : $d->cod_servicio;
				$det['txtDESCRIPCION_DET'] = (string)$d->producto_ventdet;
				$precioSinIGV = $precio - ($precio / 1.18) * 0.18;
				$det['txtPRECIO_SIN_IGV_DET'] = (string)($d->igv_ventdet > 0) ? round($precioSinIGV, 10) : $precio;
				$det['txtCODIGO_PROD_SUNAT'] = '23251602';

				$det['TIPO_IGV'] = ($d->igv_ventdet > 0) ? '1000' : '9997';
				$det['MONTO_IGV'] = ($d->igv_ventdet > 0) ? '18.00' : '0';
				$det['IGV_EXO'] = ($d->igv_ventdet > 0) ? 'IGV' : 'EXO';
				$detalle[] = $det;

				if ($d->igv_ventdet > 0) {
					$total_gravadas += $d->prec_ventdet;
				} else {
					$total_exoneradas += $d->prec_ventdet;
				}
				$n++;
			}
		}

		$data['detalle'] = $detalle;
		$data['total_gravadas'] = $total_gravadas;
		$data['total_exoneradas'] = $total_exoneradas;

		//Invocamos el servicio
		$token = ''; //en caso quieras utilizar algún token generado desde tu sistema

		//codificamos la data

		$data_json = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Authorization: Token token="' . $token . '"',
				'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);

		curl_close($ch);

		$response = json_decode($respuesta, true);
		$empresa = $this->modelgeneral->getTableWhereRow('tb_empresa', ['cod_empresa' => 1]);

		if ($response['respuesta'] == 'ok') {
			$this->db->where('cod_vent', $id)
				->set('rutaxml_vent', $response['ruta'])
				->set('archivoxml_vent', $response['archivo'])
				->set('hash_vent', $response['hash_cpe'])
				->update('tb_venta');
			if ($empresa->enviar_factura_emp == 1) {
				$response['factura_enviada'] = true;
				if ($data['cod_tipo_documento'] == '01') {
					for ($i = 0; $i < reintentos(); $i++) {
						$response['response_factura_enviada'] = $this->enviarDocumento($id);
						if ($response['response_factura_enviada']['cod_sunat'] == '0') {
							break;
						}
					}
				}

				if ($data['cod_tipo_documento'] == '03') {
					for ($i = 0; $i < reintentos(); $i++) {
						$response['response_factura_enviada'] = $this->resumenBoleta($id);
						if ($response['response_factura_enviada']['resp']['hash_cdr'] != '') {
							break;
						}
					}
				}
				if (!is_null($firmar)) {
					echo json_encode($response);
				} else {
					return $response;
				}
			} else {
				if (!is_null($firmar)) {
					echo json_encode($response);
				} else {
					$response['factura_enviada'] = false;
					return $response;
				}
			}
		} else {
			return false;
		}
	}

	private function enviarDocumento($id)
	{
		$res = $this->ventas_model->getVenta($id);

		// RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
		$ruta = base_url_app() . "/facturacion/api_facturacion/factura_enviardocumento.php";

		//se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf

		$emisor = getEmisor();
		//EMISOR
		$data['ruc'] = $emisor['ruc'];
		$data['usuariosol'] = $emisor['usuariosol'];
		$data['clavesol'] = $emisor['clavesol'];

		//RUTAS
		$data['ruta_xml'] = '../' . $res->rutaxml_vent . '/' . $res->archivoxml_vent;
		$data['ruta_cdr'] = '../' . $res->rutaxml_vent . '/';
		$data['nombre_archivo'] = $res->archivoxml_vent;

		$tipo_proceso = getTipoProceso();
		$data['ruta_ws'] = $tipo_proceso['ruta_ws'];
		$data_json = json_encode($data);
		$token = '';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Authorization: Token token="' . $token . '"',
				'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($respuesta, true);

		$query = $this->db->select('cod_vent,rutaxml_vent,archivoxml_vent')
			->where('cod_vent', $id)
			->from('tb_venta')
			->get();

		$queryFacturacion = $this->db->from('tb_facturacion')
			->where('cod_vent', $id)
			->get();

		$msj_sunat = msj_sunat($response['msj_sunat']);

		if ($response['respuesta'] == 'ok' and $response['hash_cdr'] != '' and $response['cod_sunat'] == '0') {
			$hash = $response['hash_cdr'];
			$estado = 1;
		} else {
			$hash = null;
			$estado = 2;
		}

		if ($queryFacturacion->num_rows() == 0) {
			$this->db->set('cod_fecha', date('Y-m-d'))
				->set('cod_vent', $id)
				->set('cod_usu', $this->session->userdata('cod_usu'))
				->set('hashcdr_fac', $hash)
				->set('estado_fac', $estado)
				->set('msj_sunat_fac', $msj_sunat)
				->set('cod_sunat_fac', $response['cod_sunat'])
				->insert('tb_facturacion');
		} else {
			$this->db->set('cod_fecha', date('Y-m-d'))
				->set('cod_usu', $this->session->userdata('cod_usu'))
				->set('hashcdr_fac', $hash)
				->set('estado_fac', $estado)
				->set('msj_sunat_fac', $msj_sunat)
				->set('cod_sunat_fac', $response['cod_sunat'])
				->where('cod_vent', $id)
				->update('tb_facturacion');
		}

		$response['query'] = $query->row();
		$response['estado'] = $estado;
		return $response;
	}

	public function resumenBoleta($id)
	{
		$secuencia = $this->modelgeneral->getSecuencia('tb_resumenboleta', 'secuencia_res');
		$fecha = date('Y-m-d');
		$query = $this->db->from('tb_venta')
			->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,nomb_cliente')
			->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario')
			->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
			->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente')
			->where('codsunat_tipdocu', '03')
			->where('tb_venta.cod_vent', $id)
			->get()->row();

		$verificaResumenDetalle = $this->modelgeneral->getTableWhereRow('tb_resumenboletadetalle', ['cod_vent' => $query->cod_vent]);
		if (is_null($verificaResumenDetalle)) {
			$data['codigo_res'] = 'RC';
			$data['serie_res'] = date("Ymd", strtotime($fecha));
			$data['secuencia_res'] = $secuencia;
			$data['fechareferencia_res'] = $fecha;
			$data['fechadocumento_res'] = $fecha;
			$insert = $this->modelgeneral->insertRegist('tb_resumenboleta', $data);

			$detalle['cod_res'] = $insert;
			$detalle['cod_vent'] = $query->cod_vent;
			$this->modelgeneral->insertRegist('tb_resumenboletadetalle', $detalle);

			$cod_res = $insert;
		} else {
			$cod_res = $verificaResumenDetalle->cod_res;
		}


		$resp = [];

		if (!is_null($cod_res)) {
			$resp['success'] = true;
			$resp['resp'] = $this->resumenDocumento($cod_res, $fecha, $secuencia);

			$editData['rutaxml_res'] = $resp['resp']['ruta'];
			$editData['archivoxml_res'] = $resp['resp']['archivo'];
			$editData['hash_res'] = $resp['resp']['hash_cpe'];
			$editData['ticket_res'] = $resp['resp']['id_ticket'];
			$editData['DesRptaSunat'] = msj_sunat($resp['resp']['msj_sunat']);
			$this->modelgeneral->editRegist('tb_resumenboleta', ['cod_res' => $cod_res], $editData);
		} else {
			$resp['success'] = false;
		}

		return $resp;
	}

	public function resumenDocumento($id, $fecha, $secuencia)
	{

		$query = $this->db->from('tb_resumenboletadetalle')
			->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,codmoneda_vent,nomb_cliente,serie,numero_vent,codsunat_tipdocucli,doc_cliente')
			->join('tb_venta', 'tb_resumenboletadetalle.cod_vent = tb_venta.cod_vent')
			->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario')
			->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
			->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente')
			->join('tb_tipodocumentocliente', 'tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
			->where('codsunat_tipdocu', '03')
			->where('cod_res', $id)
			->get()->result();

		// RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
		$ruta = base_url_app() . "/facturacion/api_facturacion/resumen_boletas.php";
		//se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf

		$tipo_proceso = getTipoProceso();
		$data = array(

			//Cabecera del documento

			"tipo_proceso" 					=> $tipo_proceso['tipo_proceso'],
			"codigo"						=> 'RC',
			"serie"							=> date("Ymd", strtotime($fecha)),
			"secuencia"             		=> (string)$secuencia,
			"fecha_referencia"             	=> $fecha,
			"fecha_documento"          		=> $fecha,

			//data de la empresa emisora o contribuyente que entrega el documento electrónico.
			"emisor" => getEmisor()
		);

		//items
		$detalle = [];
		$n = 1;
		foreach ($query as $q) {
			$det['ITEM'] = (string)$n;
			$det['TIPO_COMPROBANTE'] = '03';
			$det['NRO_COMPROBANTE'] = (string)$q->serie . '-' . $q->numero_vent;
			$det['NRO_DOCUMENTO'] = (string)$q->doc_cliente;
			$det['TIPO_DOCUMENTO'] = (string)$q->codsunat_tipdocucli;
			$det['NRO_COMPROBANTE_REF'] = '0';
			$det['TIPO_COMPROBANTE_REF'] = '0';
			$det['STATUS'] = '1';
			$det['COD_MONEDA'] = $q->codmoneda_vent;
			$det['TOTAL'] = (string)$q->total_vent;
			$det['GRAVADA'] = (string)$q->subtotal_vent;
			$det['EXONERADO'] = '0';
			$det['INAFECTO'] = '0';
			$det['EXPORTACION'] = '0';
			$det['GRATUITAS'] = '0';
			$det['MONTO_CARGO_X_ASIG'] = '0';
			$det['CARGO_X_ASIGNACION'] = '0';
			$det['ISC'] = '0';
			$det['IGV'] = (string)$q->igv_vent;
			$det['OTROS'] = '0';
			$detalle[] = $det;
			$n++;
		}
		$data['detalle'] = $detalle;

		//Invocamos el servicio
		$token = ''; //en caso quieras utilizar algún token generado desde tu sistema

		//codificamos la data
		$data_json = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Authorization: Token token="' . $token . '"',
				'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($respuesta, true);
		return $response;
	}


	function limpiarComprobantesTemporales()
	{

		$directorio = APP_PATH . 'assets/temporal/whatsapp_email';
		// Array en el que obtendremos los resultados
		$res = array();

		// Agregamos la barra invertida al final en caso de que no exista
		if (substr($directorio, -1) != "/") $directorio .= "/";

		// Creamos un puntero al directorio y obtenemos el listado de archivos
		$dir = @dir($directorio) or die("getFileList: Error abriendo el directorio $directorio para leerlo");
		while (($archivo = $dir->read()) !== false) {
			// Obviamos los archivos ocultos
			if ($archivo[0] == ".") continue;
			if (is_dir($directorio . $archivo)) {
				$res[] = array(
					"Nombre" => $directorio . $archivo . "/",
					"Archivo" => $archivo,
					"Tamaño" => 0,
					"Modificado" => filemtime($directorio . $archivo)
				);
			} else if (is_readable($directorio . $archivo)) {
				$res[] = array(
					"Nombre" => $directorio . $archivo,
					"Archivo" => $archivo,
					"Tamaño" => filesize($directorio . $archivo),
					"Modificado" => filemtime($directorio . $archivo)
				);
			}
		}
		$dir->close();


		$dias_eliminacion = 7;
		$dias_tiempo = 60 * 60 * 24 * $dias_eliminacion;

		foreach ($res as $key => $value) {

			$archivo_tiempo = explode('_', trim(trim($value['Archivo'], '.XML'), '.pdf'))[1];

			$sumado = $archivo_tiempo + $dias_tiempo;
			if (time() > $sumado) {
				unlink($value['Nombre']);
			}
		}
	}
	public function ConsultarEstadoTicket()
	{
		//$res = $this->ventas_model->getVenta($id);		
		$idticket = $this->input->post('idticket');
		$id = $this->input->post('idres');
		$archivo = $this->input->post('nombre');
		// RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
		$ruta = base_url_app() . "/facturacion/api_facturacion/resumen_boletas_consul_ticket.php";


		$tipo_proceso = getTipoProceso();
		$data = array(
			//Cabecera del documento
			"archivoxml_res" => $archivo,
			"ticket_res" => $idticket,
			"tipo_proceso" 					=> $tipo_proceso['tipo_proceso'],
			//data de la empresa emisora o contribuyente que entrega el documento electrónico.
			"emisor" => getEmisor()
		);

		//Invocamos el servicio
		$token = ''; //en caso quieras utilizar algún token generado desde tu sistema

		//codificamos la data
		//var_export($data);
		$data_json = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Authorization: Token token="' . $token . '"',
				'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$response = json_decode($respuesta, true);
		//var_export($httpcode);
		$resp[] = null;
		if ($httpcode == 200) { //======LA PAGINA SI RESPONDE
			//{"respuesta":"error","cod_sunat":"","mensaje":"SUNAT ESTA FUERA SERVICIO: ","hash_cdr":"","ruta_cdr":"","msj_sunat":""}1
			if ($response["respuesta"] == "error") {
				$resp["codrpta"] = 0;
			} else {
				$resp["codrpta"] = 1;
				$resp["ruta_cdr"] = $response['ruta_cdr'];
				$editData['CodRptaSunat'] = $response['cod_sunat'];
				$editData['DesRptaSunat'] = $response['mensaje'];
				$editData['RutaCdrXML'] = $response['ruta_cdr'];
				//var_export($editData);
				//var_export($id);
				$this->modelgeneral->editRegist('tb_resumenboleta', ['cod_res' => $id], $editData);
			}
		} else {
			$resp["codrpta"] = 0;
		}
		echo json_encode($response);
	}

	public function calcularGravadaExonerada()
	{
		$ventas = $this->db->from('tb_venta')->get()->result();

		foreach ($ventas as $v) {
			$v->detalle = $this->db->from('tb_venta_detalle')
				->where('cod_vent', $v->cod_vent)
				->get()
				->result();
		}

		foreach ($ventas as $venta) {
			$acumula_gravada = 0;
			$acumula_exonerada = 0;

			foreach ($venta->detalle as $detalle) {
				if ($detalle->igv_ventdet > 0) {
					$acumula_gravada += $detalle->subtotal_ventdet;
				} else {
					$acumula_exonerada += $detalle->subtotal_ventdet;
				}
			}

			$acumula_gravada -= $venta->igv_vent;
			$this->db->set('exonerada_vent', $acumula_exonerada)
				->set('gravada_vent', $acumula_gravada)
				->where('cod_vent', $venta->cod_vent)
				->update('tb_venta');
		}
	}
}
