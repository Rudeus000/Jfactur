<?php 
@session_start();
class Kardex_model extends CI_Model{
		 function __construct()
		 { 
			 parent::__construct();
		 } 
		public function KardexUndProd($params=NULL){
		//'CodProd'=>$CodProd,'CodAlmacen'=>$almacen,'anio'=>$anio,'mes'=>$mes		
			$query = $this->db->query("SELECT year(DATE_ADD('".$params['anio']."-".$params['mes']."-01', INTERVAL -30 day)) as anio,month(DATE_ADD('".$params['anio']."-".$params['mes']."-01', INTERVAL -30 day)) as mes");
			$arr_mes_ant=$query->result_array();
			//var_export($arr_mes_ant);exit(0);
			$arr_kardex=array();
			if(!empty($arr_mes_ant)){
				$anio_ant=$arr_mes_ant[0]['anio'];
				$mes_ant=$arr_mes_ant[0]['mes'];
				$this->db->select("nund_tot,ccod_art,und_medida,id_alm,nomb_product,nomb_almacen");
				$this->db->from('alm_stkund');
				$this->db->join('tb_producto', 'alm_stkund.ccod_art = tb_producto.cod_producto');
				$this->db->join('tb_almacen', 'alm_stkund.id_alm = tb_almacen.cod_almacen');
				$where = "id_alm='".$params['CodAlmacen']."' and ccod_art='".$params['CodProd']."' and anio=".$anio_ant." and mes=".$mes_ant;			
				$this->db->where($where);
				$query=$this->db->get();
				$arr_saldos=$query->result_array();
				if(sizeof($arr_saldos)>0){
					foreach($arr_saldos as $fila){
						//sacamos el saldo inicial
						$nom_almacen=$fila['nomb_almacen'];
						$cod_art=$fila['ccod_art'];
						$nom_art=$fila['nomb_product'];
						$und_medida=$fila['und_medida'];
						$clave=$cod_art." - ".$nom_art." - ".$und_medida;
						$indice=0;
							
						$saldo_actual=$fila['nund_tot'];
						$arr_kardex[$nom_almacen][$clave][$indice]['fecha']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['boleta']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['referencia']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['nroreferencia']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['operacion']='SALDO ANTERIOR';
						$arr_kardex[$nom_almacen][$clave][$indice]['ingreso']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['salida']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['saldo']=number_format($saldo_actual,3,".",",");//$saldo_actual;
						$indice++;
						//sacamos los movimientos del mes 
						$this->db->select("DATE_FORMAT(ddoc_fch, '%Y-%m-%d') as ddoc_fch,alm_nota.Serie_Nota,alm_nota.Num_Nota,alm_nota.Tipo_Nota,alm_nota.Ruc_Cliente,tb_tipodocumento.nom_tipdocumento,alm_nota.serie_doc_ref,alm_nota.num_doc_ref,alm_motivorecepcion.des_motivo,alm_kardex.nund");
						$this->db->from('alm_kardex');
						$this->db->join('alm_nota', 'alm_nota.Cod_Nota = alm_kardex.cod_nota');
						$this->db->join('alm_motivorecepcion', 'alm_nota.Motivo_Recep = alm_motivorecepcion.cod_motivo');
						//$this->db->join('tb_cliente', 'alm_nota.Ruc_Cliente = tb_cliente.doc_cliente');					
						$this->db->join('tb_tipodocumento', 'alm_nota.tip_doc_ref = tb_tipodocumento.cod_tipdocu');					
						$where = "alm_kardex.ccod_alm='".$fila['id_alm']."' and alm_kardex.ccod_art='".$fila['ccod_art']."' and year(alm_kardex.ddoc_fch)=".$params['anio']." and month(alm_kardex.ddoc_fch)=".$params['mes'];			
						$this->db->where($where);
						$query=$this->db->get();					 
						$rs=$query->result_array();
						foreach($rs as $ind=>$val){
							$arr_kardex[$nom_almacen][$clave][$indice]['fecha']=$val['ddoc_fch'];
							$arr_kardex[$nom_almacen][$clave][$indice]['boleta']=$val['Serie_Nota']."-".$val['Num_Nota'];
							$arr_kardex[$nom_almacen][$clave][$indice]['referencia']=$val['nom_tipdocumento'];
							$arr_kardex[$nom_almacen][$clave][$indice]['nroreferencia']=$val['serie_doc_ref']."-".$val['num_doc_ref'];
							$arr_kardex[$nom_almacen][$clave][$indice]['operacion']=$val['des_motivo'];
							if($val['Tipo_Nota']=="I"){
								$saldo_actual=$saldo_actual+$val['nund'];
								$arr_kardex[$nom_almacen][$clave]['ingreso']=number_format($val['nund'],3,".",",");
								$arr_kardex[$nom_almacen][$clave]['salida']='0.000';
							}
							else{
								$saldo_actual=$saldo_actual-$val['nund'];
								$arr_kardex[$nom_almacen][$clave][$indice]['ingreso']='0.000';
								$arr_kardex[$nom_almacen][$clave][$indice]['salida']=number_format($val['nund'],3,".",",");
							}						
							$arr_kardex[$nom_almacen][$clave][$indice]['saldo']=number_format($saldo_actual,3,".",",");
							$indice++;
						}
					}
				}//en caso no haya saldos iniciales
				else{
						//sacamos los movimientos del mes ya que no tiene cierre del mes pasado
						$this->db->select("alm_kardex.ccod_art,alm_kardex.ccod_undmed,DATE_FORMAT(ddoc_fch, '%Y-%m-%d') as ddoc_fch,alm_nota.Serie_Nota,alm_nota.Num_Nota,alm_nota.Tipo_Nota,alm_nota.Ruc_Cliente,tb_tipodocumento.nom_tipdocumento,alm_nota.serie_doc_ref,alm_nota.num_doc_ref,alm_motivorecepcion.des_motivo,alm_kardex.nund,tb_producto.nomb_product,tb_almacen.nomb_almacen");
						$this->db->from('alm_kardex');
						$this->db->join('alm_nota', 'alm_nota.Cod_Nota = alm_kardex.cod_nota');
						$this->db->join('alm_motivorecepcion', 'alm_nota.Motivo_Recep = alm_motivorecepcion.cod_motivo');
						//$this->db->join('tb_cliente', 'alm_nota.Ruc_Cliente = tb_cliente.doc_cliente');					
						$this->db->join('tb_tipodocumento', 'alm_nota.tip_doc_ref = tb_tipodocumento.cod_tipdocu');	
						$this->db->join('tb_producto', 'alm_kardex.ccod_art = tb_producto.cod_producto');
						$this->db->join('tb_almacen', 'alm_kardex.ccod_alm = tb_almacen.cod_almacen');						
						$where = "alm_kardex.ccod_art='".$params['CodProd']."' and year(alm_kardex.ddoc_fch)=".$params['anio']." and month(alm_kardex.ddoc_fch)=".$params['mes'];			
						$this->db->where($where);
						$query=$this->db->get();					 
						$rs=$query->result_array();
						//var_export($rs);exit(0);
						//sacamos el saldo inicial
						$saldo_actual=0;
						if(!empty($rs)){
							$nom_almacen=$rs[0]['nomb_almacen'];
							$cod_art=$rs[0]['ccod_art'];
							$nom_art=$rs[0]['nomb_product'];
							$und_medida=$rs[0]['ccod_undmed'];
							$clave=$cod_art." - ".$nom_art." - ".$und_medida;
							$indice=0;
							$arr_kardex[$nom_almacen][$clave][$indice]['fecha']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['boleta']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['referencia']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['nroreferencia']='SALDO ANTERIOR';
							$arr_kardex[$nom_almacen][$clave][$indice]['operacion']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['ingreso']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['salida']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['saldo']=number_format($saldo_actual,3,".",",");//$saldo_actual;
							$indice++;
							foreach($rs as $ind=>$val){
								$arr_kardex[$nom_almacen][$clave][$indice]['fecha']=$val['ddoc_fch'];
								$arr_kardex[$nom_almacen][$clave][$indice]['boleta']=$val['Serie_Nota']."-".$val['Num_Nota'];
								$arr_kardex[$nom_almacen][$clave][$indice]['referencia']=$val['nom_tipdocumento'];
								$arr_kardex[$nom_almacen][$clave][$indice]['nroreferencia']=$val['serie_doc_ref']."-".$val['num_doc_ref'];
								$arr_kardex[$nom_almacen][$clave][$indice]['operacion']=$val['des_motivo'];
								if($val['Tipo_Nota']=="I"){
									$saldo_actual=$saldo_actual+$val['nund'];
									$arr_kardex[$nom_almacen][$clave][$indice]['ingreso']=number_format($val['nund'],3,".",",");
									$arr_kardex[$nom_almacen][$clave][$indice]['salida']='0.000';
								}
								else{
									$saldo_actual=$saldo_actual-$val['nund'];
									$arr_kardex[$nom_almacen][$clave][$indice]['ingreso']='0.000';
									$arr_kardex[$nom_almacen][$clave][$indice]['salida']=number_format($val['nund'],3,".",",");
								}						
								$arr_kardex[$nom_almacen][$clave][$indice]['saldo']=number_format($saldo_actual,3,".",",");
								$indice++;
							}
						}
				}
			}
			 return $arr_kardex;
		}
		
