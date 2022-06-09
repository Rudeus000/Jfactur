<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regdashboard extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata("login")){
			redirect(base_url());
		}
		$this->load->model('reportdashboard_model');
	}
	
	public function index()
	{		

		$data  = array(
			'dia' => $this->reportdashboard_model->rowCountVentasDia(),
			'clientes' => $this->reportdashboard_model->rowCountClientes(),
			'credito' => $this->reportdashboard_model->rowCountVentasCredito(),
			'compras' => $this->reportdashboard_model->rowCountComprasDia(),
			'years' => $this->reportdashboard_model->years(),
			'meses' => $this->db->from('tb_cobro')
											->select("
												CASE 
							WHEN MONTH(fecha_cobro) = 1 THEN 'Enero'
							WHEN MONTH(fecha_cobro) = 2 THEN 'Febrero'
							WHEN MONTH(fecha_cobro) = 3 THEN 'Marzo'
							WHEN MONTH(fecha_cobro) = 4 THEN 'Abril'
							WHEN MONTH(fecha_cobro) = 5 THEN 'Mayo'
							WHEN MONTH(fecha_cobro) = 6 THEN 'Junio'
							WHEN MONTH(fecha_cobro) = 7 THEN 'Julio'
							WHEN MONTH(fecha_cobro) = 8 THEN 'Agosto'
							WHEN MONTH(fecha_cobro) = 9 THEN 'Septiembre'
							WHEN MONTH(fecha_cobro) = 10 THEN 'Octubre'
							WHEN MONTH(fecha_cobro) = 11 THEN 'Noviembre'
							WHEN MONTH(fecha_cobro) = 12 THEN 'Diciembre'
							END as mes") 
											->where('YEAR(fecha_cobro)',date('Y'))
											->group_by('MONTH(fecha_cobro)')
											->order_by("MONTH(fecha_cobro)","desc")																						
											->get()->result(),
				'product' => $this->db->from('tb_venta')
											->select("
												CASE 
							WHEN MONTH(fecha_vent) = 1 THEN 'Enero'
							WHEN MONTH(fecha_vent) = 2 THEN 'Febrero'
							WHEN MONTH(fecha_vent) = 3 THEN 'Marzo'
							WHEN MONTH(fecha_vent) = 4 THEN 'Abril'
							WHEN MONTH(fecha_vent) = 5 THEN 'Mayo'
							WHEN MONTH(fecha_vent) = 6 THEN 'Junio'
							WHEN MONTH(fecha_vent) = 7 THEN 'Julio'
							WHEN MONTH(fecha_vent) = 8 THEN 'Agosto'
							WHEN MONTH(fecha_vent) = 9 THEN 'Septiembre'
							WHEN MONTH(fecha_vent) = 10 THEN 'Octubre'
							WHEN MONTH(fecha_vent) = 11 THEN 'Noviembre'
							WHEN MONTH(fecha_vent) = 12 THEN 'Diciembre'
							END as mes")
											->where('YEAR(fecha_vent)',date('Y'))
											->group_by('MONTH(fecha_vent)')
											->order_by("MONTH(fecha_vent)","desc")	
											->get()->result(),
			 );

		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('reports/reportdashboard', $data);
		$this->load->view('layouts/footer');

	
	}


    public function getData()
    {
		$year = $this->input->post("year");
		$resultados = $this->reportdashboard_model->GeTventasTotales($year);
		echo json_encode($resultados);
	}

		public function jsonVentasMes()
	{
		
		$mes = $this->input->get('mes');
		$mesNum = NULL;
		switch($mes){
			case 'Enero':
				$mesNum=1;
				break;
			case 'Febrero':
				$mesNum=2;
				break;
			case 'Marzo':
				$mesNum=3;
				break;
			case 'Abril':
				$mesNum=4;
				break;
			case 'Mayo':
				$mesNum=5;
				break;
			case 'Junio':
				$mesNum=6;
				break;
			case 'Julio':
				$mesNum=7;
				break;
			case 'Agosto':
				$mesNum=8;
				break;
			case 'Septiembre':
				$mesNum=9;
				break;
			case 'Octubre':
				$mesNum=10;
				break;
			case 'Noviembre':
				$mesNum=11;
				break;
			case 'Diciembre':
				$mesNum=12;
				break;
			default:
				$mesNum=0;
				break;
		}


		$this->db->from('tb_cobro');
		$this->db->select("
			fecha_cobro as mes,sum(monto_cobro) as monto
		",NULL);
		$this->db->join('tb_venta','tb_cobro.cod_vent = tb_venta.cod_vent');
		$this->db->group_by('fecha_cobro');
		$this->db->where('YEAR(fecha_cobro)',date('Y'));
		$this->db->where('MONTH(fecha_cobro)',$mesNum);
		if ($this->session->userdata('puntoventa_reportes')!='admin') {
			$this->db->where('cod_puntoventa',$this->session->userdata('puntoventa_reportes'));
		}
		$query = $this->db->get()->result();

		echo json_encode($query);
	}


	public function vendidosProduct()
	{	
		$mes = $this->input->get('mes');
		$mesNum = NULL;
		switch($mes){
			case 'Enero':
				$mesNum=1;
				break;
			case 'Febrero':
				$mesNum=2;
				break;
			case 'Marzo':
				$mesNum=3;
				break;
			case 'Abril':
				$mesNum=4;
				break;
			case 'Mayo':
				$mesNum=5;
				break;
			case 'Junio':
				$mesNum=6;
				break;
			case 'Julio':
				$mesNum=7;
				break;
			case 'Agosto':
				$mesNum=8;
				break;
			case 'Septiembre':
				$mesNum=9;
				break;
			case 'Octubre':
				$mesNum=10;
				break;
			case 'Noviembre':
				$mesNum=11;
				break;
			case 'Diciembre':
				$mesNum=12;
				break;
			default:
				$mesNum=0;
				break;
		}

		$this->db->from('tb_venta as v');
		$this->db->select('p.nomb_product as nombreProduct,sum(vdt.cant_ventdet) as cantVentaProducto');
		$this->db->group_by('p.cod_producto,p.nomb_product');
		$this->db->join('tb_venta_detalle vdt', 'v.cod_vent = vdt.cod_vent');
		$this->db->join('tb_producto p', 'vdt.cod_producto = p.cod_producto');
		$this->db->where('YEAR(v.fecha_vent)',date('Y'));
		$this->db->where('MONTH(v.fecha_vent)',$mesNum);
		$this->db->where('v.estado_vent','G');
		if ($this->session->userdata('puntoventa_reportes')!='admin') {
			$this->db->where('cod_puntoventa',$this->session->userdata('puntoventa_reportes'));
		}
		$this->db->order_by('sum(vdt.cant_ventdet)','desc');
		$this->db->limit(10);  


		$query = $this->db->get()->result();

		echo json_encode($query);

	}

	function getCumpleanos()
	{
		$query = $this->db->from('tb_cliente')
		->select('nomb_cliente,id_cliente')
		->where('MONTH(fena_pac)',date('m'))
		->where('DAY(fena_pac)',date('d'))
		->where('cumpleano_pac',1)
		->get()->result();

		header('content-type: application/json; charset=utf-8');
		echo json_encode($query);
	}

	function desactivarCumpleano()
	{
		$id = $this->input->post('id');
		$this->db->where('id_cliente',$id)
		->set('cumpleano_pac',0)
		->update('tb_cliente');
	}

	function getUsu()
	{
		$query = $this->db->from('tb_usuario')
		->select('nomb_usu,cod_usu')
		->where('MONTH(fena_usu)',date('m'))
		->where('DAY(fena_usu)',date('d'))
		->where('cumpleano_usu',1)
		->get()->result();

		header('content-type: application/json; charset=utf-8');
		echo json_encode($query);
	}

	function desactivarCumpleanousu()
	{
		$id = $this->input->post('id');
		$this->db->where('cod_usu',$id)
		->set('cumpleano_usu',0)
		->update('tb_usuario');
	}

	public function productosStockMinimos()
	{
		if($this->session->userdata('stock_minimo') == true){
			$query = $this->db->from('tb_producto_stock')
			->select('nomb_almacen,nomb_product,nomb_categoria,prec_costo,nomb_unid,stock,stockmin_product')
			->join('tb_producto','tb_producto_stock.cod_producto = tb_producto.cod_producto')
			->join('tb_categoria','tb_categoria.cod_categoria = tb_producto.cod_categoria')
			->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
			->join('tb_almacen','tb_producto_stock.cod_almacen = tb_almacen.cod_almacen')
			->where('tb_producto_stock.stock <= tb_producto.stockmin_product', null)
			->where('tb_producto.est_product',1)
			->get();
			
			$resp = [];
			if($query->num_rows() > 0){
				$resp['success'] = true;
				$resp['data'] = $query->result();
			}else{
				$resp['success'] = false;
			}

		}else{
			$resp = [];
			$resp['success'] = false;
		}
		
		header('content-type: application/json; charset=utf-8');
		echo json_encode($resp);
	}

	public function productosStockMinimosPosponer()
	{
		$this->session->set_userdata('stock_minimo',FALSE);
	}


}

