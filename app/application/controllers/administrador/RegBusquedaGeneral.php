<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APP_TENANTPATH.'config.php');

class RegBusquedaGeneral extends CI_Controller {

    private $permisos;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ventas_model');
		$this->load->model('empresa_model');
		$this->load->model('modelgeneral');
		$this->load->helper('general');
    	//$this->permisos = $this->backend_lib->control();
	}

    public function getProductoBusqueda()
    {
        $queryLike = 'recarga';//$this->input->get('producto');
        $cambio = 1;

        $precioVenta = 'prec_venta';

        $resultProducto = $this->db->from('tb_producto')
            ->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / " . $cambio . ") as costo,(" . $precioVenta . " / " . $cambio . ") as venta,nomb_unid as unidad, (CASE WHEN stock > stockmin_product THEN 1 ELSE 0 END) as estado,peso_product, tb_producto.idTypeAssignmentProduct,stock,cod_tiparticulo, tb_almacen.nomb_almacen", FALSE)
            ->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
            ->join('tb_producto_stock', 'tb_producto_stock.cod_producto = tb_producto.cod_producto')
            ->join('tb_almacen','tb_producto_stock.cod_almacen = tb_almacen.cod_almacen')
            ->where_in('tb_producto.typeAssignmentProduct', array('H', 'N'))
            ->where('est_product', 1)
            ->where('cod_tiparticulo', 1)
            ->where('nomb_product LIKE "%' . $queryLike
                . '%" OR barra_product LIKE "%' . $queryLike . '%"', NULL)
            ->get()->result_array();

        if (empty($resultProducto)) {
            $resultProducto = $this->db->from('tb_producto')
                ->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / " . $cambio . ") as costo,(" . $precioVenta . " / " . $cambio . ") as venta,nomb_unid as unidad, (CASE WHEN stock > stockmin_product THEN 1 ELSE 0 END) as estado,peso_product, tb_producto.idTypeAssignmentProduct,stock,cod_tiparticulo", FALSE)
                ->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
                ->join('tb_producto_stock', 'tb_producto_stock.cod_producto = tb_producto.idTypeAssignmentProduct')
                ->where_in('tb_producto.typeAssignmentProduct', array('H', 'N'))
                ->where('est_product', 1)
                ->where('cod_tiparticulo', 1)
                ->where('nomb_product LIKE "%' . $queryLike
                    . '%" OR barra_product LIKE "%' . $queryLike . '%"', NULL)
                ->get()->result_array();
        }

        $this->db->flush_cache();

        $resultServicio = $this->db->from('tb_producto')
        ->select("tb_producto.cod_producto as id,nomb_product as nombre,(prec_costo / ".$cambio.") as costo,(".$precioVenta." / ".$cambio.") as venta,nomb_unid as unidad, '1' as estado,peso_product,cod_tiparticulo",FALSE)
        ->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
        ->where_in('tb_producto.typeAssignmentProduct', array('H', 'N'))
        ->where('est_product',1)
        ->where('(cod_tiparticulo = 2 AND (nomb_product LIKE "%'.$queryLike
        .'%" OR barra_product LIKE "%'.$queryLike.'%"))',NULL)
        ->get()->result_array();

        $merge = array_merge($resultProducto,$resultServicio);
        header('content-type: application/json; charset=utf-8');
        echo json_encode($merge);
    }

    public function getProducto()
	{
		$almacen = $this->input->get('almacen');
		$cambio = $this->input->get('cambio');
		$cantidad = $this->input->get('cantidad');
		$producto = $this->input->get('producto');
		$series = $this->input->get('series');

		$prod = $this->modelgeneral->getTableWhereRow('tb_producto',['cod_producto'=>$producto]);
		if ($prod->typeAssignmentProduct == 'H') {
			$ResultypeAssignmentProduct = array('tb_producto.cod_producto' => $producto, 'tb_producto.idTypeAssignmentProduct' => $prod->idTypeAssignmentProduct);
			$JoinAssignmentProduct = 'tb_producto_stock.cod_producto = tb_producto.idTypeAssignmentProduct';
			$querySeriestypeAssignmentProduct = array('cod_producto' => $prod->idTypeAssignmentProduct);
		} else {
			$ResultypeAssignmentProduct = array('tb_producto.cod_producto' => $producto);
			$JoinAssignmentProduct = 'tb_producto_stock.cod_producto = tb_producto.cod_producto';
			$querySeriestypeAssignmentProduct = array('cod_producto' => $producto);
		}

		$resp = [];
		$resp['tipo'] = $prod->cod_tiparticulo;
		if ($prod->cod_tiparticulo==1) { //PRODUCTOS
		
		$result = $this->db->from('tb_producto')
				->select("tb_producto.*,tb_marca.*,tb_unidades.*,tb_producto_stock.*,(prec_costo / " . $cambio . ") as costo,(prec_venta / " . $cambio . ") as venta,(tb_producto_stock.stock - stockmin_product ) as stock_disponible,cod_tiparticulo", FALSE)
				->join('tb_producto_stock', $JoinAssignmentProduct)
				->join('tb_marca', 'tb_producto.cod_marca = tb_marca.cod_marca')
				->join('tb_unidades', 'tb_producto.cod_unid = tb_unidades.cod_unid')
				->where('tb_producto_stock.cod_almacen', $almacen)
				->where($ResultypeAssignmentProduct)				
				->where('cod_tiparticulo', 1)
				->get()->row();

				$querySeries = $this->db->from('tb_producto_serie')
				->where($querySeriestypeAssignmentProduct)
				->where('cod_almacen', $almacen)
				->where('serie_estado', 'N')
				->get();

			if ($result->stock_disponible > $querySeries->num_rows()) {
				$result->stock_disponible -= $querySeries->num_rows();
			}		
		
			$resp['response'] = $result;

			if (!empty($resp['response'])) {
				$resp['response']->cod_producto = $producto;
			}


			if ($result->stock_disponible >= $cantidad) {
				if (is_array($series)) {
					$querySeries = $this->db->from('tb_producto_serie')
					->select('serie_descripcion as id, serie_descripcion as text')
					// ->where('cod_producto',$producto)
					->where($querySeriestypeAssignmentProduct)
					->where('cod_almacen',$almacen)
					->where('serie_estado','D')
					->get()->result();
					foreach ($querySeries as $q) {
						if(in_array($q->text,$series)){
							$q->selected = true;
						}else{
							$q->selected = false;
						}
					}
					$resp['series'] = $querySeries;
				}
				$resp['estado'] = true;
			}else{
				$resp['estado'] = false;
			}
		}else{ //SERVICIOS
			$result = $this->db->from('tb_producto')
			->select("tb_producto.*,tb_marca.*,tb_unidades.*,(prec_costo / ".$cambio.") as costo,(prec_venta / ".$cambio.") as venta,cod_tiparticulo",FALSE)
			->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca')
			->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
			// ->where('tb_producto.cod_producto',$producto)
			->where($ResultypeAssignmentProduct)
			->where('cod_tiparticulo',2)
			->get()->row();

			$querySeries = $this->db->from('tb_producto_serie')
			->select('serie_descripcion as id, serie_descripcion as text')
			// ->where('cod_producto',$producto)
			//->where($ResultypeAssignmentProduct)
			->where('cod_almacen',$almacen)
			->where('serie_estado','D')
			->get()->result();
			if(is_array($series)){
				foreach ($querySeries as $q) {
					if(in_array($q->text,$series)){
						$q->selected = true;
					}else{
						$q->selected = false;
					}
				}
			}
			
			$resp['response'] = $result;

		}

		echo json_encode($resp);
	}

}
