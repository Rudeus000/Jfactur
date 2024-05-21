<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Validardatos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //     if(!$this->session->userdata("login")){
        //         redirect(base_url());
        // }
        // Carga el modelo dentro del constructor si no lo has hecho ya
        $this->load->model("Modelgeneral");
    }
    // Variables
    public function validarDocumento()
    {
        $documento = $_REQUEST["dni"];
        $tipo_doc = $_REQUEST["tipo_doc"];
        $token =
            "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c3VhcmlvIjoiZGVudGFsc2FjIiwiZXhwIjoxNzE2NzYwNjkwfQ.rm5R4EsFJ1fUnEPOgbMDDQCDMXBXTGV6UOgWsKG9WI4";

        if ($tipo_doc == "2") {
            // Validar el formato del DNI (solo números y 8 dígitos)
            if (!preg_match('/^[0-9]{8}$/', $documento)) {
                $documento = trim($documento);
            }
            // API URL
            $url = "https://api.datos.bfacturas.pro/dni/{$documento}/token/{$token}";

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            // Execute cURL session
            $response = curl_exec($ch);

            // Get HTTP status code
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Close cURL session
            curl_close($ch);

            // Check if request was successful (HTTP status code 200)
            if ($status_code === 200) {
                // Decode JSON response
                $data = json_decode($response, true);

                // Initialize the $datos array
                $datos = [];

                if (isset($data["error"]) && $data["error"]) {
                    for ($i = 0; $i < 7; $i++) {
                        $datos[$i] = $data["error"];
                    }
                } else {
                    $datos = [
                        0 => isset($data["Dni"]) ? $data["Dni"] : null,
                        1 => isset($data["Appaterno"])
                            ? $data["Appaterno"]
                            : null,
                        2 => isset($data["Apmaterno"])
                            ? $data["Apmaterno"]
                            : null,
                        3 => isset($data["Nombres"]) ? $data["Nombres"] : null,
                        4 => isset($data["Direccion"])
                            ? $data["Direccion"]
                            : null,
                        5 => isset($data["Nombrecompleto"])
                            ? $data["Nombrecompleto"]
                            : null,
                        6 => isset($data["Fnacimiento"])
                            ? date("Y-m-d", strtotime($data["Fnacimiento"]))
                            : "1969-12-31",
                    ];
                }

                // Add the current date to the $datos array
                $datos[7] = date("Y-m-d");

                echo json_encode($datos);
            } else {
                $datos = [
                    0 => $documento,
                    1 => "Api en mantenimiento",
                    2 => "Api en mantenimiento",
                    3 => "Api en mantenimiento",
                    4 => "",
                    5 => "Api no responde ingrese manualmente los datos",
                    6 => date("Y-m-d"),
                ];

                echo json_encode($datos);
            }
        } else {
            $data = file_get_contents(
                "https://api.apis.net.pe/v1/ruc?numero=12345678789"
            );
            $info = json_decode($data, true);

            $datos = [
                0 => $info["numeroDocumento"],
                1 => $info["nombre"],
                2 => date("d/m/Y"),
                3 => $info["condicion"],
                4 => "",
                5 => $info["estado"],
                6 => date("d/m/Y"),
                7 => $info["direccion"],
                8 => $info["distrito"],
                9 => $info["provincia"],
                10 => $info["departamento"],
            ];
            echo json_encode($datos);
        }
    }
}
