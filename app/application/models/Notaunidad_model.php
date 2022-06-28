<?php 
@session_start();
class Notaunidad_model extends CI_Model{
	 function __construct()
	 { 
		 parent::__construct();
	 } 
	public function fillallnotaunidad($params=NULL){
		 
		$this->db->select("case when alm_nota.Tipo_Nota='I' then 'Ingreso' else 'Salida' end as Tipo_Nota,DATE_FORMAT(alm_nota.Fecha_Nota, '%Y-%m-%d') as Fecha_Nota,alm_nota.Cod_Nota,alm_nota.Serie_Nota,alm_nota.Num_Nota,tb_cliente.nomb_cliente,alm_motivorecepcion.des_motivo,tb_tipodocumento.nom_tipdocumento,alm_nota.serie_doc_ref,alm_nota.num_doc_ref");
		$this->db->from('alm_nota');
		$this->db->join('alm_motivorecepcion', 'alm_nota.Motivo_Recep = alm_motivorecepcion.cod_motivo');
		$this->db->join('tb_cliente', 'alm_nota.Ruc_Cliente = tb_cliente.doc_cliente');
		$this->db->join('tb_almacen', 'alm_nota.Cod_Almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_tipodocumento', 'alm_nota.tip_doc_ref = tb_tipodocumento.cod_tipdocu');
		$where = "alm_nota.Fecha_Nota between '".$params['fecha_notad']."' and '".$params['fecha_notah']."'";
		if($params['motivo_recep']!="00"){
			$where .= "and alm_nota.Motivo_Recep='".$params['motivo_recep']."' ";
		}
		if($params['tipo_nota']!="T"){
			$where .= "and alm_nota.Tipo_Nota='".$params['tipo_nota']."' ";
		}
		$this->db->where($where);
		//echo $this->db->get_compiled_select();
		 $query=$this->db->get();
		 
		 return $query->result_array();
	} 
		 public function FillAllMotivoRecep($params=NULL){			 
			 $this->db->select('cod_motivo, des_motivo');
			 $query = $this->db->get('alm_motivorecepcion');
			 return $query->result_array();
		 }
	 public function Rmvnotaunidad($params=NULL){
		 $store=$this->prepareStore('Eliminar_notaunidad',$params);
		 $consulta = $this->db->query($store);
		 return $consulta->result_array();
	 } 
	 public function ListarSerieNotaUnid($params=NULL){
		 $this->db->select('Serie as codigo,Serie as descripcion');
		 $this->db->from('tb_almacen_serie');
		 $Documentos = array('BI', 'BS');
		 $this->db->where('cod_almacen', $params['codAlmacen']);
		 $this->db->where_in('TipoDoc', $Documentos);
		 $query=$this->db->get();
		 return $query->result_array();
	 }
	 public function ListarSerieNotaVal($params=NULL){
		 $this->db->select('Serie as codigo,Serie as descripcion');
		 $this->db->from('tb_almacen_serie');
		 $Documentos = array('NI', 'NS');
		 $this->db->where_in('TipoDoc', $Documentos);
		 $query=$this->db->get();
		 return $query->result_array();
	 }
	 
