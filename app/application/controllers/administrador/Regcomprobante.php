<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regcomprobante extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ventas_model');
		$this->load->model('empresa_model');
	}
	

	public function index()
	{
		$data['empresa'] = $this->empresa_model->getEmpresa();
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/comprobantes/panel',$data);    
		$this->load->view('layouts/footer');
	}

	public function captcha()
	{
		$this->load->helper('captcha');
		$this->load->helper('string');
		$vals = array(
				'word' => $this->stringRandom(),
				'img_path' => APP_PATH.'./assets/images/captcha/',
				'img_url' => base_url_app().'assets/images/captcha/',
				'font_path' => FCPATH.'/assets/fonts/big_noodle_titling.ttf',
				'img_width' => 180,
				'img_height' => 50,
				'expiration' => (60 * 30),
				'font_size'     => 22,
				'colors'        => array(
						'background' => array(255, 255, 255),
						'border' => array(255, 255, 255),
						'text' => array(0, 0, 0),
						'grid' => array(40, 156, 255)
						)
				);
		
		$cap = create_captcha($vals);
		
		$this->session->set_userdata('captcha',$cap['word']);

		$resp = [];
		$resp['imagen'] = $cap['image'];
		$resp['archivo'] = $cap['filename'];

		header('content-type: application/json; charset=utf-8');
		echo json_encode($resp);
	}

	private function stringRandom()
	{
		$length = 6;
		$characters = '23456789ABCDEFGHJKMNPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public function verificaCaptcha()
	{
		$resp = [];
		if($this->input->get('captcha')==$this->session->userdata('captcha')){
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	public function resultados()
	{
		if($this->input->post('captcha')!=$this->session->userdata('captcha')){
			exit();
		}
		$ruc_dni = $this->input->post('ruc_dni');
		$factura_boleta = $this->input->post('factura_boleta');
		$query = $this->db->from('tb_venta,(select @add_row:=0)A')
		->select("@add_row:=@add_row+1 as num,tb_venta.cod_vent,serie,numero_vent,CONCAT(serie,'-',numero_vent) as num_comp,nom_tipdocucli,nom_tipdocumento,doc_cliente,codmoneda_vent,total_vent,fecha_vent,hashcdr_fac,estado_fac",FALSE)
		->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')
		->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
		->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario')
		->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu')
		->join('tb_facturacion','tb_venta.cod_vent = tb_facturacion.cod_vent','left')
		->where('doc_cliente',$ruc_dni)
		->having("num_comp LIKE '%".$factura_boleta."%'")
		->get()->result();

		header('content-type: application/json; charset=utf-8');
		echo json_encode($query);
	}

	public function getDetalle()
	{
		$id = $this->input->post('id');
		$res = $this->ventas_model->getVenta($id);
		echo json_encode($res);
	}

	public function imprimir($archivoxml)
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8', //MODE
			'format' => 'A4',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 5,
			'margin_bottom' => 5,
			'margin_header' => 10,
			'margin_footer' => 10
		]);
		$data['ventas'] = $this->ventas_model->getImpresionVenta($archivoxml);
		$data['empresa'] = $this->empresa_model->getEmpresa();
		$data['qr'] = $this->getQR($data['ventas']->cod_vent);
		$html = $this->load->view('admin/ventas/impventa',$data,TRUE);
		$css = $css = file_get_contents(APP_PATH.'assets/styles_pdf.css');
		$this->mpdf->SetTitle('Ventas');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output($data['ventas']->archivoxml_vent.'.pdf','I');
	}

	function getQR($id)
	{
		/***** FACTURA: DATOS OBLIGATORIOS PARA EL CÓDIGO QR *****/
		/*RUC | TIPO DE DOCUMENTO | SERIE | NUMERO | MTO TOTAL IGV | MTO TOTAL DEL COMPROBANTE | FECHA DE EMISION |TIPO DE DOCUMENTO ADQUIRENTE | NUMERO DE DOCUMENTO ADQUIRENTE |*/
		$venta = $this->ventas_model->getVenta($id);
		$ruc = getEmisor()['ruc'];
		$tipo_documento = $venta->codsunat_tipdocu;
		$serie = $venta->serie;
		$numero = $venta->numero_vent;
		$monto_total_igv = $venta->igv_vent;
		$monto_total = $venta->total_vent;
		$fecha_emision = date('d/m/Y',strtotime($venta->fecha_registro));
		$tipo_doc_cliente = $venta->codsunat_tipdocucli;
		$documento_cliente = $venta->doc_cliente;
		
		$text_qr = $ruc.'|'.$tipo_documento.'|'.$serie.'|'.$numero.'|'.$monto_total_igv.'|'.$monto_total.'|'.$fecha_emision.'|'.$tipo_doc_cliente.'|'.$documento_cliente.'|';

		return $text_qr;
	}

	public function descargar($archivoxml)
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8', //MODE
			'format' => 'A4',
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 5,
			'margin_bottom' => 5,
			'margin_header' => 10,
			'margin_footer' => 10
		]);
		$data['ventas'] = $this->ventas_model->getImpresionVenta($archivoxml);
		$data['empresa'] = $this->empresa_model->getEmpresa();
		$data['qr'] = $this->getQR($data['ventas']->cod_vent);
		$html = $this->load->view('admin/ventas/impventa',$data,TRUE);
		$css = $css = file_get_contents(APP_PATH.'assets/styles_pdf.css');
		$archivo_pdf = 'assets/temporal/comprobantes/'.$data['ventas']->archivoxml_vent.'.pdf';
		$this->mpdf->SetTitle('Ventas');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output($archivo_pdf,'F');

		$facturacion = $this->db->from('tb_facturacion')
		->where('cod_vent',$data['ventas']->cod_vent)
		->get();

		$this->load->library('zip');
		// File path
		$pdf = $archivo_pdf;
		$xml = APP_PATH.'facturacion/'.$data['ventas']->rutaxml_vent.'/'.$archivoxml.'.XML';
		
		// Add file
		$this->zip->read_file($pdf);
		$this->zip->read_file($xml);
		if($facturacion->num_rows()>0){
			$cdr = APP_PATH.'facturacion/'.$data['ventas']->rutaxml_vent.'/R-'.$archivoxml.'.XML';
			$this->zip->read_file($cdr);
		}

		// Download
		$filename = $data['ventas']->archivoxml_vent;
		$this->zip->download($filename);
	}

}

/* End of file Regcomprobante.php */
