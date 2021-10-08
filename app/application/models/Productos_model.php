<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos_model extends CI_Model {

    function getProductos($data)
    {
        $this->db->from('tb_producto');
        $queryTotal = $this->db->get();
        $this->db->from('tb_producto');
        $this->db->join('tb_tiparticulo','tb_producto.cod_tiparticulo = tb_tiparticulo.cod_tiparticulo');
        $this->db->join('tb_categoria','tb_producto.cod_categoria = tb_categoria.cod_categoria');
        $this->db->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca');
        $this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid');    
        $this->db->select('tb_producto.* , nomb_tiparticulo as TipoArticulo, nomb_product as NombreProducto, 
        , nomb_marca as marca,nomb_categoria as categoria, nomb_unid as unidad, nomb_marca as marca, prec_costo,
         prec_venta, stockmin_product, fecha_modificacion');

        if(isset($data['tb_producto'])){
            $this->db->having("nomb_product LIKE '%".$data['tb_producto']."%'");
        }
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_registro >=',$data['desde']);
			$this->db->where('fecha_registro <=',$data['hasta']);
        }

        if (isset($data['tb_categoria'])) {
            $this->db->where('tb_categoria.cod_categoria',$data['tb_categoria']);
        }
     
        if (isset($data['tb_marca'])) {
			$this->db->where('tb_marca.cod_marca',$data['tb_marca']);
        }
        if (isset($data['tb_tiparticulo'])) {
			$this->db->where('tb_tiparticulo.cod_tiparticulo',$data['tb_tiparticulo']);
		}
        $queryLike = $this->db->get();
        
        $this->db->from('tb_producto');
        $this->db->join('tb_tiparticulo','tb_producto.cod_tiparticulo = tb_tiparticulo.cod_tiparticulo');
        $this->db->join('tb_categoria','tb_producto.cod_categoria = tb_categoria.cod_categoria');
        $this->db->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca');
        $this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid');
        $this->db->select('tb_producto.* , nomb_tiparticulo as TipoArticulo, nomb_product as NombreProducto, 
        , nomb_marca as marca,nomb_categoria as categoria, nomb_unid as unidad, nomb_marca as marca, prec_costo,
         prec_venta, stockmin_product, fecha_modificacion');
        if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
        }
        if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
        }
        // if(isset($data['tb_producto'])){
        //     $this->db->having("barra_product LIKE '%".$data['tb_producto']."%'");
        // }

        if(isset($data['tb_producto'])){
            $this->db->having("nomb_product LIKE '%".$data['tb_producto']."%'");
        }
        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_registro >=',$data['desde']);
			$this->db->where('fecha_registro <=',$data['hasta']);
        }
        if (isset($data['tb_categoria'])) {
            $this->db->where('tb_categoria.cod_categoria',$data['tb_categoria']);
        }
        if (isset($data['tb_marca'])) {
			$this->db->where('tb_marca.cod_marca',$data['tb_marca']);
        }
        if (isset($data['tb_tiparticulo'])) {
			$this->db->where('tb_tiparticulo.cod_tiparticulo',$data['tb_tiparticulo']);
		}
        
        $query = $this->db->get();

		$result = array();
		$result['sEcho'] = $data['sEcho'];
		$result['iTotalRecords'] = $queryTotal->num_rows();
        $result['iTotalDisplayRecords'] = $queryLike->num_rows();
        
        $row = [];
		foreach ($query->result() as $q) {

				if ($q->est_product=='1') {
				$estado = '<label class="label label-success">Activo</label>';
			}elseif($q->est_product=='2'){
				$estado = '<label class="label label-info">Inactivo</label>';
			}	


			$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_producto.'" class="editar-producto on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarProducto" data-placement="top" ><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_producto.'" class="anular-producto on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';


                                                  

            $row[] = [$q->TipoArticulo,$q->NombreProducto,$q->marca,$q->categoria,$q->unidad,$q->prec_costo
            ,$q->prec_venta,$q->stockmin_product,$q->fecha_modificacion,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;
    }

    
}