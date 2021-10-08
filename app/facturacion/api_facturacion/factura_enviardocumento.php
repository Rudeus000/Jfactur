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

	$data_comprobante['EMISOR_RUC'] = $data['ruc'];
	$data_comprobante['EMISOR_USUARIO_SOL'] = $data['usuariosol'];
	$data_comprobante['EMISOR_PASS_SOL'] = $data['clavesol'];

	$rutas['ruta_xml'] = $data['ruta_xml'];
	$rutas['ruta_cdr'] = $data['ruta_cdr'];
	$rutas['nombre_archivo'] = $data['nombre_archivo'];
	$rutas['ruta_ws'] = $data['ruta_ws'];
	
	$procesarcomprobante = new Procesarcomprobante();
	$resp = $procesarcomprobante->procesarEnviarFactura($data_comprobante, $rutas);
	
	echo json_encode($resp);
	exit();
	
?>
