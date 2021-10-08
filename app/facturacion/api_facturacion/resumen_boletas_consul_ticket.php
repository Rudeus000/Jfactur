<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	include "../controllers/validaciondedatos.php";
	include "../controllers/procesarcomprobante.php";

	error_reporting(E_ALL ^ E_NOTICE);
	// para aceptar la conexión desde cualquier origen
	header("Access-Control-Allow-Origin: *");

	// Permite los métodos GET, POST, PUT, DELETE
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
	//exit();
	//obtenemos la data de la solicitud
	$bodyRequest = file_get_contents("php://input");

	// Decodificamos y lo guardamos en un array
	$data = json_decode($bodyRequest, true);

	$array_emisor = get_array_emisor($data);
	//$array_detalle = get_array_detalle($data);
	//$array_cabecera = get_array_cabecera($data, $array_emisor);
	$tipodeproceso = (isset($data['tipo_proceso'])) ? $data['tipo_proceso'] : "3";

	//rutas y nombres de archivos_xml_sunat
	$path = 'archivos_xml_sunat/';
	$url_base = '../archivos_xml_sunat/';
    $content_folder_xml = 'cpe_xml/';
	$content_firmas = 'certificados/';
	
	$nombre_archivo = $data['archivoxml_res'];

	if ($tipodeproceso == '1') {
		$tipo = 'produccion';
		$ruta_cdr = $url_base . $content_folder_xml . 'produccion/' . $array_emisor['ruc'] . "/";
		$ruta_firma = $url_base . $content_firmas . 'produccion/' . $array_emisor['certificado'];
		$pass_firma = $array_emisor['contrasena_certificado'];
		$ruta_ws = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService';
		$ruta_xml = $path . $content_folder_xml . 'produccion/' . $array_emisor['ruc'];
	}
	
	if ($tipodeproceso == '3') {
		$tipo = 'beta';
		//$ruta = $url_base . $content_folder_xml . 'beta/' . $array_emisor['ruc'] . "/" . $nombre_archivo;
		$ruta_cdr = $url_base . $content_folder_xml . 'beta/' . $array_emisor['ruc'] . "/";
		$ruta_firma = $url_base . $content_firmas.'beta/'.$array_emisor['certificado'];
		$pass_firma = $array_emisor['contrasena_certificado'];		
		$ruta_ws = 'https://e-beta.sunat.gob.pe:443/ol-ti-itcpfegem-beta/billService';
		$ruta_xml = $path . $content_folder_xml . 'beta/' . $array_emisor['ruc'];
	}

	$rutas = array();
    $rutas['nombre_archivo'] = $nombre_archivo;
    //$rutas['ruta_xml'] = $ruta;
    $rutas['ruta_cdr'] = $ruta_cdr;
    $rutas['ruta_firma'] = $ruta_firma;
    $rutas['pass_firma'] = $pass_firma;
	$rutas['ruta_ws'] = $ruta_ws;
	//var_export($rutas);exit(0);
	$procesarcomprobante = new Procesarcomprobante();
	//$resp_procesar = $procesarcomprobante->procesar_resumen_boletas($array_cabecera, $array_detalle, $rutas);
	$resp_ticket = $procesarcomprobante->ConsultarEstadoTicket($array_emisor,$rutas,$data['ticket_res']);
	echo json_encode($resp_ticket);
	exit();
	
	function get_array_emisor($data) {
		$data_emisor = $data['emisor'];

		//si estamos ofreciendo un servicio de facturación electrónica, aquí podemos recibir el ruc, y el resto de datos podemos extraerlos desde nuestra base de datos.
		//en este caso, asumimos que todos los datos llegan desde la petición.

		$emisor['ruc'] 						= (isset($data_emisor['ruc'])) ? $data_emisor['ruc'] : '';
		$emisor['tipo_doc'] 				= (isset($data_emisor['tipo_doc'])) ? $data_emisor['tipo_doc'] : '6';
		$emisor['nom_comercial'] 			= (isset($data_emisor['nom_comercial'])) ? $data_emisor['nom_comercial'] : '';
		$emisor['razon_social'] 			= (isset($data_emisor['razon_social'])) ? $data_emisor['razon_social'] : '';
		$emisor['codigo_ubigeo'] 			= (isset($data_emisor['codigo_ubigeo'])) ? $data_emisor['codigo_ubigeo'] : '';
		$emisor['direccion'] 				= (isset($data_emisor['direccion'])) ? $data_emisor['direccion'] : '';
		$emisor['direccion_departamento'] 	= (isset($data_emisor['direccion_departamento'])) ? $data_emisor['direccion_departamento'] : '';
		$emisor['direccion_provincia'] 		= (isset($data_emisor['direccion_provincia'])) ? $data_emisor['direccion_provincia'] : '';
		$emisor['direccion_distrito'] 		= (isset($data_emisor['direccion_distrito'])) ? $data_emisor['direccion_distrito'] : '';
		$emisor['direccion_codigopais'] 	= (isset($data_emisor['direccion_codigopais'])) ? $data_emisor['direccion_codigopais'] : '';
		$emisor['usuariosol'] 				= (isset($data_emisor['usuariosol'])) ? $data_emisor['usuariosol'] : '';
		$emisor['clavesol'] 				= (isset($data_emisor['clavesol'])) ? $data_emisor['clavesol'] : '';
		$emisor['certificado'] 				= (isset($data_emisor['certificado'])) ? $data_emisor['certificado'] : '';
		$emisor['contrasena_certificado'] 				= (isset($data_emisor['contrasena_certificado'])) ? $data_emisor['contrasena_certificado'] : '';
		$emisor['codigo_sunat'] = (isset($data_emisor['codigo_sunat'])) ? $data_emisor['codigo_sunat'] : '';

		//Todos los campos anteriores son obligatorios
		//Aquí se pueden generar todas las validaciones que se necesiten.
		//por ejemplo: si ruc está vacio, retornar un error

		return $emisor;
	}
?>
