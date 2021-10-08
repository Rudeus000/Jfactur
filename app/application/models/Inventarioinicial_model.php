<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventarioinicial_model extends CI_Model {

    function getProductos($data)
    {
      $this->db->from('tb_producto');
      $this->db->select('tb_producto.*,nomb_marca,nomb_categoria,nomb_unid,tb_producto_stock.stock_inicial,stock,tb_producto_stock.cod_almacen');
      $this->db->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca');
      $this->db->join('tb_categoria','tb_producto.cod_categoria = tb_categoria.cod_categoria');
			$this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid');
      $this->db->where_in('tb_producto.typeAssignmentProduct', array('P','N'));
			$this->db->where_not_in('tb_producto.cod_tiparticulo','2');
      $this->db->join('tb_producto_stock','tb_producto.cod_producto = tb_producto_stock.cod_producto AND tb_producto_stock.cod_almacen='.$data['almacen'],'left');
      $queryTotal = $this->db->get();


      $this->db->from('tb_producto');
      $this->db->select('tb_producto.*,nomb_marca,nomb_categoria,nomb_unid,tb_producto_stock.stock_inicial,stock,tb_producto_stock.cod_almacen');
      $this->db->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca');
      $this->db->join('tb_categoria','tb_producto.cod_categoria = tb_categoria.cod_categoria');
			$this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid');
      $this->db->where_in('tb_producto.typeAssignmentProduct', array('P','N'));
			$this->db->where_not_in('tb_producto.cod_tiparticulo','2');
      $this->db->join('tb_producto_stock','tb_producto.cod_producto = tb_producto_stock.cod_producto AND tb_producto_stock.cod_almacen='.$data['almacen'],'left');
      if (isset($data['producto'])) {
      	$this->db->like('nomb_product',$data['producto']);
      }
      if (isset($data['categoria'])) {
      	$this->db->where('tb_categoria.cod_categoria',$data['categoria']);
      }
      if (isset($data['marca'])) {
      	$this->db->where('tb_marca.cod_marca',$data['marca']);
      }
      $queryLike = $this->db->get();
      

      $this->db->from('tb_producto');
      $this->db->select('tb_producto.*,nomb_marca,nomb_categoria,nomb_unid,tb_producto_stock.stock_inicial,stock,tb_producto_stock.cod_almacen');
      $this->db->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca');
      $this->db->join('tb_categoria','tb_producto.cod_categoria = tb_categoria.cod_categoria');
      $this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid');
      $this->db->where_in('tb_producto.typeAssignmentProduct', array('P','N'));
      $this->db->where_not_in('tb_producto.cod_tiparticulo','2');
      $this->db->join('tb_producto_stock','tb_producto.cod_producto = tb_producto_stock.cod_producto AND tb_producto_stock.cod_almacen='.$data['almacen'],'left');
      if (isset($data['producto'])) {
      	$this->db->like('nomb_product',$data['producto']);
      }
      if (isset($data['categoria'])) {
      	$this->db->where('tb_categoria.cod_categoria',$data['categoria']);
      }
      if (isset($data['marca'])) {
      	$this->db->where('tb_marca.cod_marca',$data['marca']);
      }
      if ($data['length']!=-1) {
  			$this->db->limit($data['length'],$data['start']);
      }
      if (isset($data['orderCampo'])) {
  			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
      }
    
      $query = $this->db->get();

  		$result = array();
  		$result['sEcho'] = $data['sEcho'];
  		$result['iTotalRecords'] = $queryTotal->num_rows();
          $result['iTotalDisplayRecords'] = $queryLike->num_rows();
          
      $row = [];
  		foreach ($query->result() as $q) {
        $stockInicial = 0;
  			if (is_null($q->stock_inicial)) {
					$inputStockInicial = '
					<div id="producto-'.$q->cod_producto.'">
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<button  data-producto="'.$q->cod_producto.'" class="btn btn-info btn-sm agregar-series"><i class="fa fa-barcode"></i> Series</button>
							</div>
							<input id="cantidad-producto-'.$q->cod_producto.'" type="text" class="form-control">
							<div class="input-group-append">
								<button id="agregar-producto-'.$q->cod_producto.'" data-producto="'.$q->cod_producto.'" data-almacen="'.$data['almacen'].'" class="agregarStockAlmacenInicial btn btn-sm btn-primary" type="button"><i class="fa fa-plus"></i></button>
							</div>
						</div>
					</div>';
  			}else{
          $stockInicial = $q->stock_inicial;
          $buttonSeries = $this->getSeriesStockInicial($q->cod_producto,$q->cod_almacen);
          $inputStockInicial = '
            <div class="input-group mb-3">
              '.$buttonSeries.'
              <input type="text" class="form-control" value="'.$q->stock_inicial.'" disabled>
              <div class="input-group-append">
                <button class="btn btn-sm btn-pink disabled" type="button"><i class="fa fa-plus"></i></button>
              </div>
            </div>';
  			}

        
        $row[] = [$q->nomb_product,$q->nomb_marca,$q->nomb_categoria,$q->nomb_unid,$q->prec_costo,$q->prec_venta,$q->stock,$inputStockInicial];
  		}

  		$result['aaData'] = $row;
  		return $result;
    }

    function getSeriesStockInicial($producto,$almacen)
    {
      $query = $this->db->from('tb_producto_serie')
      ->where('cod_producto',$producto)
      ->where('cod_almacen',$almacen)
      ->where('cod_comp IS NULL')
      ->get();
      
      if($query->num_rows()>0){
        $button = '<div class="input-group-prepend">
                    <button data-almacen="'.$almacen.'" data-producto="'.$producto.'" class="btn btn-pink btn-sm obtener-series"><i class="fa fa-barcode"></i> Series</button>
                  </div>';
      }else{
        $button = '';
      }

      return $button;

    }

}

/* End of file Inventarioinicial_model.php */
/* Location: ./application/models/Inventarioinicial_model.php */
