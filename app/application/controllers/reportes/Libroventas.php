<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Libroventas extends CI_Controller
{

  public function index()
  {
    $fecha = new DateTime();
    $fecha->modify('first day of this month');
    $data['desde'] = $fecha->format('Y-m-d');

    $data['hasta'] =  date('Y-m-d');
    $data['sedes'] = $this->modelgeneral->getTable('sede');
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('reports/libroventas',$data);    
    $this->load->view('layouts/footer');
  }

  public function jsonLibroVentas()
  {
    $data['desde'] = $this->input->get('desde');
    $data['hasta'] = $this->input->get('hasta');
    $data['tipo_comprobante'] = $this->input->get('tipo_comprobante');
    $datos = $this->getLibroVentas($data);
    $response = [];
    $response['data'] = $datos;
    header('content-type: application/json; charset=utf-8');
		echo json_encode($response);
  }

  private function getLibroVentas($data)
  {
    if($data['desde'] == '' OR $data['hasta'] == ''){
      return [];
    }
    $this->db->from('v_libro_electronico_ventas');
    $this->db->where("DATE_FORMAT(STR_TO_DATE(F_EMISION,'%d/%m/%Y'),'%Y-%m-%d') >=",$data['desde']);
    $this->db->where("DATE_FORMAT(STR_TO_DATE(F_EMISION,'%d/%m/%Y'),'%Y-%m-%d') <=",$data['hasta']);
    if($data['tipo_comprobante']!=''){
      $this->db->where('TIPO_DOCUMENTO',$data['tipo_comprobante']);
    }
    $datos = $this->db->get()->result_array();
    
    $i = 1;
    foreach ($datos as $key => $value) {
      $datos[$key]['COD_UNIC'] = $i;
      $i++;
    }
    return $datos;
  }

  public function descargarTxtSunat()
  {
    $data = [];
    $data['desde'] = $this->input->get('desde');
    $data['hasta'] = $this->input->get('hasta');
    $data['tipo_comprobante'] = $this->input->get('tipo_comprobante');
    $datos = $this->getLibroVentas($data);
    
    $empresa = $this->modelgeneral->getTableWhereRow("tb_empresa",['cod_empresa' => 1]);
    $time = time();
    $nombre_txt = "LE" . $empresa->ruc_emp . date('Ym') . "00" .  "140100" . "00" . "1" . "1" . "1" . "1";


    $fila = '';
    foreach ($datos as $key => $value) {
      $fila .= implode('|',$value)."\r\n";
    }
    
    $carpeta = APP_TENANTPATH."assets/temporal/libro_electronico/";
    
    $ruta = $carpeta.$nombre_txt."_".$time.".txt";
    
    $myfile = fopen($ruta, "w") or die("Unable to open file!");
    fwrite($myfile, $fila);
    fclose($myfile);
    
    //header('Location: '.base_url("assets/libro_electronico/".$nombre_txt));
    
    $resp = [];
    $resp['success'] = true;
    $resp['nombre'] = $nombre_txt.'.txt';
    $resp['link'] = base_url("assets/temporal/libro_electronico/".$nombre_txt."_".$time.".txt");
    header('content-type: application/json; charset=utf-8');
    echo json_encode($resp);

  }

  public function descargarExcelSunat()
  {
    $array = [];
    $array['desde'] = $this->input->get('desde');
    $array['hasta'] = $this->input->get('hasta');
    $array['tipo_comprobante'] = $this->input->get('tipo_comprobante');
    $data['datos'] = $this->getLibroVentas($array);
    $this->load->view('reports/libro_ventas_excel',$data);
  }

  public function descargarExcelEJB()
  {
    $array = [];
    $array['desde'] = $this->input->get('desde');
    $array['hasta'] = $this->input->get('hasta');
    $array['tipo_comprobante'] = $this->input->get('tipo_comprobante');
    $data['datos'] = $this->getLibroVentas($array);
    $this->load->view('reports/libro_ventas_ejb',$data);
  }

  public function limpiarArchivos()
  {

    $directorio = "assets/temporal/libro_electronico/";
		// Array en el que obtendremos los resultados
		$res = array();

		// Agregamos la barra invertida al final en caso de que no exista
		if (substr($directorio, -1) != "/") $directorio .= "/";

		// Creamos un puntero al directorio y obtenemos el listado de archivos
		$dir = @dir($directorio) or die("getFileList: Error abriendo el directorio $directorio para leerlo");
		while (($archivo = $dir->read()) !== false) {
			// Obviamos los archivos ocultos
			if ($archivo[0] == ".") continue;
			if (is_dir($directorio . $archivo)) {
				$res[] = array(
					"Nombre" => $directorio . $archivo . "/",
					"Archivo" => $archivo,
					"Tama単o" => 0,
					"Modificado" => filemtime($directorio . $archivo)
				);
			} else if (is_readable($directorio . $archivo)) {
				$res[] = array(
					"Nombre" => $directorio . $archivo,
					"Archivo" => $archivo,
					"Tama単o" => filesize($directorio . $archivo),
					"Modificado" => filemtime($directorio . $archivo)
				);
			}
		}
		$dir->close();


		$dias_eliminacion = 1;
		$dias_tiempo = 60 * 60 * 24 * $dias_eliminacion;

		foreach ($res as $key => $value) {
			$pos = strpos($value['Archivo'], '.txt');
			if($pos !== false){

				$archivo_tiempo = explode('_', trim($value['Archivo'], '.txt'))[1];
	
				$sumado = $archivo_tiempo + $dias_tiempo;
				if (time() > $sumado) {
					unlink($value['Nombre']);
				}
			}
		}

  }


}


/* End of file Balance.php */
/* Location: ./application/controllers/mantenimiento/Balance.php */