<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regcotizacion extends CI_Controller {
	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cotizacion_model');
		$this->load->model('empresa_model');
		$this->load->model('modelgeneral');
		$this->load->model('ventas_model');
		$this->load->helper('general');
    $this->permisos = $this->backend_lib->control();
	}
	
	public function index()
	{
		$data['tipos_pagos'] = $this->modelgeneral->getTableWhere('tb_tipo_pago',['estado_tipopago'=>1]);
		$data['tipos_tarjetas'] = $this->modelgeneral->getTableWhere('tb_tarjeta',['estado_tarj'=>1]);
		$data['apertura'] = $this->ventas_model->getCajaApertura();
		$data['punto'] = $this->modelgeneral->getTableWhereRow('tb_puntoventa',['cod_puntoventa'=>$this->session->userdata('puntoventa')]);
		$data['permisos'] =$this->permisos;
		$data['almacenes'] = $this->ventas_model->getAlmacenesDisponibles();
		$this->load->view('layouts/header');
	    $this->load->view('layouts/aside');
	    $this->load->view('admin/cotizacion/panel',$data);    
	    $this->load->view('layouts/footer');
	}

	public function procesarCotizacion()
	{
		$apertura = $this->ventas_model->getCajaApertura();
		if ($apertura==false) {
			return false;
		}

		$data['fecha_vent'] = $this->input->post('fecha');
		$data['hora_vent'] = date('H:i:s');
		$data['id_cliente'] = $this->input->post('cliente');
		$data['cod_almacen'] = $this->input->post('almacen');
		$data['cod_talonario'] = $this->input->post('tipo_documento');
		$talonario = $this->modelgeneral->getTableWhereRow('tb_talonario',['cod_talonario'=>$data['cod_talonario']]);
		$data['numero_vent'] = $this->numeracion($data['cod_talonario']);
		$data['moneda_vent'] = $this->input->post('moneda');
		if ($data['moneda_vent']=='S') {
			$data['codmoneda_vent'] = 'PEN';
		}else {
			$data['codmoneda_vent'] = 'USD';
		}
		$data['cambio_vent'] = $this->input->post('tipoCambio');
		$data['monto_vent'] = $this->input->post('monto');
		$data['pago_vent'] = $this->input->post('pago');
		$data['igv_vent'] = ($this->input->post('total') / 1.18) * 0.18;
		$data['subtotal_vent'] = $this->input->post('total') - $data['igv_vent'];
		$data['total_vent'] = $this->input->post('total');
		$data['montorecibido_vent'] = $this->input->post('montoRecibido');
		$data['vuelto_vent'] = $this->input->post('vuelto');
		$data['pendiente_vent'] = $data['total_vent'] - $data['monto_vent'];
		$data['estado_vent'] = 'G';
		$data['cod_usu'] = $this->session->userdata('cod_usu');
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
		$insert = $this->modelgeneral->insertRegist('tb_venta',$data);

		$venta = $this->db->from('tb_venta')
							->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
							->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
							->get()->row();
		$cobro['cod_vent'] = $insert;
		if ($this->input->post('pago')=='CO') {
			$tipo_cobro = 'Contado';
		}else{
			$tipo_cobro = 'Credito';
		}
		$cobro['tipo_cobro'] = $tipo_cobro;
		$cobro['cod_caja'] = $apertura->cod_caja;
		$cobro['fecha_cobro'] = $this->input->post('fecha');
		$cobro['detalle_cobro'] = 'PAGO COBRO: '.$venta->nom_tipdocumento.'-'.$venta->serie.'-'.$venta->numero_vent;
		$cobro['monto_cobro'] = $this->input->post('monto');
		$this->modelgeneral->insertRegist('tb_cobro',$cobro);


		$resp = [];
		if (!is_null($insert)) {
			$this->aumentarNumeracion($data['cod_talonario'],$data['numero_vent']);
			
			foreach ($_POST['id_prod'] as $key => $value) {
				$pos = strpos($value, 'ser-');
				if ($pos !== false) {
					$precio_unitario = $_POST['prec_prod'][$value];
					$codigo_producto = null;
					$detalle['cod_servicio'] = substr($value,4).'-'.$insert;
				}else{
					$producto = $this->modelgeneral->getTableWhereRow('tb_producto',['cod_producto'=>$value]);
					$codigo_producto = $_POST['id_prod'][$key];
					$precio_unitario = $producto->prec_costo;
				}
				
				$detalle['cod_vent'] = $insert;
				$detalle['cod_producto'] = $codigo_producto;
				$detalle['producto_ventdet'] = $_POST['nombre_prod'][$key];
				$detalle['cant_ventdet'] = $_POST['cant_prod'][$key];
				$detalle['precunitcomp_ventdet'] = $precio_unitario;
				$detalle['precunit_ventdet'] = $_POST['prec_prod'][$key];
				if($_POST['desc_prod'][$key]==''){
					$descuento = 0;
				}else{
					$descuento = $_POST['desc_prod'][$key];
				}
				$detalle['subtotal_ventdet'] = (($detalle['precunit_ventdet'] - $descuento) * $detalle['cant_ventdet']);
				$detalle['igv_ventdet'] = (($detalle['subtotal_ventdet'])  / 1.18) * 0.18;
				$detalle['prec_ventdet'] = $detalle['subtotal_ventdet'] - $detalle['igv_ventdet'];
				$detalle['descuento_ventdet'] = $descuento;
				$detalle['estado_ventdet'] = 'S';

				$detalle['unidad_ventdet'] = $_POST['unidad_prod'][$key];
				$detalle['peso_ventdet'] = $_POST['peso_prod'][$key];


				$insertDetalle = $this->modelgeneral->insertRegist('tb_venta_detalle',$detalle);
				
				$pos = strpos($value, 'ser-');
				if ($pos === false) {
					if ($producto->cod_tiparticulo==1) { //SI ES PRODUCTO 
						$this->descontarDeAlmacen($detalle,$data['cod_almacen']); //DESCONTAR STOCK
						if (isset($_POST['series'])) {
							$series = $_POST['series'][$producto->cod_producto];
							if(is_array($series)){
								foreach ($series as $key => $value) {
									$whereSeries['cod_almacen'] = $data['cod_almacen'];
									$whereSeries['cod_producto'] = $producto->cod_producto;
									$whereSeries['serie_descripcion'] = $value;
									$dataSeries['cod_vent'] = $insert;
									$dataSeries['serie_estado'] = 'N';
									$this->modelgeneral->editRegist('tb_producto_serie',$whereSeries,$dataSeries);
		
									$dataVentaDetalleSerie['cod_ventdet '] = $insertDetalle;
									$dataVentaDetalleSerie['cod_producto'] = $producto->cod_producto;
									$dataVentaDetalleSerie['serie_ventdetserie '] = $value;
									$this->modelgeneral->insertRegist('tb_venta_detalle_serie',$dataVentaDetalleSerie);
								}
							}else{
								$whereSeries['cod_almacen'] = $data['cod_almacen'];
								$whereSeries['cod_producto'] = $producto->cod_producto;
								$whereSeries['serie_descripcion'] = $series;
								$dataSeries['cod_vent'] = $insert;
								$dataSeries['serie_estado'] = 'N';
								$this->modelgeneral->editRegist('tb_producto_serie',$whereSeries,$dataSeries);
		
								$dataVentaDetalleSerie['cod_ventdet '] = $insertDetalle;
								$dataVentaDetalleSerie['cod_producto'] = $producto->cod_producto;
								$dataVentaDetalleSerie['serie_ventdetserie '] = $series;
								$this->modelgeneral->insertRegist('tb_venta_detalle_serie',$dataVentaDetalleSerie);
							}
						}
					}
				}
			}

			$resp['success'] = true;
			$resp['id'] = $insert;
			if ($talonario->siglas_talonario=='FC') {
				$resp['xml'] = $this->xmlHash($insert);
			}else{
				$resp['xml']['archivo'] = $this->generarNoXml($insert);
			}
		}else{
			$resp['success'] = false;
		}
		
		echo json_encode($resp);
	}

	private function generarNoXml($id)
	{
		$res = $this->db->from('tb_venta')
		->select('doc_cliente,serie,numero_vent')
    ->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')
    ->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
		->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
		->where('cod_vent',$id)
		->get()->row();
		$noxml = $res->doc_cliente.'-0-'.$res->serie.'-'.$res->numero_vent;
		$this->db->where('cod_vent',$id)
		->set('noxml_vent',$noxml)
		->update('tb_venta');
		return $noxml;
	}

	private function xmlHash($id)
	{
		$res = $this->ventas_model->getVenta($id);
		
		// RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
		$ruta = base_url_app()."/facturacion/api_facturacion/factura_xml.php";
 
		//se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf
		$tipo_proceso = getTipoProceso();
		$data = array(

			//Cabecera del documento
			"tipo_proceso" 					=> $tipo_proceso['tipo_proceso'],
			"tipo_operacion"				=> "0101", //Venta interna pag 28
			"total_gravadas"               	=> strval($res->subtotal_vent),
			"total_inafecta"                => "0",
			"total_exoneradas"				=> "0",
			"total_gratuitas"			    => "0",
			"total_exportacion"		    	=> "0",
			"total_descuento"	    		=> "0",
			"sub_total"              		=> strval($res->subtotal_vent),
			"porcentaje_igv"                => "18.00",
			"total_igv"                     => strval($res->igv_vent),
			"total_isc"                   	=> "0",
			"total_otr_imp"                 => "0",
			"total"                  		=> strval($res->total_vent),
			"total_letras"              	=> 'SON '.strtoupper(convertir(intval($res->total_vent))),
			"nro_guia_remision"             => "",
			"cod_guia_remision"             => "",
			"nro_otr_comprobante"           => "",
			"serie_comprobante"             => $res->serie, //Para Facturas la serie debe comenzar por la letra F, seguido de tres dígitos
			"numero_comprobante"            => (string)$res->numero_vent,
			"fecha_comprobante"             => $res->fecha_vent,
			"fecha_vto_comprobante"         => date('Y-m-d'),
			"cod_tipo_documento"            => strval($res->codsunat_tipdocu),
			"cod_moneda"                    => $res->codmoneda_vent,

			//Datos del cliente
				"cliente_numerodocumento"       => $res->doc_cliente,
				"cliente_nombre"                => $res->nomb_cliente,
				"cliente_tipodocumento"         => (string)$res->codsunat_tipdocucli,
				"cliente_direccion"             => $res->direc_cliente,
				"cliente_pais"         			=> "PE",
				"cliente_ciudad"				=> "Lima",
				"cliente_codigoubigeo"          => "",
				"cliente_departamento"          => "",
				"cliente_provincia"         	=> "",
				"cliente_distrito"              => "",

			//data de la empresa emisora o contribuyente que entrega el documento electrónico.
				"emisor" => getEmisor()
			//items del documento
		);

		$detalle = [];
		$n = 1;
		foreach ($res->detalle as $d) {
			$precio = $d->precunit_ventdet - $d->descuento_ventdet;
			$det['txtITEM'] = $n;
			$det['txtUNIDAD_MEDIDA_DET'] = (!is_null($d->cod_producto))?'NIU':'ZZ'; //NIU = BIENES, ZZ = SERVICIOS
			$det['txtCANTIDAD_DET'] = (string)$d->cant_ventdet;
			$det['txtPRECIO_DET'] = (string)$precio;
			$det['txtSUB_TOTAL_DET'] = (string)$d->prec_ventdet;
			$det['txtPRECIO_TIPO_CODIGO'] = '01';
			
			$det['txtIGV'] = $d->igv_ventdet;
			$det['txtISC'] = '0';
			$det['txtIMPORTE_DET'] = (string)$d->prec_ventdet;
			$det['txtCOD_TIPO_OPERACION'] = '10';
			$det['txtCODIGO_DET'] = (string)(!is_null($d->cod_producto))?$d->cod_producto:$d->cod_servicio;
			$det['txtDESCRIPCION_DET'] = (string)$d->producto_ventdet;
			$precioSinIGV = $precio - ($precio / 1.18) * 0.18;
			$det['txtPRECIO_SIN_IGV_DET'] = (string)round($precioSinIGV,2);
			$det['txtCODIGO_PROD_SUNAT'] = '23251602';
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
			$ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Token token="'.$token.'"',
			'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($respuesta,true);

		if($response['respuesta']=='ok'){
			$this->db->where('cod_vent',$id)
			->set('rutaxml_vent',$response['ruta'])
			->set('archivoxml_vent',$response['archivo'])
			->set('hash_vent',$response['hash_cpe'])
			->update('tb_venta');
			return $response;
		}else{
			return false;
		}
		
	}

	public function getCotizar()
	{
		$id = $this->input->get('id');
		$cotizacion = $this->cotizacion_model->obtenerCotizacion($id);

		if($cotizacion->codsunat_tipdocucli=='6'){
			$tipo_documento_activo = 'doccliruc_talonario';
		}else{
			$tipo_documento_activo = 'docclidni_talonario';
		}
		$documentos = $this->cotizacion_model->getTiposVentas($tipo_documento_activo);
		
		$res = [];

		$res['cotizacion'] = $cotizacion;
		$res['documentos'] = $documentos;
		echo json_encode($res);
	}

	public function numeracion($tipo=NULL)
	{
		if (is_null($tipo)) {
			$id = $this->input->get('id');
		}else{
			$id = $tipo;
		}
		$query =  $this->db->from('tb_talonario')
							->select('correlativo_actual,serie')
							->where('cod_puntoventa',$this->session->userdata('puntoventa'))
							->where('cod_talonario',$id)
							->get()->row();
		if (is_null($tipo)) {
			echo json_encode($query);
		}else{
			return $query->correlativo_actual;
		}
	}

	private function descontarDeAlmacen($data,$almacen)
	{
		//RESTAR DE ALMACEN
		$this->db->query("UPDATE tb_producto_stock SET stock = stock - ".$data['cant_ventdet']." WHERE cod_almacen = ".$almacen." AND cod_producto = ".$data['cod_producto']);
	}


	public function jsonCotizacion()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho']  = $this->input->get_post('_', true);
		$columns= ['','cod_cot','nomb_cliente'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['desde'] = $this->input->get_post('desde');
		$data['hasta'] = $this->input->get_post('hasta');
		$data['estado'] = $this->input->get_post('estado');
		$data['pago'] = $this->input->get_post('pago');

		$datos = $this->cotizacion_model->getCotizacion($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	public function agregar()
	{
		$data['doc_clientes'] = $this->ventas_model->getDocumentosCliente();
		$data['tipos'] = $this->cotizacion_model->getTipos();
		$data['dolar'] = $this->modelgeneral->getTableWhereRow('parametros',['nom_paramt'=>'DOLAR']);
		$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/cotizacion/agregar',$data);    
    $this->load->view('layouts/footer');
	}

	private function aumentarNumeracion($tipo,$actual)
	{
		$nuevo = $actual + 1;
		$this->db->set('correlativo_actual',$nuevo)
		->where('cod_puntoventa',$this->session->userdata('puntoventa'))
		->where('cod_talonario',$tipo)
		->update('tb_talonario');
	}


	public function getClientes()
	{
		$q = $this->input->get('q');

		$result = $this->db->from('tb_cliente')
		->select('id_cliente as id,nomb_cliente as nombre,doc_cliente as ruc, direc_cliente as direccion,precio_cliente')
		->like('nomb_cliente',$q)
		->get()->result();

		echo json_encode($result);
	}

	public function getProductoBusqueda()
	{
		$producto = $this->input->get('producto');
		$cambio = $this->input->get('cambio');
		$precioCliente = $this->input->get('precio_cliente');
		
		if($precioCliente=='Normal'){
			$precioVenta = 'prec_venta';
		}else if($precioCliente=='Mayor'){
			$precioVenta = 'prec_mayor_venta';
		}else if($precioCliente=='Especial'){
			$precioVenta = 'prec_especial_venta';
		}

		$result = $this->db->from('tb_producto')
		->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / ".$cambio.") as costo,(".$precioVenta." / ".$cambio.") as venta,nomb_unid as unidad",FALSE)
		->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
		->where('est_product',1)
		->like('nomb_product',$producto)
		->get()->result();
		echo json_encode($result);
	}

	public function getProducto()
	{
		$producto = $this->input->get('producto');
		$result = $this->db->from('tb_producto')
		->where('cod_producto',$producto)
		->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca')
		->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
		->get()->row();
		echo json_encode($result);
	}

	public function agregarCotizacion()
	{
		$data['fecha_cot'] = $this->input->post('fecha');
		$data['id_cliente'] = $this->input->post('cliente');
		$data['cod_talonario'] = $this->input->post('tipoPedido');
		$data['numero_cot'] = $this->numeracion($data['cod_talonario']);
		$data['moneda_cat'] = $this->input->post('moneda');
		$data['cambio_cat'] = $this->input->post('tipoCambio');
		$data['monto_cot'] = $this->input->post('monto');
		$data['pago_cot'] = $this->input->post('pago');
		$data['igv_cot'] = ($this->input->post('total') / 1.18) * 0.18;
		$data['subtotal_cot'] = $this->input->post('total') - $data['igv_cot'];
		$data['total_cot'] = $this->input->post('total');
		$data['estado_cot'] = 'G';
		if (isset($_POST['dias'])) {
			$data['dias_cot'] = $this->input->post('dias');
			$data['fechavenc_cot'] = $this->input->post('fecVenc');
			$data['saldo_cot'] = $this->input->post('saldo');
		}
		$insert = $this->modelgeneral->insertRegist('tb_cotizacion',$data);


		$resp = [];
		if (!is_null($insert)) {
			$this->aumentarNumeracion($data['cod_talonario'],$data['numero_cot']);
			
			foreach ($_POST['id_prod'] as $key => $value) {
				$producto = $this->modelgeneral->getTableWhereRow('tb_producto',['cod_producto'=>$value]);
				
				$detalle['cod_cot'] = $insert;
				$detalle['cod_producto'] = $_POST['id_prod'][$key];
				$detalle['cant_cotdet'] = $_POST['cant_prod'][$key];
				$detalle['precunit_cotdet'] = $_POST['prec_prod'][$key] ;
				if($_POST['desc_prod'][$key]==''){
					$descuento = 0;
				}else{
					$descuento = $_POST['desc_prod'][$key];
				}
				$detalle['subtotal_cotdet'] = (($detalle['precunit_cotdet'] - $descuento) * $detalle['cant_cotdet']);
				$detalle['igv_cotdet'] = (($detalle['subtotal_cotdet'])  / 1.18) * 0.18;
				$detalle['prec_cotdet'] = $detalle['subtotal_cotdet'] - $detalle['igv_cotdet'];
				$detalle['descuento_cotdet'] = $descuento;
				$this->modelgeneral->insertRegist('tb_cotizacion_detalle',$detalle);
			}

			$resp['success'] = true;
			$resp['redirect'] = 'administrador/regcotizacion';
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	public function editar($id)
	{
		$data['cotizacion'] = $this->db->from('tb_cotizacion')
													->join('tb_cliente','tb_cotizacion.id_cliente = tb_cliente.id_cliente')
													->join('tb_talonario','tb_cotizacion.cod_talonario = tb_talonario.cod_talonario')
    											->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
													->where('cod_cot',$id)
													->get()->row();
		$data['detalle'] = $this->db->from('tb_cotizacion_detalle')
											  	->join('tb_producto','tb_cotizacion_detalle.cod_producto = tb_producto.cod_producto')
											  	->join('tb_unidades','tb_unidades.cod_unid = tb_producto.cod_unid')
											  	->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca')
											  	->where('cod_cot',$id)
													->get()->result();
		$data['doc_clientes'] = $this->ventas_model->getDocumentosCliente();
		$data['dolar'] = $this->modelgeneral->getTableWhereRow('parametros',['nom_paramt'=>'DOLAR']);
		$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/cotizacion/editar',$data);    
    $this->load->view('layouts/footer');
	}

	public function editarCotizacion()
	{
		$data['id_cliente'] = $this->input->post('cliente');
	
		$data['moneda_cat'] = $this->input->post('moneda');
		$data['cambio_cat'] = $this->input->post('tipoCambio');
		$data['monto_cot'] = $this->input->post('monto');
		$data['pago_cot'] = $this->input->post('pago');
		$data['igv_cot'] = ($this->input->post('total') / 1.18) * 0.18;
		$data['subtotal_cot'] = $this->input->post('total') - $data['igv_cot'];
		$data['total_cot'] = $this->input->post('total');
		$data['estado_cot'] = 'G';
		$data['dias_cot'] = NULL;
		$data['fechavenc_cot'] = NULL;
		$data['saldo_cot'] = NULL;
		if (isset($_POST['dias'])) {
			$data['dias_cot'] = $this->input->post('dias');
			$data['fechavenc_cot'] = $this->input->post('fecVenc');
			$data['saldo_cot'] = $this->input->post('saldo');
		}
		$where['cod_cot'] = $this->input->post('id');
		$editar = $this->modelgeneral->editRegist('tb_cotizacion',$where,$data);

		if ($editar) {
			$this->db->where('cod_cot',$this->input->post('id'))
			->delete('tb_cotizacion_detalle');
			foreach ($_POST['id_prod'] as $key => $value) {

				$producto = $this->modelgeneral->getTableWhereRow('tb_producto',['cod_producto'=>$value]);
				
				$detalle['cod_cot'] = $where['cod_cot'];
				$detalle['cod_producto'] = $_POST['id_prod'][$key];
				$detalle['cant_cotdet'] = $_POST['cant_prod'][$key];
				$detalle['precunit_cotdet'] = $_POST['prec_prod'][$key];
				if($_POST['desc_prod'][$key]==''){
					$descuento = 0;
				}else{
					$descuento = $_POST['desc_prod'][$key];
				}
				$detalle['subtotal_cotdet'] = (($detalle['precunit_cotdet'] - $descuento) * $detalle['cant_cotdet']);
				$detalle['igv_cotdet'] = (($detalle['subtotal_cotdet'])  / 1.18) * 0.18;
				$detalle['prec_cotdet'] = $detalle['subtotal_cotdet'] - $detalle['igv_cotdet'];
				$detalle['descuento_cotdet'] = $descuento;
				$this->modelgeneral->insertRegist('tb_cotizacion_detalle',$detalle);
			}
		}
		$resp = [];
		if ($editar) {
			$resp['success'] = true;
			$resp['redirect'] = 'administrador/regcotizacion';
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	function agregarCliente()
	{
		$this->form_validation->set_rules('tipo','','required');
		$this->form_validation->set_rules('nombre','','required');
		$this->form_validation->set_rules('documento','','required');
		$this->form_validation->set_rules('telefono','','required');
    if($this->form_validation->run() == TRUE){
			$data['cod_tipdocucli'] = $this->input->post('tipo');
			$data['precio_cliente'] = $this->input->post('precio');
    	$data['nomb_cliente'] = $this->input->post('nombre');
    	$data['doc_cliente'] = $this->input->post('documento');
    	$data['telf_cliente'] = $this->input->post('telefono');
    	$data['direc_cliente'] = $this->input->post('direccion');
    	$data['contac_cliente'] = $this->input->post('contacto');
    	$data['email_cliente'] = $this->input->post('email');
    	$insert = $this->modelgeneral->insertRegist('tb_cliente',$data);

    	$resp = [];
    	if (!is_null($insert)) {
				$resp['cliente'] = $this->modelgeneral->getTableWhereRow('tb_cliente',['id_cliente'=>$insert]);
    		$resp['success'] = true;
    	}else{
    		$resp['success'] = false;
    	}

    	echo json_encode($resp);
    }
	}

	public function getCliente()
  {
	  $id = $this->input->get('id');
	  $cliente = $this->modelgeneral->getTableWhereRow('tb_cliente',['id_cliente'=>$id]);
	  echo json_encode($cliente);
  }

	public function editarCLiente()
  {
    $this->form_validation->set_rules('id','','required');
    $this->form_validation->set_rules('nombre','','required');
    $this->form_validation->set_rules('documento','','required');
    $this->form_validation->set_rules('telefono','','required');
    if($this->form_validation->run() == TRUE){
			$data['cod_tipdocucli'] = $this->input->post('tipo');
			$data['precio_cliente'] = $this->input->post('precio');
      $data['nomb_cliente'] = $this->input->post('nombre');
      $data['doc_cliente'] = $this->input->post('documento');
      $data['telf_cliente'] = $this->input->post('telefono');
      $data['direc_cliente'] = $this->input->post('direccion');
      $data['contac_cliente'] = $this->input->post('contacto');
      $data['email_cliente'] = $this->input->post('email');
      $data['estado_cliente'] = $this->input->post('estado');
      $where['id_cliente'] = $this->input->post('id');
      $editar = $this->modelgeneral->editRegist('tb_cliente',$where,$data);

      $resp = [];
      if ($editar) {
				$resp['cliente'] = $this->modelgeneral->getTableWhereRow('tb_cliente',$where['id_cliente']);
        $resp['success'] = true;
      }else{
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
	  $data['datos'] = $this->getCotizacionReporte();
		$html = $this->load->view('admin/cotizacion/reporte_pdf',$data,TRUE);
		$css = file_get_contents(APP_PATH.'assets/styles_pdf.css');
		$this->mpdf->SetTitle('Cotización');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('COTIZACION','I');
  }

  function reporteExcel()
  {
  	$data['datos'] = $this->getCotizacionReporte();
  	$this->load->view('admin/cotizacion/reporte_excel',$data);
  }

  function getCotizacionReporte()
  {
  	$this->db->from('tb_cotizacion');
		$this->db->select('cod_cot,nomb_cliente,pago_cot,monto_cot,pago_cot,fecha_cot,estado_cot');
		$this->db->join('tb_cliente','tb_cotizacion.id_cliente = tb_cliente.id_cliente');
		$this->db->where('fecha_cot >= ',$this->input->get('desde'));
    $this->db->where('fecha_cot <=',$this->input->get('hasta'));
    if ($this->input->get('estado')!='') {
      $this->db->where('estado_cot',$this->input->get('estado'));
    }
    if ($this->input->get('pago')) {
      $this->db->where('pago_cot',$this->input->get('pago'));
    }

		$query = $this->db->get();

		foreach ($query->result() as $q) {
			$q->detalle = $this->cotizacion_model->getDetalle($q->cod_cot);
		}
	  return $query;
  }

  function anular()
  {
    $data['estado_cot'] = 'A'; //ANULAR  
    $where['cod_cot'] = $this->input->get('id');  
    
    $edit = $this->modelgeneral->editRegist('tb_cotizacion',$where,$data);
    $resp = [];
    if ($edit) {
      $resp['success'] = true;
    }else{
      $resp['success'] = false;
    }
    echo json_encode($resp);
  }


  function imprimirCotizacion($id)
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4'
		]);
		$data['cotizacion'] = $this->cotizacion_model->getImpresionCotizacion($id);
		$data['empresa'] = $this->empresa_model->getEmpresa($data);
		$html = $this->load->view('admin/cotizacion/imprimircotizacion',$data,TRUE);
		$css = file_get_contents(APP_PATH.'assets/styles_pdf.css');
		$this->mpdf->SetTitle('Cotizacion');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('assets/cotizacion.pdf','I');

	}



}

/* End of file Regcotizacion.php */
/* Location: ./application/controllers/administrador/Regcotizacion.php */
