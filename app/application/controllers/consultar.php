<?php
	require 'simple_html_dom.php';
	error_reporting(E_ALL ^ E_NOTICE);

	$documento  = $_REQUEST['dni'];
	$tipo_doc   = $_REQUEST['tipo_doc'];
	


		// $consulta = file_get_html('https://api.reniec.cloud/dni/'.$documento)->plaintext;
		 
		//LA LOGICA DE LA PAGINAS ES APELLIDO PATERNO | APELLIDO MATERNO | NOMBRES
		if($tipo_doc == "2"){
			// $data = file_get_contents("https://api.persona.bfacturas.com/consulta.php?ndni=".$documento."&source=jne");
			//$data = file_get_contents("http://pad.minem.gob.pe/SIGEDVIRTUAL_INGRESO/Solicitud/ConsultaDNI?dni=".$documento);
			$data = file_get_contents("http://app20.susalud.gob.pe:8080/registro-renipress-webapp/login.htm?action=buscarPersona&dat_fechaNacimiento=14/01/2021&cmb_sexo=1&cmb_tipoDocumentoIdentidad=1&txt_numeroDocumentoIdentidad=".$documento);
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
				0 => $resultado['nuDni'], 
				1 => $resultado['verificacion'],
				2 => $resultado['apPaterno'],
				3 => $resultado['apMaterno'],
				4 => $resultado['preNombres'],
				5 => $resultado['deDireccion'],
				6 => date('Y-m-d', strtotime(str_replace('/', '-',$resultado['feNac']))),
				
	
			);
				echo json_encode($datos);
	
			//var_dump($datos);
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
			8 => $info['emision_electronica']

		);
			echo json_encode($datos);

		//var_dump($datos);
		// }
	}


?>