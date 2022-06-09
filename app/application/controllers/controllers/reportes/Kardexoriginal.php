<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kardex extends CI_Controller {

	public $igv = 18;
  
	public function __construct()
	{
		parent::__construct();
	}

	public function kardexFisico()
	{
		$month_start = strtotime('first day of this month', time());
		$data['desde'] = date('Y-m-d', $month_start);
		$month_end = strtotime('last day of this month', time());
		$data['hasta'] = date('Y-m-d', $month_end);
		$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('reports/kardex_fisico',$data);
		$this->load->view('layouts/footer');
	}
	
	public function kardexValorado()
	{
		$month_start = strtotime('first day of this month', time());
		$data['desde'] = date('Y-m-d', $month_start);
		$month_end = strtotime('last day of this month', time());
		$data['hasta'] = date('Y-m-d', $month_end);
		$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('reports/kardex_valorado',$data);
		$this->load->view('layouts/footer');
  }
  
  public function jsonKardex()
  {
    $almacen = $this->input->get('almacen');
		$fecha = $this->input->get('fecha');
		$anio = explode('-',$fecha)[0];
		$mes = explode('-',$fecha)[1];
		$query =  $this->query($almacen,$anio,$mes);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($query);
  }

	public function query($almacen,$anio,$mes)
	{
		$almacen = $almacen;
		
		$query = $this->db->from('tb_venta_detalle')
		->select("
		
				tb_producto_stock.cod_producto,
				tb_tiparticulo.nomb_tiparticulo, 
				tb_unidades.nomb_unid, 
				tb_producto.nomb_product,
				tb_producto.prec_costo,
				SUM(CASE WHEN tipo_ventdet='V' THEN cant_ventdet ELSE 0 END) as venta_cantidad,
				SUM(CASE WHEN tipo_ventdet='O' THEN cant_ventdet ELSE 0 END) as obsequio_cantidad,
				SUM(CASE WHEN tipo_ventdet='B' THEN cant_ventdet ELSE 0 END) as bonificacion_cantidad"
				
		
		,FALSE)	


		->join('tb_venta', 'tb_venta_detalle.cod_vent = tb_venta.cod_vent')
			->join('tb_producto_stock', 'tb_venta_detalle.cod_father_product = tb_producto_stock.cod_producto')
			->join('tb_producto', 'tb_producto_stock.cod_producto = tb_producto.cod_producto')
			->join('tb_tiparticulo', 'tb_producto.cod_tiparticulo = tb_tiparticulo.cod_tiparticulo')
			->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
			->where('tb_venta.cod_almacen', $almacen)
			->where('tb_producto_stock.cod_almacen', $almacen)
			->where('YEAR(fecha_vent)', $anio)
			->where('MONTH(fecha_vent)', $mes)
			->group_by(array("tb_producto_stock.cod_producto", "tb_tiparticulo.nomb_tiparticulo", "tb_unidades.nomb_unid","tb_producto.nomb_product","tb_producto.prec_costo"))
			->order_by('tb_producto_stock.cod_producto', 'DESC')
			->get()->result();
		
		foreach ($query as $q) {
			$compra = $this->compraTotal($q->cod_producto,$almacen,$anio,$mes);
			$q->compra_precio = (float)$compra->total_precio; 
			$q->compra_cantidad = (int)$compra->total_numero;
			$q->compra_precio_promedio = (float)$compra->promedio_precio; 

			$stock = $this->stockProducto($almacen,$q->cod_producto);
			$q->stock = $q->compra_cantidad;
			$q->stock_inicial = (int)$stock->stock_inicial;

			$q->traspasos_recibidos = (int)$this->traspasosRecibidos($q->cod_producto,$almacen,$anio,$mes)
			;
			$q->traspasos_enviados = (int)$this->traspasosEnviados($q->cod_producto,$almacen,$anio,$mes);

			$q->stock_final = ($q->stock_inicial + $q->stock + $q->traspasos_recibidos) - ($q->venta_cantidad + $q->traspasos_enviados + $q->obsequio_cantidad + $q->bonificacion_cantidad);

			$q->valorado_stock_inicial = round($q->prec_costo /(($this->igv / 100)+1)* $q->stock_inicial,2);
			$q->valorado_traspasos_recibidos = $q->traspasos_recibidos * $q->compra_precio_promedio;
			$q->valorado_traspasos_enviados = $q->traspasos_enviados * $q->compra_precio_promedio;

			$q->venta_precio= round((($q->valorado_stock_inicial + $q->compra_precio)/($q->stock_inicial + $q->stock)*$q->venta_cantidad),2);
			$q->obsequio_precio= round((($q->valorado_stock_inicial + $q->compra_precio)/($q->stock_inicial + $q->stock)*$q->obsequio_cantidad),2);
			$q->bonificacion_precio= round((($q->valorado_stock_inicial + $q->compra_precio)/($q->stock_inicial + $q->stock)*$q->bonificacion_cantidad),2);
			

			$q->valorado_stock_final = ($q->valorado_stock_inicial + $q->compra_precio + $q->valorado_traspasos_recibidos) - ($q->venta_precio + $q->valorado_traspasos_enviados + $q->obsequio_precio + $q->bonificacion_precio);
			
			$q->valorado_stock_final =  round($q->valorado_stock_final,2);

			

		}

		return $query;
	}

	private function stockProducto($almacen,$producto)
	{
		return $this->db->from('tb_producto_stock')
		->select('stock_inicial')
		->where('cod_producto',$producto)
		->where('cod_almacen',$almacen)
		->get()->row();
	}

	private function traspasosEnviados($producto,$almacen,$anio,$mes)
	{
		return $this->db->from('tb_traspasos_detalles')
		->join('tb_traspasos','tb_traspasos_detalles.cod_tras = tb_traspasos.cod_tras')
		->where('cod_producto',$producto)
		->where('origen_tras',$almacen)
		->where('YEAR(fecha_tras)',$anio)
    ->where('MONTH(fecha_tras)',$mes)
		->get()->row()->cant_trasdet;
	}

	private function traspasosRecibidos($producto,$almacen,$anio,$mes)
	{
		return $this->db->from('tb_traspasos_detalles')
		->join('tb_traspasos','tb_traspasos_detalles.cod_tras = tb_traspasos.cod_tras')
		->where('cod_producto',$producto)
		->where('destino_tras',$almacen)
		->where('YEAR(fecha_tras)',$anio)
    ->where('MONTH(fecha_tras)',$mes)
		->get()->row()->cant_trasdet;
	}

	private function compraTotal($producto,$almacen,$anio,$mes)
	{
		return $this->db->from('tb_compra_detalle')
		->select("SUM(cant_compdet) as total_numero, SUM(precventa_compdet) as total_precio, AVG(precunit_compdet) - AVG(precunit_compdet * ($this->igv / 100)) as promedio_precio")
		->join('tb_compra','tb_compra_detalle.cod_comp = tb_compra.cod_comp')
		->where('cod_producto',$producto)
		->where('cod_almacen',$almacen)
		->where('YEAR(fecha_comp)',$anio)
    ->where('MONTH(fecha_comp)',$mes)
		->group_by('cod_producto')
		->get()->row();
	}

}

/* End of file Regcajaapertura.php */
/* Location: ./application/controllers/administrador/Regcajaapertura.php */
