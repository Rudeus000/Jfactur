<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regcuentascobrar extends CI_Controller {

	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cuentascobrar_model');
		$this->load->model('modelgeneral');        
        $this->load->helper('general');
		$this->load->model('ventas_model');
		$this->permisos = $this->backend_lib->control();

	}

	public function index()
	{
	$data['permisos'] =$this->permisos;
	$data['apertura'] = $this->ventas_model->getCajaApertura();
	$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/cuentascobrar/panel',$data);    
    $this->load->view('layouts/footer');
	}

	public function jsonCuentas()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
    $data['sEcho']  = $this->input->get_post('_', true);
    $columns= ['nomb_cliente'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['cliente'] = $this->input->get_post('cliente');

		$datos = $this->cuentascobrar_model->getCuentas($data);
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
		$cliente = $this->input->get('cliente');
		$this->db->from('tb_venta');
		$this->db->select('tb_venta.id_cliente,nomb_cliente,doc_cliente,SUM(saldo_vent) as monto');
		$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->group_by('tb_venta.id_cliente');
		$this->db->where('estado_vent','G');
		$this->db->where('pendiente_vent > ',0);
		if ($cliente!='') {
			$this->db->like('nomb_cliente',$cliente);
		}
		$query = $this->db->get()->result();
		foreach ($query as $q) {
			$abono = $this->cuentascobrar_model->getAbonos($q->id_cliente);
			$saldo = $q->monto - $abono;
			$q->abono = $abono;
			$q->saldo = $saldo;
		}
		return $query;
	}

	function reporteExcel()
  {
  	$data['datos'] = $this->getCuentasCobrar();
  	$this->load->view('admin/cuentascobrar/reporte_excel',$data);
  }

	public function detalle($cliente)
	{
		$data['cliente'] = $this->modelgeneral->getTableWhereRow('tb_cliente',['id_cliente'=>$cliente]);		
		$data['caja'] = $this->ventas_model->getCajaApertura();
		$data['datos'] = $this->getCuentasXCliente($cliente);
		$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/cuentascobrar/detalle',$data);
    $this->load->view('layouts/footer');
	}

	private function getCuentasXCliente($cliente)
	{
		$this->db->from('tb_venta');
		$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		$this->db->where('estado_vent','G');
		$this->db->where('saldo_vent > ',0);
		$this->db->where('tb_venta.id_cliente',$cliente);
		$this->db->order_by('cod_vent','desc');
		$query = $this->db->get()->result();
		foreach ($query as $q) {
			$q->abono = $this->db->from('tb_cobro')
									->select('SUM(monto_cobro) as abono')
									->where('tipo_cobro','Credito')
									->where('cod_vent',$q->cod_vent)
									->group_by('cod_vent')
									->get()->row()->abono;
			$q->cobros = $this->db->from('tb_cobro')
									->join('tb_caja','tb_cobro.cod_caja = tb_caja.cod_caja')
									->where('tipo_cobro','Credito')
									->where('cod_vent',$q->cod_vent)
									->get()->result();
		}

		return $query;
	}

	public function getCuenta()
	{
		$id = $this->input->get('id');
		$this->db->from('tb_venta');
		$this->db->select('tb_venta.cod_vent,nom_tipdocumento,doc_cliente,nomb_cliente,numero_vent,pendiente_vent as saldo,tb_venta.id_cliente,serie');
		$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		$this->db->where('estado_vent','G');
		$this->db->where('saldo_vent > ',0);
		$this->db->where('tb_venta.cod_vent',$id);
		$query = $this->db->get()->row();
		echo json_encode($query);
	}

	public function getVentaDetalle()
	{
		$id = $this->input->get('id');
		$this->load->model('ventas_model');
		$res = $this->ventas_model->getVenta($id);
		echo json_encode($res);
	}

	public function cobrar()
	{
		$caja = $this->ventas_model->getCajaApertura();
		$data['cod_vent'] = $this->input->post('venta');
		$data['tipo_cobro'] = 'Credito';		
		$data['cod_caja'] = $caja->cod_caja;
		$data['fecha_cobro'] = $this->input->post('fecha');
		$data['detalle_cobro'] = $this->input->post('detalle');
		$data['monto_cobro'] = $this->input->post('importe');
		$insert = $this->modelgeneral->insertRegist('tb_cobro',$data);
		$resp = [];
		if (!is_null($insert)) {
			$this->db->query("UPDATE tb_venta SET pendiente_vent = pendiente_vent - ".$data['monto_cobro']." WHERE cod_vent = ".$data['cod_vent']);
			$resp['success'] = true;
			$resp['redirect'] = 'administrador/regcuentascobrar/detalle/'.$this->input->post('cliente');
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
	  $data['datos'] = $this->getCuentasXCliente($proveedor);
		$html = $this->load->view('admin/cuentascobrar/detalle_pdf',$data,TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Ventas');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('Ventas','I');
	}

	function reporteDetalleExcel($proveedor)
  {
  	$data['datos'] = $this->getCuentasXCliente($proveedor);
  	$this->load->view('admin/cuentascobrar/detalle_excel',$data);
  }




}

/* End of file Regcuentascobrar.php */
/* Location: ./application/controllers/administrador/Regcuentascobrar.php */
