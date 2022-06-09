<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Cron extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$token = $this->uri->segment(3);
		if(!$token=='A524F65AS558SFG'){
			exit();		}
	
		$this->load->model('facturacion_model');
		$this->load->model('ventas_model');
		$this->load->model('modelgeneral');
    	$this->load->helper('general');
	}
	

	public function cumpleanos()
	{
		$this->db->where('MONTH(fena_pac)',date('m'))
		->where('DAY(fena_pac)',date('d'))
		->set('cumpleano_pac',1)
		->update('tb_cliente');
	}
       
    public function cumpleanosusu()
	{
		$this->db->where('MONTH(fena_usu)',date('m'))
		->where('DAY(fena_usu)',date('d'))
		->set('cumpleano_usu',1)
		->update('tb_usuario');
	} 


	// public function __construct()
	// {
	// 	parent::__construct();
	// 	$this->load->model('facturacion_model');
	// 	$this->load->model('ventas_model');
	// 	$this->load->model('modelgeneral');
    // $this->load->helper('general');
	// }

	// Buscar factura 

	public function buscarFacturasAyer()
	{
		$diaAnterior = date('Y-m-d', strtotime('-1 day'));
		$query = $this->db->from('tb_venta')
		->select('tb_venta.cod_vent as codigo_venta')
		->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
		->join('tb_facturacion','tb_venta.cod_vent = tb_facturacion.cod_vent','left')
		->where('codsunat_tipdocu','01')
		->where('fecha_vent',$diaAnterior)
		->where('tb_facturacion.cod_vent',NULL)
		->get();

		if($query->num_rows()>0){
			foreach ($query->result() as $q) {
				$this->enviarFacturas($q->codigo_venta);
			}
		}

	}

	public function enviarFacturas($id)
	{

		$res = $this->ventas_model->getVenta($id);
		
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
		
		$query = $this->db->select('cod_vent,rutaxml_vent,archivoxml_vent')
		->where('cod_vent',$id)
		->from('tb_venta')
		->get();

		$queryFacturacion = $this->db->from('tb_facturacion')
		->where('cod_vent',$id)
		->get();
		if ($response['respuesta']=='ok' AND $response['hash_cdr'] != '') {	
			$hash = $response['hash_cdr'];
			$estado = 1;
		}else{
			$hash = '';
			$estado = 2;
		}

		if($queryFacturacion->num_rows()==0){
			$this->db->set('cod_fecha',date('Y-m-d'))
			->set('cod_vent',$id)
			->set('cod_usu',$this->session->userdata('cod_usu'))
			->set('hashcdr_fac',$hash)
			->set('estado_fac',$estado)
			->insert('tb_facturacion');
		}else{
			$this->db->set('cod_fecha',date('Y-m-d'))
			->set('cod_usu',$this->session->userdata('cod_usu'))
			->set('hashcdr_fac',$hash)
			->set('estado_fac',$estado)
			->where('cod_vent',$id)
			->update('tb_facturacion');
		}
		
		$response['query'] = $query->row();
		$response['estado'] = $estado;
		//echo json_encode($response);
	}

	public function resumenBoleta()
	{
		$fecha = date('Y-m-d', strtotime('-1 day'));
    $secuencia = $this->modelgeneral->getSecuencia('tb_resumenboleta','secuencia_res');


    $query = $this->db->from('tb_venta')
    ->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,nomb_cliente')
    ->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
    ->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
    ->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')
		->join('tb_resumenboletadetalle','tb_venta.cod_vent = tb_resumenboletadetalle.cod_vent','left')
		->where('tb_resumenboletadetalle.cod_vent',NULL)
    ->where('codsunat_tipdocu','03')
    ->where('fecha_vent',$fecha)
    ->get()->result();

		var_dump($query);
		exit();


    $data['codigo_res'] = 'RC';
    $data['serie_res'] = date("Ymd", strtotime($fecha));
    $data['secuencia_res'] = $secuencia;
    $data['fechareferencia_res'] = $fecha;
    $data['fechadocumento_res'] = $fecha;
    $insert = $this->modelgeneral->insertRegist('tb_resumenboleta',$data);

    foreach ($query as $q) {
      $detalle['cod_res'] = $insert;
      $detalle['cod_vent'] = $q->cod_vent;
      $this->modelgeneral->insertRegist('tb_resumenboletadetalle',$detalle);
    }

    $resp = [];
    if (!is_null($insert)) {
      $resp['success'] = true;
      $resp['resp'] = $this->resumenDocumento($insert,$fecha,$secuencia);
      $resp['redirect'] = 'administrador/regdocumentoelectronico/resumen';

      $editData['rutaxml_res'] = $resp['resp']['ruta'];
      $editData['archivoxml_res'] = $resp['resp']['archivo'];
      $editData['hash_res'] = $resp['resp']['hash_cpe'];
      $editData['ticket_res'] = $resp['resp']['id_ticket'];
      $this->modelgeneral->editRegist('tb_resumenboleta',['cod_res'=>$insert],$editData);

    }else{
      $resp['success'] = false;
    }

    echo json_encode($resp);
	}

	public function resumenDocumento($id,$fecha,$secuencia)
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
    return $response;
  }
  public function ConsultarTicketResumenSinRespuesta(){

	//sacamos los resumen diario que no tiene respuesta de ticket

	$where = "IFNULL(CodRptaSunat,'')='' and IFNULL(DesRptaSunat,'')='' and IFNULL(ticket_res,'')<>''";

	$query = $this->db->from('tb_resumenboleta')

	->select('cod_res,ticket_res,archivoxml_res')

	->where($where)

	->get()->result();

	

	//ruta a  consultar el api sunat	

	$ruta = base_url_app()."/facturacion/api_facturacion/resumen_boletas_consul_ticket.php";

	$tipo_proceso = getTipoProceso();

	//por cada resumen diario consultamos por su ticket 

	foreach($query as $ind=>$val){

		$data["cod_resumen"]=$val->cod_res;

		$data["cod_ticket"]=$val->ticket_res;

		$data["tipo_proceso"]=$tipo_proceso['tipo_proceso'];

		$data["archivoxml_res"]=$val->archivoxml_res;

		$data["emisor"]=getEmisor();

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

		if($response["respuesta"]!="ok"){

			

		}

		else{

			$editData['CodRptaSunat'] = $response['cod_sunat'];

			$editData['DesRptaSunat'] = $response['msj_sunat'];

			$editData['RutaCdrXML'] = $response['ruta_cdr'];	  

			$this->modelgeneral->editRegist('tb_resumenboleta',['cod_res'=>$val->cod_res],$editData);

		}

		//return $response;

	}		

} 
public function EnviarBoletasSinResumen()

