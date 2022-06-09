<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reganancvent extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('reportgananciavent_model');
	}

	public function index()
	{
	$data['punto'] = $this->modelgeneral->getTableWhere('tb_puntoventa',['estad_pto' =>'1']);	
	$data['marca'] = $this->modelgeneral->getTableWhere('tb_marca',['est_marca'=>'1']);
	$data['categoria'] = $this->modelgeneral->getTableWhere('tb_categoria',['est_categoria'=>'1']);	
	$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('reports/reportgananciavent',$data);    
    $this->load->view('layouts/footer');
	}

	public function jsonReportgananciaproductos()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
        $data['sEcho']  = $this->input->get_post('_', true);
        $columns= ['cod_producto','nomb_product','nomb_marca'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$desde = $this->input->get_post('desde');
		$hasta = $this->input->get_post('hasta');
		$producto = $this->input->get_post('producto');
		$estado = $this->input->get_post('estado');
		$punto = $this->input->get_post('punto');
		$tb_marca = $this->input->get_post('tb_marca');
		$tb_categoria = $this->input->get_post('tb_categoria');
		if ($desde!='' AND $hasta!='') {
			$data['desde'] = $desde;
			$data['hasta'] = $hasta;
		}
		if ($producto!='') {
			$data['producto'] = $producto;
		}
		$data['estado'] = $estado;
		$data['punto'] = $punto;
		if ($tb_marca!='') {
			$data['tb_marca'] = $tb_marca;
		}
		if ($tb_categoria!='') {
			$data['tb_categoria'] = $tb_categoria;
		}
		$datos = $this->reportgananciavent_model->getreportgananciaproductos($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	public function reportePdf()
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4',
			'orientation' => 'L',
			'margin_left' => 10,
			'margin_right' => 10,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_header' => 10,
			'margin_footer' => 10
		]);
	  $data['datos'] = $this->getCuentasCobrar();
		$html = $this->load->view('admin/cuentascobrar/reporte_pdf',$data,TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('Compras','I');
	}

	public function getCuentasCobrar()
	{
		$proveedor = $this->input->get('proveedor');
		$this->db->from('tb_compra');
		$this->db->select('tb_compra.tb_proveedor_id,tb_proveedor_nom,tb_proveedor_doc,SUM(saldo_comp) as monto');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
		$this->db->group_by('tb_compra.tb_proveedor_id');
		$this->db->where('estado_comp',1);
		$this->db->where('pendiente_comp > ',0);
		if ($proveedor!='') {
			$this->db->like('tb_proveedor_nom',$proveedor);
		}
		$query = $this->db->get()->result();
		foreach ($query as $q) {
			$abono = $this->cuentascobrar_model->getAbonos($q->tb_proveedor_id);
			$saldo = $q->monto - $abono;
			$q->abono = $abono;
			$q->saldo = $saldo;
		}
		return $query;
	}

	function reporteExcel()
  {
  	$data['datos'] = $this->getCuentasCobrar();
  	$this->load->view('reports/reportutilidadexcel',$data);
  }

	public function detalle($proveedor)
	{
		$data['proveedor'] = $this->modelgeneral->getTableWhereRow('tb_proveedor',['tb_proveedor_id'=>$proveedor]);
		$data['cajas'] = $this->modelgeneral->getTableWhere('tb_caja',['est_caja'=>1]);
		$data['datos'] = $this->getCuentasXProveedor($proveedor);
		$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/cuentascobrar/detalle',$data);
    $this->load->view('layouts/footer');
	}

	private function getCuentasXProveedor($proveedor)
	{
		$this->db->from('tb_compra');
		$this->db->where('estado_comp',1);
		$this->db->where('saldo_comp > ',0);
		$this->db->where('tb_compra.tb_proveedor_id',$proveedor);
		$query = $this->db->get()->result();
		foreach ($query as $q) {
			$q->abono = $this->db->from('tb_pago')
									->select('SUM(monto_pago) as abono')
									->where('tipo_pago','Credito')
									->where('cod_comp',$q->cod_comp)
									->group_by('cod_comp')
									->get()->row()->abono;
			$q->pagos = $this->db->from('tb_pago')
									->join('tb_caja','tb_pago.cod_caja = tb_caja.cod_caja')
									->where('tipo_pago','Credito')
									->where('cod_comp',$q->cod_comp)
									->get()->result();
		}

		return $query;
	}

	public function getCuenta()
	{
		$id = $this->input->get('id');
		$this->db->from('tb_compra');
		$this->db->select('tb_compra.cod_comp,documento_comp,tb_proveedor_doc,tb_proveedor_nom,numdocumento_comp,pendiente_comp as saldo,tb_compra.tb_proveedor_id');
		$this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
		$this->db->where('estado_comp',1);
		$this->db->where('saldo_comp > ',0);
		$this->db->where('tb_compra.cod_comp',$id);
		$query = $this->db->get()->row();
		echo json_encode($query);
	}

	public function pagar()
	{
		$data['cod_comp'] = $this->input->post('compra');
		$data['tipo_pago'] = 'Credito';
		$data['cod_caja'] = $this->input->post('caja');
		$data['fecha_pago'] = $this->input->post('fecha');
		$data['detalle_pago'] = $this->input->post('detalle');
		$data['monto_pago'] = $this->input->post('importe');
		$insert = $this->modelgeneral->insertRegist('tb_pago',$data);
		$resp = [];
		if (!is_null($insert)) {
			$this->db->query("UPDATE tb_compra SET pendiente_comp = pendiente_comp - ".$data['monto_pago']." WHERE cod_comp = ".$data['cod_comp']);
			$resp['success'] = true;
			$resp['redirect'] = 'administrador/regcuentascobrar/detalle/'.$this->input->post('proveedor');
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	public function reporteDetallePdf($proveedor)
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4',
			'orientation' => 'L',
			'margin_left' => 10,
			'margin_right' => 10,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_header' => 10,
			'margin_footer' => 10
		]);
	  $data['datos'] = $this->getCuentasXProveedor($proveedor);
		$html = $this->load->view('admin/cuentascobrar/detalle_pdf',$data,TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('Compras','I');
	}

// Exportar a excel
	function reporteDetalleExcel(){
	$data['desde'] = $this->input->get('desde');
	$data['hasta'] = $this->input->get('hasta');
  	$data['datos'] = $this->reportgananciavent_model->getreportgananciaexcel($data);
  	$this->load->view('reports/reportutilidadexcel',$data);
  }




}

/* End of file Regcuentascobrar.php */
/* Location: ./application/controllers/administrador/Regcuentascobrar.php */
