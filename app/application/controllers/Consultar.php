<?php
require 'simple_html_dom.php';
error_reporting(E_ALL ^ E_NOTICE);
$documento  = $_REQUEST['dni'];
$tipo_doc   = $_REQUEST['tipo_doc'];
if ($tipo_doc == "2") {
	// $data = file_get_contents("https://bfacturas.000webhostapp.com/datos.php?token=Mmo0RmlzT3EwMG5Ib2JqT2EwTkQ0Zz09&dni=". $documento);
	// $info = json_decode($data, true);
	// $result = $info["dataJson"];
	// $resultado = $result["persona"];
	// $datos = array(
	// 	0 => $info['nuDni'],
	// 	1 => $info['verificacion'],
	// 	2 => $info['apellidoPrimero'],
	// 	3 => $info['apellidoSegundo'],
	// 	4 => $info['prenombreInscrito'],
	// 	5 => $info['domicilio'],
	// 	6 => date('Y-m-d', strtotime($info['fechaNacimiento'])),
	$data = file_get_contents("https://api.apis.net.pe/v1/dni?numero=" . $documento);
	$info = json_decode($data, true);

	$datos = array(
		0 => $info['numeroDocumento'],
		1 => $info['verificacion'],	
		2 => $info['apellidoPaterno'],
		3 => $info['apellidoMaterno'],
		4 => $info['nombres'],	
		5 => 'S/N',
		6 => $info['distrit'],

	);
	echo json_encode($datos);
} else {
	$data = file_get_contents("https://api.apis.net.pe/v1/ruc?numero=" . $documento);
	$info = json_decode($data, true);

	$datos = array(
		0 => $info['numeroDocumento'],
		1 => $info['nombre'],
		2 => date("d/m/Y", strtotime($result['inicio_actividades'])),
		3 => $info['condicion'],
		4 => $info['tipo'],
		5 => $info['estado'],
		6 => date("d/m/Y", strtotime($result['fecha_inscripcion'])),
		7 => $info['direccion'],
		8 => $info['distrito'],
		9 => $info['provincia'],
		10 => $info['departamento']

	);
	echo json_encode($datos);
}
