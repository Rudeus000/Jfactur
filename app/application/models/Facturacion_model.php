<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Facturacion_model extends CI_Model
{

	function getFacturas($data)
	{
		$this->db->from('v_documentos_electronicos');
		$this->db->where('fecha >= ', $data['desde']);
		$this->db->where('fecha <=', $data['hasta']);
		$queryLike = $this->db->get();


		$this->db->from('v_documentos_electronicos');
		$this->db->where('fecha >= ', $data['desde']);
		$this->db->where('fecha <=', $data['hasta']);
		if ($data['length'] != -1) {
			$this->db->limit($data['length'], $data['start']);
		}
		if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'], $data['orderDireccion']);
		}

		$query = $this->db->get();


		$result = array();
		$result['sEcho'] = $data['sEcho'];
		$result['iTotalRecords'] = $queryLike->num_rows();
		$result['iTotalDisplayRecords'] = $queryLike->num_rows();

		$row = [];
		foreach ($query->result() as $q) {
			$date1 = new DateTime($q->fecha);
			$date2 = new DateTime(date("Y-m-d"));
			$diff = $date1->diff($date2);
			$dias = $diff->days;

			$limite = '';
			if ($dias >= 0 and $dias <= 5) {
				$limite = '<label class="label label-info">' . (5 - $dias) . ' dias</label>';
			} else {
				$limite = '<label class="label label-danger">Caducó</label>';
			}

			$check = '';
			$label = '';

			if ((!is_null($q->cod_doc) or in_array($q->tipo_documento, ['NOTA DE CRÉDITO', 'NOTA DE DÉBITO','BOLETA ELECTRONICA'])) AND ($q->hash != '' OR !is_null($q->hash))) {
				$label = '<label class="label label-success">Aceptada</label>';
				if ($q->estado_vent == 'A') {
					$label = '<label class="label label-danger">Anulado</label>';
				}
				$imprimir = '';
				if (in_array($q->tipo_documento, ['FACTURA ELECTRONICA', 'BOLETA ELECTRONICA'])) {
					$imprimir = base_url('administrador/regventas/imprimirVenta/' . $q->archivoxml_boleta);
				}
				if (in_array($q->tipo_documento, ['NOTA DE CRÉDITO', 'NOTA DE DÉBITO'])) {
					$imprimir = base_url('administrador/regdocumentoelectronico/imprimirCredito/' . $q->id_impresion);
				}

				if($q->tipo_documento=='BOLETA ELECTRONICA'){
					$ruta_xml = base_url_app('facturacion/' . $q->ruta_xml . '/' . $q->archivo_xml . '.XML');
				}else{
					$ruta_xml = base_url_app('facturacion/' . $q->ruta_xml . '/R-' . $q->archivo_xml . '.XML');
				}

				$check = '
				<a href="' . $imprimir . '" target="_blank" class="btn btn-sm btn-primary" title="Imprimir"><i class="far fa-file-alt"></i></a>
				<a target="_blank" href="' . base_url_app('facturacion/' . $q->ruta_xml . '/' . $q->archivo_xml . '.XML') . '" class="btn btn-sm btn-primary">XML</a><a target="_blank" href="' . $ruta_xml . '" class="btn btn-sm btn-primary">CDR</a>';
				$limite = '<label class="label label-primary">Procesado</label>';
			} else {

				if ($dias >= 0 and $dias <= 5) {

					$label = '<label class="label label-info">Pendiente</label>';
					$check = $check = '<input type="checkbox" name="factura" class="seleccion" data-id="' . $q->cod_vent . '" value="' . $q->cod_vent . '" />';
				}
			}

			//$nota_credito = $this->getNotaCreditoDebito($q->cod_vent,'Crédito');
			//$nota_debito = $this->getNotaCreditoDebito($q->cod_vent,'Débito');


			$msj_sunat = '';
			if($q->msj_sunat!=''){
				$msj_sunat = '<button data-mensaje="'.$q->msj_sunat.'" class="btn btn-sm btn-info ver-mensaje-sunat">Mensaje</button>';
			}

			if($q->estado_doc=='2'){
				$label = '<label class="label label-danger">Rechazada</label>';
			}
			$row[] = [$q->id, $q->nomb_cliente, $q->fecha, $q->subtotal, $q->igv, $q->total, $q->tipo_documento . ' ' . $q->serie . '-' . $q->numero, $limite, $label, $check, $msj_sunat];
		}

		$result['aaData'] = $row;
		return $result;
	}

	public function getNotaCreditoDebito($venta, $tipo)
	{

		$query = $this->db->from('tb_nota')
			->where('tiponota_nota', $tipo)
			->where('cod_vent', $venta)
			->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$botones = '
				<a href=' . base_url('administrador/regdocumentoelectronico/imprimirCredito/' . $row->cod_nota) . ' target="_blank" class="btn btn-info btn-sm"><i class="fa fa-print"></i></a>
				<a href="' . base_url_app('facturacion/' . $row->rutaxml_nota . '/' . $row->archivoxml_nota . '.XML') . '" target="_blank" class="btn btn-info btn-sm">XML</a>
				<a href="' . base_url_app('facturacion/' . $row->rutaxml_nota . '/R-' . $row->archivoxml_nota . '.XML') . '" target="_blank" class="btn btn-info btn-sm">CDR</a>
			';
			return $botones;
		} else {
			return '';
		}
	}



	function getCeprocesadosExcel($data)
	{
		// 	$this->db->from('v_documentos_electronicos');
		// 	$this->db->where('fecha >= ',$data['desde']);
		// 	$this->db->where('fecha <=',$data['hasta']);
		//   if ($data['length']!=-1) {
		//     $this->db->limit($data['length'],$data['start']);
		//   }
		//   if (isset($data['orderCampo'])) {
		//     $this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		//   }

		// $query = $this->db->get();
		// $queryLike = $this->db->get();
		$this->db->from('v_documentos_electronicos');
		$this->db->select('id,doc_cliente,cod_vent,estado_doc,nomb_cliente,serie,numero,fecha,subtotal,igv,total,tipo_documento,ruta_xml,archivo_xml,cod_doc,estado_vent');
		// $this->db->join('tb_facturacion','tb_venta.cod_vent = tb_facturacion.cod_vent','left') ;
		// $this->db->join('tb_cliente','tb_venta.id_cliente = tb_cliente.id_cliente');
		// $this->db->join('tb_talonario','tb_venta.cod_talonario = tb_talonario.cod_talonario');
		// $this->db->join('tb_tipodocumento','tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		// $this->db->where('fecha_vent >= ',$data['desde']);
		// $this->db->where('fecha_vent <=',$data['hasta']);
		// $this->db->where('siglas_talonario','FC');

		$this->db->where('fecha >= ', $data['desde']);
		$this->db->where('fecha <=', $data['hasta']);
		//   if ($data['length']!=-1) {
		//     $this->db->limit($data['length'],$data['start']);
		//   }
		//   if (isset($data['orderCampo'])) {
		//     $this->db->order_by($data['orderCampo'],$data['orderDireccion']);
		//   }


		return $this->db->get()->result();
	}
}

/* End of file Facturacion_model.php */
/* Location: ./application/models/Facturacion_model.php */
