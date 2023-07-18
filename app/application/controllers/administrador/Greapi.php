<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Greapi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("America/Lima");
        if (!$this->session->userdata("login")) {
            redirect(base_url());
        }
        //$this->permisos = $this->backend_lib->control();
        $this->load->model('modelgeneral');
        $this->load->model('confempresa_model');
        // Cargar el modelo
        $this->load->model('tokensunat_model');

        // Generar el token




    }


    public function enviargre()
    {
        // Obtener el token de autenticación
        $token = $this->tokensunat_model->generateToken();

        if ($token) {
            // URL de la API para enviar la guía de remisión electrónica
            $url = 'https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/{numRucEmisor}-{codCpe}-{numSerie}-{numCpe}';

            // Datos de la guía de remisión electrónica
            $numRucEmisor = '20161515648'; // Reemplaza con el número de RUC del emisor
            $codCpe = '09'; // Reemplaza con el código del tipo de comprobante (09 para guía de remisión)
            $numSerie = 'T001'; // Reemplaza con el número de serie de la guía de remisión
            $numCpe = '121'; // Reemplaza con el número de la guía de remisión

            // Archivo XML de la guía de remisión
            $archivoZipPath = base_url() . 'assets/temporal/20161515648-09-T001-121.zip'; // Reemplaza con la ruta al archivo ZIP de la guía de remisión
            $archivoZipBase64 = base64_encode(file_get_contents($archivoZipPath));

            // Hash del archivo ZIP (SHA-256)
            $hashZip = hash('sha256', file_get_contents($archivoZipPath));

            // Construir la URL con los parámetros de la guía de remisión
            $url = str_replace('{numRucEmisor}', $numRucEmisor, $url);
            $url = str_replace('{codCpe}', $codCpe, $url);
            $url = str_replace('{numSerie}', $numSerie, $url);
            $url = str_replace('{numCpe}', $numCpe, $url);

            // Datos del cuerpo de la solicitud
            $data = array(
                'archivo' => array(
                    'nomArchivo' => "{$numRucEmisor}-{$codCpe}-{$numSerie}-{$numCpe}.zip",
                    'arcGreZip' => $archivoZipBase64,
                    'hashZip' => $hashZip
                )
            );

            // Inicializar cURL
            $ch = curl_init();

            // Establecer opciones de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);

            // Obtener el código de estado de la respuesta
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Cerrar la conexión cURL
            curl_close($ch);

            // Manejar la respuesta
            // ...               
            // Verificar el código de estado de la respuesta
            if ($httpStatus === 200) {
                // Decodificar la respuesta JSON
                $response = json_decode($response, true);

                // Obtener el valor de 'numTicket' de la respuesta
                $numTicket = $response['numTicket'];

                // Almacenar $numTicket en una variable para uso posterior
                $variableNumTicket = $numTicket;

                // echo $variableNumTicket;

                // Consultar el estado de la guía de remisión electrónica con el número de ticket

                $url = 'https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/' . $numTicket;

                // Inicializar cURL
                $ch = curl_init();

                // Establecer opciones de cURL para la consulta del ticket
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer ' . $token
                ));

                // Ejecutar la solicitud de consulta del ticket y obtener la respuesta
                $ticketResponse = curl_exec($ch);

                // Cerrar la conexión cURL
                curl_close($ch);

                if ($httpStatus === 200) {
                    // Decodificar la respuesta JSON
                    $ticketResponse = json_decode($ticketResponse, true);

                    // Procesar el estado de la guía de remisión electrónica
                    // $estado = $response['codRespuesta'];

                    echo json_encode($ticketResponse);

                    // Realizar acciones según el estado de la guía de remisión electrónica
                    // if ($estado === 'aceptado') {
                    //     // La guía de remisión electrónica fue aceptada
                    //     // ...
                    // } elseif ($estado === 'rechazado') {
                    //     // La guía de remisión electrónica fue rechazada
                    //     // ...
                    // } else {
                    //     // La guía de remisión electrónica está en proceso u otro estado
                    //     // ...
                    // }

                    // Verificar el código de estado de la respuesta


                    // Obtener el valor de 'arcCdr' de la respuesta
                    $arcCdr = $ticketResponse['arcCdr'];

                    //echo $arcCdr;


                    // Decodificar el base64 y guardar el XML zipeado
                    $xmlZipPath = 'assets/temporal/archivo_xml.zip'; // Ruta y nombre de archivo para guardar el XML zipeado
                    $decodedXMLZip = base64_decode($arcCdr);
                    if (file_put_contents($xmlZipPath, $decodedXMLZip) !== false) {
                        echo "El archivo se ha guardado correctamente";
                        // Realizar acciones adicionales con el XML zipeado
                        // ...
                    } else {
                        echo "Error al guardar el archivo";
                        // Manejar el error de alguna manera
                        // ...
                    }
                }
            }



            //echo "La Guia de remision fue enviada de manera satisfactoria:";

            //echo $response;
        }
    }

    public function consultarticket($variableNumTicket)
    {
        // URL del API para consultar el estado del ticket
        $url = 'https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/' . $variableNumTicket;

        // Obtener el token de autenticación
        $token = $this->tokensunat_model->generateToken();

        if ($token) {
            // Inicializar cURL
            $ch = curl_init();

            // Establecer opciones de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));

            // Ejecutar la solicitud y obtener la respuesta
            $response = curl_exec($ch);

            // Obtener el código de estado de la respuesta
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Cerrar la conexión cURL
            curl_close($ch);

            // Verificar el código de estado de la respuesta
            if ($httpStatus === 200) {
                // Decodificar la respuesta JSON
                $response = json_decode($response, true);

                // Procesar el estado de la guía de remisión electrónica
                // $estado = $response['codRespuesta'];

                echo json_encode($response);

                // Realizar acciones según el estado de la guía de remisión electrónica
                // if ($estado === 'aceptado') {
                //     // La guía de remisión electrónica fue aceptada
                //     // ...
                // } elseif ($estado === 'rechazado') {
                //     // La guía de remisión electrónica fue rechazada
                //     // ...
                // } else {
                //     // La guía de remisión electrónica está en proceso u otro estado
                //     // ...
                // }
            }
        }
    }
}
