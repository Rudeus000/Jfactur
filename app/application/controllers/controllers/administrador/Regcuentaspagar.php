<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regcuentaspagar extends CI_Controller {
	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cuentaspagar_model');
		$this->load->model('modelgeneral');
        $this->load->helper('general');
        $this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
	$data['permisos'] =$this->permisos;
	$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/cuentaspagar/panel',$data);    
    $this->load->view('layouts/footer');
	}

	public function jsonCuentas()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho']  = $this->input->get_post('_', true);
		$columns= ['tb_proveedor_nom'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['proveedor'] = $this->input->get_post('proveedor');

		$datos = $this->cuentaspagar_model->getCuentas($data);
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
	  $data['datos'] = $this->getCuentasPagar();
		$html = $this->load->view('admin/cuentaspagar/reporte_pdf',$data,TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('Compras','I');
	}

	public function getCuentasPagar()
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
			$abono = $this->cuentaspagar_model->getAbonos($q->tb_proveedor_id);
			$saldo = $q->monto - $abono;
			$q->abono = $abono;
			$q->saldo = $saldo;
		}
		return $query;
	}

	function reporteExcel()
  {
  	$data['datos'] = $this->getCuentasPagar();
  	$this->load->view('admin/cuentaspagar/reporte_excel',$data);
  }

	public function detalle($proveedor)
	{
		$data['proveedor'] = $this->modelgeneral->getTableWhereRow('tb_proveedor',['tb_proveedor_id'=>$proveedor]);
		$data['cajas'] = $this->modelgeneral->getTableWhere('tb_caja',['est_caja'=>1]);
		$data['datos'] = $this->getCuentasXProveedor($proveedor);
		$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/cuentaspagar/detalle',$data);
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
									->where('tipo_pago','CRE')
									->where('cod_comp',$q->cod_comp)
									->group_by('cod_comp')
									->get()->row()->abono;
			$q->pagos = $this->db->from('tb_pago')
									->join('tb_caja','tb_pago.cod_caja = tb_caja.cod_caja')
									->where('tipo_pago','CRE')
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
			$resp['redirect'] = 'administrador/regcuentaspagar/detalle/'.$this->input->post('proveedor');
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
		$html = $this->load->view('admin/cuentaspagar/detalle_pdf',$data,TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('Compras','I');
	}

	function reporteDetalleExcel($proveedor)
  {
  	$data['datos'] = $this->getCuentasXProveedor($proveedor);
  	$this->load->view('admin/cuentaspagar/detalle_excel',$data);
  }

}

/* End of file Regcuentaspagar.php */
/* Location: ./application/controllers/administrador/Regcuentaspagar.php */
