<?php 
function getEmisor()
{
	$empresa = getDatosEmpresa();

	$datos = [
		"ruc"						=> $empresa['empresa']->ruc_emp,
		"tipo_doc" 					=> "6",
		"nom_comercial" 			=> $empresa['empresa']->nombre_comercial,
		"razon_social" 				=> $empresa['empresa']->razon_social,
		"codigo_ubigeo" 			=> $empresa['punto_venta']->ubigeo_puntoventa,
		"direccion"					=> $empresa['punto_venta']->direccion_puntoventa,
		"direccion_departamento" 	=> strtoupper($empresa['punto_venta']->departamento),
		"direccion_provincia" 		=> strtoupper($empresa['punto_venta']->provincia),
		"direccion_distrito" 		=> strtoupper($empresa['punto_venta']->distrito),
		"direccion_codigopais" 		=> "PE",
		"codigo_sunat" 				=> $empresa['punto_venta']->codigosunat_puntoventa,
	];

	$tipo = getTipoProceso();
	if($tipo['tipo_proceso']=='1'){ //PRODUCCION
		$datos['usuariosol'] = $empresa['empresa']->usuario_sol_emp;
		$datos['clavesol'] = $empresa['empresa']->contrasena_sol_emp;
		$datos['certificado'] = $empresa['empresa']->certificado_emp;
		$datos['contrasena_certificado'] = $empresa['empresa']->contrasena_certificado_emp;
	}else{ //BETA
		$datos['usuariosol'] = 'MODDATOS';
		$datos['clavesol'] = 'moddatos';
		$datos['certificado'] = 'firmabeta.pfx';
		$datos['contrasena_certificado'] = '123456';
	}

	return $datos;
}

function getDatosEmpresa()
{
	$CI =& get_instance();
	$resultado = [];
	$resultado['empresa'] = $CI->db->from('tb_empresa')
	->where('cod_empresa',1)
	->get()->row();

	$resultado['punto_venta'] = $CI->db->from('tb_puntoventa')
	->select('tb_puntoventa.*,ubigeo_distritos.nombre as distrito,ubigeo_provincias.nombre as provincia, ubigeo_departamentos.nombre as departamento')
	->where('cod_puntoventa',$CI->session->userdata('puntoventa'))
	->join('ubigeo_distritos','tb_puntoventa.ubigeo_puntoventa = ubigeo_distritos.id')
	->join('ubigeo_provincias','ubigeo_provincias.id = ubigeo_distritos.provincia_id')
	->join('ubigeo_departamentos','ubigeo_departamentos.id = ubigeo_distritos.departamento_id')
	// ->where('cod_empresa',1)
	->get()->row();
	return $resultado;
}

function getTipoProceso()
{
	$datos['beta']['tipo_proceso'] = '3';
	$datos['beta']['ruta_ws'] = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';
	
	$datos['produccion']['tipo_proceso'] = '1';
	$datos['produccion']['ruta_ws'] = 'https://www.sunat.gob.pe/ol-ti-itcpfegem/billService';

	return $datos['beta'];
}

function getTipoTransporte($tipo)
{
	$data = [
		'01' => 'Transporte Público',
		'02' => 'Transporte Privado'
	];

	return $data[(string)$tipo];
}

function getTipoDocumentoTransporte($tipo)
{
	$data = [
		'6' => 'RUC',
		'1' => 'DNI'
	];

	return $data[(string)$tipo];
}

function getMotivoTraslado($motivo)
{
	$data = [
		'01' => 'VENTA',
		'14' => 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR',
		'04' => 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA',
		'18' => 'TRASLADO EMISOR ITINERANTE CP',
		'08' => 'IMPORTACION',
		'09' => 'EXPORTACION',
		'19' => 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR',
		'13' => 'OTROS'
	];

	return $data[(string)$motivo];
}

function getMotivoNotaDebito($motivo)
{
	$data = [
		'01' => 'INTERES POR MORA2',
		'02' => 'AUMENTO DE VALOR',
		'03' => 'PENALIDADES'
	];

	return $data[(string)$motivo];
}

function getMotivoNotaCredito($motivo)
{
	$data = [
		'01' => 'ANULACION DE LA OPERACION',
		'02' => 'ANULACION POR ERROR EN RUC',
		'03' => 'CORRECCION POR ERROR DE LA DESCRIPCION',
		'04' => 'DESCUENTO GLOBAL',
		'05' => 'DESCUENTO POR ITEM',
		'06' => 'DEVOLUCION GLOBAL',
		'07' => 'DEVOLUCION POR ITEM'
	];

	return $data[(string)$motivo];
}


function msj_sunat($string)
{
	$emisor = getEmisor();

	$pos = strpos($string, "?xml version=");
	if($pos !== false){
		$xml = simplexml_load_string($string, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		if($emisor['usuariosol']=='MODDATOS'){
			$soap = $xml->children($ns['soap-env']);
		}else{
			$soap = $xml->children($ns['env']);
		}
		return (string)$soap->Body->Fault->children()->faultstring[0];
	}else{
		return $string;
	}
}

function reintentos()
{
	return 3;
}

?>