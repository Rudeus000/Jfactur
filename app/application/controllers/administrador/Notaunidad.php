<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notaunidad extends CI_Controller {

  private $permisos;
  public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Lima");
		/*if(!$this->session->userdata("login")){
				redirect(base_url());
		}
		$this->load->model('modelgeneral');
		$this->load->model('confempresa_model');*/
		$this->load->model('notaunidad_model');
		$this->load->model('kardex_model');

	}
	public function index()
	{
		//$data['permisos'] =$this->permisos;
		$this->load->helper('url');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/BoletaAlmacen/FrmListaNotaUnidad');
		$this->load->view('layouts/footer');
	}
	public function frmcierreund()
	{
		//$data['permisos'] =$this->permisos;
		$this->load->helper('url');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/almacen/FrmCierreUnd');
		$this->load->view('layouts/footer');
	}
	public function fillkardexunid(){
		$CodProd = $this->input->post('CodProd');
		$almacen = $this->input->post('almacen');
		$fecha = $this->input->post('fecha');
		$anio = explode('-', $fecha)[0];
		$mes = explode('-', $fecha)[1];
		$res_cou = $this->kardex_model->KardexUndProd(array('CodProd'=>$CodProd,'CodAlmacen'=>$almacen,'anio'=>$anio,'mes'=>$mes));
		$result=array();
		if(count($res_cou)>0){
			 $result['status']=1;
			 $result['data']=$res_cou;
			 $_SESSION['data_kardex']=$res_cou;
		}
		echo  json_encode($result);
	}
	public function getProductoBusqueda(){
		$producto = $this->input->get('producto');		
		$res_cou = $this->kardex_model->ListProducts(array('producto'=>$producto));		
		echo  json_encode($res_cou);
	}
	public function ListSearchEntities(){
		$entidad = $this->input->get('entidad');		
		$res_cou = $this->kardex_model->ListEntities(array('nombre'=>$entidad));		
		echo  json_encode($res_cou);
	}
				
	public function ExportKdxUnd($fecha){
		$data['datos'] = $_SESSION['data_kardex'];
		$data['fecha'] = $fecha;
		$this->load->view('admin/almacen/reportedekardexund',$data);
	}
	public function reportePdf($id_boleta)
	{
			$this->mpdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8',
				'format' => 'A4',
				'margin_left' => 10,
				'margin_right' => 10,
				'margin_top' => 10,
				'margin_bottom' => 10,
				'margin_header' => 10,
				'margin_footer' => 10
			]);	
			$data['datos'] =  $this->kardex_model->ListBoletId(array('idboleta'=>$id_boleta));	
			$html = $this->load->view('admin/BoletaAlmacen/BoletaAlmacenPDF',$data,TRUE);
			$css = file_get_contents(APP_PATH.'assets/styles_pdf.css');
			$this->mpdf->SetTitle('Boleta Almacen');
			$this->mpdf->writeHTML($css,1);
			$this->mpdf->writeHTML($html,2);
			$this->mpdf->Output('BoletaAlmacen','I');
	}	
	 public function FillAllMotivoRecep(){
		$result['status']=0; 
		 $dfill=$this->notaunidad_model->FillAllMotivoRecep(); 
		 if(sizeof($dfill)>0){ 
			 $result['data']=$dfill; 
			 $result['status']=1; 
		 } 
		 echo json_encode($result); 
	 }
	 public function FindDocRefNum(){
		$result=NULL; 
		$result['status']=0; 
		$MotivoRecepcion=trim($this->input->post('vp_motivo_recep'));
		$TipoDocRef=trim($this->input->post('vp_tip_doc_ref'));
		$SerieDocRef=trim($this->input->post('vp_serie_doc_ref'));
		$NumDocRef=trim($this->input->post('vp_num_doc_ref'));
		$numruc=trim($this->input->post('vp_num_ruc'));
		
		$numdoc="";
		$dfill=NULL;		
		$dfill=$this->notaunidad_model->FindDocRefNum(array('numruc'=>$numruc,'TipoDocRef'=>$TipoDocRef,'seriedoc'=>$SerieDocRef,'numdoc'=>$NumDocRef,'MotivoRecepcion'=>$MotivoRecepcion)); 			 
		$cod_almacen=''; 
		$fecha_emision=''; 
		 if(sizeof($dfill)>0){
			$cod_almacen=$dfill[0]['cod_almacen']; 
			$fecha_emision=$dfill[0]['fecha_emision']; 
			foreach($dfill as $ind=>$val){
				$key=$val['nomb_tipunidad']."_".$val['cod_producto']."_";
				$arr[$key]['nund']=trim($val['cantidad']);
				$arr[$key]['ccod_undmed']=trim($val['nomb_tipunidad']);
				if(trim($val['padre'])!=""){
					$arr[$key]['ccod_art']=trim($val['padre']);
				}
				else{
					$arr[$key]['ccod_art']=trim($val['cod_producto']);
					$val['padre']=trim($val['cod_producto']);
				}
				
				$arr[$key]['cdsc_art']=trim($val['nomb_product']);
				$arr[$key]['bind_lote']="N";
				$arr[$key]['cnro_lote']="";
				$dfill_series=$this->notaunidad_model->FindDocRefNumSeries(array('cod_producto'=>$val['padre'],'cod_vent'=>$val['cod_vent'],'cod_motivo'=>$MotivoRecepcion)); 			 
				//var_export($dfill_series);
				$arr_series=array();
				if(sizeof($dfill_series)>0){
					foreach($dfill_series as $ind_det=>$val_det){
						$arr_series[$val_det["serie_descripcion"]]=$val_det["serie_descripcion"];
					}
				}
				$arr[$key]['series']=$arr_series;
			}
			$result['status']=1;
			$_SESSION['ALM_Kardex_det']=$arr;
			$result['data']=$arr;
			$result['cod_almacen']=$cod_almacen;
			$result['fecha_emision']=$fecha_emision;
		 }
		 echo json_encode($result);
	 }
		 public function Rmvnotaunidad(){
			 $result=NULL; 
			 $result['status']=0; 
			 if(!$this->datosuser_model->ValidaSession()){
			 $result['status']=2; 
			 $result['msg']='SESSION EXPIRADA, VUELVA A INICIAR!'; 
			 echo json_encode($result); 
				 exit(0); 
			 }
			 $arr['vp_id']=trim($this->input->post('vp_id')); 
			 $drmv=$this->notaunidad_model->Rmvnotaunidad($arr); 
			 if($drmv[0]['status']==1){ 
				 $result['status']=1; 
			 } 
			 echo json_encode($result); 
		 } 
		public function getSeriesProducto()
		{
			$producto = $this->input->get('producto');
			$almacen = $this->input->get('almacen');
			$query = $this->db->from('tb_producto_serie')
			->select('serie_descripcion as id, serie_descripcion as text')
			->where('cod_producto',$producto)
			->where('cod_almacen',$almacen)
			->where('serie_estado','D')
			->get()->result();

			header('content-type: application/json; charset=utf-8');
			echo json_encode($query);
		}
		 public function fillallnotaunidad(){
			 $result['status']=0;
			 /*if(!$this->datosuser_model->ValidaSession()){
			 $result['status']=2; 
			 $result['msg']='SESSION EXPIRADA, VUELVA A INICIAR!'; 
			 echo json_encode($result); 
				 exit(0); 
			 }*/
			 $arr['fecha_notad']=trim($this->input->post('vp_fecha_notad'));
			 $arr['fecha_notah']=trim($this->input->post('vp_fecha_notah'));
			 $arr['motivo_recep']=trim($this->input->post('vp_motivo_recep'));
			 $arr['tipo_nota']=trim($this->input->post('vp_tipo_nota'));
			 $res_cou = $this->notaunidad_model->fillallnotaunidad($arr);
			if(count($res_cou)>0){
				 $result['status']=1;
				 $result['data']=$res_cou;
			}
			echo  json_encode($result);
		}
		 public function Findnotaunidad($id){
			 /*if(!$this->datosuser_model->ValidaSession()){
				 echo 'SESSION EXPIRADA, VUELVA A INICIAR!'; 
				 exit(0); 
			 }*/
			 $arr['vp_id']=$id; 
			 $dfill=$this->notaunidad_model->Findnotaunidad($arr); 
			 foreach($dfill as $ind=>$val){ 
				 foreach($val as $pk=>$value){ 
					 $entity[$pk]=$value; 
				 } 
			 }
			 $data['entity']=$entity; 
			 $data['id']=$id; 
			 $dfilldet=$this->notaunidad_model->FinddetALM_Kardex($arr); 
			 unset($_SESSION['ALM_Kardex_det']); 
			 $arr_sesion=NULL; 
			 foreach($dfilldet as $ind=>$val){ 
				 $key=$val['ccod_undmed']."_".$val['ccod_art']."_".$val['cdsc_art']."_".$val['bind_serie']."_".$val['cnro_lote'];
				 
				 $arr_sesion[$key]['nund']=$val['nund']; 
				 $arr_sesion[$key]['ccod_undmed']=$val['ccod_undmed']; 
				 $arr_sesion[$key]['ccod_art']=$val['ccod_art']; 
				 $arr_sesion[$key]['cdsc_art']=$val['cdsc_art']; 
				
				 $arr_sesion[$key]['bind_lote']=$val['bind_serie']; 
				 $arr_sesion[$key]['cnro_lote']=$val['cnro_lote']; 
				 
			 } 
			 $_SESSION['ALM_Kardex_det']=$arr_sesion; 
			 $_SESSION['notaunidadid']=$id; 
			 $data['update']=1; 
			 //$this->load->view('NotaUnidad/FrmMntNotaUnid',$data); 
			 $this->load->helper('url');
			$this->load->view('layouts/header');
			$this->load->view('layouts/aside');
			$this->load->view('admin/BoletaAlmacen/FrmMntNotaUnid',$data);
			$this->load->view('layouts/footer');
		 }
		 public function ListarSerieNotaUnid($codAlmacen){			
		 $result['status']=0; 
			 $dfill=$this->notaunidad_model->ListarSerieNotaUnid(array('codAlmacen'=>$codAlmacen)); 
			 if(sizeof($dfill)>0){ 
				 $result['data']=$dfill; 
				 $result['status']=1; 
			 } 
			 echo json_encode($result); 
		 }
		 public function ListarSerieNotaVal(){			
		 $result['status']=0; 
			 $dfill=$this->notaunidad_model->ListarSerieNotaVal(); 
			 if(sizeof($dfill)>0){ 
				 $result['data']=$dfill; 
				 $result['status']=1; 
			 } 
			 echo json_encode($result); 
		 }
		 
		public function ProxCorrelativoUnidad(){			
			$result['status']=0; 
			$codalm=trim($this->input->post('vp_almacen'));
			$seriealm=trim($this->input->post('vp_serie'));
			 $dfill=$this->notaunidad_model->ProxCorrelativoUnidad(array('codalm'=>$codalm,'seriealm'=>$seriealm)); 
			 if(sizeof($dfill)>0){ 
				 $result['prox']=($dfill[0]['ultimo']+1); 
				 $result['status']=1; 
			 }
			 echo json_encode($result); 
		}
		public function ProxCorrelativoVal(){			
			$result['status']=0; 
			$seriealm=trim($this->input->post('vp_serie'));
			 $dfill=$this->notaunidad_model->ProxCorrelativoVal(array('seriealm'=>$seriealm)); 
			 if(sizeof($dfill)>0){ 
				 $result['prox']=($dfill[0]['ultimo']+1); 
				 $result['status']=1; 
			 }
			 echo json_encode($result); 
		}
		
		 public function ListarAlmacenes(){
			 /*if(!$this->datosuser_model->ValidaSession()){
				 echo 'SESSION EXPIRADA, VUELVA A INICIAR!'; 
				 exit(0); 
			 }*/
		 $result['status']=0; 
			 $dfill=$this->notaunidad_model->ListarAlmacenes(); 
			 if(sizeof($dfill)>0){ 
				 $result['data']=$dfill; 
				 $result['status']=1; 
			 } 
			 echo json_encode($result); 
		 }
		 public function ListarTipoDocumento(){			 
		 $result['status']=0; 
			 $dfill=$this->notaunidad_model->ListarTipoDocumento(); 
			 if(sizeof($dfill)>0){ 
				 $result['data']=$dfill; 
				 $result['status']=1; 
			 } 
			 echo json_encode($result); 
		 }
		 public function Adddet_ALM_Kardex(){
			$result=NULL; 
			$result['status']=0; 
			$seriesd=$this->input->post('vp_serie');
			$series_arr=array();
			$cant=trim($this->input->post('vp_nund'));
				
			if($seriesd!=""){
				 $dataseries=explode("&",$seriesd);
				 foreach ($dataseries as $x) {
					$series=explode("=",$x);
					$series_arr[$series[1]]=$series[1];
				}
			}
			$total_series=0;
			if(trim($this->input->post('vp_bind_lote'))=="S"){
				$seriesarr=$this->input->post('vp_cnro_lote');
				if(is_array($seriesarr)){
					foreach ($seriesarr as $x) {
						$series_arr[$x]=$x;
						$total_series=$total_series+1;
					}
				}
				else{
					$series_arr[$this->input->post('vp_cnro_lote')]=$this->input->post('vp_cnro_lote');
					$total_series=$total_series+1;
				}
				
				$cant=$total_series;
			}
			 $key=trim($this->input->post('vp_ccod_undmed'))."_".trim($this->input->post('vp_ccod_art'));//."_".trim($this->input->post('vp_cnro_lote'));
			 if(!empty($_SESSION['ALM_Kardex_det'])){
				 $arr=$_SESSION['ALM_Kardex_det'];
			 }
			 $arr[$key]['nund']=$cant;
			 $arr[$key]['ccod_undmed']=trim($this->input->post('vp_ccod_undmed'));
			 $arr[$key]['ccod_art']=trim($this->input->post('vp_ccod_art'));
			 $arr[$key]['cdsc_art']=trim($this->input->post('vp_cdsc_art'));
			 $arr[$key]['bind_lote']=trim($this->input->post('vp_bind_lote'));
			 $arr[$key]['cnro_lote']='';
			 $arr[$key]['series']=$series_arr;
			 $result['status']=1; 
			 $_SESSION['ALM_Kardex_det']=$arr; 
			 $result['data']=$arr; 
			 echo json_encode($result); 
		 }
		 public function Cargadet_ALM_Kardex(){
			 $result=NULL; 
			 $result['status']=0; 
			 /*if(!$this->datosuser_model->ValidaSession()){
			 $result['status']=2; 
			 $result['msg']='SESSION EXPIRADA, VUELVA A INICIAR!'; 
			 echo json_encode($result); 
				 exit(0); 
			 }*/
			 $arr=array();
			 if(!empty($_SESSION['ALM_Kardex_det'])){
				 $result['status']=1; 
				 $arr=$_SESSION['ALM_Kardex_det']; 
			 }
				 $result['data']=$arr; 
			 echo json_encode($result); 
		 }
		 public function RmvDet_ALM_Kardex(){
			 $result=NULL; 
			 $result['status']=0; 
			 /*if(!$this->datosuser_model->ValidaSession()){
			 $result['status']=2; 
			 $result['msg']='SESSION EXPIRADA, VUELVA A INICIAR!'; 
			 echo json_encode($result); 
				 exit(0); 
			 }*/
			 $key=trim($this->input->post('vp_id'));
			 $arr=$_SESSION['ALM_Kardex_det'];
			 if(sizeof($arr)>0){ 
				 if(array_key_exists($key,$arr)){ 
					 unset($arr[$key]); 
				 } 
			 } 
				 $result['status']=1; 
				 $_SESSION['ALM_Kardex_det']=$arr; 
				 $result['data']=$arr; 
			 echo json_encode($result); 
		 }
		 public function RmvDet_ALM_KardexSerie(){
			 $result=NULL; 
			 $result['status']=0; 
			 $key=trim($this->input->post('vp_id'));
			 $serie=trim($this->input->post('vp_serie'));
			 $arr=$_SESSION['ALM_Kardex_det'];
			 if(sizeof($arr)>0){ 
				 if(array_key_exists($key,$arr)){ 
					 unset($arr[$key]['series'][$serie]); 
				 } 
			 } 
				 $result['status']=1; 
				 $_SESSION['ALM_Kardex_det']=$arr; 
				 $result['data']=$arr; 
			 echo json_encode($result); 
		 }
		 public function Savenotaunidad(){
			 $result=NULL; 
			 $result['status']=0; 
			 /*if(!$this->datosuser_model->ValidaSession()){
			 $result['status']=2; 
			 $result['msg']='SESSION EXPIRADA, VUELVA A INICIAR!'; 
			 echo json_encode($result); 
				 exit(0); 
			 }*/
			 $arr_ses=$_SESSION['ALM_Kardex_det']; 
			 if(sizeof($arr_ses)<1){ 
				 $result['status']=2; 
				 $result['msg']='POR FAVOR INGRESAR LOS DETALLES DE ALM_Kardex, PARA CONTINUAR'; 
				 echo json_encode($result); 
				 exit(0); 
			 } 
			 $arr['Serie_Nota']=trim($this->input->post('vp_serie_nota'));
			 $arr['Num_Nota']=trim($this->input->post('vp_num_nota'));
			 $arr['Tipo_Nota']=trim($this->input->post('vp_tipo_nota'));
			 $arr['Ruc_Cliente']=trim($this->input->post('vp_ruc_cliente'));
			 $arr['Fecha_Nota']=trim($this->input->post('vp_fecha_nota'));
			 $arr['Motivo_Recep']=trim($this->input->post('vp_motivo_recep'));
			 $arr['obs_Nota']=trim($this->input->post('vp_obs_nota'));
			 $arr['Cod_Almacen']=trim($this->input->post('vp_cod_almacen'));
			 $arr['tip_doc_ref']=trim($this->input->post('vp_tip_doc_ref'));
			 $arr['serie_doc_ref']=trim($this->input->post('vp_serie_doc_ref'));
			 $arr['num_doc_ref']=trim($this->input->post('vp_num_doc_ref'));
			 $dins=$this->notaunidad_model->Insnotaunidad($arr); 
				 if(sizeof($dins)>0){ 
					 $result=$dins; 
				 } 
				 else{ 
					 $result['status']=2; 
					 $result['msg']='PROBLEMAS AL GUARDAR EL REGISTRO'; 
				 } 
			 echo json_encode($result); 
		 }
		 public function Editnotaunidad(){
			 $result=NULL; 
			 $result['status']=0; 
			 /*if(!$this->datosuser_model->ValidaSession()){
			 $result['status']=2; 
			 $result['msg']='SESSION EXPIRADA, VUELVA A INICIAR!'; 
			 echo json_encode($result); 
				 exit(0); 
			 }*/
			 if(!empty($_SESSION['notaunidadid'])){ 
				 if(!empty($_SESSION['ALM_Kardex_det'])){ 
					 $arr_ses=$_SESSION['ALM_Kardex_det']; 
					 if(sizeof($arr_ses)<1){ 
						 $result['status']=2; 
						 $result['msg']='POR FAVOR INGRESAR LOS DETALLES DE ALM_Kardex, PARA CONTINUAR'; 
						 echo json_encode($result); 
						 exit(0); 
					 } 
				 } 
				 else { 
						 $result['status']=2; 
						 $result['msg']='POR FAVOR INGRESAR LOS DETALLES DE ALM_Kardex, PARA CONTINUAR'; 
						 echo json_encode($result); 
						 exit(0); 
				 } 
				 $arr['vp_id']=$_SESSION['notaunidadid'];
				 //$arr['vp_serie_nota']=trim($this->input->post('vp_serie_nota'));
				 //$arr['vp_num_nota']=trim($this->input->post('vp_num_nota'));
				 //$arr['vp_tipo_nota']=trim($this->input->post('vp_tipo_nota'));
				 $arr['vp_ruc_cliente']=trim($this->input->post('vp_ruc_cliente'));
				 $arr['vp_fecha_nota']=trim($this->input->post('vp_fecha_nota'));
				 $arr['vp_motivo_recep']=trim($this->input->post('vp_motivo_recep'));
				 $arr['vp_obs_nota']=trim($this->input->post('vp_obs_nota'));
				 $arr['vp_cod_almacen']=trim($this->input->post('vp_cod_almacen'));
				 $arr['vp_tip_doc_ref']=trim($this->input->post('vp_tip_doc_ref'));
				 $arr['vp_serie_doc_ref']=trim($this->input->post('vp_serie_doc_ref'));
				 $arr['vp_num_doc_ref']=trim($this->input->post('vp_num_doc_ref'));
					 $dins=$this->notaunidad_model->Editnotaunidad($arr); 
					 if(sizeof($dins)>0){ 
						 $result=$dins; 
					 } 
					 else{ 
						 $result['status']=2; 
						 $result['msg']='PROBLEMAS AL GUARDAR EL REGISTRO'; 
					 } 
				 } 
			 echo json_encode($result); 
		 }
		 public function New_notaunidad(){			
			$data['update']=0; 
			unset($_SESSION['entityid']); 
			unset($_SESSION['ALM_Kardex_det']); 
			 
			 		//$data['permisos'] =$this->permisos;
			$this->load->helper('url');
			$this->load->view('layouts/header');
			$this->load->view('layouts/aside');
			$this->load->view('admin/BoletaAlmacen/FrmMntNotaUnid',$data);
			$this->load->view('layouts/footer');
		 }
	}
?>