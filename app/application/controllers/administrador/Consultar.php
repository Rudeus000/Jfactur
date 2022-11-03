<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
//require(APP_TENANTPATH.'config.php');
	require 'simple_html_dom.php';
	error_reporting(E_ALL ^ E_NOTICE);


			//private $permisos;
	$documento  = $_REQUEST['dni'];
	$tipo_doc   = $_REQUEST['tipo_doc'];
	
	


		// $consulta = file_get_html('https://api.reniec.cloud/dni/'.$documento)->plaintext;
		 
		//LA LOGICA DE LA PAGINAS ES APELLIDO PATERNO | APELLIDO MATERNO | NOMBRES
		if($tipo_doc == "2"){
			// $data = file_get_contents("https://api.persona.bfacturas.com/consulta.php?ndni=".$documento."&source=jne");
			//$data = file_get_contents("http://pad.minem.gob.pe/SIGEDVIRTUAL_INGRESO/Solicitud/ConsultaDNI?dni=".$documento);
			$data = file_get_contents("https://api.persona.bfacturas.com/persona/".$documento);
			$info = json_decode($data, true);
			$result=$info["dataJson"];
			$resultado=$result["persona"];				
	
		// 	if($info['success']===false){
				
		// 		$datos = array(
		// 			0 => $info['message'],
		// 			1 => $info[''],
		// 			2 => $info[''],
		// 			3 => $info[''],
		// 			4 => $info['message']
	
		// 		);
		// 		echo json_encode($datos);
			
			
		// }
		//else{
			$datos= array(				
				0 => $info['nuDni'], 
				1 => $info['verificacion'],
				2 => $info['apellidoPrimero'],
				3 => $info['apellidoSegundo'],
				4 => $info['prenombreInscrito'],
				5 => $info['domicilio'],
				6 => date('Y-m-d', strtotime($info['fechaNacimiento'])),
				// 6 => date('Y-m-d', strtotime(str_replace('/', '-',$resultado['echaNacimiento']))),
				
	
			);
				echo json_encode($datos);
	
		//var_dump($info);
			//}
			
		
	
		}
else if($tipo_doc == "4"){
		$data = file_get_contents("https://api.apis.net.pe/v1/ruc?numero=".$documento);
		$info = json_decode($data, true);
		// $result=$info['result'];

		// if($data==='[]' || $result['fecha_inscripcion']==='--'){
		// 	$datos = array(0 => 'nada');
		// 	echo json_encode($datos);
		// }else{
		$datos= array(				
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

		//var_dump($datos);
		// }
	}


?>