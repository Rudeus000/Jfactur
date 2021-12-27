<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturacion_model extends CI_Model {

	function getFacturas($data)
	{
	$this->db->from('tb_venta');
	$this->db->join('tb_facturacion','tb_venta.cod_vent = tb_facturacion.cod_vent','left') ;
    $this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
    $this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
    $this->db->where('fecha_vent >= ',$data['desde']);
	$this->db->where('fecha_vent <=',$data['hasta']);
	$this->db->where('siglas_talonario','FC');
	$this->db->where('estado_vent','G');
   	$queryLike = $this->db->get();


	// $this->db->from('tb_venta');
	// $this->db->select('tb_venta.cod_vent,tb_facturacion.cod_fac,estado_fac,nomb_cliente,serie,numero_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,nom_tipdocumento,rutaxml_vent,archivoxml_vent','tb_resumenboleta.cod_res');
	// $this->db->join('tb_facturacion','tb_venta.cod_vent = tb_facturacion.cod_vent','left') ;
   	// $this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
   	// $this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    // $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
	// $this->db->join('tb_resumenboletadetalle', 'tb_venta.cod_vent=tb_resumenboletadetalle.cod_vent','left');
	// $this->db->join('tb_resumenboleta',  'tb_resumenboletadetalle.cod_res=tb_resumenboleta.cod_res','left' );
    // $this->db->where('fecha_vent >= ',$data['desde']);
	// $this->db->where('fecha_vent <=',$data['hasta']);
	// $this->db->where('siglas_talonario','FC');
	// $this->db->where('estado_vent','G');

	//   if ($data['length']!=-1) {
	//     $this->db->limit($data['length'],$data['start']);
	//   }
	//   if (isset($data['orderCampo'])) {
	//     $this->db->order_by($data['orderCampo'],$data['orderDireccion']);
	//   }

	//    $query = $this->db->get();

	   $sql="SELECT tb_tipodocumento.cod_tipdocu,tb_venta.cod_vent,
	   case when tb_tipodocumento.cod_tipdocu in(2) then 
	   rb.cod_res
	   else tb_facturacion.cod_fac end as cod_fac , 
	   case when tb_tipodocumento.cod_tipdocu in(2) then 
	   case when ifnull(rb.cod_res,0)=0 then NULL else 1 end
	   else estado_fac end as estado_fac ,
	   nomb_cliente, serie, numero_vent, fecha_vent, subtotal_vent, igv_vent, total_vent, nom_tipdocumento,
	   case when tb_tipodocumento.cod_tipdocu in(2) then 
	   rb.archivoxml_res
	   else archivoxml_vent end as archivoxml_vent,
	   archivoxml_vent as archivoxml_boleta,
	   rutaxml_vent
	   FROM tb_venta
	   LEFT JOIN tb_facturacion ON tb_venta.cod_vent = tb_facturacion.cod_vent
	   JOIN tb_cliente ON tb_venta.id_cliente = tb_cliente.id_cliente
	   JOIN tb_talonario ON tb_venta.cod_talonario = tb_talonario.cod_talonario
	   JOIN tb_tipodocumento ON tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu
	   left join tb_resumenboletadetalle rbd on tb_venta.cod_vent=rbd.cod_vent
	   left join tb_resumenboleta rb on  rbd.cod_res=rb.cod_res 
	   WHERE fecha_vent >= ".$this->db->escape($data['desde'])."
		AND fecha_vent <= ".$this->db->escape($data['hasta'])."
		AND siglas_talonario = 'FC' 
		AND estado_vent='G'";
	   if (isset($data['orderCampo'])) {
		   $sql.=" ORDER BY  ".$data['orderCampo']." ".$data['orderDireccion'];
	   }
	   
	   if ($data['length']!=-1) {
		   $sql.=" LIMIT ".$data['length'];
		   if(!empty($data['start'])){
			   $sql.=",".$data['start'];
		   }
	   }
		
	   $query=$this->db->query($sql);
		
    $result = array();
    $result['sEcho'] = $data['sEcho'];
    $result['iTotalRecords'] = $queryLike->num_rows();
    $result['iTotalDisplayRecords'] = $queryLike->num_rows();    

    $row = [];
    foreach ($query->result() as $q) {
			$date1 = new DateTime($q->fecha_vent);
			$date2 = new DateTime(date("Y-m-d"));
			$diff = $date1->diff($date2);
			$dias = $diff->days;

			$limite = '';
			if($dias >= 0 AND $dias <= 7){
				$limite = '<label class="label label-info">'.(7 - $dias).' dias</label>';
			}else{
				$limite = '<label class="label label-danger">Caducó</label>';
			}

			$check = '';
			$label = '';
			if (!is_null($q->cod_fac)) {
				$label = '<label class="label label-success">Aceptada</label>';
				$check = '
				<a href="'.base_url('administrador/regventas/imprimirVenta/'.$q->archivoxml_vent).'" target="_blank" class="btn btn-sm btn-primary" title="Imprimir"><i class="far fa-file-alt"></i></a>
				<a target="_blank" href="'.base_url_app('facturacion/'.$q->rutaxml_vent.'/'.$q->archivoxml_vent.'.XML').'" class="btn btn-sm btn-primary">XML</a><a target="_blank" href="'.base_url_app('facturacion/'.$q->rutaxml_vent.'/R-'.$q->archivoxml_vent.'.XML').'" class="btn btn-sm btn-primary">CDR</a>';
				$limite = '<label class="label label-primary">Procesado</label>';
			}else{
				if($dias >= 0 AND $dias <= 7){

					$label = '<label class="label label-info">Pendiente</label>';
					$check = $check = '<input type="checkbox" name="factura" class="seleccion" data-id="'.$q->cod_vent.'" value="'.$q->cod_vent.'" />';
				}
			}

			$nota_credito = $this->getNotaCreditoDebito($q->cod_vent,'Crédito');
			$nota_debito = $this->getNotaCreditoDebito($q->cod_vent,'Débito');



      $row[] = [$q->cod_vent,$q->nomb_cliente,$q->fecha_vent,$q->subtotal_vent,$q->igv_vent,$q->total_vent,$q->nom_tipdocumento.' '.$q->serie.'-'.$q->numero_vent,$limite,$label,$check,$nota_credito,$nota_debito];
    }

    $result['aaData'] = $row;
    return $result;
  }

	public function getNotaCreditoDebito($venta,$tipo)
	{
		
		$query = $this->db->from('tb_nota')
		->where('tiponota_nota',$tipo)
		->where('cod_vent',$venta)
		->get();

		if($query->num_rows() > 0){
			$row = $query->row();
			$botones = '
				<a href='.base_url('administrador/regdocumentoelectronico/imprimirCredito/'.$row->cod_nota).' target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i></a>
				<a href="'.base_url_app('facturacion/'.$row->rutaxml_nota.'/'.$row->archivoxml_nota.'.XML').'" target="_blank" class="btn btn-info btn-sm">XML</a>
				<a href="'.base_url_app('facturacion/'.$row->rutaxml_nota.'/R-'.$row->archivoxml_nota.'.XML').'" target="_blank" class="btn btn-info btn-sm">CDR</a>
			';
			return $botones;
		}else{
			return '';
		}
	}

	

  	function getCeprocesadosExcel($data)
	{
		$this->db->from('tb_venta');
		$this->db->select('tb_venta.cod_vent,tb_facturacion.cod_fac,estado_fac,doc_cliente,nomb_cliente,serie,numero_vent,fecha_vent,subtotal_vent,igv_vent,total_vent,nom_tipdocumento,rutaxml_vent,archivoxml_vent,estado_vent');
		$this->db->join('tb_facturacion','tb_venta.cod_vent = tb_facturacion.cod_vent','left') ;
	   	$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
	   	$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
	    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
	    $this->db->where('fecha_vent >= ',$data['desde']);
		$this->db->where('fecha_vent <=',$data['hasta']);
		$this->db->where('siglas_talonario','FC');

		  // if ($data['length']!=-1) {
		  //   $this->db->limit($data['length'],$data['start']);
		  // }
		  // if (isset($data['orderCampo'])) {
		  //   $this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		  // }
		  
	   return $this->db->get()->result();
		
		
    

    
   
  }

}

/* End of file Facturacion_model.php */
/* Location: ./application/models/Facturacion_model.php */