		public function ListProducts($params){
			$result = $this->db->from('tb_producto')
			->select('tb_producto.cod_producto as id,nomb_product as nombre,prec_costo as costo,prec_venta as venta,nomb_unid as unidad,tb_producto.cod_unid as codund')
			->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid')
			->where('est_product',1)
			->where_in('typeAssignmentProduct',array('P','N'))
			->like('nomb_product',$params['producto'])
			->get()->result();
			return $result;
		}	
		public function ListEntities($params){
			$query = $this->db->query("select distinct codigo,nombre from (select doc_cliente as codigo,nomb_cliente as nombre from tb_cliente union all select tb_proveedor_doc as codigo,tb_proveedor_nom as nombre from tb_proveedor) as t where t.nombre like '%".$params['nombre']."%'");
			return $query->result();;
		}	
		public function ListBoletId($params){
			$result = $this->db->from('alm_nota')
			->select('alm_nota.Serie_Nota,alm_nota.Num_Nota,alm_nota.Tipo_Nota,tb_almacen.nomb_almacen,alm_nota.Fecha_Nota,tb_cliente.doc_cliente,tb_cliente.nomb_cliente, alm_motivorecepcion.des_motivo,tb_tipodocumento.nom_tipdocumento,alm_nota.serie_doc_ref,alm_nota.num_doc_ref, alm_kardex.ccod_art,alm_kardex.cdsc_art,alm_kardex.ccod_undmed,alm_kardex.nund,alm_kardex.tobs')
			->join('alm_kardex','alm_nota.Cod_Nota=alm_kardex.cod_nota')
			->join('tb_producto','alm_kardex.ccod_art=tb_producto.cod_producto')
			->join('tb_cliente','alm_nota.Ruc_Cliente=tb_cliente.doc_cliente')
			->join('tb_almacen','alm_nota.Cod_Almacen=tb_almacen.cod_almacen')
			->join('tb_tipodocumento','alm_nota.tip_doc_ref=tb_tipodocumento.cod_tipdocu')
			->join('alm_motivorecepcion','alm_nota.Motivo_Recep=alm_motivorecepcion.cod_motivo')			
			->where('alm_nota.Cod_Nota',$params['idboleta'])
			->get()->result();
			return $result;
		}
		public function ListNoteId($params){
			$result = $this->db->from('alm_notaval')
			->select('alm_notaval.ccod_mon,alm_notaval.nt_cambio,alm_notaval.Serie_Nota,alm_notaval.Num_Nota,alm_notaval.Tipo_Nota,alm_notaval.Fecha_Nota,tb_cliente.doc_cliente,tb_cliente.nomb_cliente, alm_motivorecepcion.des_motivo,tb_tipodocumento.nom_tipdocumento,alm_notaval.serie_doc_ref,alm_notaval.num_doc_ref, alm_kardexval.ccod_art,alm_kardexval.cdsc_art,alm_kardexval.ccod_undmed,tb_tipounidad.nomb_tipunidad,alm_kardexval.nund,alm_kardexval.tobs,alm_kardexval.ncosto')
			->join('alm_kardexval','alm_notaval.Cod_Nota=alm_kardexval.cod_nota')
			->join('tb_producto','alm_kardexval.ccod_art=tb_producto.cod_producto')
			->join('tb_tipounidad','tb_producto.cod_unid=tb_tipounidad.cod_tipunidad')
			->join('tb_cliente','alm_notaval.Ruc_Cliente=tb_cliente.doc_cliente')
			//->join('tb_almacen','alm_notaval.Cod_Almacen=tb_almacen.cod_almacen')
			->join('tb_tipodocumento','alm_notaval.tip_doc_ref=tb_tipodocumento.cod_tipdocu')
			->join('alm_motivorecepcion','alm_notaval.CodMotivo=alm_motivorecepcion.cod_motivo')			
			->where('alm_notaval.Cod_Nota',$params['idboleta'])
			->get()->result();
			return $result;
		}
		public function ConsultarKdxUnd($params){
			$codart="";
			if(trim($params['CodProd'])!=""){
				$codart=$params['CodProd'];
			}
			$codalm="";
			if(trim($params['CodAlmacen'])!="00"){
				$codalm=$params['CodAlmacen'];
			}
			$result = $this->db->from('alm_stkund')
			->select('tb_almacen.nomb_almacen,tb_producto.cod_producto,tb_producto.nomb_product,tb_tipounidad.nomb_tipunidad,alm_stkund.nund_tot')
			->join('tb_producto','alm_stkund.ccod_art=tb_producto.cod_producto')
			->join('tb_tipounidad','alm_stkund.und_medida=tb_tipounidad.cod_tipunidad')
			->join('tb_almacen','alm_stkund.id_alm=tb_almacen.cod_almacen')
			->where('alm_stkund.anio',$params['anio'])
			->where('alm_stkund.mes',$params['mes'])
			->like('alm_stkund.id_alm',$codalm)
			->like('alm_stkund.ccod_art',$codart)
			->get()->result_array();
			//echo $this->db->last_query();exit(0);
			return $result;
		}
		
