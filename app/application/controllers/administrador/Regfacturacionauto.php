<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Regfacturacion extends CI_Controller {

	private $permisos;

	public function __construct()

	{

		parent::__construct();

		$this->load->model('facturacion_model');

		$this->load->model('ventas_model');

		$this->load->model('modelgeneral');

    $this->load->helper('general');

    //$this->permisos = $this->backend_lib->control();

	}



	public function index()

	{

	  $data['permisos'] =$this->permisos;

	  $this->load->view('layouts/header');

	  $this->load->view('layouts/aside');

	  $this->load->view('admin/facturacion/panel',$data);    

	  $this->load->view('layouts/footer');

	}



	public function jsonFacturas()

	{

		$data['start'] = $this->input->get_post('start', true);

		$data['length'] = $this->input->get_post('length', true);

    $data['sEcho']  = $this->input->get_post('_', true);

    $columns= ['cod_vent','nomb_cliente'];

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



		$datos = $this->facturacion_model->getFacturas($data);

		header('content-type: application/json; charset=utf-8');

		echo json_encode($datos);

	}



	function enviarDocumentoSunat()

	{

		$id = $this->input->post('id');



		$res = $this->ventas_model->getVenta($id);

		

		// RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección

		$ruta = base_url()."/facturacion/api_facturacion/factura_enviardocumento.php";

 

		//se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf



		$emisor = getEmisor();

		//EMISOR

		$data['ruc'] = $emisor['ruc'];

		$data['usuariosol'] = $emisor['usuariosol'];

		$data['clavesol'] = $emisor['clavesol'];



		//RUTAS

		$data['ruta_xml'] = '../'.$res->rutaxml_vent.'/'.$res->archivoxml_vent;

		$data['ruta_cdr'] = '../'.$res->rutaxml_vent.'/';

		$data['nombre_archivo'] = $res->archivoxml_vent;



		$tipo_proceso = getTipoProceso();

		$data['ruta_ws'] = $tipo_proceso['ruta_ws'];

		$data_json = json_encode($data);


		$token="";
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

		

		if ($response['respuesta']=='ok' AND $response['hash_cdr'] != '') {

			$this->db->set('cod_fecha',date('Y-m-d'))

			->set('cod_vent',$id)

			->set('cod_usu',$this->session->userdata('cod_usu'))

			->set('hashcdr_fac',$response['hash_cdr'])

			->set('estado_fac',"1")

			->insert('tb_facturacion');



			$query = $this->db->select('cod_vent,rutaxml_vent,archivoxml_vent')

			->where('cod_vent',$id)

			->from('tb_venta')

			->get()->row();



			$response['query'] = $query;

		}else{



		}



		echo json_encode($response);

	}

// reporte en excel FE



	function reporteComprobates()

	{

		$data['desde'] = $this->input->get('desde');

		$data['hasta'] = $this->input->get('hasta');

		// $data['cliente'] = $this->input->get('cliente');

		// $data['vendedor'] = $this->input->get('vendedor');

		// $data['almacen'] = $this->input->get('almacen');

		// $data['datos'] = $this->reportedetallado_model->getVentasDetalladasExcel($data);

		$data['datos'] = $this->facturacion_model->getCeprocesadosExcel($data);

		$this->load->view('admin/facturacion/reportedecomprobanteselectronicos',$data);

	}



}



/* End of file Regfacturacion.php */

/* Location: ./application/controllers/administrador/Regfacturacion.php */

