<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regcompras extends CI_Controller {
	private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('compras_model');
		$this->load->model('empresa_model');
		$this->load->model('modelgeneral');
		$this->load->model('notaunidad_model');
		$this->load->model('notavalorizado_model');
		$this->load->helper('general');
    $this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['permisos'] =$this->permisos;
		$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
		$this->load->view('layouts/header');
	    $this->load->view('layouts/aside');
	    $this->load->view('admin/compras/listgetcompras',$data);    
	    $this->load->view('layouts/footer');
	}

	public function jsonCompras()
  {
    $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
    $data['sEcho']  = $this->input->get_post('_', true);
    $columns= ['fecha_comp','fecha_comp','cod_comp'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['desde'] = $this->input->get_post('desde');
		$data['hasta'] = $this->input->get_post('hasta');
		$data['almacen'] = $this->input->get_post('almacen');
		$data['proveedor'] = $this->input->get_post('proveedor');
		$data['estado'] = $this->input->get_post('estado');

		$datos = $this->compras_model->getCompras($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
  }

  public function agregar()
  {
  	$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
  	$data['cajas'] = $this->modelgeneral->getTableWhere('tb_caja',['est_caja'=>1]);
  	$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/compras/agregar',$data);    
    $this->load->view('layouts/footer');
  }

  function getProveedores()
	{
		$q = $this->input->get('q');

		$result = $this->db->from('tb_proveedor')
		->select('tb_proveedor_id as id,tb_proveedor_nom as nombre,tb_proveedor_doc as ruc')
		->like('tb_proveedor_nom',$q)
		->get()->result();

		echo json_encode($result);
	}

	function getProductoBusqueda()
	{
		$producto = $this->input->get('producto');
		$result = $this->db->from('tb_producto')
		->select('tb_producto.cod_producto as id,nomb_product as nombre,prec_costo as costo,prec_venta as venta,nomb_unid as unidad')
		->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
		->where('est_product',1)
		
		->where_in('typeAssignmentProduct',array('P','N'))
		->where('(nomb_product LIKE "%' . $producto
				. '%" OR barra_product LIKE "%' . $producto . '%")', NULL)
		->get()->result();
		echo json_encode($result);
	}

	function getProducto()
	{
		$producto = $this->input->get('producto');
		$result = $this->db->from('tb_producto')
		->where('cod_producto',$producto)
		->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca')
		->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
		->get()->row();
		echo json_encode($result);
	}

	function agregarProveedor()
	{
		$this->form_validation->set_rules('nombre','','required');
		$this->form_validation->set_rules('documento','','required');
		$this->form_validation->set_rules('telefono','','required');
    if($this->form_validation->run() == TRUE){
    	$data['tb_proveedor_nom'] = $this->input->post('nombre');
    	$data['tb_proveedor_doc'] = $this->input->post('documento');
    	$data['tb_proveedor_tel'] = $this->input->post('telefono');
    	$data['tb_proveedor_dir'] = $this->input->post('direccion');
    	$data['tb_proveedor_ema'] = $this->input->post('email');
    	$insert = $this->modelgeneral->insertRegist('tb_proveedor',$data);

    	$resp = [];
    	if (!is_null($insert)) {
    		$resp['success'] = true;
    	}else{
    		$resp['success'] = false;
    	}

    	echo json_encode($resp);
    }
	}
	
	function editarProveedor()
	{
		$this->form_validation->set_rules('id','','required');
		$this->form_validation->set_rules('nombre','','required');
		$this->form_validation->set_rules('documento','','required');
		$this->form_validation->set_rules('telefono','','required');
    if($this->form_validation->run() == TRUE){
    	$data['tb_proveedor_nom'] = $this->input->post('nombre');
    	$data['tb_proveedor_doc'] = $this->input->post('documento');
    	$data['tb_proveedor_tel'] = $this->input->post('telefono');
    	$data['tb_proveedor_dir'] = $this->input->post('direccion');
    	$data['tb_proveedor_ema'] = $this->input->post('email');
    	$where['tb_proveedor_id'] = $this->input->post('id');
    	$editar = $this->modelgeneral->editRegist('tb_proveedor',$where,$data);

    	$resp = [];
    	if ($editar) {
    		$resp['success'] = true;
    	}else{
    		$resp['success'] = false;
    	}

    	echo json_encode($resp);
    }
	}

	function getProveedor()
	{
		$id = $this->input->get('id');
		$proveedor = $this->modelgeneral->getTableWhereRow('tb_proveedor',['tb_proveedor_id'=>$id]);
		echo json_encode($proveedor);
	}

	function verificaSerie()
	{
		$serie = $this->input->post('serie');
		$producto = $this->input->post('producto');
		$query = $this->db->from('tb_producto_serie')
		//->where('cod_producto',$producto)
		->where('serie_descripcion',$serie)
		->join('tb_producto','tb_producto_serie.cod_producto = tb_producto.cod_producto')
		->get();
		$resp = [];
		if ($query->num_rows()==0) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
			$resp['compra'] = $query->row()->cod_comp;
			$resp['producto'] = $query->row()->nomb_product;
		}
		echo json_encode($resp);
	}

	function agregarCompra()
	{
		
		$data['fecha_comp'] = $this->input->post('fecha');
		$data['documento_comp'] = $this->input->post('documento');
		$data['numdocumento_comp'] = $this->input->post('numDocumento');
		$data['tb_proveedor_id'] = $this->input->post('proveedor');
		$data['cod_almacen'] = $this->input->post('almacen');
		$data['efectivo_comp'] = $this->input->post('efectivo');
		$data['saldo_comp'] = $this->input->post('credito');
		$data['cod_caja'] = $this->input->post('caja');
		$data['pago_comp'] = $this->input->post('pago');
		if (isset($_POST['fecVenc'])) {
			$data['dias_comp'] = $this->input->post('dias');
			$data['fecvenc_comp'] = $this->input->post('fecVenc');
		}

		$insert = $this->modelgeneral->insertRegist('tb_compra',$data);

		$pago['cod_comp'] = $insert;
		$pago['tipo_pago'] =$this->input->post('pago');
		$pago['cod_caja'] = $this->input->post('caja');
		$pago['fecha_pago'] = $this->input->post('fecha');
		$pago['detalle_pago'] = 'PAGO COMPRA:'.$this->input->post('documento').' '.$this->input->post('numDocumento');
		$pago['monto_pago'] = $this->input->post('efectivo');
		$this->modelgeneral->insertRegist('tb_pago',$pago);

		$resp = [];
		if (!is_null($insert)) {
			$total = 0;
			$datos_empresa=$this->modelgeneral->getTableWhereRow('tb_empresa',['cod_empresa'=>1]);
			
			foreach ($_POST['id_prod'] as $key => $value) {
				$producto = $this->modelgeneral->getTableWhereRow('tb_producto',['cod_producto'=>$value]);				
				if(!empty($datos_empresa)){
					if($datos_empresa->MovAlmacenAutomatico=="S"){
						/*Poblamos el detalle para la boleta de ingreso */
						$undmed_prod = $this->modelgeneral->getTableWhereRow('tb_unidades',['cod_unid'=>$producto->cod_unid]);
						$arr_det[$value]['nund']=$_POST['cant_prod'][$key]; 
						$arr_det[$value]['ccod_undmed']=$undmed_prod->abreviatura_unid; 
						$arr_det[$value]['ccod_art']=$value; 
						$arr_det[$value]['cdsc_art']=$producto->nomb_product; 				
						$arr_det[$value]['bind_lote']='N'; 
						$arr_det[$value]['cnro_lote']='';
						/*FIN Poblamos el detalle para la boleta de ingreso */
						/*Poblamos el detalle para la nota de ingreso */
						$arr_detval[$value]['nund']=$_POST['cant_prod'][$key]; 
						$arr_detval[$value]['ccod_undmed']=$undmed_prod->abreviatura_unid; ; 
						$arr_detval[$value]['ccod_art']=$value; 
						$arr_detval[$value]['cdsc_art']=$producto->nomb_product; 
						$arr_detval[$value]['ncosto']=$_POST['prec_prod'][$key]; 
						/*FIN Poblamos el detalle para la nota de ingreso */
					}
				}				
				$precio_unit = $_POST['prec_prod'][$key];
				$detalle['cod_comp'] = $insert;
				$detalle['cod_producto'] = $value;
				$detalle['cant_compdet'] = $_POST['cant_prod'][$key];
				$detalle['precunit_compdet'] = $precio_unit;
				$detalle['igv_compdet'] = (($precio_unit * $_POST['cant_prod'][$key])  / 1.18) * 0.18;
				$detalle['precventa_compdet'] = $precio_unit * $_POST['cant_prod'][$key] - $detalle['igv_compdet'];
				$detalle['subtotal_compdet'] = $precio_unit * $detalle['cant_compdet'];
				$idCompraDetalle = $this->modelgeneral->insertRegist('tb_compra_detalle',$detalle);
				$total += $detalle['subtotal_compdet'];

				/*===================================
				=            SUMAS STOCK            =
				===================================*/
				
				//obtenemos stock actual
				$whereStock = [
					'cod_producto' => $value,
					'cod_almacen' => $data['cod_almacen']
				];
				$prodStock = $this->modelgeneral->getTableWhereRow('tb_producto_stock',$whereStock);

				$ganancia = $producto->prec_venta - $producto->prec_costo;
				$this->db->where('cod_producto',$value)
				->set('prec_costo', $precio_unit)
				->set('prec_venta', $precio_unit + $ganancia)
				->update('tb_producto');

				if (!is_null($prodStock)) {
					//sumamos el stock actual con la cantidad de productos ques se ingresa
					$nuevoStock = $prodStock->stock  + $detalle['cant_compdet'];
					//guardamos el nuevo stock
					$this->modelgeneral->editRegist('tb_producto_stock',$whereStock,['stock'=>$nuevoStock]);
					$this->modelgeneral->editRegist('tb_compra_detalle',['cod_compdet'=> $idCompraDetalle],['historialstock_compdet'=>$nuevoStock]);
				}else{
					$this->modelgeneral->insertRegist('tb_producto_stock',[
						'cod_producto' => $value,
						'cod_almacen' => $data['cod_almacen'],
						'stock' => $detalle['cant_compdet']
					]);
					$this->modelgeneral->editRegist('tb_compra_detalle',['cod_compdet'=> $idCompraDetalle],['historialstock_compdet'=>$detalle['cant_compdet']]);
				}
				/*=====  End of SUMAS STOCK  ======*/
				
				/* ==== AGREGAR SERIES ==== */
				$sumStock = 1;
				if(isset($_POST['series'][$value])){
					foreach ($_POST['series'][$value] as $keySerie => $valueSerie) {
						$dataSerie['cod_producto'] = $value;
						$dataSerie['cod_almacen'] = $data['cod_almacen'];
						$dataSerie['serie_descripcion '] = $valueSerie;
						$dataSerie['cod_comp'] = $insert;
						$dataSerie['histcompstock_serie'] = $prodStock->stock + $sumStock;
						$this->modelgeneral->insertRegist('tb_producto_serie',$dataSerie);
						$sumStock++;
					}
				}
				/* ==== AGREGAR SERIES ==== */

			}
			
			$dataCompra['total_comp'] = $total;
			$dataCompra['igv_comp'] = ($total / 1.18) * 0.18;
			$dataCompra['subtotal_comp'] = $total - $dataCompra['igv_comp'];
			$dataCompra['pendiente_comp'] = $total - $data['efectivo_comp'];
			$this->modelgeneral->editRegist('tb_compra',['cod_comp'=>$insert],$dataCompra);
		if(!empty($datos_empresa)){
			if($datos_empresa->MovAlmacenAutomatico=="S"){
				/*poblamos array para boleta de ingreso*/
				$_SESSION['ALM_Kardex_det']=$arr_det;
				$arr_serie=$this->notaunidad_model->ProxCorrelativoAlmacen(array('tipo'=>'BI','codalm'=>$this->input->post('almacen')));
				if(sizeof($arr_serie)>0){
					$arrboleta['Serie_Nota']=$arr_serie[0]['serie'];		
					$arrboleta['Num_Nota']=($arr_serie[0]['correlativo']+1);
				}
				else{
					$resp['success'] = false;
					echo json_encode($resp);exit(0);
				}
				$arrboleta['Tipo_Nota']='I';
				$arrboleta['Ruc_Cliente']=$this->input->post('rucdni');
				$arrboleta['Fecha_Nota']=date('Y-m-d');
				$arrboleta['Motivo_Recep']='19';
				$arrboleta['obs_Nota']='Ingresado desde modulo de compras';
				$arrboleta['Cod_Almacen']=$this->input->post('almacen');
				if($this->input->post('documento')=="BOLETA ELECTRONICA"){
					$arrboleta['tip_doc_ref']="03";
				}
				else{
					$arrboleta['tip_doc_ref']="01";
				}
				$arrboleta['serie_doc_ref']='';
				$arrboleta['num_doc_ref']=$this->input->post('numDocumento');
				$arrboleta['Estado']='R';
				$arrboleta['usu_reg']=$this->session->cod_usu;
				$arrboleta['Fec_Reg']=date('Y-m-d');
				$dins=$this->notaunidad_model->Insnotaunidad($arrboleta); 
				 if(sizeof($dins)>0){ 
					 if($dins['status']!="1"){
						$resp['success'] = false;	
					 }
					else{
						$this->notaunidad_model->ActualizarCorrelativoAlmacen(array('tipo'=>'BI','codalm'=>$this->input->post('almacen'),'Numero'=>$arrboleta['Num_Nota']));
					}	
				 } 
				 else{ 
					 $result['status']=2; 
					 $result['msg']='PROBLEMAS AL GUARDAR EL REGISTRO'; 
				 } 	 
				/*fin poblamos array para boleta de ingreso*/
				/*poblamos array para nota de ingreso*/
				$_SESSION['ALM_Kardexval_det']=$arr_detval;
				$arr_serie=$this->notaunidad_model->ProxCorrelativoAlmacen(array('tipo'=>'NI','codalm'=>''));
				if(sizeof($arr_serie)>0){
					$arrnota['Serie_Nota']=$arr_serie[0]['serie'];		
					$arrnota['Num_Nota']=($arr_serie[0]['correlativo']+1);
				}
				else{
					$resp['success'] = false;
					echo json_encode($resp);exit(0);
				}
				$arrnota['Tipo_Nota']='I';
				$arrnota['Ruc_Cliente']=$this->input->post('rucdni');
				$arrnota['Fecha_Nota']=date('Y-m-d');
				$arrnota['CodMotivo']='19';
				$arrnota['obs_Nota']='Ingresado desde modulo de compras';
				if($this->input->post('documento')=="BOLETA ELECTRONICA"){
					$arrnota['tip_doc_ref']="03";
				}
				else{
					$arrnota['tip_doc_ref']="01";
				}
				$arrnota['serie_doc_ref']='';
				$arrnota['num_doc_ref']=$this->input->post('numDocumento');
				$arrnota['ccod_mon']='S';
				$arrnota['nt_cambio']='3.50';			 
				$arrnota['Estado']='R';
				$arrnota['usu_reg']=$this->session->cod_usu;								
				$arrnota['Fec_Reg']=date('Y-m-d');
				$dins=$this->notavalorizado_model->Insnotaunidad($arrnota); 
				 if(sizeof($dins)>0){ 
					 if($dins['status']!="1"){
						$resp['success'] = false;	
					 }
					 else{
						$this->notaunidad_model->ActualizarCorrelativoAlmacen(array('tipo'=>'NI','codalm'=>'','Numero'=>$arrnota['Num_Nota']));
					 }
				 } 
				 else{ 
					$resp['success'] = false;
				 } 	 
				/*fin poblamos array para nota de ingreso*/
			}
		}
			$resp['success'] = true;
			$resp['redirect'] = 'administrador/regcompras';
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);

	}

	function editar($id)
	{
	$data['almacenes'] = $this->modelgeneral->getTable('tb_almacen');
  	$data['cajas'] = $this->modelgeneral->getTableWhere('tb_caja',['est_caja'=>1]);
  	$data['compra'] = $this->db->from('tb_compra')
  	->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id')
  	->join('tb_caja','tb_compra.cod_caja = tb_caja.cod_caja','left')
  	->where('tb_compra.cod_comp',$id)
  	->get()->row();
  	$detalle = $this->db->from('tb_compra_detalle')
  	->join('tb_producto','tb_compra_detalle.cod_producto = tb_producto.cod_producto')
  	->join('tb_unidades','tb_unidades.cod_unid = tb_producto.cod_unid')
  	->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca')
  	->where('cod_comp',$id)
		->get()->result();
		foreach ($detalle as $key => $value) {
			$value->series = $this->modelgeneral->getTableWhere('tb_producto_serie',['cod_producto'=>$value->cod_producto,'cod_comp'=>$value->cod_comp]);
		}
		$data['detalle'] = $detalle;
  	$this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('admin/compras/editar',$data);    
    $this->load->view('layouts/footer');
	}

	function editarCompra()
	{
		$data['fecha_comp'] = $this->input->post('fecha');
		$data['documento_comp'] = $this->input->post('documento');
		$data['numdocumento_comp'] = $this->input->post('numDocumento');
		$data['cod_almacen'] = $this->input->post('almacen');
	
		$where['cod_comp'] = $this->input->post('id');
		$insert = $this->modelgeneral->editRegist('tb_compra',$where,$data);

		$resp = [];
		if (!is_null($insert)) {
			$total = 0;
			foreach ($_POST['id_prod'] as $key => $value) {
				$producto = $this->modelgeneral->getTableWhereRow('tb_compra_detalle',['cod_compdet'=>$value]);
				$precio_unit = $_POST['prec_prod'][$key];
				
				
				$detalle['precunit_compdet'] = $precio_unit;
				$detalle['igv_compdet'] = (($precio_unit * $_POST['cant_prod'][$key])  * 1.18) * 0.18;
				$detalle['precventa_compdet'] = $precio_unit * $producto->cant_compdet - $detalle['igv_compdet'];
				$detalle['subtotal_compdet'] = $precio_unit * $producto->cant_compdet;
				$whereDetalle['cod_compdet'] = $value;
				$this->modelgeneral->editRegist('tb_compra_detalle',$whereDetalle,$detalle);
				$total += $detalle['subtotal_compdet'];
			}
			$this->modelgeneral->editRegist('tb_compra',['cod_comp'=>$this->input->post('id')],['total_comp'=>$total]);
			$resp['success'] = true;
		
			$resp['redirect'] = 'administrador/regcompras';
	
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	function anularCompra()
  {
    $data['estado_comp'] = 2; //ANULAR	
    $where['cod_comp'] = $this->input->get('id');  
		
		/*=======================================
		=            DESCONTAR STOCK            =
		=======================================*/
		$compra = $this->modelgeneral->getTableWhereRow('tb_compra',$where);
		$detalle = $this->modelgeneral->getTableWhere('tb_compra_detalle',$where);

		foreach ($detalle as $d) {
			$whereStock['cod_producto'] = $d->cod_producto;
			$whereStock['cod_almacen'] = $compra->cod_almacen;
			$productoStock = $this->modelgeneral->getTableWhereRow('tb_producto_stock',$whereStock);


			$nuevoStock = $productoStock->stock - $d->cant_compdet;
			$edit = $this->modelgeneral->editRegist('tb_producto_stock',$whereStock,['stock'=>$nuevoStock]);

			
			$this->db->where('cod_comp',$this->input->get('id'));		
			// $this->db->where('cod_producto',$producto);
		 //  $this->db->where('cod_almacen',$almacen);
		  	$this->db->delete('tb_producto_serie');

		}
		/*=====  End of DESCONTAR STOCK  ======*/
		
		$edit = $this->modelgeneral->editRegist('tb_compra',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}
		echo json_encode($resp);
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
	  $data['datos'] = $this->getComprasReporte();
		$html = $this->load->view('admin/compras/reporte_pdf',$data,TRUE);
		$css = file_get_contents(APP_PATH.'assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('Compras','I');
  }

  function reporteExcel()
  {
  	$data['datos'] = $this->getComprasReporte();
  	$this->load->view('admin/compras/reporte_excel',$data);
  }

  function getComprasReporte()
  {
  	$this->db->from('tb_compra');
	  $this->db->join('tb_almacen',' tb_compra.cod_almacen = tb_almacen.cod_almacen');
	  $this->db->join('tb_proveedor','tb_compra.tb_proveedor_id = tb_proveedor.tb_proveedor_id');
	  $this->db->where('estado_comp',$this->input->get('estado'));
	  $this->db->where('fecha_comp >= ',$this->input->get('desde'));
	  $this->db->where('fecha_comp <=',$this->input->get('hasta'));
	  if ($this->input->get('proveedor')!='') {
	    $this->db->like('tb_proveedor_nom',$data['proveedor']);
	  }
	  if ($this->input->get('almacen')!='') {
	    $this->db->where('tb_compra.cod_almacen',$data['almacen']);
	  }
	  $query = $this->db->get()->result();
	  foreach ($query as $q) {
	  	$q->detalle = $this->compras_model->getDetalle($q->cod_comp);
	  }
	  return $query;
  }



  function imprimirCompra($id)
	{
		$this->mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4'
		]);
	
		$data['compras'] = $this->compras_model->getImpresionCompras($id);
		$data['empresa'] = $this->empresa_model->getEmpresa($data);
		$html = $this->load->view('admin/compras/imprimircompra',$data,TRUE);
		$css  = file_get_contents(APP_PATH.'assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('assets/compras.pdf','I');
	}
}

/* End of file Regcompras.php */
/* Location: ./application/controllers/administrador/Regcompras.php */
