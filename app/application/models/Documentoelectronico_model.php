<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Model Documentoelectronico_model
 *
 * This Model for ...
 * 
 * @package		CodeIgniter
 * @category	Model
 * @author    Setiawan Jodi <jodisetiawan@fisip-untirta.ac.id>
 * @link      https://github.com/setdjod/myci-extension/
 * @param     ...
 * @return    ...
 *
 */

class Documentoelectronico_model extends CI_Model {

  // ------------------------------------------------------------------------

  public function __construct()
  {
    parent::__construct();
  }

  // ------------------------------------------------------------------------


  // ------------------------------------------------------------------------
  public function getVentasAnuladas()
  {
    $this->db->from('tb_venta');
    $this->db->select('tb_venta.cod_vent,nom_tipdocumento,serie,numero_vent,fecha_vent,nomb_cliente,igv_vent,subtotal_vent,total_vent,cod_baja,motivo_baja,secuencia_baja,fecha_baja,rutaxml_baja,archivoxml_baja,hash_baja');
    $this->db->join('tb_facturacion','tb_venta.cod_vent = tb_facturacion.cod_vent');
   	$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
   	$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
    $this->db->join('tb_bajasunat','tb_venta.cod_vent = tb_bajasunat.cod_vent','left');
    $this->db->where('tb_venta.estado_vent','A');
    $this->db->where('tb_tipodocumento.cod_tipdocu',1);
    return $this->db->get()->result();
  }

  public function getBaja($id)
  {
    $this->db->from('tb_venta');
    $this->db->select('tb_venta.cod_vent,nom_tipdocumento,serie,numero_vent,fecha_vent,nomb_cliente,igv_vent,subtotal_vent,total_vent,serie,numero_vent,nom_tipdocumento,codigo_baja,serie_baja,cod_baja,motivo_baja,secuencia_baja,fecha_baja,rutaxml_baja,archivoxml_baja,hash_baja');
    $this->db->join('tb_facturacion','tb_venta.cod_vent = tb_facturacion.cod_vent');
   	$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
   	$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
    $this->db->join('tb_bajasunat','tb_venta.cod_vent = tb_bajasunat.cod_vent');
    $this->db->where('tb_venta.estado_vent','A');
    $this->db->where('tb_tipodocumento.cod_tipdocu',1);
    $this->db->where('cod_baja',$id);
    return $this->db->get()->row();
  }

  public function getGuiaRemision()
  {
    $this->db->from('tb_venta');
    $this->db->select('tb_venta.cod_vent,nom_tipdocumento,serie,numero_vent,fecha_vent,nomb_cliente,igv_vent,subtotal_vent,total_vent,cod_guia,fecha_guia,secuencia_guia,nota_guia,rutaxml_guia,archivoxml_guia,hash_guia');
   	$this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
   	$this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
    $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
    $this->db->join('tb_guiaremision','tb_venta.cod_vent = tb_guiaremision.cod_vent','left');
    $this->db->where('tb_venta.estado_vent','G');
    $this->db->where('siglas_talonario','FC');
    return $this->db->get()->result();
  }

  function getNotas($tipo,$fecha)
  {
    if($tipo=='Débito'){
      $this->db->from('v_notas_debito');
    }else{
      $this->db->from('v_notas_credito');
    }
    $this->db->where('fecha_vent >=',$fecha['desde']);
    $this->db->where('fecha_vent <=',$fecha['hasta']);
    return $this->db->get()->result();
  }

  function getNota($id,$tipo)
  {
    if($tipo=='Débito'){
      $this->db->from('v_notas_debito');
    }else{
      $this->db->from('v_notas_credito');
    }
    $this->db->where('cod_nota',$id);
    $query =  $this->db->get()->row();
    
    $query->detalle = $this->db->from('tb_notadetalle')
    ->where('cod_nota',$id)
    ->get()->result();

    return $query;
  }

  function getGuiaRemisionImpresion($id)
  {
    $guia = $this->db->from('tb_guiaremision')
    ->join('tb_venta','tb_guiaremision.cod_vent = tb_venta.cod_vent')
    ->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente')
    ->join('tb_tipodocumentocliente','tb_cliente.cod_tipdocucli = tb_tipodocumentocliente.cod_tipdocucli')
    ->where('cod_guia',$id)
    ->get()->row();

    $guia->detalle = $this->db->from('tb_venta_detalle')
    ->join('tb_producto','tb_venta_detalle.cod_producto = tb_producto.cod_producto')
    ->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
    ->join('tb_marca','tb_marca.cod_marca = tb_producto.cod_marca')
    ->where('cod_vent',$guia->cod_vent)
    ->get()->result();

    return $guia;
  }

}

/* End of file Documentoelectronico_model.php */
/* Location: ./application/models/Documentoelectronico_model.php */
