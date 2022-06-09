<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Notavalorizado extends CI_Controller {

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
		$this->load->model('notavalorizado_model');
		$this->load->model('notaunidad_model');		
		$this->load->model('kardex_model');
	}
	public function index()
	{
		//$data['permisos'] =$this->permisos;
		$this->load->helper('url');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/NotaAlmacen/FrmListaNotaValorizado');
		$this->load->view('layouts/footer');
	}
	public function frmcierreund()
	{
		//$data['permisos'] =$this->permisos;
		$this->load->helper('url');
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/almacen/FrmCierreValorizado');
		$this->load->view('layouts/footer');
	}
	public function fillkardexvalorizado(){
		$CodProd = $this->input->post('CodProd');
		//$almacen = $this->input->post('almacen');
		$fecha = $this->input->post('fecha');
		$anio = explode('-', $fecha)[0];
		$mes = explode('-', $fecha)[1];
		$res_cou = $this->kardex_model->KardexValProd(array('CodProd'=>$CodProd,'anio'=>$anio,'mes'=>$mes));
		$result=array();
		if(count($res_cou)>0){
			 $result['status']=1;
			 $result['data']=$res_cou;
			 $_SESSION['data_kardexval']=$res_cou;
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
				
	public function ExportKdxVal($fecha){
		$data['datos'] = $_SESSION['data_kardexval'];
		$data['fecha'] = $fecha;
		$this->load->view('admin/almacen/reportedekardexval',$data);
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
			$data['datos'] =  $this->kardex_model->ListNoteId(array('idboleta'=>$id_boleta));	
			$data['logo'] =  $this->session->foto;
			$data['empresa'] =  $this->session->empresa;
			//var_export($data);exit(0);
			$html = $this->load->view('admin/NotaAlmacen/NotaAlmacenPDF',$data,TRUE);
			$css = file_get_contents(APP_PATH.'assets/styles_pdf.css');
			$this->mpdf->SetTitle('Nota Almacen');
			$this->mpdf->writeHTML($css,1);
			$this->mpdf->writeHTML($html,2);
			$this->mpdf->Output('NotaAlmacen','I');
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
		$dfill=$this->notavalorizado_model->FindDocRefNum(array('numruc'=>$numruc,'TipoDocRef'=>$TipoDocRef,'seriedoc'=>$SerieDocRef,'numdoc'=>$NumDocRef,'MotivoRecepcion'=>$MotivoRecepcion)); 			 
		$cod_almacen=''; 
		$fecha_emision=''; 
		 if(sizeof($dfill)>0){
			$cod_almacen=$dfill[0]['cod_almacen']; 
			$fecha_emision=$dfill[0]['fecha_emision']; 
			foreach($dfill as $ind=>$val){
				$key=$val['nomb_tipunidad']."_".$val['cod_producto']."_";
				$arr[$key]['nund']=trim($val['cantidad']);
				$arr[$key]['ccod_undmed']=trim($val['nomb_tipunidad']);
				$arr[$key]['ccod_art']=trim($val['cod_producto']);
				$arr[$key]['cdsc_art']=trim($val['nomb_product']);
				$arr[$key]['bind_lote']="N";
				$arr[$key]['cnro_lote']="";
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
			 $drmv=$this->notavalorizado_model->Rmvnotaunidad($arr); 
			 if($drmv[0]['status']==1){ 
				 $result['status']=1; 
			 } 
			 echo json_encode($result); 
		 } 
		 public function fillallnotavalorizado(){
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
			 $res_cou = $this->notavalorizado_model->fillallnotavalorizado($arr);
			if(count($res_cou)>0){
				 $result['status']=1;
				 $result['data']=$res_cou;
			}
			echo  json_encode($result);
		}
		 public function Findnotavalorizado($id){
			 /*if(!$this->datosuser_model->ValidaSession()){
				 echo 'SESSION EXPIRADA, VUELVA A INICIAR!'; 
				 exit(0); 
			 }*/
			 $arr['vp_id']=$id; 
			 $dfill=$this->notavalorizado_model->Findnotavalorizado($arr); 
			 foreach($dfill as $ind=>$val){ 
				 foreach($val as $pk=>$value){ 
					 $entity[$pk]=$value; 
				 } 
			 }
			 $data['entity']=$entity; 
			 $data['id']=$id; 
			 $dfilldet=$this->notavalorizado_model->FinddetALM_Kardex($arr); 
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
		 public function ListarSerieNotaUnid(){
			 /*if(!$this->datosuser_model->ValidaSession()){
				 echo 'SESSION EXPIRADA, VUELVA A INICIAR!'; 
				 exit(0); 
			 }*/
		 $result['status']=0; 
			 $dfill=$this->notavalorizado_model->ListarSerieNotaUnid(); 
			 if(sizeof($dfill)>0){ 
				 $result['data']=$dfill; 
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
			 /*if(!$this->datosuser_model->ValidaSession()){
			 $result['status']=2; 
			 $result['msg']='SESSION EXPIRADA, VUELVA A INICIAR!'; 
			 echo json_encode($result); 
				 exit(0); 
			 }*/
			 $key=trim($this->input->post('vp_ccod_undmed'))."_".trim($this->input->post('vp_ccod_art'))."_".trim($this->input->post('vp_cnro_lote'));
			 if(!empty($_SESSION['ALM_Kardexval_det'])){
				 $arr=$_SESSION['ALM_Kardexval_det'];
			 }
			 
			 $arr[$key]['nund']=trim($this->input->post('vp_nund'));
			 $arr[$key]['ccod_undmed']=trim($this->input->post('vp_ccod_undmed'));
			 $arr[$key]['ccod_art']=trim($this->input->post('vp_ccod_art'));
			 $arr[$key]['cdsc_art']=trim($this->input->post('vp_cdsc_art'));
			 $arr[$key]['ncosto']=trim($this->input->post('vp_costo'));
			 $arr[$key]['nsubtotal']=$arr[$key]['nund']*$arr[$key]['ncosto'];
			 $arr[$key]['bind_lote']=trim($this->input->post('vp_bind_lote'));
			 $arr[$key]['cnro_lote']=trim($this->input->post('vp_cnro_lote'));
			
			 $result['status']=1; 
			 $_SESSION['ALM_Kardexval_det']=$arr; 
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
			 if(!empty($_SESSION['ALM_Kardexval_det'])){
				 $result['status']=1; 
				 $arr=$_SESSION['ALM_Kardexval_det']; 
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
			 $arr=$_SESSION['ALM_Kardexval_det'];
			 if(sizeof($arr)>0){ 
				 if(array_key_exists($key,$arr)){ 
					 unset($arr[$key]); 
				 } 
			 } 
				 $result['status']=1; 
				 $_SESSION['ALM_Kardexval_det']=$arr; 
				 $result['data']=$arr; 
			 echo json_encode($result); 
		 }
		 public function Savenotavalorizado(){
			 $result=NULL; 
			 $result['status']=0; 
			 /*if(!$this->datosuser_model->ValidaSession()){
			 $result['status']=2; 
			 $result['msg']='SESSION EXPIRADA, VUELVA A INICIAR!'; 
			 echo json_encode($result); 
				 exit(0); 
			 }*/
			 $arr_ses=$_SESSION['ALM_Kardexval_det']; 
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
			 $arr['CodMotivo']=trim($this->input->post('vp_motivo_recep'));
			 $arr['obs_Nota']=trim($this->input->post('vp_obs_nota'));
			 //$arr['Cod_Almacen']=trim($this->input->post('vp_cod_almacen'));
			 $arr['tip_doc_ref']=trim($this->input->post('vp_tip_doc_ref'));
			 $arr['serie_doc_ref']=trim($this->input->post('vp_serie_doc_ref'));
			 $arr['num_doc_ref']=trim($this->input->post('vp_num_doc_ref'));
			 $arr['ccod_mon']=trim($this->input->post('vp_cbo_moneda'));
			 $arr['nt_cambio']=trim($this->input->post('vp_tipocambio'));			 
			 $arr['usu_reg']=$this->session->cod_usu;
			 //var_export($arr);exit(0);
			 $dins=$this->notavalorizado_model->Insnotaunidad($arr); 
				 if(sizeof($dins)>0){ 
					 $result=$dins; 
				 } 
				 else{ 
					 $result['status']=2; 
					 $result['msg']='PROBLEMAS AL GUARDAR EL REGISTRO'; 
				 } 
			 echo json_encode($result); 
		 }

		 public function New_notavalorizado(){			
			$data['update']=0; 
			unset($_SESSION['entityid']); 
			unset($_SESSION['ALM_Kardexval_det']); 
			 
			 		//$data['permisos'] =$this->permisos;
			$this->load->helper('url');
			$this->load->view('layouts/header');
			$this->load->view('layouts/aside');
			$this->load->view('admin/NotaAlmacen/FrmMntNotaValorizado',$data);
			$this->load->view('layouts/footer');
		 }
	}
?>