{

$fecha = date('Y-m-d');

$secuencia = $this->modelgeneral->getSecuencia('tb_resumenboleta','secuencia_res');



//verificamos cuales son las boletas que no estan dentro de un resumen diario

$query= $this->db->from('tb_venta')

->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,nomb_cliente')

->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')

->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')

->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')

->join('tb_resumenboletadetalle','tb_venta.cod_vent = tb_resumenboletadetalle.cod_vent','left')

->where('tb_resumenboletadetalle.cod_vent',NULL)

->where('codsunat_tipdocu','03')

->where('fecha_vent',$fecha)

//->get_compiled_select();exit(0);

->get()->result();
//echo sizeof($query);exit(0);
//si no hay boletas no generamos el resumen 
if(is_array($query)){
  if(sizeof($query)<1){
	echo "sin boletas";
	exit(0);
  }  
}else{
	echo "sin boletas";
	exit(0);
}



//insertamos la cabecera del resumen diario

$data['codigo_res'] = 'RC';

$data['serie_res'] = date("Ymd", strtotime($fecha));

$data['secuencia_res'] = $secuencia;

$data['fechareferencia_res'] = $fecha;

$data['fechadocumento_res'] = $fecha;

$insert = $this->modelgeneral->insertRegist('tb_resumenboleta',$data);



//insertamos detalles del resumen diario

foreach ($query as $q) {

  $detalle['cod_res'] = $insert;

  $detalle['cod_vent'] = $q->cod_vent;

  $this->modelgeneral->insertRegist('tb_resumenboletadetalle',$detalle);

}



$resp = [];

if (!is_null($insert)) {

  $resp['success'] = true;

  //genera el xml y envia a sunat 

  $resp_resumen = $this->resumenDocumento($insert,$fecha,$secuencia);	  

  $resp['rpta'] = $resp_resumen;

  //var_export($resp_resumen);exit(0);	  

  //respuesta de la creacion del xml

  $arr_rspta_creacion=$resp_resumen["rpta_procesar"]["resp_creacion_resumen"];

  //respuesta de la firma del documento	

  $arr_rspta_firma=$resp_resumen["rpta_procesar"]["resp_firma"];

  //respuesta del envio del documento

  $arr_rspta_envio=$resp_resumen["rpta_procesar"]["resp_envio_sunat"];

  //respuesta del estado del documento

  $arr_rspta_estado=$resp_resumen["rpta_procesar"]["resp_estado_ticket"];

	//actualizamos el resumen diario con el ticket

  $editData['rutaxml_res'] = $resp_resumen['ruta'];

  $editData['archivoxml_res'] = $resp_resumen['archivo'];

  $editData['hash_res'] = $arr_rspta_firma['hash_cpe'];

  $editData['ticket_res'] = $arr_rspta_envio['cod_ticket'];	  

  $this->modelgeneral->editRegist('tb_resumenboleta',['cod_res'=>$insert],$editData);



}else{

  $resp['success'] = false;

}



var_dump($resp);

}

}
        
    /* End of file  Cron.php */             