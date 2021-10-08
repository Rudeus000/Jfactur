<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traspasos_model extends CI_Model {

	function getTraspasos($data)
	{
		$this->db->from('tb_traspasos');
    $this->db->join('tb_almacen origen',' tb_traspasos.origen_tras = origen.cod_almacen');
    $this->db->join('tb_almacen destino',' tb_traspasos.destino_tras = destino.cod_almacen');
     $this->db->join('tb_traspasos_detalles detallet','tb_traspasos.cod_tras = detallet.cod_tras'); 
      $this->db->join('tb_producto','detallet.cod_producto = tb_producto.cod_producto');
      $this->db->join('tb_usuario usuario','tb_traspasos.cod_usu = usuario.cod_usu'); 
    $this->db->where('fecha_tras >= ',$data['desde']);
    $this->db->where('fecha_tras <=',$data['hasta']);
    if ($data['origen']!='') {
    	$this->db->where('origen.cod_almacen',$data['origen']);
    }
    if ($data['destino']!='') {
    	$this->db->where('destino.cod_almacen',$data['destino']);
    }
   	$queryLike = $this->db->get();


   	// $this->db->from('tb_traspasos');
   	// $this->db->select('cod_tras,fecha_tras,origen.nomb_almacen as origen, destino.nomb_almacen as destino,observacion_tras');
    // $this->db->join('tb_almacen origen',' tb_traspasos.origen_tras = origen.cod_almacen');
    // $this->db->join('tb_almacen destino',' tb_traspasos.destino_tras = destino.cod_almacen');
    $this->db->from('tb_traspasos');
    $this->db->select('tb_traspasos.cod_tras,detallet.cod_tras,fecha_tras,origen.nomb_almacen as origen, destino.nomb_almacen as destino,observacion_tras,nomb_product,cant_trasdet,serie_trasdet,usuario.nomb_usu as usuario');
      $this->db->join('tb_almacen origen',' tb_traspasos.origen_tras = origen.cod_almacen');
      $this->db->join('tb_almacen destino',' tb_traspasos.destino_tras = destino.cod_almacen');     
      $this->db->join('tb_traspasos_detalles detallet','tb_traspasos.cod_tras = detallet.cod_tras'); 
      $this->db->join('tb_producto','detallet.cod_producto = tb_producto.cod_producto');
      $this->db->join('tb_usuario usuario','tb_traspasos.cod_usu = usuario.cod_usu');  
    $this->db->where('fecha_tras >= ',$data['desde']);
    $this->db->where('fecha_tras <=',$data['hasta']);
     if ($data['origen']!='') {
    	$this->db->like('origen.cod_almacen',$data['origen']);
    }
    if ($data['destino']!='') {
    	$this->db->like('destino.cod_almacen',$data['destino']);
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
    $result['iTotalRecords'] = $queryLike->num_rows();
    $result['iTotalDisplayRecords'] = $queryLike->num_rows();

    $row = [];
    foreach ($query->result() as $q) {
      $detalle = $this->getDetalle($q->cod_tras);
      $buttons = '
      	<div class="btn-footer text-center">
      		<a class="btn btn-sm btn-info" href="'.base_url('administrador/regtraspasos/editar/'.$q->cod_tras).'" title="Editar Traspaso"><i class="fa fa-edit"></i></a>
      	</div>
			';
			
			

      $boton_detalle = '<button class="btn btn-icon waves-effect waves-light btn-success" ><span class="fas fa-arrow-alt-circle-down"></span></button>';
      $row[] = [$boton_detalle,$q->cod_tras,$q->fecha_tras,$q->origen,$q->destino,$q->nomb_product,$q->cant_trasdet,$q->usuario,$q->observacion_tras,'',json_encode($detalle)];
      // $row[] = [$boton_detalle,$q->fecha_tras,$q->origen,$q->destino,$q->observacion_tras,$q->cod_tras,'',json_encode($detalle)];
     }

     $result['aaData'] = $row;
     return $result;
	}
	

	function getDetalle($id)
	{
		return $this->db->from('tb_traspasos_detalles')
		->select('nomb_product, cant_trasdet, serie_trasdet')
		->join('tb_producto','tb_traspasos_detalles.cod_producto = tb_producto.cod_producto')
		->where('cod_tras',$id)
		->get()->result();
	}

}

/* End of file traspasos_model.php */
/* Location: ./application/models/traspasos_model.php */