	 public function ProxCorrelativoUnidad($params=NULL){
		 $this->db->select('max(correlativo) as ultimo');
		 $this->db->from('tb_almacen_serie');
		 $this->db->where('cod_almacen', $params['codalm']);
		 $this->db->where('Serie', $params['seriealm']);
		 $query=$this->db->get();
		 return $query->result_array();
	 }
	 public function ProxCorrelativoAlmacen($params=NULL){
		 $this->db->select('max(correlativo) as correlativo,min(serie) as serie');
		 $this->db->from('tb_almacen_serie');
		 $this->db->where('TipoDoc', $params['tipo']);
		 if($params['codalm']!=""){
			$this->db->where('cod_almacen', $params['codalm']);
		 }
		 $query=$this->db->get();
		 return $query->result_array();
	 }
	 public function ActualizarCorrelativoAlmacen($params=NULL){
		$arrupd['correlativo']=$params['Numero'];
		if($params['codalm']!=""){
			$this->db->update('tb_almacen_serie', $arrupd,array('TipoDoc' =>$params['tipo'],'cod_almacen'=>$params['codalm']));
		}
		else{
			$this->db->update('tb_almacen_serie', $arrupd,array('TipoDoc' =>$params['tipo']));
		}		
	 }
	 public function ProxCorrelativoVal($params=NULL){
		 $this->db->select('max(correlativo) as ultimo');
		 $this->db->from('tb_almacen_serie');
		 $this->db->where('Serie', $params['seriealm']);
		 $query=$this->db->get();
		 return $query->result_array();
	 }
	 public function ListarAlmacenes($params=NULL){
		$this->db->select('cod_almacen, nomb_almacen');
		 $query = $this->db->get('tb_almacen');
		 return $query->result_array();
	 }
	 public function ListarTipoDocumento($params=NULL){
		 $this->db->select('cod_tipdocu, nom_tipdocumento');
		 $query = $this->db->get('tb_tipodocumento');
		 $this->db->where('est_tipdocum','1');
		 return $query->result_array();
	 }
	 public function Findnotaunidad($params=NULL){
		 $query = $this->db->get_where('alm_nota', array('Cod_Nota' => $params['vp_id']));
		 return $query->result_array();
	 } 
	 public function FinddetALM_Kardex($params=NULL){
		$query = $this->db->get_where('alm_kardex', array('Cod_Nota' => $params['vp_id']));
		 return $query->result_array();
	 } 
	 public function FindDocRefNum($params=NULL){
		$motivo=$params['MotivoRecepcion'];
		switch ($motivo) {
			case 1:
			case 19:
			case 6:
			case 7:
				$numdoc=''; 
				if(trim($params['seriedoc'])!=""){
					$numdoc=$params['seriedoc'].'-'.$params['numdoc'];
				}			
				else{
					$numdoc=$params['numdoc'];
				}
				$result = $this->db->from('tb_compra')
				->select('tb_producto.idTypeAssignmentProduct as padre,tb_compra_detalle.cod_comp as cod_vent,tb_tipounidad.nomb_tipunidad,tb_producto.cod_producto,tb_producto.nomb_product,cant_compdet as cantidad,tb_proveedor.tb_proveedor_doc as codigo_entidad,tb_proveedor.tb_proveedor_nom as des_entidad,tb_compra.cod_almacen,DATE_FORMAT(tb_compra.fecha_comp, \'%Y-%m-%d\') as fecha_emision')
				->join('tb_compra_detalle','tb_compra.cod_comp=tb_compra_detalle.cod_comp')
				->join('tb_producto','tb_compra_detalle.cod_producto=tb_producto.cod_producto')
				->join('tb_tipounidad','tb_producto.cod_unid=tb_tipounidad.cod_tipunidad')	
				->join('tb_proveedor','tb_compra.tb_proveedor_id=tb_proveedor.tb_proveedor_id')	
				->where('tb_compra.numdocumento_comp',$numdoc)
				->where('tb_proveedor.tb_proveedor_doc',$params['numruc'])
				->get()->result_array();
				return $result;
				break;
			case 2:
			case 4:
			case 5:
			case 8:
			case 10:
			case 12:
			case 21:
				$result = $this->db->from('tb_venta')
				->select('tb_producto.idTypeAssignmentProduct as padre,tb_venta_detalle.cod_vent,tb_tipounidad.nomb_tipunidad,tb_producto.cod_producto,tb_producto.nomb_product,tb_venta_detalle.cant_ventdet as cantidad, tb_cliente.doc_cliente as codigo_entidad,tb_cliente.nomb_cliente as des_entidad,tb_talonario.serie,tb_venta.numero_vent,tb_talonario.cod_tipdocu,tb_venta.cod_almacen,DATE_FORMAT(tb_venta.fecha_vent, \'%Y-%m-%d\') as fecha_emision')
				->join('tb_talonario','tb_venta.cod_talonario=tb_talonario.cod_talonario and tb_venta.cod_puntoventa=tb_talonario.cod_puntoventa')
				->join('tb_cliente','tb_venta.id_cliente=tb_cliente.id_cliente')
				->join('tb_venta_detalle','tb_venta.cod_vent=tb_venta_detalle.cod_vent')	
				->join('tb_producto','tb_venta_detalle.cod_producto=tb_producto.cod_producto')	
				->join('tb_tipounidad','tb_producto.cod_unid=tb_tipounidad.cod_tipunidad')	
				->where('tb_talonario.serie',$params['seriedoc'])
				->where('tb_venta.numero_vent',$params['numdoc'])
				->where('tb_talonario.cod_tipdocu',$params['TipoDocRef'])
				->where('tb_cliente.doc_cliente',$params['numruc'])			
				->get()->result_array();
				return $result;
				break;
		}
		//var_export($params);
		 /*if($params['MotivoRecepcion']=="19" || $params['MotivoRecepcion']=="6"){
			$numdoc=''; 
			if(trim($params['seriedoc'])!=""){
				$numdoc=$params['seriedoc'].'-'.$params['numdoc'];
			}			
			else{
				$numdoc=$params['numdoc'];
			}
			$result = $this->db->from('tb_compra')
			->select('tb_producto.idTypeAssignmentProduct as padre,tb_compra_detalle.cod_comp as cod_vent,tb_tipounidad.nomb_tipunidad,tb_producto.cod_producto,tb_producto.nomb_product,cant_compdet as cantidad,tb_proveedor.tb_proveedor_doc as codigo_entidad,tb_proveedor.tb_proveedor_nom as des_entidad,tb_compra.cod_almacen,DATE_FORMAT(tb_compra.fecha_comp, \'%Y-%m-%d\') as fecha_emision')
			->join('tb_compra_detalle','tb_compra.cod_comp=tb_compra_detalle.cod_comp')
			->join('tb_producto','tb_compra_detalle.cod_producto=tb_producto.cod_producto')
			->join('tb_tipounidad','tb_producto.cod_unid=tb_tipounidad.cod_tipunidad')	
			->join('tb_proveedor','tb_compra.tb_proveedor_id=tb_proveedor.tb_proveedor_id')	
			->where('tb_compra.numdocumento_comp',$numdoc)
			->where('tb_proveedor.tb_proveedor_doc',$params['numruc'])
			->get()->result_array();
			//echo $this->db->last_query();exit(0);
			return $result;
		 }
		 else if($params['MotivoRecepcion']=="4"){
			$result = $this->db->from('tb_venta')
			->select('tb_producto.idTypeAssignmentProduct as padre,tb_venta_detalle.cod_vent,tb_tipounidad.nomb_tipunidad,tb_producto.cod_producto,tb_producto.nomb_product,tb_venta_detalle.cant_ventdet as cantidad, tb_cliente.doc_cliente as codigo_entidad,tb_cliente.nomb_cliente as des_entidad,tb_talonario.serie,tb_venta.numero_vent,tb_talonario.cod_tipdocu,tb_venta.cod_almacen,DATE_FORMAT(tb_venta.fecha_vent, \'%Y-%m-%d\') as fecha_emision')
			->join('tb_talonario','tb_venta.cod_talonario=tb_talonario.cod_talonario and tb_venta.cod_puntoventa=tb_talonario.cod_puntoventa')
			->join('tb_cliente','tb_venta.id_cliente=tb_cliente.id_cliente')
			->join('tb_venta_detalle','tb_venta.cod_vent=tb_venta_detalle.cod_vent')	
			->join('tb_producto','tb_venta_detalle.cod_producto=tb_producto.cod_producto')	
			->join('tb_tipounidad','tb_producto.cod_unid=tb_tipounidad.cod_tipunidad')	
			->where('tb_talonario.serie',$params['seriedoc'])
			->where('tb_venta.numero_vent',$params['numdoc'])
			->where('tb_talonario.cod_tipdocu',$params['TipoDocRef'])
			->where('tb_cliente.doc_cliente',$params['numruc'])			
			->get()->result_array();
			//echo $this->db->last_query();exit(0);
			return $result; 
		 }else{
			$result = $this->db->from('tb_venta')
			->select('tb_producto.idTypeAssignmentProduct as padre,tb_venta_detalle.cod_vent,tb_tipounidad.nomb_tipunidad,tb_producto.cod_producto,tb_producto.nomb_product,tb_venta_detalle.cant_ventdet as cantidad, tb_cliente.doc_cliente as codigo_entidad,tb_cliente.nomb_cliente as des_entidad,tb_talonario.serie,tb_venta.numero_vent,tb_talonario.cod_tipdocu,tb_venta.cod_almacen,DATE_FORMAT(tb_venta.fecha_vent, \'%Y-%m-%d\') as fecha_emision')
			->join('tb_talonario','tb_venta.cod_talonario=tb_talonario.cod_talonario and tb_venta.cod_puntoventa=tb_talonario.cod_puntoventa')
			->join('tb_cliente','tb_venta.id_cliente=tb_cliente.id_cliente')
			->join('tb_venta_detalle','tb_venta.cod_vent=tb_venta_detalle.cod_vent')	
			->join('tb_producto','tb_venta_detalle.cod_producto=tb_producto.cod_producto')	
			->join('tb_tipounidad','tb_producto.cod_unid=tb_tipounidad.cod_tipunidad')	
			->where('tb_talonario.serie',$params['seriedoc'])
			->where('tb_venta.numero_vent',$params['numdoc'])
			->where('tb_talonario.cod_tipdocu',$params['TipoDocRef'])
			->where('tb_cliente.doc_cliente',$params['numruc'])			
			->get()->result_array();
			//echo $this->db->last_query();exit(0);
			return $result; 
		 }*/
	 } 
	 public function FindDocRefNumSeries($params=NULL){
				$motivo=$params['cod_motivo'];
				switch ($motivo) {
					case 1:
					case 19:
					case 6:
					case 7:
					$result = $this->db->from('tb_producto_serie')
					->select('serie_descripcion')
					->where('tb_producto_serie.cod_producto',$params['cod_producto'])
					->where('tb_producto_serie.cod_comp',$params['cod_vent'])
					->get()->result_array();
					break;
					case 2:
					case 4:
					case 5:
					case 8:
					case 10:
					case 12:
					case 21:
						$result = $this->db->from('tb_producto_serie')
						->select('serie_descripcion')
						->where('tb_producto_serie.cod_producto',$params['cod_producto'])
						->where('tb_producto_serie.cod_vent',$params['cod_vent'])
						->get()->result_array();
					break;
				}
				return $result;
/*			if($params["cod_motivo"]=="19" || $params["cod_motivo"]=="6"){
				$result = $this->db->from('tb_producto_serie')
			->select('serie_descripcion')
			->where('tb_producto_serie.cod_producto',$params['cod_producto'])
				->where('tb_producto_serie.cod_comp',$params['cod_vent'])
				->get()->result_array();
			}
			else{
				
				$result = $this->db->from('tb_producto_serie')
			->select('serie_descripcion')
			->where('tb_producto_serie.cod_producto',$params['cod_producto'])
				->where('tb_producto_serie.cod_vent',$params['cod_vent'])
				->get()->result_array();
			}
			return $result;	*/	
	 } 
	 
