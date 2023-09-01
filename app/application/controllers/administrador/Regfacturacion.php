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
		$this->load->model('empresa_model');
		$this->load->model('modelgeneral');
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

		if($res->codsunat_tipdocu == '03'){
			$response = $this->enviarDocumentoResumenSunat($id);
		}else{
			$response = $this->enviarDocumentoFacturaSunat($res);
		}

		echo json_encode($response);
	}

	private function enviarDocumentoResumenSunat($id)
	{
		$verifica = $this->db->from('tb_resumenboletadetalle')
		->join('tb_resumenboleta','tb_resumenboletadetalle.cod_res = tb_resumenboleta.cod_res')
		->where('tb_resumenboletadetalle.cod_vent',$id)
		->get();

		if($verifica->num_rows() > 0){
			$row = $verifica->row();
			$response = $this->resumenDocumento($row->cod_res,$row->fechadocumento_res,$row->secuencia_res);
			$editData['rutaxml_res'] = $response['ruta'];
			$editData['archivoxml_res'] = $response['archivo'];
			$editData['hash_res'] = $response['hash_cpe'];
			$editData['ticket_res'] = $response['id_ticket'];
			$editData['DesRptaSunat'] = msj_sunat($response['msj_sunat']);
			$this->modelgeneral->editRegist('tb_resumenboleta',['cod_res'=>$row->cod_res],$editData);
			return $response;
		}else{

			$fecha = date('Y-m-d');
			$query = $this->db->from('tb_venta')
			->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,nomb_cliente')
			->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
			->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
			->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')
			->where('codsunat_tipdocu','03')
			->where('tb_venta.cod_vent',$id)
			->get()->row();
			
			$secuencia = $this->modelgeneral->getSecuencia('tb_resumenboleta','secuencia_res');
	
			$data['codigo_res'] = 'RC';
			$data['serie_res'] = date("Ymd", strtotime($fecha));
			$data['secuencia_res'] = $secuencia;
			$data['fechareferencia_res'] = $fecha;
			$data['fechadocumento_res'] = $fecha;
			$insert = $this->modelgeneral->insertRegist('tb_resumenboleta',$data);
	
			
			$detalle['cod_res'] = $insert;
			$detalle['cod_vent'] = $query->cod_vent;
			$this->modelgeneral->insertRegist('tb_resumenboletadetalle',$detalle);

			$response = $this->resumenDocumento($insert,$fecha,$secuencia);
		
			$editData['rutaxml_res'] = $response['ruta'];
			$editData['archivoxml_res'] = $response['archivo'];
			$editData['hash_res'] = $response['hash_cpe'];
			$editData['ticket_res'] = $response['id_ticket'];
			$editData['DesRptaSunat'] = msj_sunat($response['msj_sunat']);
			$this->modelgeneral->editRegist('tb_resumenboleta',['cod_res'=>$insert],$editData);	

			return $response;
		}

	}

	private function resumenDocumento($id,$fecha,$secuencia)
  {

    $query = $this->db->from('tb_resumenboletadetalle')
    ->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,codmoneda_vent,nomb_cliente,serie,numero_vent,codsunat_tipdocucli,doc_cliente')
    ->join('tb_venta','tb_resumenboletadetalle.cod_vent = tb_venta.cod_vent')
    ->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
    ->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')
    ->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
    ->where('codsunat_tipdocu','03')
    ->where('cod_res',$id)
    ->get()->result();

    // RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
    $ruta = base_url_app()."/facturacion/api_facturacion/resumen_boletas.php";
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
      $det['NRO_COMPROBANTE'] = (string)$q->serie.'-'.$q->numero_vent;
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

		$query = $this->db->from('tb_resumenboleta')
		->select('archivoxml_res as archivoxml_vent, cod_res as cod_vent, rutaxml_res as rutaxml_vent')
		->where('cod_res',$id)
		->get()->row();
		$response['query'] = $query;
		return $response;
  }
	
	private function enviarDocumentoFacturaSunat($res)
	{
		
		// RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
		$ruta = base_url_app()."/facturacion/api_facturacion/factura_enviardocumento.php";
 
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

		$token='';
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
		
		$msj_sunat = msj_sunat($response['msj_sunat']);

		$verifica = $this->modelgeneral->getTableWhereRow('tb_facturacion',['cod_vent' => $res->cod_vent]);

		if ($response['respuesta']=='ok' AND $response['hash_cdr'] != '' AND $response['cod_sunat'] == '0') {

			$this->modelgeneral->getTableWhereRow('tb_facturacion',['cod_vent' => $res->cod_vent]);
			$this->db->set('cod_fecha',date('Y-m-d'));
			$this->db->set('msj_sunat_fac',$msj_sunat);
			$this->db->set('cod_sunat_fac',$response['cod_sunat']);
			$this->db->set('cod_usu',$this->session->userdata('cod_usu'));
			$this->db->set('hashcdr_fac',$response['hash_cdr']);
			$this->db->set('estado_fac',"1");
			
			
			if(is_null($verifica)){
				$this->db->set('cod_vent',$res->cod_vent);
				$this->db->insert('tb_facturacion');
			}else{
				$this->db->where('cod_vent',$res->cod_vent);
				$this->db->update('tb_facturacion');
			}

			$query = $this->db->select('cod_vent,rutaxml_vent,archivoxml_vent')
			->where('cod_vent',$res->cod_vent)
			->from('tb_venta')
			->get()->row();

			$response['query'] = $query;
		}else{
			
			$this->modelgeneral->getTableWhereRow('tb_facturacion',['cod_vent' => $res->cod_vent]);
			$this->db->set('cod_fecha',date('Y-m-d'));
			$this->db->set('msj_sunat_fac',$msj_sunat);
			$this->db->set('cod_sunat_fac',$response['cod_sunat']);
			$this->db->set('estado_fac',"2");
			
			if(is_null($verifica)){
				$this->db->set('cod_vent',$res->cod_vent);
				$this->db->insert('tb_facturacion');
			}else{
				$this->db->where('cod_vent',$res->cod_vent);
				$this->db->update('tb_facturacion');
			}
		}

		$response['msj_sunat'] = $msj_sunat;
		return $response;
	}

// reporte en excel FE

	function reporteComprobates()
	{
		$data['empresa'] = $this->empresa_model->getEmpresa();
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
