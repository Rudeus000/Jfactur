<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reginventarioinicial extends CI_Controller {

	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventarioinicial_model');
		$this->load->model('modelgeneral');
        $this->load->helper('general');
        $this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['permisos'] =$this->permisos;
		$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
		$data['categorias'] = $this->modelgeneral->getTable('tb_categoria');
		$data['marcas'] = $this->modelgeneral->getTable('tb_marca');
		$this->load->view('layouts/header');
	    $this->load->view('layouts/aside');
	    $this->load->view('admin/inventarioinicial/listgetinventario',$data);    
	    $this->load->view('layouts/footer');
	}

	public function jsonInventarioInicial()
  {
    $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
    $data['sEcho']  = $this->input->get_post('_', true);
    $columns= ['nomb_product','nomb_marca'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['almacen'] = $this->input->get_post('almacen');

		$producto = $this->input->get_post('producto');
		if ($producto!='') {
			$data['producto'] = $producto;
		}

		$categoria = $this->input->get_post('categoria');
		if ($categoria!='') {
			$data['categoria'] = $categoria;
		}

		$marca = $this->input->get_post('marca');
		if ($marca!='') {
			$data['marca'] = $marca;
		}

		$datos = $this->inventarioinicial_model->getProductos($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
  }

  public function guardarStockInicial()
  {
		$producto = $this->input->get('producto');
		$data['cod_producto'] = $this->input->get('producto');
  	$data['cod_almacen'] = $this->input->get('almacen');
  	$data['stock_inicial'] = $this->input->get('stock');
  	$data['stock'] = $this->input->get('stock');
		$insert = $this->modelgeneral->insertRegist('tb_producto_stock',$data);
		// if(is_array($this->input->get('series')['producto-'.$producto])){
			if(isset($_GET['series']) AND is_array($_GET['series'])){
			$this->guardarSeries($this->input->get('series')['producto-'.$producto],$data['cod_producto'],$data['cod_almacen']);
		}
  	$resp = [];
  	if (!is_null($insert)) {
  		$resp['success'] = true;
  	}else{
  		$resp['success'] = false;
  	}

  	echo json_encode($resp);
	}
	public function verificarSerieUnico()
	{
		$serie = $this->input->post('serie');
		$query = $this->db->from('tb_producto_serie')
		->where('serie_descripcion',$serie)
		->join('tb_producto','tb_producto_serie.cod_producto = tb_producto.cod_producto')
		->get();
		$resp = [];
		if ($query->num_rows()==0) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
			$resp['serie'] = $serie;
		}
		echo json_encode($resp);
	}
	public function guardarSeries($series,$producto,$almacen)
	{
		// $num = 1;
		// foreach ($series as $key => $value) {
			if(is_array($series)){
				$num = 1;
				foreach ($series as $key => $value) {
			$data['cod_producto'] = $producto;
			$data['cod_almacen'] = $almacen;
			$data['serie_descripcion'] = $value;
			$data['cod_comp'] = null;
			$data['cod_vent'] = null;
			$data['serie_estado'] = 'D';
			$data['histcompstock_serie'] = $num;
			$this->modelgeneral->insertRegist('tb_producto_serie',$data);
			$num++;
		}
	}else {
		$data['cod_producto'] = $producto;
			$data['cod_almacen'] = $almacen;
			$data['serie_descripcion'] = $series;
			$data['cod_comp'] = null;
			$data['cod_vent'] = null;
			$data['serie_estado'] = 'D';
			$data['histcompstock_serie'] = 1;
			$this->modelgeneral->insertRegist('tb_producto_serie',$data);
			// $num++;


	}
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
		
  	$data['almacen'] = $this->modelgeneral->getTableWhereRow('tb_almacen',['cod_almacen'=>$this->input->get('almacen')]);
	  $data['datos'] = $this->getInventarioInicialReporte();
		$html = $this->load->view('admin/inventarioinicial/reporte_pdf',$data,TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('Reporte','I');
  }

  function reporteExcel()
  {
  	$data['datos'] = $this->getInventarioInicialReporte();
  	$this->load->view('admin/inventarioinicial/reporte_excel',$data);
  }

  function getInventarioInicialReporte()
  {
  	$this->db->from('tb_producto');
    $this->db->select('tb_producto.*,nomb_marca,nomb_categoria,nomb_unid,tb_producto_stock.stock_inicial,stock,tb_producto_stock.cod_almacen');
    $this->db->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca');
    $this->db->join('tb_categoria','tb_producto.cod_categoria = tb_categoria.cod_categoria');
    $this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid');
    $this->db->join('tb_producto_stock','tb_producto.cod_producto = tb_producto_stock.cod_producto AND tb_producto_stock.cod_almacen='.$this->input->get('almacen'),'left');
    if ($this->input->get('producto')!='') {
      $this->db->like('nomb_product',$this->input->get('producto'));
    }
    if ($this->input->get('categoria')!='') {
    	$this->db->where('tb_categoria.cod_categoria',$this->input->get('categoria'));
    }
    if ($this->input->get('marca')!='') {
    	$this->db->where('tb_marca.cod_marca',$this->input->get('marca'));
    }
	  return $this->db->get()->result();
	}
	
	function getSeriesInventario()
	{
		$producto = $this->input->get('producto');
		$almacen = $this->input->get('almacen');

		$query = $this->db->from('tb_producto_serie')
		->where('cod_producto',$producto)
		->where('cod_almacen',$almacen)
		->get()->result();
		header('content-type: application/json; charset=utf-8');
		echo json_encode($query);
	}




  function reporteExcelSeries()
  {
  	$data['datos'] = $this->getInventarioInicialReporteseries();
  	$this->load->view('admin/inventarioinicial/reporte_excel_series',$data);
  }

   function getInventarioInicialReporteseries()
  {
  	$this->db->from('tb_producto');
    $this->db->select('tb_producto.*,nomb_almacen,serie_descripcion,cod_comp,cod_vent,serie_estado,histcompstock_serie');
    $this->db->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca');
    $this->db->join('tb_categoria','tb_producto.cod_categoria = tb_categoria.cod_categoria');
    $this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid');
    $this->db->join('tb_producto_serie','tb_producto.cod_producto = tb_producto_serie.cod_producto');
    $this->db->join('tb_almacen','tb_producto_serie.cod_almacen = tb_almacen.cod_almacen');
    $this->db->join('tb_producto_stock','tb_producto.cod_producto = tb_producto_stock.cod_producto AND tb_producto_stock.cod_almacen='.$this->input->get('almacen'),'left');
    if ($this->input->get('producto')!='') {
      $this->db->like('nomb_product',$this->input->get('producto'));
    }
    if ($this->input->get('categoria')!='') {
    	$this->db->where('tb_categoria.cod_categoria',$this->input->get('categoria'));
    }
    if ($this->input->get('marca')!='') {
    	$this->db->where('tb_marca.cod_marca',$this->input->get('marca'));
    }
	  return $this->db->get()->result();
	}
	}
/* End of file Reginventarioinicial.php */
/* Location: ./application/controllers/administrador/Reginventarioinicial.php */
