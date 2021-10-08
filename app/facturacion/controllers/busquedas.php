<?php
// RUTA para enviar documentos: Tu puedes definir tu propia ruta, en nustro caso la tenemos en la siguiente dirección
$num_documento = $_POST['num_documento'];
$tipo = $_POST['tipo'];
$password = 'washington_KLKJASSD87987DF36'; //AQUÍ TU PASSWORD

if($tipo == 'dni') {
	$ruta = "https://facturalahoy.com/api/persona/".$num_documento.'/'.$password;
} elseif ($tipo == 'ruc') {
	$ruta = "https://facturalahoy.com/api/empresa/".$num_documento.'/'.$password;
} else {
	$resp['repsuesta'] = 'error';
	echo json_encode();
}

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $ruta,
    CURLOPT_USERAGENT => 'Consulta Datos'
));

$resp = curl_exec($curl);
echo $resp;
curl_close($curl);
exit();
?>