<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Regcajacierre extends CI_Controller
{
	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ventas_model');
		$this->load->model('empresa_model');
		$this->load->model('cajacierre_model');
		$this->load->model('modelgeneral');
		$this->load->helper('general');
		$this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['doc_clientes'] = $this->ventas_model->getDocumentosCliente();
		$data['tipogastos'] = $this->modelgeneral->getTable('tb_tipo_gastos');
		$data['gastos'] = $this->modelgeneral->getTable('tb_gastos');
		$data['banco'] = $this->modelgeneral->getTable('tb_banco');
		$data['tipos_pagos'] = $this->modelgeneral->getTableWhere('tb_tipo_pago', ['estado_tipopago' => 1]);
		$data['tipos_tarjetas'] = $this->modelgeneral->getTableWhere('tb_tarjeta', ['estado_tarj' => 1]);
		$data['permisos'] = $this->permisos;
		$data['vendedores'] = $this->ventas_model->getVendedores();
		$data['cajas_destinos'] = $this->cajacierre_model->getCajasDestinos();
		$data['cajas_aperturas'] = $this->cajacierre_model->getCajasAperturas();
		$data['puntos'] = $this->ventas_model->getPuntos();
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/cierre/panel', $data);
		$this->load->view('layouts/footer');
	}

	function jsonCierre()
	{
		
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho']  = $this->input->get_post('_', true);
		$columns = ['cod_apertura', 'fechacierre_apertura'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['caja'] = $this->input->get_post('caja');
		$data['usuario'] = $this->input->get_post('usuario');

		$data['desde'] = $this->input->get_post('desde');
		$data['hasta'] = $this->input->get_post('hasta');

		$datos = $this->cajacierre_model->getCierre($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	public function getAperturas()
	{
		$caja = $this->input->get('caja');
		$query = $this->db->from('tb_caja_apertura')
			->join('tb_caja', 'tb_caja_apertura.cod_caja = tb_caja.cod_caja')
			->where('cod_usu', $this->session->userdata('cod_usu'))
			->where('tb_caja_apertura.cod_caja', $caja)
			->where('estado_apertura', 'A')
			->get()->result();

		echo json_encode($query);
	}

	public function datosCajaApertura()
	{
		$result = [];
		$apertura = $this->input->get('apertura');
		$query = $this->db->from('tb_venta')
			->select(" 
			IFNULL(SUM(CASE WHEN cod_tipopago = 1 AND pago_vent = 'CO' THEN monto_vent END ),0) as efectivo,
			IFNULL(SUM(CASE WHEN cod_tipopago = 2 AND pago_vent = 'CO' THEN monto_vent END ),0) as tarjeta,
			IFNULL(SUM(pendiente_vent),0) as credito,
			", FALSE)
			->where('estado_vent', 'G')
			->where('cod_apertura', $apertura)
			->group_by('cod_apertura')
			->get();

		if ($query->num_rows() == 0) {
			$result['efectivo'] = 0;
			$result['tarjeta'] = 0;
			$result['credito'] = 0;
		} else {
			$result['efectivo'] = $query->row()->efectivo;
			$result['tarjeta'] = $query->row()->tarjeta;
			$result['credito'] = $query->row()->credito;
		}


		$queryApertura = $this->modelgeneral->getTableWhereRow('tb_caja_apertura', ['cod_apertura' => $apertura]);

		$bonos = $this->db->from('tb_cobro')
			->select('IFNULL(SUM(monto_cobro),0) as bonos_cobrados', FALSE)
			->where('cod_caja', $queryApertura->cod_caja)
			->where('fecha_cobro', $queryApertura->fecha_apertura)
			->where('tipo_cobro', 'Credito')
			->group_by('fecha_cobro')
			->get();
		if ($bonos->num_rows() != 0) {
			$result['bonos_cobrados'] = $bonos->row()->bonos_cobrados;
		} else {
			$result['bonos_cobrados'] = 0;
		}
		echo json_encode($result);
	}

	public function agregar()
	{
		$data['fechacierre_apertura'] = $this->input->post('fechaHora');
		$data['efectivo_apertura'] = $this->input->post('efectivo');
		$data['tarjeta_apertura'] = $this->input->post('tarjeta');
		$data['credito_apertura'] = $this->input->post('credito');
		$data['bonos_apertura'] = $this->input->post('bonos_cobrados');
		$data['total_apertura'] = $this->input->post('totalCierre');
		$data['estado_apertura'] = 'C';
		$data['destino_apertura'] = $this->input->post('destino');
		$data['tipo_movimiento'] = $this->input->post('tipmovement');
		$data['monto_movimiento'] = $this->input->post('amountmovement');
		$data['obs_movimiento'] = $this->input->post('obsmovement');


		$where['cod_apertura'] = $this->input->post('apertura');
		$edit = $this->modelgeneral->editRegist('tb_caja_apertura', $where, $data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		} else {
			$resp['success'] = false;
		}
		echo json_encode($resp);
	}

	function imprimirCierrecaja($id)
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
		//$data['qr'] = $this->getQR($id);
		$data['cajacierre'] = $this->cajacierre_model->getImpresionCierre($id);
		//$data['empresa'] = $this->empresa_model->getEmpresa($data);
		$html = $this->load->view('admin/cierre/impresion_cierre', $data, TRUE);
		$css = $css = file_get_contents(APP_PATH.'assets/styles_pdf.css');
		$this->mpdf->SetTitle('Ventas');
		$this->mpdf->writeHTML($css, 1);
		$this->mpdf->writeHTML($html, 2);
		$this->mpdf->Output('assets/ventas.pdf', 'I');
	}
	

	function addEgresosIngresos()
	{
		date_default_timezone_set("America/Lima");
		$this->form_validation->set_rules('tipomovimiento', '', 'required');
		$this->form_validation->set_rules('banco', '', 'required');
		// $this->form_validation->set_rules('nombre','','required');
		$this->form_validation->set_rules('montom', '', 'required');
		if ($this->form_validation->run() == TRUE) {
			$data['cod_tipgastos'] = $this->input->post('tipomovimiento');
			$data['cod_puntoventa'] = $this->input->post('sucursal');
			$data['cod_usu'] = $this->input->post('usuario');
			$data['fecha_registro'] = date("Y-m-d H:i:s");
			$data['tipo_movimiento'] = $this->input->post('tipmovimiento');
			$data['cod_tipopago'] = $this->input->post('tipoAbono');
			$data['cod_tarj'] = $this->input->post('tipoTarjeta');
			$data['cod_ban'] = $this->input->post('banco');
			$data['cuenta_gastos'] = $this->input->post('cuenta');
			$data['oper_gastos'] =  $this->input->post('operacion');
			$data['nomb_gastoS'] = $this->input->post('descripcion');
			$data['observacion_gastos'] = $this->input->post('observacionm');
			$data['total_gastos'] = $this->input->post('montom');
			$data['documento_gastos'] = $this->input->post('documento');
			$data['persona_gastos'] = $this->input->post('namemovimiento');
			//$data['observacion_gastos']= $this->input->post('observacionm');
			$data['est_gastos'] =  1;
			$insert = $this->modelgeneral->insertRegist('tb_gastos', $data);
			$resp = [];
			if (!is_null($insert)) {
				//  $insert = $this->modelgeneral->insertRegist('tb_usuario',$data);
				$resp['success'] = true;
			} else {
				$resp['success'] = false;
			}
			echo json_encode($resp);
		}
	}
}

/* End of file Regcajacierre.php */
/* Location: ./application/controllers/administrador/Regcajacierre.php */