	public function Insnotaunidad($params=NULL,$FlgActualizarStock="S"){
		$this->db->trans_begin();
		$this->db->insert('alm_nota', $params);
		$id=$this->db->insert_id();
		if(!empty($id)){
			if($id!=0){ 
				$codigo_generado=$id; 
				//mantenimiento tabla ALM_Kardex
				$arr=$_SESSION['ALM_Kardex_det']; 
				$arr_det=NULL; 
				foreach($arr as $ind=>$val){
					//$arr_det['id']=$codigo_generado; 
					$arr_det['ccod_eje']=date('Y'); 
					$arr_det['ccod_per']=date('Ymm');
					$arr_det['ccod_alm']=$params['Cod_Almacen']; 
					$arr_det['ctipo_mov']=$params['Tipo_Nota']; 
					$arr_det['ccod_oper_log']=$params['Motivo_Recep']; 
					$arr_det['serie_nota']=$params['Serie_Nota']; 
					$arr_det['Num_Nota']=$params['Num_Nota']; 
					$arr_det['ddoc_fch']=$params['Fecha_Nota']; 
					$arr_det['nund']=$val['nund']; 
					$arr_det['ccod_undmed']=$val['ccod_undmed']; 
					$arr_det['ccod_art']=$val['ccod_art']; 
					$arr_det['cdsc_art']=$val['cdsc_art']; 
					$arr_det['bind_serie']=$val['bind_lote']; 
					$arr_det['cnro_lote']=$val['cnro_lote']; 					
					$arr_det['cod_nota']=$codigo_generado; 
					$this->db->insert('alm_kardex', $arr_det);
					if(!empty($this->db->affected_rows())){
						if($this->db->affected_rows()!=1){ 
							$this->db->trans_rollback(); 
							$result['status']=2; 
							$result['msg']='No se pudo guardar el detalle'; 
							return $result; 
						}
					} 
					else{
						$this->db->trans_rollback(); 
						$result['status']=2; 
						$result['msg']='No se pudo guardar el detalle'; 
						return $result; 
					}
					if($FlgActualizarStock=="S"){
						//actualizamos el stock actual
						$query = $this->db->get_where('tb_producto_stock', array('cod_producto' =>$val['ccod_art'],'cod_almacen'=>$params['Cod_Almacen']));
						$arr_data_prod=$query->result_array();					
						$arr_stk_actual['cod_almacen']=$params['Cod_Almacen'];
						$arr_stk_actual['cod_producto']=$val['ccod_art'];
						//$arr_stk_actual['und_medida']=$val['ccod_undmed'];
						$stock_serie=0;
						if(!empty($arr_data_prod)){
							if(sizeof($arr_data_prod)>0){
								$stock_serie=$arr_data_prod[0]["stock"];
								if($params['Tipo_Nota']=="S"){
									$arr_stk_actual['stock']=$arr_data_prod[0]["stock"]-($val['nund']);
								}	
								else{
									$arr_stk_actual['stock']=$arr_data_prod[0]["stock"]+($val['nund']);
								}	
								$this->db->update('tb_producto_stock', $arr_stk_actual,array('cod_producto' =>$val['ccod_art'],'cod_almacen'=>$params['Cod_Almacen']));							
							}
						}
						else{
							if($params['Tipo_Nota']=="S"){
								$arr_stk_actual['stock']=$val['nund']*-1;
							}	
							else{
								$arr_stk_actual['stock']=$val['nund'];
							}							
							//$arr_stk_actual['cusu_crea']='';
							//$arr_stk_actual['dfch_crea']='';
							$this->db->insert('tb_producto_stock', $arr_stk_actual);
						}
						//vemos si tiene series
						if(is_array($val['series'])){
							if(sizeof($val['series'])>0){
								$sumStock = 1;
								foreach($val['series'] as $ind_serie=>$val_serie){
									if($params['Tipo_Nota']=="S"){
										$whereSeries['cod_almacen'] = $params['Cod_Almacen'];
										$whereSeries['cod_producto'] = $val['ccod_art'];
										$whereSeries['serie_descripcion'] = $val_serie;
										$dataSeries['cod_vent'] = $codigo_generado;
										$dataSeries['serie_estado'] = 'N';
										$dataSerie['FlgNota'] = "S";
										$this->modelgeneral->editRegist('tb_producto_serie',$whereSeries,$dataSeries);
									}
									else{						
										$query_ing = $this->db->get_where('tb_producto_serie', array('cod_producto' =>$val['ccod_art'],'cod_almacen'=>$params['Cod_Almacen'],'serie_descripcion'=>$val_serie));
										$arr_data_ing=$query->result_array();					
										if(sizeof($arr_data_ing)>0){
											$whereSeries['cod_almacen'] = $params['Cod_Almacen'];
											$whereSeries['cod_producto'] = $val['ccod_art'];
											$whereSeries['serie_descripcion'] = $val_serie;
											$dataSeries['cod_vent'] = 0;
											$dataSeries['serie_estado'] = 'D';
											$dataSerie['FlgNota'] = "S";
											$this->modelgeneral->editRegist('tb_producto_serie',$whereSeries,$dataSeries);
										}	
										else{
											$dataSerie['cod_producto'] = $val['ccod_art'];
											$dataSerie['cod_almacen'] = $params['Cod_Almacen'];
											$dataSerie['serie_descripcion '] = $val_serie;
											$dataSerie['cod_comp'] = $codigo_generado;
											$dataSerie['histcompstock_serie'] = $stock_serie+ $sumStock;
											$dataSerie['FlgNota'] = "S";										
											$this->modelgeneral->insertRegist('tb_producto_serie',$dataSerie);
											$sumStock = 1;
										}
										
									}
								}
							}
						}
					}
				} 
			} 
			else{ 
				$this->db->trans_rollback(); 
				$result['status']=0; 
				$result['msg']='Problemas al ejecutar la transacción'; 
				return $result; 
			} 
		} 
		else{ 
			$this->db->trans_rollback(); 
			$result['status']=0; 
			$result['msg']='Problemas al ejecutar la transacción'; 
			return $result; 
		} 
		$this->db->trans_commit(); 
		unset($_SESSION['ALM_Kardex_det']); 
		$result['status']=1; 
		$result['msg']='Se registro la transacción'; 
		return $result; 
	} 
	 public function Editnotaunidad($params=NULL){
		 $db1->trans_begin(); 
		 $store=$this->prepareStore('Editar_ALM_Nota',$params);
		 $consulta = $this->db->query($store);
		 $data=$consulta->result_array();
			 if(!empty($data)){ 
				 if($data[0]['status']==1){ 
					 $arr=$_SESSION['ALM_Kardex_det']; 
					 $arr_det=NULL; 
					 $id=$params['vp_id']; 
						 foreach($arr as $ind=>$val){ 
							  $arr_det['id']=$id; 
							  /*$arr_det['ccod_eje']=$val['ccod_eje']; 
							  $arr_det['ccod_per']=$val['ccod_per']; 
							  $arr_det['ccod_alm']=$val['ccod_alm']; 
							  $arr_det['ctipo_mov']=$val['ctipo_mov']; 
							  $arr_det['ccod_oper_log']=$val['ccod_oper_log']; 
							  $arr_det['cdoc_serie']=$val['cdoc_serie']; 
							  $arr_det['cdoc_nro']=$val['cdoc_nro']; 
							  $arr_det['ddoc_fch']=$val['ddoc_fch'];*/ 
							  $arr_det['nund']=$val['nund']; 
							  $arr_det['ccod_undmed']=$val['ccod_undmed']; 
							  $arr_det['ccod_art']=$val['ccod_art']; 
							  $arr_det['cdsc_art']=$val['cdsc_art']; 
							  /*$arr_det['ccod_mon']=$val['ccod_mon']; 
							  $arr_det['nt_cambio']=$val['nt_cambio']; 
							  $arr_det['ncos_ua_mof']=$val['ncos_ua_mof']; 
							  $arr_det['ncos_t_mof']=$val['ncos_t_mof']; 
							  $arr_det['cref_doc']=$val['cref_doc']; 
							  $arr_det['cref_ser']=$val['cref_ser']; 
							  $arr_det['cref_nro']=$val['cref_nro'];*/ 
							  $arr_det['bind_lote']=$val['bind_lote']; 
							  $arr_det['cnro_lote']=$val['cnro_lote']; 
							  /*$arr_det['cref_doc2']=$val['cref_doc2']; 
							  $arr_det['cref_ser2']=$val['cref_ser2']; 
							  $arr_det['cref_nro2']=$val['cref_nro2']; 
							  $arr_det['cod_nota']=$val['cod_nota']; */
							 $store=$this->prepareStore('Insertar_ALM_Kardex',$arr_det);
							 $consultadet = $this->db->query($store);
							 $datadet=$consultadet->result_array();
								 if(!empty($datadet)){ 
									 if($datadet[0]['status']!=1){ 
										 $this->db->trans_rollback(); 
										 return 0; 
									 } 
								 } 
								 else{ 
									 $this->db->trans_rollback(); 
										 $result['status']=2; 
										 $result['msg']='PROBLEMAS AL EJECUTAR LA TRANSACCION'; 
										 return $result; 
								 } 
						 } 
				 } 
				 else{ 
					 $this->db->trans_rollback(); 
										 $result['status']=2; 
										 $result['msg']='PROBLEMAS AL EJECUTAR LA TRANSACCION'; 
										 return $result; 
				 } 
			 } 
			 else{ 
				 $this->db->trans_rollback(); 
										 $result['status']=2; 
										 $result['msg']='PROBLEMAS AL EJECUTAR LA TRANSACCION'; 
										 return $result; 
			 } 
	 $this->db->trans_commit(); 
	 unset($_SESSION['ALM_Kardex_det']); 
										 $result['status']=1; 
										 $result['msg']='SE ACTUALIZO SATISFACTORIAMENTE'; 
										 return $result; 
	 } 
	}
?>