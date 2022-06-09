<?php 
@session_start();
class Notavalorizado_model extends CI_Model{
	 function __construct()
	 { 
		 parent::__construct();
	 } 
	public function fillallnotavalorizado($params=NULL){		 
		$this->db->select("case when alm_notaval.Tipo_Nota='I' then 'Ingreso' else 'Salida' end as Tipo_Nota,DATE_FORMAT(alm_notaval.Fecha_Nota, '%Y-%m-%d') as Fecha_Nota,alm_notaval.Cod_Nota,alm_notaval.Serie_Nota,alm_notaval.Num_Nota,alm_motivorecepcion.des_motivo,tb_tipodocumento.nom_tipdocumento,alm_notaval.serie_doc_ref,alm_notaval.num_doc_ref");
		$this->db->from('alm_notaval');
		$this->db->join('alm_motivorecepcion', 'alm_notaval.CodMotivo = alm_motivorecepcion.cod_motivo');
		//$this->db->join('tb_cliente', 'alm_notaval.Ruc_Cliente = tb_cliente.doc_cliente');
		//$this->db->join('tb_almacen', 'alm_notaval.Cod_Almacen = tb_almacen.cod_almacen');
		$this->db->join('tb_tipodocumento', 'alm_notaval.tip_doc_ref = tb_tipodocumento.cod_tipdocu');
		$where = "alm_notaval.Fecha_Nota between '".$params['fecha_notad']."' and '".$params['fecha_notah']."'";
		if($params['motivo_recep']!="00"){
			$where .= "and alm_notaval.Motivo_Recep='".$params['motivo_recep']."' ";
		}
		if($params['tipo_nota']!="T"){
			$where .= "and alm_notaval.Tipo_Nota='".$params['tipo_nota']."' ";
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
		 $this->db->select('serie as codigo,serie as descripcion');
		 $this->db->from('tb_talonario');
		 $this->db->join('tb_tipodocumento', 'tb_talonario.cod_tipdocu = tb_tipodocumento.cod_tipdocu');
		 $Documentos = array('BOLETA DE INGRESO', 'BOLETA DE SALIDA');
		 $this->db->where_in('nom_tipdocumento', $Documentos);
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
		 $query = $this->db->get_where('alm_notaval', array('Cod_Nota' => $params['vp_id']));
		 return $query->result_array();
	 } 
	 public function FinddetALM_Kardex($params=NULL){
		$query = $this->db->get_where('alm_kardexval', array('Cod_Nota' => $params['vp_id']));
		 return $query->result_array();
	 } 
	 public function FindDocRefNum($params=NULL){
		 if($params['MotivoRecepcion']=="19"){
			$numdoc=''; 
			if(trim($params['seriedoc'])!=""){
				$numdoc=$params['seriedoc'].'-'.$params['numdoc'];
			}			
			else{
				$numdoc=$params['numdoc'];
			}
			$result = $this->db->from('tb_compra')
			->select('tb_tipounidad.nomb_tipunidad,tb_producto.cod_producto,tb_producto.nomb_product,cant_compdet as cantidad,tb_proveedor.tb_proveedor_doc as codigo_entidad,tb_proveedor.tb_proveedor_nom as des_entidad,tb_compra.cod_almacen,DATE_FORMAT(tb_compra.fecha_comp, \'%Y-%m-%d\') as fecha_emision')
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
			->select('tb_tipounidad.nomb_tipunidad,tb_producto.cod_producto,tb_producto.nomb_product,tb_venta_detalle.cant_ventdet as cantidad, tb_cliente.doc_cliente as codigo_entidad,tb_cliente.nomb_cliente as des_entidad,tb_talonario.serie,tb_venta.numero_vent,tb_talonario.cod_tipdocu,tb_venta.cod_almacen,DATE_FORMAT(tb_venta.fecha_vent, \'%Y-%m-%d\') as fecha_emision')
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
		 }
	 } 
	public function Insnotaunidad($params=NULL){
		$this->db->trans_begin();
		$this->db->insert('alm_notaval', $params);
		$id=$this->db->insert_id();
		if(!empty($id)){
			if($id!=0){ 
				$codigo_generado=$id; 
				//mantenimiento tabla ALM_Kardex
				$arr=$_SESSION['ALM_Kardexval_det']; 
				$arr_det=NULL; 
				foreach($arr as $ind=>$val){
					//$arr_det['id']=$codigo_generado; 
					$arr_det['ccod_eje']=date('Y'); 
					$arr_det['ccod_per']=date('Ymm');
					//$arr_det['id_alm']=$params['Cod_Almacen']; 
					$arr_det['ctipo_mov']=$params['Tipo_Nota']; 
					$arr_det['cod_motivo_recep']=$params['CodMotivo']; 
					$arr_det['serie_nota']=$params['Serie_Nota']; 
					$arr_det['Num_Nota']=$params['Num_Nota']; 
					$arr_det['ddoc_fch']=$params['Fecha_Nota']; 
					$arr_det['nund']=$val['nund']; 
					$arr_det['ccod_undmed']=$val['ccod_undmed']; 
					$arr_det['ccod_art']=$val['ccod_art']; 
					$arr_det['cdsc_art']=$val['cdsc_art']; 
					$arr_det['ncosto']=$val['ncosto']; 
					$arr_det['cod_nota']=$codigo_generado; 
					$arr_det['ccod_mon']=$params['ccod_mon'];
					$arr_det['nt_cambio']=$params['nt_cambio'];
					$this->db->insert('alm_kardexval', $arr_det);
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
					//$costo=$val['ncosto'];
					$costo=$this->CostoPromedio($val['ccod_art'],$val['ccod_undmed'],$params['Tipo_Nota'],$val['ncosto'],date('Y'),date('mm'),$val['nund']);
					
					//actualizamos el stock actual
					$query = $this->db->get_where('alm_stkval_actual', array('ccod_art' =>$val['ccod_art'],'und_medida'=>$val['ccod_undmed']));
					$arr_data_prod=$query->result_array();					
					//$arr_stk_actual['ccod_alm']=$params['Cod_Almacen'];
					$arr_stk_actual['ccod_art']=$val['ccod_art'];
					$arr_stk_actual['und_medida']=$val['ccod_undmed'];					
					$arr_stk_actual['ncosto']=$costo;					
					if(!empty($arr_data_prod)){
						if(sizeof($arr_data_prod)>0){
							//var_export($arr_data_prod);exit(0);
							if($params['Tipo_Nota']=="S"){
								$arr_stk_actual['nund_tot']=$arr_data_prod[0]["nund_tot"]-($val['nund']);
							}	
							else{
								$arr_stk_actual['nund_tot']=$arr_data_prod[0]["nund_tot"]+($val['nund']);
							}	
							//var_export($arr_stk_actual);exit(0);							
							$this->db->update('alm_stkval_actual', $arr_stk_actual,array('ccod_art' =>$val['ccod_art'],'und_medida'=>$val['ccod_undmed']));							
						}
					}
					else{
						if($params['Tipo_Nota']=="S"){
							$arr_stk_actual['nund_tot']=$val['nund']*-1;
						}	
						else{
							$arr_stk_actual['nund_tot']=$val['nund'];
						}
						$arr_stk_actual['ncosto']=$costo;						
						$arr_stk_actual['cusu_crea']='';
						$arr_stk_actual['dfch_crea']='';
						$this->db->insert('alm_stkval_actual', $arr_stk_actual);
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
		unset($_SESSION['ALM_Kardexval_det']); 
		$result['status']=1; 
		$result['msg']='Se registro la transacción'; 
		return $result; 
	} 
	public function CostoPromedio($ccod_art,$ccod_undmed,$Tipo_Nota,$ncosto,$anio,$mes,$cant){
		$costo_actual=0;
		$query = $this->db->get_where('alm_stkval_actual', array('ccod_art' =>$ccod_art,'und_medida'=>$ccod_undmed));
		$arr_data_prod=$query->result_array();									
		if(!empty($arr_data_prod)){
			if(sizeof($arr_data_prod)>0){
				if($Tipo_Nota=="I"){
					$total_valorizado=$arr_data_prod[0]["ncosto"]*$arr_data_prod[0]["nund_tot"];
					$valor_ingreso=$ncosto*$cant;
					$costo_actual=($total_valorizado+$valor_ingreso)/($arr_data_prod[0]["nund_tot"]+$cant);
					/*echo 'total valorizado '.$total_valorizado."<br>";
					echo 'total valor  '.$valor_ingreso."<br>";
					echo 'cantidad '.($arr_data_prod[0]["nund_tot"]+$cant)."<br>";*/
				}else{
					$costo_actual=$arr_data_prod[0]["ncosto"];	
				}
			}
		}
		else{
			$costo_actual=$ncosto;	
		}
		return $costo_actual; 		
	}
}
?>