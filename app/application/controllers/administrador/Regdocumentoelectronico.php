<?php

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

defined('BASEPATH') or exit('No direct script access allowed');
// Don't forget include/define REST_Controller path

/**
 *
 * Controller Regdocumentoelectronico
 *
 * This controller for ...
 *
 * @package   CodeIgniter
 * @category  Controller CI
 * @author    Setiawan Jodi <jodisetiawan@fisip-untirta.ac.id>
 * @author    Raul Guerrero <r.g.c@me.com>
 * @link      https://github.com/setdjod/myci-extension/
 * @param     ...
 * @return    ...
 *
 */

class Regdocumentoelectronico extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('documentoelectronico_model');
    $this->load->model('ventas_model');
    $this->load->model('empresa_model');
  }

  public function index()
  {
    // 
  }

  public function resumen()
  {
    $data['datos'] = $this->modelgeneral->getTable('tb_resumenboleta');
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/documentoelectronico/resumen', $data);
    $this->load->view('layouts/footer');
  }

  public function getResumenFecha()
  {
    $fecha = $this->input->get('fecha');

    $verifica = $this->db->from('tb_resumenboleta')
      ->where('fechadocumento_res', $fecha)
      ->get();

    $res = [];

    if ($verifica->num_rows() == 0) {
      $res['verifica'] = true;
      $res['result'] = $this->db->from('tb_venta')
        ->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,nomb_cliente')
        ->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario')
        ->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
        ->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente')
        ->where('tb_tipodocumento.cod_tipdocu', 2)
        ->where('fecha_vent', $fecha)
        ->get()->result();
    } else {
      $res['verifica'] = false;
    }

    header('content-type: application/json; charset=utf-8');
    echo json_encode($res);
  }

  public function agregarResumen()
  {
    $fecha = $this->input->post('fecha');
    $codigo = $this->input->post('codigo');
    $serie = $this->input->post('serie');
    $secuencia = $this->modelgeneral->getSecuencia('tb_resumenboleta', 'secuencia_res');


    $query = $this->db->from('tb_venta')
      ->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,nomb_cliente')
      ->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario')
      ->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
      ->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente')
      ->where('codsunat_tipdocu', '03')
      ->where('fecha_vent', $fecha)
      ->get()->result();

    $data['codigo_res'] = 'RC';
    $data['serie_res'] = date("Ymd", strtotime($fecha));
    $data['secuencia_res'] = $secuencia;
    $data['fechareferencia_res'] = $this->input->post('fecha');
    $data['fechadocumento_res'] = $this->input->post('fecha');
    $insert = $this->modelgeneral->insertRegist('tb_resumenboleta', $data);

    foreach ($query as $q) {
      $detalle['cod_res'] = $insert;
      $detalle['cod_vent'] = $q->cod_vent;
      $this->modelgeneral->insertRegist('tb_resumenboletadetalle', $detalle);
    }

    $resp = [];
    if (!is_null($insert)) {
      $resp['success'] = true;
      $resp['resp'] = $this->resumenDocumento($insert, $fecha, $secuencia);
      $resp['redirect'] = 'administrador/regdocumentoelectronico/resumen';

      //respuesta de la creacion del xml

      // $arr_rspta_creacion=$resp["resp"]["resp_creacion_resumen"];

      // //respuesta de la firma del documento 

      // $arr_rspta_firma=$resp["resp"]["resp_firma"];

      // //respuesta del envio del documento

      // $arr_rspta_envio=$resp["resp"]["resp_envio_sunat"];

      // //respuesta del estado del documento

      // $arr_rspta_estado=$resp["resp"]["resp_estado_ticket"];

      $editData['rutaxml_res'] = $resp['resp']['ruta'];
      $editData['archivoxml_res'] = $resp['resp']['archivo'];
      $editData['hash_res'] = $resp['resp']['hash_cpe'];
      $editData['ticket_res'] = $resp['resp']['id_ticket'];
      $this->modelgeneral->editRegist('tb_resumenboleta', ['cod_res' => $insert], $editData);
    } else {
      $resp['success'] = false;
    }
    echo json_encode($resp);
  }

  public function resumenDocumento($id, $fecha, $secuencia)
  {

    $query = $this->db->from('tb_resumenboletadetalle')
      ->select('tb_venta.cod_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,gravada_vent,exonerada_vent,free_vent,codmoneda_vent,nomb_cliente,serie,numero_vent,codsunat_tipdocucli,doc_cliente')
      ->join('tb_venta', 'tb_resumenboletadetalle.cod_vent = tb_venta.cod_vent')
      ->join('tb_talonario', 'tb_venta.cod_talonario = tb_talonario.cod_talonario')
      ->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
      ->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente')
      ->join('tb_tipodocumentocliente', 'tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
      ->where('tb_tipodocumento.cod_tipdocu', 2)
      ->where('cod_res', $id)
      ->get()->result();

    // RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
    $ruta = base_url_app() . "/facturacion/api_facturacion/resumen_boletas.php";
    //se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf

    $tipo_proceso = getTipoProceso();
    $data = array(

      //Cabecera del documento

      "tipo_proceso"           => $tipo_proceso['tipo_proceso'],
      "codigo"            => 'RC',
      "serie"              => date("Ymd", strtotime($fecha)),
      "secuencia"                 => (string)$secuencia,
      "fecha_referencia"               => $fecha,
      "fecha_documento"              => $fecha,

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
      $det['GRAVADA'] = (string)$q->gravada_vent;
      $det['EXONERADO'] = (string)$q->exonerada_vent;
      $det['INAFECTO'] = '0';
      $det['EXPORTACION'] = '0';
      $det['GRATUITAS'] = (string)$q->free_vent;
      $det['MONTO_CARGO_X_ASIG'] = '0';
      $det['CARGO_X_ASIGNACION'] = '0';
      $det['ISC'] = '0';
      $det['EXO'] = '0';
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
//var_dump($data_json);
//exit();
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
//var_dump($respuesta);
//exit();
    $response = json_decode($respuesta, true);
    return $response;
  }

  // public function getBoletas()
  // {
  //   $id = $this->input->post('id');
  //   $boletas = $this->modelgeneral->getTableWhere('tb_resumenboletadetalle',['cod_res'=>$id]);
  //   echo json_encode($boletas);
  // }
  public function getBoletas()
  {
    $id = $this->input->post('id');
    /*$boletas = $this->modelgeneral->getTableWhere('tb_resumenboletadetalle',['cod_res'=>$id]);
    echo json_encode($boletas);*/

    $query = $this->db->from('tb_resumenboleta')
      ->select('archivoxml_vent,tb_venta.cod_vent,cod_talonario,numero_vent,fecha_vent,moneda_vent,tb_venta.id_cliente,doc_cliente,nomb_cliente,total_vent')
      ->join('tb_resumenboletadetalle', 'tb_resumenboleta.cod_res = tb_resumenboletadetalle.cod_res')
      ->join('tb_venta', 'tb_resumenboletadetalle.cod_vent = tb_venta.cod_vent')
      ->join('tb_cliente', 'tb_venta.id_cliente = tb_cliente.id_cliente')
      ->where('tb_resumenboleta.cod_res', $id)
      ->get()->result();
    $arr_res = null;
    $arr_res['status'] = 0;
    if (sizeof($query) > 0) {
      $arr_res['status'] = 1;
      $arr_res['data'] = $query;
    }
    echo json_encode($arr_res);
    /*$id = $this->input->post('id');
    $boletas = $this->modelgeneral->getTableWhere('tb_resumenboletadetalle',['cod_res'=>$id]);
    echo json_encode($boletas);*/
  }

  public function bajas()
  {
    $data['datos'] = $this->documentoelectronico_model->getVentasAnuladas();
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/documentoelectronico/bajas', $data);
    $this->load->view('layouts/footer');
  }

  public function impresionBaja($id)
  {

    $this->mpdf = new \Mpdf\Mpdf([
      'mode' => 'utf-8', //MODE
      'format' => 'A4',
      'orientation' => 'L',
      'margin_left' => 5,
      'margin_right' => 5,
      'margin_top' => 5,
      'margin_bottom' => 5,
      'margin_header' => 10,
      'margin_footer' => 10
    ]);
    $data = [];
    $data['empresa'] = $this->empresa_model->getEmpresa($data);
    $data['baja'] = $this->documentoelectronico_model->getBaja($id);

    $html = $this->load->view('admin/documentoelectronico/baja_impresion', $data, TRUE);
    $css = file_get_contents(APP_PATH . 'assets/styles_pdf.css');
    $this->mpdf->SetTitle('Comunicación de Baja');
    $this->mpdf->writeHTML($css, 1);
    $this->mpdf->writeHTML($html, 2);
    $this->mpdf->Output('Comunicación de Baja.pdf', 'I');
  }

  public function bajaDocumento()
  {
    $id = $this->input->post('id');
    $motivo = $this->input->post('motivo');
    $secuencia = $this->modelgeneral->getSecuencia('tb_bajasunat', 'secuencia_baja');
    $res = $this->ventas_model->getVenta($id);
    $fecha = $this->ventas_model->getVenta($id);
    $ruta = base_url_app() . "/facturacion/api_facturacion/baja_sunat.php";

    //se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf
    $tipo_proceso = getTipoProceso();
    $data = array(

      //Cabecera del documento
      "tipo_proceso"           => $tipo_proceso['tipo_proceso'],
      "codigo"            => "RA",
      "serie"              => date('Ymd'),
      "secuencia"                 => $secuencia,
      "fecha_referencia"               => date('Y-m-d'),
      "fecha_baja"                => date('Y-m-d'),
      "fecha_documento"              => $fecha->fecha_vent,

      //data de la empresa emisora o contribuyente que entrega el documento electrónico.
      "emisor" => getEmisor(),

      //items
      "detalle" => array(
        array(
          "ITEM"            => "1",
          "TIPO_COMPROBANTE"  => (string)$res->codsunat_tipdocu,
          "SERIE"             => (string)$res->serie,
          "NUMERO"            => (string)$res->numero_vent,
          "MOTIVO"            => $motivo
        )
      )
    );

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


    $resp = [];
    if ($response['respuesta'] == 'ok') {
      $dataInsert['cod_vent'] = $this->input->post('id');
      $dataInsert['fecha_baja'] = date('Y-m-d');
      $dataInsert['motivo_baja'] = $this->input->post('motivo');
      $dataInsert['codigo_baja'] = $data['codigo'];
      $dataInsert['serie_baja'] = $data['serie'];
      $dataInsert['secuencia_baja'] = $data['secuencia'];
      $dataInsert['rutaxml_baja'] = $response['ruta'];
      $dataInsert['archivoxml_baja'] = $response['archivo'];
      $dataInsert['hash_baja'] = $response['hash_cpe'];
      $insert = $this->modelgeneral->insertRegist('tb_bajasunat', $dataInsert);
      $resp['success'] = true;
      $resp['redirect'] = 'administrador/regdocumentoelectronico/bajas';
    } else {
      $resp['success'] = false;
      $resp['resp'] = $data;
    }

    echo json_encode($resp);
  }

  public function guia()
  {
    $data['ubigeos'] = $this->ubigeo();
    $data['datos'] = $this->documentoelectronico_model->getGuiaRemision();
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/documentoelectronico/guia', $data);
    $this->load->view('layouts/footer');
  }

  public function ubigeo()
  {
    return $this->db->from('ubigeo_distritos')
      ->select('ubigeo_distritos.nombre as distrito,ubigeo_provincias.nombre as provincia, ubigeo_departamentos.nombre as departamento, ubigeo_distritos.id as ubigeo')
      ->join('ubigeo_provincias', 'ubigeo_provincias.id = ubigeo_distritos.provincia_id')
      ->join('ubigeo_departamentos', 'ubigeo_departamentos.id = ubigeo_distritos.departamento_id')
      ->get()->result();
  }

  function imprimirGuia($id)
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

    $data['empresa'] = $this->empresa_model->getEmpresa();
    $data['guia'] = $this->documentoelectronico_model->getGuiaRemisionImpresion($id);
    $html = $this->load->view('admin/documentoelectronico/guia_impresion', $data, TRUE);
    $css = file_get_contents(APP_PATH . 'assets/styles_pdf.css');
    $this->mpdf->SetTitle('Guia de Remisión');
    $this->mpdf->writeHTML($css, 1);
    $this->mpdf->writeHTML($html, 2);
    $this->mpdf->Output('Guia de Remisión.pdf', 'I');
  }

  public function guiaRemisionDocumento()
  {
    $ruta = base_url_app() . "/facturacion/api_facturacion/guia_remision.php";
    $secuencia = $this->modelgeneral->getSecuencia('tb_guiaremision', 'secuencia_guia');
    $id = $this->input->post('id');
    $res = $this->ventas_model->getVenta($id);

    //se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf

    $tipo_proceso = getTipoProceso();
    $data = array(

      //Cabecera del documento
      "tipo_proceso"           => $tipo_proceso['tipo_proceso'],
      "serie_comprobante"             => "T001",
      "numero_comprobante"            => $secuencia,
      "fecha_comprobante"             => date('Y-m-d'),
      "cod_tipo_documento"            => "09",
      "nota"                          => $this->input->post('nota'),

      //01 VENTA, 14 VENTA SUJETA A CONFIRMACION DEL COMPRADOR, 02 COMPRA
      //04 TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA, 18 TRASLADO EMISOR ITINERANTE CP
      //08 IMPORTACION, 09 EXPORTACION, 19 TRASLADO A ZONA PRIMARIA, 13 OTROS
      "codmotivo_traslado"      => (string)$this->input->post('motivo'),
      "motivo_traslado"        => getMotivoTraslado($this->input->post('motivo')),
      "peso"              => (string)$this->input->post('peso'),
      "numero_paquetes"        => (string)$this->input->post('num_paquetes'),
      "codtipo_transportista"      => (string)$this->input->post('tipo_transportista'), //01 Transporte público, 02 Transporte privado

      "tipo_documento_transporte"    => (string)$this->input->post('doc_transporte'), //6: indica RUC: Catálogo 06
      "nro_documento_transporte"    => (string)$this->input->post('num_doc_transporte'),
      "razon_social_transporte"    => (string)$this->input->post('razon_social_transporte'),
      "ubigeo_partida"        => (string)$this->input->post('ubigeo_partida'),
      "dir_partida"          => (string)$this->input->post('direccion_partida'),
      "ubigeo_destino"        => (string)$this->input->post('ubigeo_destino'),
      "dir_destino"          => (string)$this->input->post('direccion_destino'),

      //Datos del cliente
      "cliente_numerodocumento"       => (string)$res->doc_cliente,
      "cliente_nombre"                => (string)$res->nomb_cliente,
      "cliente_tipodocumento"         => (string)$res->cod_tipdocucli, //1: DNI

      //data de la empresa emisora o contribuyente que entrega el documento electrónico.
      "emisor" => getEmisor()
    );

    $detalle = [];
    $n = 1;
    foreach ($res->detalle as $q) {
      $det['ITEM'] = (string)$n;
      $det['PESO'] = (!is_null($q->peso_ventdet)) ? $q->peso_ventdet : '1';
      $det['NUMERO_ORDEN'] = (string)$n;
      $det['DESCRIPCION'] = (string)$q->producto_ventdet;
      $det['CODIGO_PRODUCTO'] = "PIUU8";
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


    $resp = [];
    if ($response['respuesta'] == 'ok') {
      $dataInsert['cod_vent'] = $this->input->post('id');
      $dataInsert['fecha_guia'] = date('Y-m-d');
      $dataInsert['nota_guia'] = $this->input->post('nota');
      $dataInsert['serie_guia'] = $data['serie_comprobante'];
      $dataInsert['secuencia_guia'] = $secuencia;
      $dataInsert['codmotivo_guia'] = $this->input->post('motivo');
      $dataInsert['motivo_guia'] = getMotivoTraslado($this->input->post('motivo'));
      $dataInsert['peso_guia'] = $data['peso'];
      $dataInsert['numpaq_guia'] = $this->input->post('num_paquetes');
      $dataInsert['codtransp_guia'] = $this->input->post('tipo_transportista');
      $dataInsert['tipotransporte_guia'] = getTipoTransporte($this->input->post('tipo_transportista'));
      $dataInsert['tipdoctransp_guia'] = $this->input->post('doc_transporte');
      $dataInsert['doctransporte_guia'] = getTipoDocumentoTransporte($this->input->post('doc_transporte'));
      $dataInsert['numdoctransp_guia'] = $this->input->post('num_doc_transporte');
      $dataInsert['razontransp_guia'] = $this->input->post('razon_social_transporte');
      $dataInsert['ubigpartida_guia'] = $this->input->post('ubigeo_partida');
      $dataInsert['direcpartida_guia'] = $this->input->post('direccion_partida');
      $dataInsert['ubigdestino_guia'] = $this->input->post('ubigeo_destino');
      $dataInsert['direcdestino_guia'] = $this->input->post('direccion_destino');

      $dataInsert['rutaxml_guia'] = $response['ruta'];
      $dataInsert['archivoxml_guia'] = $response['archivo'];
      $dataInsert['hash_guia'] = $response['hash_cpe'];
      $insert = $this->modelgeneral->insertRegist('tb_guiaremision', $dataInsert);
      $resp['success'] = true;
      $resp['redirect'] = 'administrador/regdocumentoelectronico/guia';
    } else {
      $resp['success'] = false;
      $resp['resp'] = $data;
    }

    echo json_encode($resp);
  }

  public function Remitente_trasportepublico()
  {
    $tipo_proceso = getTipoProceso();
    $data = array(

      "INDICADOR_M1_L" => 0,
      "INDICADOR_TRASLADO_TOTAL_DAM_DS" => 0,
      "INDICADOR_BIEN_NORMALIZADO" => 1,
      "NRO_LICENCIA_CONDUCT" => "",
      "NRO_REGISTRO_MTC" => "",
      "PESO_TRASLADADO_PARCIAL_DAM_DS" => "0.00",
      "NUM_NIF_LLEGADA_PARTIDA" => "20555700785",
      "TXT_VERS_UBL" => "2.1",
      "TXT_VERS_ESTRUCT_UBL" => "2.0",
      "TOKEN" => "gN8zNRBV+/FVxTLwdaZx0w==",
      "RETORNA_XML_ENVIO" => false,
      "RETORNA_XML_CDR" => false,
      "RETORNA_PDF" => true,
      "OBSERVACIONES" => "",
      "COD_TIP_NIF_EMIS" => "6",
      "NUM_NIF_EMIS" => "20100100100",
      "NOM_COMER_EMIS" => "TU NOMBRE COMERCIAL",
      "TXT_DMCL_FISC_EMIS" => "CALLE LAS GAVIOTAS 117 SURQUILLO",
      "NOM_RZN_SOC_EMIS" => "OSYS COMPANY SAC",
      "COD_UBI_EMIS" => "150101",
      "COD_TIP_GUR" => "09",
      "NUM_SERIE_GUR" => "T004",
      "NUM_CORRE_GUR" => "00000781",
      "ENVIAR_A_SUNAT" => true,
      "COD_PRCD_CARGA" => "001",
      "FEC_EMIS_GUR" => "2022-12-12",
      "COD_TIP_NIF_DEST" => "6",
      "NUM_NIF_DEST" => "20605457003",
      "NOM_RZN_SOC_DEST" => "KORBOS LOGISTIC EIRL",
      "DIR_LLEGADA" => "CAL. LAS GAVIOTAS NRO. 117 URB. LIMATAMBO - LIMA LIMA SURQUILLO",
      "UBI_LLEGADA" => "150105",
      "MOT_TRASLADO" => "02",
      "TXT_MOT_TRASLADO" => "COMPRA",
      "IND_TRANSBORDO" => false,
      "MOD_TRASLADO" => "01",
      "FEC_TRASLADO" => "2022-12-13",
      "NUM_NIF_CONDUCT" => "",
      "COD_TIP_NIF_CONDUCT" => "",
      "NOM_RZN_SOC_CONDUCT" => "",
      "NRO_BULTOS" => "",
      "COD_TIP_NIF_TRANSP" => "6",
      "NUM_NIF_TRANSP" => "20498189637",
      "NOM_RZN_SOC_TRANSP" => "AREQUIPA EXPRESO MARVISUR EIRL",
      "NRO_CONTENEDOR" => "",
      "DIR_PARTIDA" => "Av. Tacna 670 - Lima",
      "UBI_PARTIDA" => "150101",
      "UND_MEDIDA" => "KGM",
      "PESO_BRUTO" => "220",
      // "items"=> [
      //    {
      //       "CANT_ITEM"=> 1,
      //       "COD_ITEM"=> "--",
      //       "DESC_ITEM"=> "tomatodo",
      //       "PESO_ITEM"=> 1,
      //       "COD_UND_MEDIDA_ITEM"=>"EA",
      //       "NUM_LINEA": 1

      //    }
      // ],
      "docs_referenciado" => []
    );
  }
  public function debito()
  {
    if (isset($_GET['desde']) and isset($_GET['hasta'])) {
      $fecha['desde'] = $this->input->get('desde');
      $fecha['hasta'] = $this->input->get('hasta');
    } else {
      $fecha['desde'] = date('Y-m-d');
      $fecha['hasta'] = date('Y-m-d');
    }
    $data['datos'] = $this->documentoelectronico_model->getNotas('Débito', $fecha);
    $data['cambio'] = $this->modelgeneral->getTableWhereRow('parametros', ['nom_paramt' => 'Dolar']);
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/documentoelectronico/debito', $data);
    $this->load->view('layouts/footer');
  }

  function imprimirDebito($id)
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
    $nota = $this->documentoelectronico_model->getNota($id, 'Débito');
    $data['qr'] = $this->getQRNota($nota, '08');
    $data['nota'] = $nota;
    $data['empresa'] = $this->empresa_model->getEmpresa($data);
    $html = $this->load->view('admin/documentoelectronico/debito_impresion', $data, TRUE);
    $css = file_get_contents(APP_PATH . 'assets/styles_pdf.css');
    $this->mpdf->SetTitle('Nota de Débito');
    $this->mpdf->writeHTML($css, 1);
    $this->mpdf->writeHTML($html, 2);
    $this->mpdf->Output('Nota de Débito.pdf', 'I');
  }

  public function getVenta()
  {
    $id = $this->input->get('id');
    $query = $this->ventas_model->getVenta($id);
    echo json_encode($query);
  }

  public function debitoDocumento()
  {
    $id = $this->input->post('id');
    $res = $this->ventas_model->getVenta($id);
    $ruta = base_url_app() . "/facturacion/api_facturacion/notadebito.php";

    //se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf

    $tipo_proceso = getTipoProceso();
    $serie = ($res->codsunat_tipdocu == "01") ? "FD01" : "BD01";
    $data = array(
      //Cabecera del documento
      "tipo_proceso"           => $tipo_proceso['tipo_proceso'],
      "porcentaje_igv"                => "18.00",
      "serie_comprobante"             => $serie,
      "numero_comprobante"            => (string)$this->modelgeneral->getSecuenciaNotas($serie, 'Débito'),
      "fecha_comprobante"             => date('Y-m-d'),
      "cod_tipo_documento"            => "08",
      "cod_moneda"                    => (string)$res->codmoneda_vent,

      "tipo_comprobante_modifica"   => $res->codsunat_tipdocu,
      "nro_documento_modifica"     => (string)$res->serie . '-' . $res->numero_vent,
      "cod_tipo_motivo"         => (string)$this->input->post('motivo'),
      "descripcion_motivo"       => (string)getMotivoNotaDebito($this->input->post('motivo')),

      //Datos del cliente
      "cliente_numerodocumento"       => (string)$res->doc_cliente,
      "cliente_nombre"                => (string)$res->nomb_cliente,
      "cliente_tipodocumento"         => (string)$res->codsunat_tipdocucli, //1: DNI

      //data de la empresa emisora o contribuyente que entrega el documento electrónico.
      "emisor" => getEmisor(),
    );

    $precioSinIGVTotal = 0;
    $total = 0;
    $IGVtotal = 0;

    $detalle = [];
    $n = 1;
    foreach ($_POST['id_prod'] as $key => $value) {
      $producto = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $value]);
      if (isset($_POST['id_detalle'][$key])) {
        $detalleVenta = $this->modelgeneral->getTableWhereRow('tb_venta_detalle', ['cod_ventdet' => $_POST['id_detalle'][$key]]);
        $nombre = $detalleVenta->producto_ventdet;
      } else {
        $nombre = $producto->nomb_product;
      }

      $precioConIGV = $_POST['prec_prod'][$key];
      $precioSinIGV = round($precioConIGV - ($precioConIGV / 1.18) * 0.18, 5);

      $cantidad = $_POST['cant_prod'][$key];
      $det = [];
      $det['unidad'] = isset($producto->nomb_unid) ? $producto->nomb_unid : '';
      $det['txtITEM'] = $n;
      $det['txtUNIDAD_MEDIDA_DET'] = 'NIU'; //NIU = BIENES, ZZ = SERVICIOS
      $det['txtCANTIDAD_DET'] = (string)$cantidad;
      $det['txtPRECIO_DET'] = (string)$precioConIGV; //PRECIO UNITARIO CON IGV
      $det['txtSUB_TOTAL_DET'] = (string)round(($precioSinIGV * $cantidad), 2); //SUBTOTAL SIN IGV
      $det['txtPRECIO_TIPO_CODIGO'] = '01';

      $igv = round((($precioConIGV * $cantidad) / 1.18) * 0.18, 2); //IGV TOTAL
      $det['txtIGV'] = (string)$igv;
      $det['txtISC'] = '0';
      $det['txtIMPORTE_DET'] = (string)round(($precioSinIGV * $cantidad), 2); //SUBTOTAL SIN IGV
      $det['txtCOD_TIPO_OPERACION'] = '10';
      $det['txtCODIGO_DET'] = (string)$producto->cod_producto;
      $det['txtDESCRIPCION_DET'] = (string)$nombre;

      $det['txtPRECIO_SIN_IGV_DET'] = (string)$precioSinIGV;
      $det['txtCODIGO_PROD_SUNAT'] = '23251602';
      $detalle[] = $det;

      $IGVtotal += round($igv, 2);
      $total += round($precioConIGV * $_POST['cant_prod'][$key], 2);
      $precioSinIGVTotal += round($precioSinIGV * $cantidad, 2);
      $n++;
    }

    $data['detalle'] = $detalle;

    $data['total_gravadas'] = (string)$precioSinIGVTotal;
    $data['total_igv'] = (string)$IGVtotal;
    $data['total'] = (string)$total;


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


    if ($response['respuesta'] == 'ok') {
      $dataInsert['tiponota_nota'] = 'Débito';
      $dataInsert['cod_vent'] = (string)$id;
      $dataInsert['totalgravadas_nota'] = (string)$data['total_gravadas'];
      $dataInsert['porcentigv_nota'] = '18.00';
      $dataInsert['totaligv_nota'] = (string)$data['total_igv'];
      $dataInsert['total_nota'] = $data['total'];
      $dataInsert['seriecomp_nota'] = ($res->codsunat_tipdocu == "01") ? "FD01" : "BD01";
      $dataInsert['numcomp_nota'] = (string)$data['numero_comprobante'];
      $dataInsert['codmotivo_nota'] = $data['cod_tipo_motivo'];
      $dataInsert['motivo_nota'] = $data['descripcion_motivo'];
      $dataInsert['fecha_nota'] = date('Y-m-d');

      $dataInsert['rutaxml_nota'] = $response['ruta'];
      $dataInsert['archivoxml_nota'] = $response['archivo'];
      $dataInsert['hash_nota'] = $response['hash_cpe'];
      $insert = $this->modelgeneral->insertRegist('tb_nota', $dataInsert);

      foreach ($detalle as $key => $value) {
        $dataDetalleInsert['cod_nota'] = $insert;
        $dataDetalleInsert['item_notdet'] = $value['txtITEM'];
        $dataDetalleInsert['unidad_notdet'] = $value['unidad'];
        $dataDetalleInsert['unimed_notdet'] = $value['txtUNIDAD_MEDIDA_DET'];
        $dataDetalleInsert['cant_notdet'] = $value['txtCANTIDAD_DET'];
        $dataDetalleInsert['precio_notdet'] = $value['txtPRECIO_DET'];
        $dataDetalleInsert['subtotal_notdet'] = $value['txtSUB_TOTAL_DET'];
        $dataDetalleInsert['preciotipocodigo_notdet'] = $value['txtPRECIO_TIPO_CODIGO'];
        $dataDetalleInsert['igv_notdet'] = $value['txtIGV'];
        $dataDetalleInsert['isc_notdet'] = $value['txtISC'];
        $dataDetalleInsert['importe_notdet'] = $value['txtIMPORTE_DET'];
        $dataDetalleInsert['tipooperac_not'] = $value['txtCOD_TIPO_OPERACION'];
        $dataDetalleInsert['coddet_notdet'] = $value['txtCODIGO_DET'];
        $dataDetalleInsert['descripcion_notdet'] = $value['txtDESCRIPCION_DET'];
        $dataDetalleInsert['preciosinigv_notdet'] = $value['txtPRECIO_SIN_IGV_DET'];
        $dataDetalleInsert['codprodsunat_notdet'] = $value['txtCODIGO_PROD_SUNAT'];
        $this->modelgeneral->insertRegist('tb_notadetalle', $dataDetalleInsert);
      }

      $resp['success'] = true;
      $resp['redirect'] = 'administrador/regdocumentoelectronico/debito';
    } else {
      $resp['success'] = false;
      $resp['resp'] = $data;
    }

    echo json_encode($resp);
  }

  public function credito()
  {
    if (isset($_GET['desde']) and isset($_GET['hasta'])) {
      $fecha['desde'] = $this->input->get('desde');
      $fecha['hasta'] = $this->input->get('hasta');
    } else {
      $fecha['desde'] = date('Y-m-d');
      $fecha['hasta'] = date('Y-m-d');
    }
    $data['datos'] = $this->documentoelectronico_model->getNotas('Crédito', $fecha);
    $data['cambio'] = $this->modelgeneral->getTableWhereRow('parametros', ['nom_paramt' => 'Dolar']);
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/documentoelectronico/credito', $data);
    $this->load->view('layouts/footer');
  }

  function imprimirCredito($id)
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
    $nota = $this->documentoelectronico_model->getNota($id, 'Crédito');
    $data['qr'] = $this->getQRNota($nota, '07');
    $data['nota'] = $nota;
    $data['empresa'] = $this->empresa_model->getEmpresa($data);
    $html = $this->load->view('admin/documentoelectronico/credito_impresion', $data, TRUE);
    $css = file_get_contents(APP_PATH . 'assets/styles_pdf.css');
    $this->mpdf->SetTitle('Nota de Crédito');
    $this->mpdf->writeHTML($css, 1);
    $this->mpdf->writeHTML($html, 2);
    $this->mpdf->Output('Nota de Crédito.pdf', 'I');
  }

  public function creditoDocumento()
  {
    $id = $this->input->post('id');
    $res = $this->ventas_model->getVenta($id);
    $ruta = base_url_app() . "/facturacion/api_facturacion/notacredito.php";

    //se recomienda leer: http://cpe.sunat.gob.pe/sites/default/files/inline-images/Guia%2BXML%2BFactura%2Bversion%202-1%2B1%2B0%20%282%29.pdf

    $tipo_proceso = getTipoProceso();
    $retencion = array();
    $retencion['activo'] = false;
    if ($res->retencion_base_imp != '' and $res->retencion_porcentaje != '') {
      $retencion['activo'] = true;
      $retencion['retencion_base_imp'] = round($res->retencion_base_imp, 2);
      $retencion['retencion_porcentaje'] = $res->retencion_porcentaje;
      $retencion['retencion_monto'] = round($res->retencion_monto, 2);
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
    $serie = ($res->codsunat_tipdocu == "01") ? "FC01" : "BC01";
    $data = array(
      // RETENCION
      "retencion" => $retencion,
      //DETRACION
      "detraccion" => $detraccion,
      //Cabecera del documento
      "tipo_proceso"           => $tipo_proceso['tipo_proceso'],
      "tipo_operacion"        => $detraccion['activo'] == true ? "1001" : "0101", //Venta interna pag 28
      "porcentaje_igv"                => "18.00",
      "serie_comprobante"             => $serie,
      "numero_comprobante"            => (string)$this->modelgeneral->getSecuenciaNotas($serie, 'Crédito'),
      "fecha_comprobante"             => date('Y-m-d'),
      "cod_tipo_documento"            => "07",
      "cod_moneda"                    => (string)$res->codmoneda_vent,
      "total"                      => strval($res->total_vent),
      "total_igv_cabecera"                      => strval($res->igv_vent),
      "tipo_comprobante_modifica"   => $res->codsunat_tipdocu,
      "nro_documento_modifica"     => (string)$res->serie . '-' . $res->numero_vent,
      "cod_tipo_motivo"         => (string)$this->input->post('motivo'),
      "descripcion_motivo"       => (string)getMotivoNotaCredito($this->input->post('motivo')),

      //Datos del cliente
      "cliente_numerodocumento"       => (string)$res->doc_cliente,
      "cliente_nombre"                => (string)$res->nomb_cliente,
      "cliente_tipodocumento"         => (string)$res->codsunat_tipdocucli, //1: DNI

      //data de la empresa emisora o contribuyente que entrega el documento electrónico.
      "emisor" => getEmisor(),
    );

    // $precioSinIGVTotal = 0;
    // $total = 0;
    // $IGVtotal = 0;

    $detalle = [];
    $n = 1;


    foreach ($_POST['id_prod'] as $key => $value) {
      if (isset($_POST['id_detalle'][$key])) {
        $detalleVenta = $this->modelgeneral->getTableWhereRow('tb_venta_detalle', ['cod_ventdet' => $_POST['id_detalle'][$key]]);
      }

      $precioConIGV = $_POST['prec_prod'][$key];
      $precioSinIGV = round($precioConIGV - ($precioConIGV / 1.18) * 0.18, 5);
      $cantidad = $_POST['cant_prod'][$key];
      $precio = $detalleVenta->precunit_ventdet - $detalleVenta->descuento_ventdet;

      // Inicializar el detalle para este producto
      $det = [
        'txtITEM' => $n,
        'txtUNIDAD_MEDIDA_DET' => $detalleVenta->unidad_abreviatura_ventdet,
        'txtCANTIDAD_DET' => (string)$detalleVenta->cant_ventdet,
        'txtPRECIO_DET' => (string)$precio,
        'txtSUB_TOTAL_DET' => (string)round(($precioSinIGV * $cantidad), 2),
        'txtIGV' => $detalleVenta->igv_ventdet,
        'txtISC' => '0',
        'txtIMPORTE_DET' => (string)$detalleVenta->prec_ventdet,
        'txtCODIGO_DET' => (string)(!is_null($detalleVenta->cod_producto)) ? $detalleVenta->cod_producto : $detalleVenta->cod_servicio,
        'txtDESCRIPCION_DET' => (string)$detalleVenta->producto_ventdet,
        'txtCODIGO_PROD_SUNAT' => '23251602'
      ];

      // Procesar el tipo de IGV para este producto
      if (isset($_POST['tipo_igv'][$key])) {
        $tipo_igv = $_POST['tipo_igv'][$key] ?? null;
        // Manejar el caso en que $tipo_igv no esté definido o sea un valor no esperado
        if ($tipo_igv == '1') {
          $det['txtPRECIO_TIPO_CODIGO'] = '01';
          $det['txtCOD_TIPO_OPERACION'] = '10';
          $det['txtPRECIO_SIN_IGV_DET'] = round($precioSinIGV, 10);
          $det['TIPO_IGV'] = '1000';
          $det['MONTO_IGV'] = '18.00';
          $det['IGV_EXO'] = 'IGV';
          $det['TIPO_IMPUESTO'] = 'VAT';
          $det['TAX_CATEGORY_IDENTIFIER'] = 'S';
        } elseif ($tipo_igv == '4') {
          $det['txtPRECIO_TIPO_CODIGO'] = '01';
          $det['txtCOD_TIPO_OPERACION'] = '20';
          $det['txtPRECIO_SIN_IGV_DET'] = $precioConIGV;
          $det['TIPO_IGV'] = '9997';
          $det['MONTO_IGV'] = '0';
          $det['IGV_EXO'] = 'EXO';
          $det['TIPO_IMPUESTO'] = 'VAT';
          $det['TAX_CATEGORY_IDENTIFIER'] = 'E';
        } elseif ($tipo_igv == '5') {
          $det['txtPRECIO_TIPO_CODIGO'] = '02';
          $det['txtCOD_TIPO_OPERACION'] = '36';
          $det['txtPRECIO_SIN_IGV_DET'] = $precioConIGV - $precioConIGV;
          $det['TIPO_IGV'] = '9996';
          $det['MONTO_IGV'] = '0';
          $det['IGV_EXO'] = 'GRA';
          $det['TIPO_IMPUESTO'] = 'FRE';
          $det['TAX_CATEGORY_IDENTIFIER'] = 'Z';
        }
      }

      // Agregar el detalle del producto al array de detalles
      $detalle[] = $det;
      $n++;
    }

    // Asignar el detalle al arreglo de datos
    $data['detalle'] = $detalle;

    $total_gravada = 0;
    $total_exonerada = 0;
    $total_free = 0;

    foreach ($_POST['tipo_igv'] as $id_prod => $tipo_igv) {
      // Verificar si hay un tipo de IGV definido para este producto
      if (is_array($_POST['tipo_igv'])) {
        switch ($tipo_igv) {
          case '1':
            $total_gravada += $res->detalle[$id_prod]->prec_ventdet;
            break;
          case '4':
            $total_exonerada += $res->detalle[$id_prod]->prec_ventdet;
            break;
          case '5':
            $total_free += $res->detalle[$id_prod]->prec_ventdet;
            break;
          default:
            // En caso de que el tipo de IGV no sea ni 1 ni 4, no hacemos nada
            break;
        }
      }
    }

    $data['TIPO_IGV'] = [];
    $data['MONTO_IGV'] = [];
    $data['IGV_EXO'] = [];
    $data['TIPO_IMPUESTO'] = [];
    $data['TAX_CATEGORY_IDENTIFIER'] = [];
    $data['TOTAL_GRA_EXO_FRE'] = [];
    $data['TOTAL_IGV'] = [];

    $igv_venta = 0;
    $igv_venta = (strval($res->igv_vent) - $res->igv_vent);

    foreach ($_POST['id_prod'] as $key => $value) {
      if (isset($_POST['tipo_igv']) && is_array($_POST['tipo_igv'])) {
        $tipo_igv = $_POST['tipo_igv'][$key] ?? null;
        // $prec_ventdet = $res->detalle[$key]->prec_ventdet;
        // Manejar el caso en que $tipo_igv no esté definido o sea un valor no esperado
        switch ($tipo_igv) {
          case '1':
            $data['TIPO_IGV'][] = '1000';
            $data['MONTO_IGV'][] = '18.00';
            $data['IGV_EXO'][] = 'IGV';
            $data['TIPO_IMPUESTO'][] = 'VAT';
            $data['TAX_CATEGORY_IDENTIFIER'][] = 'S';
            $data['TOTAL_GRA_EXO_FRE'][] = strval($total_gravada);
            $data['TOTAL_IGV'][] = strval($res->igv_vent);
            // Otros valores para 'MONTO_IGV', 'IGV_EXO', 'TIPO_IMPUESTO', 'TAX_CATEGORY_IDENTIFIER'
            break;
          case '4':
            $data['TIPO_IGV'][] = '9997';
            $data['MONTO_IGV'][] = '0';
            $data['IGV_EXO'][] = 'EXO';
            $data['TIPO_IMPUESTO'][] = 'VAT';
            $data['TAX_CATEGORY_IDENTIFIER'][] = 'E';
            $data['TOTAL_GRA_EXO_FRE'][] = strval($total_exonerada);
            $data['TOTAL_IGV'][] = $igv_venta;
            // Otros valores para 'MONTO_IGV', 'IGV_EXO', 'TIPO_IMPUESTO', 'TAX_CATEGORY_IDENTIFIER'
            break;
          case '5':
            $data['TIPO_IGV'][] = '9996';
            $data['MONTO_IGV'][] = '0';
            $data['IGV_EXO'][] = 'GRA';
            $data['TIPO_IMPUESTO'][] = 'FRE';
            $data['TAX_CATEGORY_IDENTIFIER'][] = 'Z';
            $data['TOTAL_GRA_EXO_FRE'][] = strval($total_free);
            $data['TOTAL_IGV'][] = $igv_venta;
            // Otros valores para 'MONTO_IGV', 'IGV_EXO', 'TIPO_IMPUESTO', 'TAX_CATEGORY_IDENTIFIER'
            break;
          default:
            // Manejar el caso de un valor inesperado en $tipo_igv
            // Por ejemplo: lanzar una excepción o establecer un valor predeterminado
            break;
        }
      }
    }
    //Invocamos el servicio
    $token = ''; //en caso quieras utilizar algún token generado desde tu sistema

    //codificamos la data
    $data_json = json_encode($data);
    // var_dump($data_json);
    // exit();
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
    // echo $respuesta;
    //   exit();
    $response = json_decode($respuesta, true);

    //   if($response === null) {
    //     // Manejar el error de decodificación JSON
    //     echo 'Error al decodificar la respuesta JSON: ' . json_last_error_msg();
    //     exit();
    // }

    if ($response !== null && isset($response['respuesta']) && $response['respuesta'] == 'ok') {
      $dataInsert['tiponota_nota'] = 'Crédito';
      $dataInsert['cod_vent'] = (string)$id;
      $dataInsert['totalgravadas_nota'] = $total_gravada;
      $dataInsert['exo_nota'] = $total_exonerada;
      $dataInsert['free_nota'] = $total_free;
      $dataInsert['porcentigv_nota'] = '18.00';
      $dataInsert['totaligv_nota'] = strval($res->igv_vent);
      $dataInsert['total_nota'] = $data['total'];
      $dataInsert['seriecomp_nota'] = ($res->codsunat_tipdocu == "01") ? "FC01" : "BC01";
      $dataInsert['numcomp_nota'] = (string)$data['numero_comprobante'];
      $dataInsert['codmotivo_nota'] = $data['cod_tipo_motivo'];
      $dataInsert['motivo_nota'] = $data['descripcion_motivo'];
      $dataInsert['fecha_nota'] = date('Y-m-d');

      $dataInsert['rutaxml_nota'] = $response['ruta'];
      $dataInsert['archivoxml_nota'] = $response['archivo'];
      $dataInsert['hash_nota'] = $response['hash_cpe'];
      $insert = $this->modelgeneral->insertRegist('tb_nota', $dataInsert);

      foreach ($detalle as $key => $value) {
        $dataDetalleInsert['cod_nota'] = $insert;
        $dataDetalleInsert['item_notdet'] = $value['txtITEM'];
        $dataDetalleInsert['unimed_notdet'] = $value['txtUNIDAD_MEDIDA_DET'];
        $dataDetalleInsert['cant_notdet'] = $value['txtCANTIDAD_DET'];
        $dataDetalleInsert['precio_notdet'] = $value['txtPRECIO_DET'];
        $dataDetalleInsert['subtotal_notdet'] = $value['txtSUB_TOTAL_DET'];
        $dataDetalleInsert['preciotipocodigo_notdet'] = $value['txtPRECIO_TIPO_CODIGO'];
        $dataDetalleInsert['igv_notdet'] = $value['txtIGV'];
        $dataDetalleInsert['isc_notdet'] = $value['txtISC'];
        $dataDetalleInsert['importe_notdet'] = $value['txtIMPORTE_DET'];
        $dataDetalleInsert['tipooperac_not'] = $value['txtCOD_TIPO_OPERACION'];
        $dataDetalleInsert['coddet_notdet'] = $value['txtCODIGO_DET'];
        $dataDetalleInsert['descripcion_notdet'] = $value['txtDESCRIPCION_DET'];
        $dataDetalleInsert['preciosinigv_notdet'] = $value['txtPRECIO_SIN_IGV_DET'];
        $dataDetalleInsert['codprodsunat_notdet'] = $value['txtCODIGO_PROD_SUNAT'];
        $this->modelgeneral->insertRegist('tb_notadetalle', $dataDetalleInsert);
      }
      $this->devolverStock($id, $data);

      $resp['success'] = true;
      $resp['sunat_message'] = isset($response['msj_sunat']) ? $response['msj_sunat'] : '';
      //$resp['redirect'] = 'administrador/regdocumentoelectronico/credito';
    } else {
      $resp['success'] = false;
      $resp['message'] = 'Hubo un error al procesar la solicitud';
      // Si hay un mensaje de la SUNAT en la respuesta de error, agregarlo a la respuesta
      if (isset($response['msj_sunat'])) {
        $resp['sunat_message'] = $response['msj_sunat'];
      }
    }

    echo json_encode($resp);
    //var_dump($resp);

  }
  private function devolverStock($id_venta, $data)
  {

    //Obtener información de la venta y sus detalles
    $venta = $this->modelgeneral->getTableWhereRow('tb_venta', ['cod_vent' => $id_venta]);
    $detalle = $this->modelgeneral->getTableWhere('tb_venta_detalle', ['cod_vent' => $id_venta]);

    foreach ($detalle as $d) {
      // Comprobar si es un servicio
      if ($d->cod_father_product === null) {
        continue; // Si es un servicio, omitir la iteración y pasar al siguiente detalle
      }

      $producto = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $d->cod_father_product]);
      $producto_detalle = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $d->cod_producto]);

      // Verificar si el padre del producto es del tipo PRODUCTO
      if ($producto && $producto->cod_tiparticulo == 1) {
        $whereStock['cod_producto'] = $d->cod_father_product;
        $whereStock['cod_almacen'] = $venta->cod_almacen;

        $productoStock = $this->modelgeneral->getTableWhereRow('tb_producto_stock', $whereStock);

        if ($producto_detalle->typeAssignmentProducto == 'G') {
          // Si el producto tiene asignación 'G', devolver el stock basado en el costo
          $nuevoStock = $productoStock->stock + $d->precunit_ventdet;
        } else {
          $nuevoStock = $productoStock->stock + $d->cant_ventdet;
        }

        // Actualizar el stock del producto
        $edit = $this->modelgeneral->editRegist('tb_producto_stock', $whereStock, ['stock' => $nuevoStock]);

        // REGRESAR DISPONIBILIDAD A SERIE
        $this->db->where('cod_vent', $id_venta)
          ->set('serie_estado', 'D')
          ->set('cod_vent', null)
          ->update('tb_producto_serie');
      }
    }


    // Cambiar estado de la venta
    // $edit = $this->modelgeneral->editRegist('tb_venta', ['cod_vent' => $venta_id], $data);

    // Preparar respuesta JSON
    // $resp = ['where' => $whereStock];
    // $resp['success'] = $edit ? true : false;
    // echo json_encode($resp);
  }




  public function getProductoBusqueda()
  {
    $queryLike = $this->input->get('producto');
    $cambio = $this->input->get('cambio');
    $resultProducto = $this->db->from('tb_producto')
      ->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / " . $cambio . ") as costo,(prec_venta / " . $cambio . ") as venta,nomb_unid as unidad", FALSE)
      ->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
      ->where('est_product', 1)
      ->where('cod_tiparticulo', 1)
      ->like('nomb_product', $queryLike)
      ->get()->result_array();

    $this->db->flush_cache();

    $resultServicio = $this->db->from('tb_producto')
      ->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / " . $cambio . ") as costo,(prec_venta / " . $cambio . ") as venta,nomb_unid as unidad, '1' as estado", FALSE)
      ->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
      ->where('est_product', 1)
      ->where('cod_tiparticulo', 2)
      ->like('nomb_product', $queryLike)
      ->get()->result_array();

    $merge = array_merge($resultProducto, $resultServicio);
    echo json_encode($merge);
  }

  public function getProducto()
  {
    $cambio = $this->input->get('cambio');
    $cantidad = $this->input->get('cantidad');
    $producto = $this->input->get('producto');

    $prod = $this->modelgeneral->getTableWhereRow('tb_producto', ['cod_producto' => $producto]);

    $resp = [];
    $resp['tipo'] = $prod->cod_tiparticulo;
    if ($prod->cod_tiparticulo == 1) { //PRODUCTOS
      $result = $this->db->from('tb_producto')
        ->select("tb_producto.*,tb_marca.*,tb_unidades.*,(prec_costo / " . $cambio . ") as costo,(prec_venta / " . $cambio . ") as venta", FALSE)
        ->join('tb_marca', 'tb_producto.cod_marca = tb_marca.cod_marca')
        ->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
        ->where('tb_producto.cod_producto', $producto)
        ->where('cod_tiparticulo', 1)
        ->get()->row();

      $resp['response'] = $result;
    } else { //SERVICIOS
      $result = $this->db->from('tb_producto')
        ->select("tb_producto.*,tb_marca.*,tb_unidades.*,(prec_costo / " . $cambio . ") as costo,(prec_venta / " . $cambio . ") as venta,cod_tiparticulo", FALSE)
        ->join('tb_marca', 'tb_producto.cod_marca = tb_marca.cod_marca')
        ->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
        ->where('tb_producto.cod_producto', $producto)
        ->where('cod_tiparticulo', 2)
        ->get()->row();

      $resp['response'] = $result;
    }

    echo json_encode($resp);
  }

  function getQRNota($data, $tipo)
  {
    /***** FACTURA: DATOS OBLIGATORIOS PARA EL CÓDIGO QR *****/
    /*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE |*/
    $empresa = getDatosEmpresa();
    $ruc = $empresa['empresa']->ruc_emp;
    $tipo_documento = $tipo;
    $serie = $data->seriecomp_nota;
    $numero = $data->numcomp_nota;
    $monto_total_igv = $data->totaligv_nota;
    $monto_total = $data->total_nota;
    $fecha_emision = date('d/m/Y', strtotime($data->fecha_nota));
    $tipo_doc_cliente = $data->codsunat_tipdocucli;
    $documento_cliente = $data->doc_cliente;

    $text_qr = $ruc . '|' . $tipo_documento . '|' . $serie . '|' . $numero . '|' . $monto_total_igv . '|' . $monto_total . '|' . $fecha_emision . '|' . $tipo_doc_cliente . '|' . $documento_cliente . '|';

    return $text_qr;
  }
}


/* End of file Regdocumentoelectronico.php */
/* Location: ./application/controllers/Regdocumentoelectronico.php */