		public function CierreKdxUnd($params=NULL){	
			$arr_resp=NULL;
			$query = $this->db->query("SELECT year(DATE_ADD('".$params['anio']."-".$params['mes']."-01', INTERVAL -30 day)) as anio,month(DATE_ADD('".$params['anio']."-".$params['mes']."-01', INTERVAL -30 day)) as mes");
			$arr_mes_ant=$query->result_array();
			$arr_resp['status']=0;
			if(!empty($arr_mes_ant)){
				$anio_ant=$arr_mes_ant[0]['anio'];
				$mes_ant=$arr_mes_ant[0]['mes'];
				//sacamos los productos de almacen
				$this->db->select("cod_producto,nomb_product,cod_unid");
				$this->db->from('tb_producto');
				if($params['CodProd']!=""){
					$where = "ccod_art='".$params['CodProd']."'";			
					$this->db->where($where);
				}
				$query=$this->db->get();
				$arr_prod=$query->result_array();
				//sacamos los almacenes
				$this->db->select("cod_almacen,nomb_almacen");
				$this->db->from('tb_almacen');
				if($params['CodAlmacen']!="00"){
					$where = "cod_almacen='".$params['CodAlmacen']."'";			
					$this->db->where($where);
				}
				$query=$this->db->get();
				$arr_almacen=$query->result_array();
				foreach($arr_almacen as $ind_alm=>$val_alm){
					foreach($arr_prod as $ind=>$val){	
						//sacamos el saldo del mes anterior 
						$this->db->select("nund_tot,ccod_art,und_medida,id_alm");
						$this->db->from('alm_stkund');
						$where = "alm_stkund.id_alm='".$val_alm['cod_almacen']."' and alm_stkund.ccod_art='".$val['cod_producto']."' and anio=".$anio_ant." and mes=".$mes_ant;			
						$this->db->where($where);
						$query=$this->db->get();
						$arr_saldos=$query->result_array();
						$saldo=0;	
						if(sizeof($arr_saldos)>0){
							$saldo=$arr_saldos[0]['nund_tot'];						
						}
						//sacamos los movimientos del mes 
						$this->db->select("sum(case when ctipo_mov='S' then nund*-1 else nund end) as totalmov");
						$this->db->from('alm_kardex');										
						$where = "alm_kardex.ccod_alm='".$val_alm['cod_almacen']."' and alm_kardex.ccod_art='".$val['cod_producto']."' and year(alm_kardex.ddoc_fch)=".$params['anio']." and month(alm_kardex.ddoc_fch)=".$params['mes'];			
						$this->db->where($where);
						$query=$this->db->get();					 
						$rs=$query->result_array();
						$total_mov_mes=0;
						if(sizeof($rs)>0){
							$total_mov_mes=$rs[0]['totalmov'];
						}
						$total_mes=$saldo+$total_mov_mes;
						//insertamos en la tabla de cierre mensual, si en el caso existe se actualizara
						$this->db->select("nund_tot,ccod_art,und_medida,id_alm");
						$this->db->from('alm_stkund');
						$where = "alm_stkund.id_alm='".$val_alm['cod_almacen']."' and alm_stkund.ccod_art='".$val['cod_producto']."' and anio=".$params['anio']." and mes=".$params['mes'];			
						$this->db->where($where);
						$query=$this->db->get();
						$arr_saldos=$query->result_array();
							
						if(sizeof($arr_saldos)>0){
							//actualizamos	
							$arr_cond['ccod_eje']=$params['anio']; 
							$arr_cond['ccod_per']=$params['anio'].''.$params['mes'];
							$arr_cond['id_alm']=$val_alm['cod_almacen']; 
							$arr_cond['ccod_art']=$val['cod_producto']; 
							$arr_cond['und_medida']=$val['cod_unid']; 
							$arr_cond['mes']=$params['mes']; 
							$arr_cond['anio']=$params['anio']; 
							$arr_det['nund_tot']=$total_mes; 
							$arr_det['nund_totfinmes']=$total_mes; 	
							$arr_det['dfch_modi']=date('Y-%m-%d'); 
							$this->db->update('alm_stkund', $arr_det, $arr_cond);
							/*if ($this->db->_error_message()){
								$msg = $this->db->_error_message(); 
								$num = $this->db->_error_number(); 
								$arr_resp['msg'] = "Error(".$num.") ".$msg;
								return $arr_resp;
							}*/							
						}
						else{
							//insertamos
							$arr_det['ccod_eje']=$params['anio']; 
							$arr_det['ccod_per']=$params['anio'].''.$params['mes'];
							$arr_det['id_alm']=$val_alm['cod_almacen']; 
							$arr_det['ccod_art']=$val['cod_producto']; 
							$arr_det['und_medida']=$val['cod_unid']; 
							$arr_det['nund_tot']=$total_mes; 
							$arr_det['nund_totfinmes']=$total_mes; 
							$arr_det['mes']=$params['mes']; 
							$arr_det['anio']=$params['anio']; 
							$arr_det['dfch_crea']=date('Y-%m-%d'); 		
							$this->db->insert('alm_stkund', $arr_det);
							/*if ($this->db->_error_message()){
								$msg = $this->db->_error_message(); 
								$num = $this->db->_error_number(); 
								$arr_resp['msg'] = "Error(".$num.") ".$msg;
								return $arr_resp;
							}*/
						}
					}//fin productos
				}//fin almacenes
			}
			$arr_resp['msg']="Se realizo el cierre satisfactoriamente";
			$arr_resp['status']=1;
			 return $arr_resp;
		}
		public function KardexValProd($params=NULL){
		//'CodProd'=>$CodProd,'CodAlmacen'=>$almacen,'anio'=>$anio,'mes'=>$mes		
			$query = $this->db->query("SELECT year(DATE_ADD('".$params['anio']."-".$params['mes']."-01', INTERVAL -30 day)) as anio,month(DATE_ADD('".$params['anio']."-".$params['mes']."-01', INTERVAL -30 day)) as mes");
			$arr_mes_ant=$query->result_array();
			//var_export($arr_mes_ant);exit(0);
			$arr_kardex=array();
			if(!empty($arr_mes_ant)){
				$anio_ant=$arr_mes_ant[0]['anio'];
				$mes_ant=$arr_mes_ant[0]['mes'];
				$this->db->select("nund_tot,ccod_art,und_medida,nomb_product,alm_stkval.ncosto");
				$this->db->from('alm_stkval');
				$this->db->join('tb_producto', 'alm_stkval.ccod_art = tb_producto.cod_producto');
				//$this->db->join('tb_almacen', 'alm_stkval.id_alm = tb_almacen.cod_almacen');
				$where = "ccod_art='".$params['CodProd']."' and anio=".$anio_ant." and mes=".$mes_ant;			
				$this->db->where($where);
				$query=$this->db->get();
				$arr_saldos=$query->result_array();
				if(sizeof($arr_saldos)>0){
					foreach($arr_saldos as $fila){
						//sacamos el saldo inicial
						$nom_almacen="";
						$cod_art=$fila['ccod_art'];
						$nom_art=$fila['nomb_product'];
						$und_medida=$fila['und_medida'];
						$clave=$cod_art." - ".$nom_art." - ".$und_medida;
						$indice=0;
							
						$saldo_actual=$fila['nund_tot'];
						$costo_actual=$fila['ncosto'];
						$total_valorizado=$saldo_actual*$costo_actual;
						$arr_kardex[$nom_almacen][$clave][$indice]['fecha']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['boleta']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['referencia']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['nroreferencia']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['operacion']='SALDO ANTERIOR';
						$arr_kardex[$nom_almacen][$clave][$indice]['cantingreso']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['costoingreso']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['totalingreso']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['cantsalida']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['costosalida']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['totalsalida']='';
						$arr_kardex[$nom_almacen][$clave][$indice]['cntsaldo']=number_format($saldo_actual,3,".",",");//$saldo_actual;
						$arr_kardex[$nom_almacen][$clave][$indice]['costosaldo']=number_format($costo_actual,3,".",",");//$saldo_actual;
						$arr_kardex[$nom_almacen][$clave][$indice]['totalsaldo']=number_format(($saldo_actual*$costo_actual),3,".",",");//$saldo_actual;
						$indice++;
						//sacamos los movimientos del mes 
						$this->db->select("DATE_FORMAT(ddoc_fch, '%Y-%m-%d') as ddoc_fch,alm_notaval.Serie_Nota,alm_notaval.Num_Nota,alm_notaval.Tipo_Nota,alm_notaval.Ruc_Cliente,tb_tipodocumento.nom_tipdocumento,alm_notaval.serie_doc_ref,alm_notaval.num_doc_ref,alm_motivorecepcion.des_motivo,alm_kardexval.nund,alm_kardexval.ncosto");
						$this->db->from('alm_kardexval');
						$this->db->join('alm_notaval', 'alm_notaval.Cod_Nota = alm_kardexval.cod_nota');
						$this->db->join('alm_motivorecepcion', 'alm_notaval.codmotivo = alm_motivorecepcion.cod_motivo');
						//$this->db->join('tb_cliente', 'alm_notaval.Ruc_Cliente = tb_cliente.doc_cliente');					
						$this->db->join('tb_tipodocumento', 'alm_notaval.tip_doc_ref = tb_tipodocumento.cod_tipdocu');					
						$where = "alm_kardexval.ccod_art='".$fila['ccod_art']."' and year(alm_kardexval.ddoc_fch)=".$params['anio']." and month(alm_kardexval.ddoc_fch)=".$params['mes'];			
						$this->db->where($where);
						$query=$this->db->get();					 
						$rs=$query->result_array();
						foreach($rs as $ind=>$val){
							$arr_kardex[$nom_almacen][$clave][$indice]['fecha']=$val['ddoc_fch'];
							$arr_kardex[$nom_almacen][$clave][$indice]['boleta']=$val['Serie_Nota']."-".$val['Num_Nota'];
							$arr_kardex[$nom_almacen][$clave][$indice]['referencia']=$val['nom_tipdocumento'];
							$arr_kardex[$nom_almacen][$clave][$indice]['nroreferencia']=$val['serie_doc_ref']."-".$val['num_doc_ref'];
							$arr_kardex[$nom_almacen][$clave][$indice]['operacion']=$val['des_motivo'];
							if($val['Tipo_Nota']=="I"){
								$saldo_actual=$saldo_actual+$val['nund'];
								/*$arr_kardex[$nom_almacen][$clave]['ingreso']=number_format($val['nund'],3,".",",");
								$arr_kardex[$nom_almacen][$clave]['salida']='0.000';*/
								$arr_kardex[$nom_almacen][$clave][$indice]['cantingreso']=number_format($val['nund'],3,".",",");;
								$arr_kardex[$nom_almacen][$clave][$indice]['costoingreso']=number_format($val['ncosto'],3,".",",");;
								$arr_kardex[$nom_almacen][$clave][$indice]['totalingreso']=number_format(($val['nund']*$val['ncosto']),3,".",",");;
								$arr_kardex[$nom_almacen][$clave][$indice]['cantsalida']='';
								$arr_kardex[$nom_almacen][$clave][$indice]['costosalida']='';
								$arr_kardex[$nom_almacen][$clave][$indice]['totalsalida']='';
								$valor_nuevo_ingreso=($val['nund']*$val['ncosto']);
								$total_valorizado=$total_valorizado+$valor_nuevo_ingreso;
								$costo_actual=($total_valorizado/$saldo_actual);
								
								$arr_kardex[$nom_almacen][$clave][$indice]['cntsaldo']=number_format($saldo_actual,3,".",",");//$saldo_actual;
								$arr_kardex[$nom_almacen][$clave][$indice]['costosaldo']=number_format($costo_actual,3,".",",");//$saldo_actual;
								$arr_kardex[$nom_almacen][$clave][$indice]['totalsaldo']=number_format(($saldo_actual*$costo_actual),3,".",",");//$saldo_actual;
							}
							else{
								$saldo_actual=$saldo_actual-$val['nund'];
								/*$arr_kardex[$nom_almacen][$clave][$indice]['ingreso']='0.000';
								$arr_kardex[$nom_almacen][$clave][$indice]['salida']=number_format($val['nund'],3,".",",");*/
								$arr_kardex[$nom_almacen][$clave][$indice]['cantingreso']='';
								$arr_kardex[$nom_almacen][$clave][$indice]['costoingreso']='';
								$arr_kardex[$nom_almacen][$clave][$indice]['totalingreso']='';
								$arr_kardex[$nom_almacen][$clave][$indice]['cantsalida']=number_format($val['nund'],3,".",",");;
								$arr_kardex[$nom_almacen][$clave][$indice]['costosalida']=number_format($val['ncosto'],3,".",",");;
								$arr_kardex[$nom_almacen][$clave][$indice]['totalsalida']=number_format(($val['nund']*$val['ncosto']),3,".",",");;
								
								$total_valorizado=$total_valorizado-($saldo_actual*$costo_actual);
								$arr_kardex[$nom_almacen][$clave][$indice]['cntsaldo']=number_format($saldo_actual,3,".",",");//$saldo_actual;
								$arr_kardex[$nom_almacen][$clave][$indice]['costosaldo']=number_format($costo_actual,3,".",",");//$saldo_actual;
								$arr_kardex[$nom_almacen][$clave][$indice]['totalsaldo']=number_format($total_valorizado,3,".",",");//$saldo_actual;
							}						
							//$arr_kardex[$nom_almacen][$clave][$indice]['saldo']=number_format($saldo_actual,3,".",",");
							$indice++;
						}
					}
				}//en caso no haya saldos iniciales
				else{
						//sacamos los movimientos del mes ya que no tiene cierre del mes pasado
						$this->db->select("alm_kardexval.ccod_art,alm_kardexval.ccod_undmed,DATE_FORMAT(ddoc_fch, '%Y-%m-%d') as ddoc_fch,alm_notaval.Serie_Nota,alm_notaval.Num_Nota,alm_notaval.Tipo_Nota,alm_notaval.Ruc_Cliente,tb_tipodocumento.nom_tipdocumento,alm_notaval.serie_doc_ref,alm_notaval.num_doc_ref,alm_motivorecepcion.des_motivo,alm_kardexval.nund,alm_kardexval.ncosto,tb_producto.nomb_product");
						$this->db->from('alm_kardexval');
						$this->db->join('alm_notaval', 'alm_notaval.Cod_Nota = alm_kardexval.cod_nota');
						$this->db->join('alm_motivorecepcion', 'alm_notaval.codmotivo = alm_motivorecepcion.cod_motivo');
						//$this->db->join('tb_cliente', 'alm_notaval.Ruc_Cliente = tb_cliente.doc_cliente');					
						$this->db->join('tb_tipodocumento', 'alm_notaval.tip_doc_ref = tb_tipodocumento.cod_tipdocu');	
						$this->db->join('tb_producto', 'alm_kardexval.ccod_art = tb_producto.cod_producto');
						//$this->db->join('tb_almacen', 'alm_kardexval.id_alm = tb_almacen.cod_almacen');						
						$where = "alm_kardexval.ccod_art='".$params['CodProd']."' and year(alm_kardexval.ddoc_fch)=".$params['anio']." and month(alm_kardexval.ddoc_fch)=".$params['mes'];			
						$this->db->where($where);
						$query=$this->db->get();					 
						$rs=$query->result_array();
						//var_export($rs);exit(0);
						//sacamos el saldo inicial
						$saldo_actual=0;
						$costo_actual=0;
						$total_valorizado=0;
						if(!empty($rs)){
							$nom_almacen="";//$rs[0]['nomb_almacen'];
							$cod_art=$rs[0]['ccod_art'];
							$nom_art=$rs[0]['nomb_product'];
							$und_medida=$rs[0]['ccod_undmed'];
							$clave=$cod_art." - ".$nom_art." - ".$und_medida;
							$indice=0;
							$arr_kardex[$nom_almacen][$clave][$indice]['fecha']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['boleta']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['referencia']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['nroreferencia']='SALDO ANTERIOR';
							$arr_kardex[$nom_almacen][$clave][$indice]['operacion']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['cantingreso']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['costoingreso']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['totalingreso']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['cantsalida']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['costosalida']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['totalsalida']='';
							$arr_kardex[$nom_almacen][$clave][$indice]['cntsaldo']=number_format($saldo_actual,3,".",",");//$saldo_actual;
							$arr_kardex[$nom_almacen][$clave][$indice]['costosaldo']=number_format($costo_actual,3,".",",");//$saldo_actual;
							$arr_kardex[$nom_almacen][$clave][$indice]['totalsaldo']=number_format(($saldo_actual*$costo_actual),3,".",",");//$saldo_actual;
							$indice++;
							foreach($rs as $ind=>$val){
								$arr_kardex[$nom_almacen][$clave][$indice]['fecha']=$val['ddoc_fch'];
								$arr_kardex[$nom_almacen][$clave][$indice]['boleta']=$val['Serie_Nota']."-".$val['Num_Nota'];
								$arr_kardex[$nom_almacen][$clave][$indice]['referencia']=$val['nom_tipdocumento'];
								$arr_kardex[$nom_almacen][$clave][$indice]['nroreferencia']=$val['serie_doc_ref']."-".$val['num_doc_ref'];
								$arr_kardex[$nom_almacen][$clave][$indice]['operacion']=$val['des_motivo'];
								if($val['Tipo_Nota']=="I"){
									$saldo_actual=$saldo_actual+$val['nund'];
									$arr_kardex[$nom_almacen][$clave][$indice]['cantingreso']=number_format($val['nund'],3,".",",");;
									$arr_kardex[$nom_almacen][$clave][$indice]['costoingreso']=number_format($val['ncosto'],3,".",",");;
									$arr_kardex[$nom_almacen][$clave][$indice]['totalingreso']=number_format(($val['nund']*$val['ncosto']),3,".",",");;
									$arr_kardex[$nom_almacen][$clave][$indice]['cantsalida']='';
									$arr_kardex[$nom_almacen][$clave][$indice]['costosalida']='';
									$arr_kardex[$nom_almacen][$clave][$indice]['totalsalida']='';
									$valor_nuevo_ingreso=($val['nund']*$val['ncosto']);
									$total_valorizado=$total_valorizado+$valor_nuevo_ingreso;
									$costo_actual=($total_valorizado/$saldo_actual);									
									$arr_kardex[$nom_almacen][$clave][$indice]['cntsaldo']=number_format($saldo_actual,3,".",",");//$saldo_actual;
									$arr_kardex[$nom_almacen][$clave][$indice]['costosaldo']=number_format($costo_actual,3,".",",");//$saldo_actual;
									$arr_kardex[$nom_almacen][$clave][$indice]['totalsaldo']=number_format(($saldo_actual*$costo_actual),3,".",",");//$saldo_actual;
									
								}
								else{
									$saldo_actual=$saldo_actual-$val['nund'];
									$arr_kardex[$nom_almacen][$clave][$indice]['cantingreso']='';
									$arr_kardex[$nom_almacen][$clave][$indice]['costoingreso']='';
									$arr_kardex[$nom_almacen][$clave][$indice]['totalingreso']='';
									$arr_kardex[$nom_almacen][$clave][$indice]['cantsalida']=number_format($val['nund'],3,".",",");;
									$arr_kardex[$nom_almacen][$clave][$indice]['costosalida']=number_format($val['ncosto'],3,".",",");;
									$arr_kardex[$nom_almacen][$clave][$indice]['totalsalida']=number_format(($val['nund']*$val['ncosto']),3,".",",");;
									$valor_salida=($val['nund']*$val['ncosto']);
									$total_valorizado=$total_valorizado-$valor_salida;
									$arr_kardex[$nom_almacen][$clave][$indice]['cntsaldo']=number_format($saldo_actual,3,".",",");//$saldo_actual;
									$arr_kardex[$nom_almacen][$clave][$indice]['costosaldo']=number_format($costo_actual,3,".",",");//$saldo_actual;
									$arr_kardex[$nom_almacen][$clave][$indice]['totalsaldo']=number_format($total_valorizado,3,".",",");//$saldo_actual;
								}						
								//$arr_kardex[$nom_almacen][$clave][$indice]['saldo']=number_format($saldo_actual,3,".",",");
								$indice++;
							}
						}
				}
			}
			 return $arr_kardex;
		}
		public function CierreKdxValorizado($params=NULL){	
			$arr_resp=NULL;
			$query = $this->db->query("SELECT year(DATE_ADD('".$params['anio']."-".$params['mes']."-01', INTERVAL -30 day)) as anio,month(DATE_ADD('".$params['anio']."-".$params['mes']."-01', INTERVAL -30 day)) as mes");
			$arr_mes_ant=$query->result_array();
			$arr_resp['status']=0;
			if(!empty($arr_mes_ant)){
				$anio_ant=$arr_mes_ant[0]['anio'];
				$mes_ant=$arr_mes_ant[0]['mes'];
				//sacamos los productos de almacen
				$this->db->select("cod_producto,nomb_product,cod_unid");
				$this->db->from('tb_producto');
				if($params['CodProd']!=""){
					$where = "ccod_art='".$params['CodProd']."'";			
					$this->db->where($where);
				}
				$query=$this->db->get();
				$arr_prod=$query->result_array();
				//sacamos los almacenes
				/*$this->db->select("cod_almacen,nomb_almacen");
				$this->db->from('tb_almacen');
				if($params['CodAlmacen']!="00"){
					$where = "cod_almacen='".$params['CodAlmacen']."'";			
					$this->db->where($where);
				}
				$query=$this->db->get();
				$arr_almacen=$query->result_array();*/
				//foreach($arr_almacen as $ind_alm=>$val_alm){
					foreach($arr_prod as $ind=>$val){	
						//sacamos el saldo del mes anterior 
						$this->db->select("nund_tot,ccod_art,und_medida,ncosto");
						$this->db->from('alm_stkval');
						$where = "alm_stkval.ccod_art='".$val['cod_producto']."' and anio=".$anio_ant." and mes=".$mes_ant;			
						$this->db->where($where);
						$query=$this->db->get();
						$arr_saldos=$query->result_array();
						$saldo_actual=0;
						$costo_actual=0;
						$total_valorizado=0;	
						if(sizeof($arr_saldos)>0){
							$saldo_actual=$fila['nund_tot'];
							$costo_actual=$fila['ncosto'];
							$total_valorizado=$saldo_actual*$costo_actual;						
						}
						//sacamos los movimientos del mes 
						$this->db->select("DATE_FORMAT(ddoc_fch, '%Y-%m-%d') as ddoc_fch,alm_notaval.Serie_Nota,alm_notaval.Num_Nota,alm_notaval.Tipo_Nota,alm_notaval.Ruc_Cliente,tb_tipodocumento.nom_tipdocumento,alm_notaval.serie_doc_ref,alm_notaval.num_doc_ref,alm_motivorecepcion.des_motivo,alm_kardexval.nund,alm_kardexval.ncosto");
						$this->db->from('alm_kardexval');
						$this->db->join('alm_notaval', 'alm_notaval.Cod_Nota = alm_kardexval.cod_nota');
						$this->db->join('alm_motivorecepcion', 'alm_notaval.codmotivo = alm_motivorecepcion.cod_motivo');
						$this->db->join('tb_cliente', 'alm_notaval.Ruc_Cliente = tb_cliente.doc_cliente');					
						$this->db->join('tb_tipodocumento', 'alm_notaval.tip_doc_ref = tb_tipodocumento.cod_tipdocu');					
						$where = "alm_kardexval.ccod_art='".$val['cod_producto']."' and year(alm_kardexval.ddoc_fch)=".$params['anio']." and month(alm_kardexval.ddoc_fch)=".$params['mes'];			
						$this->db->where($where);
						$query=$this->db->get();					 
						$rs=$query->result_array();
						foreach($rs as $ind=>$val_mov){
							if($val_mov['Tipo_Nota']=="I"){
								$saldo_actual=$saldo_actual+$val_mov['nund'];
								$valor_nuevo_ingreso=($val_mov['nund']*$val_mov['ncosto']);
								$total_valorizado=$total_valorizado+$valor_nuevo_ingreso;
								$costo_actual=($total_valorizado/$saldo_actual);
							}
							else{
								$saldo_actual=$saldo_actual-$val_mov['nund'];								
								$total_valorizado=$total_valorizado-($saldo_actual*$costo_actual);
							}
						}
						//insertamos en la tabla de cierre mensual, si en el caso existe se actualizara
						$this->db->select("nund_tot,ccod_art,und_medida");
						$this->db->from('alm_stkval');
						$where = "alm_stkval.ccod_art='".$val['cod_producto']."' and anio=".$params['anio']." and mes=".$params['mes'];			
						$this->db->where($where);
						$query=$this->db->get();
						$arr_saldos=$query->result_array();
							
						if(sizeof($arr_saldos)>0){
							//actualizamos	
							$arr_cond['ccod_eje']=$params['anio']; 
							$arr_cond['ccod_per']=$params['anio'].''.$params['mes'];
							//$arr_cond['id_alm']=$val_alm['cod_almacen']; 
							$arr_cond['ccod_art']=$val['cod_producto']; 
							$arr_cond['und_medida']=$val['cod_unid']; 
							$arr_cond['mes']=$params['mes']; 
							$arr_cond['anio']=$params['anio']; 
							$arr_det['nund_tot']=$saldo_actual; 
							$arr_det['ncosto']=$costo_actual; 	
							$arr_det['dfch_modi']=date('Y-%m-%d'); 
							$this->db->update('alm_stkval', $arr_det, $arr_cond);			
						}
						else{
							//insertamos
							$arr_det['ccod_eje']=$params['anio']; 
							$arr_det['ccod_per']=$params['anio'].''.$params['mes'];
							//$arr_det['id_alm']=$val_alm['cod_almacen']; 
							$arr_det['ccod_art']=$val['cod_producto']; 
							$arr_det['und_medida']=$val['cod_unid']; 
							$arr_det['nund_tot']=$saldo_actual; 
							$arr_det['ncosto']=$costo_actual; 
							$arr_det['mes']=$params['mes']; 
							$arr_det['anio']=$params['anio']; 
							$arr_det['dfch_crea']=date('Y-%m-%d'); 		
							$this->db->insert('alm_stkval', $arr_det);							
						}
					}//fin productos
				//}//fin almacenes
			}
			$arr_resp['msg']="Se realizo el cierre satisfactoriamente";
			$arr_resp['status']=1;
			 return $arr_resp;
		}
		public function consultakardexvalorizado($params){
			$codart="";
			if(trim($params['CodProd'])!=""){
				$codart=$params['CodProd'];
			}
			$codalm="";
			/*if(trim($params['CodAlmacen'])!="00"){
				$codalm=$params['CodAlmacen'];
			}*/
			$result = $this->db->from('alm_stkval')
			->select('tb_producto.cod_producto,tb_producto.nomb_product,tb_tipounidad.nomb_tipunidad,alm_stkval.nund_tot,alm_stkval.ncosto,(alm_stkval.nund_tot*alm_stkval.ncosto) as totalvalorizado')
			->join('tb_producto','alm_stkval.ccod_art=tb_producto.cod_producto')
			->join('tb_tipounidad','alm_stkval.und_medida=tb_tipounidad.cod_tipunidad')
			//->join('tb_almacen','alm_stkval.id_alm=tb_almacen.cod_almacen')
			->where('alm_stkval.anio',$params['anio'])
			->where('alm_stkval.mes',$params['mes'])
			//->like('alm_stkval.id_alm',$codalm)
			->like('alm_stkval.ccod_art',$codart)
			->get()->result_array();
			return $result;
		}
	}
?>