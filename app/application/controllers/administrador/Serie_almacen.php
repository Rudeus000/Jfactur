<?php
defined('BASEPATH') or exit('No direct script access allowed');
	 class Serie_almacen extends CI_Controller{
		public function __construct(){
			parent::__construct();
			if(!$this->session->userdata("login")){
				redirect(base_url());
			}
			$this->load->model('serie_almacen_model');
		}	
		public function index(){
			$this->load->view('layouts/header');
			$this->load->view('layouts/aside');			
			$this->load->view('admin/serie_almacen/frmlistaseriealmacen');
			$this->load->view('layouts/footer');
		 }
		 public function Rmvserie_almacen(){
			 $arr['vp_id']=trim($this->input->post('vp_id'));
			 $result=NULL; 
			 $result['CodMsg']=0; 						 
			 $dins=$this->serie_almacen_model->Rmvserie_almacen($arr); 
			$result['CodMsg']=1; 
			 echo json_encode($result);
		 }
		 public function fillallseriealmacen(){
			$res_cou = $this->serie_almacen_model->fillallseriealmacen();
			$result=array();
			if(count($res_cou)>0){
				 $result['status']=1;
				 $result['data']=$res_cou;
			}
			echo  json_encode($result);
		}
		 public function Findserie_almacen($id){
			$dfill=$this->serie_almacen_model->Findserie_almacen(array('IDAlmacenSerie'=>$id));
			foreach($dfill as $ind=>$val){ 
				 foreach($val as $pk=>$value){ 
					 $entity[$pk]=$value; 
				 } 
			}
			//var_export($entity);exit(0);
			$data['entity']=$entity;
			$data['id']=$id;
			$_SESSION['entityid']=$id;
			$data['update']=1;
			$this->load->view('layouts/header');
			$this->load->view('layouts/aside');			
			$this->load->view('admin/serie_almacen/FrmMantSeriaAlmacen',$data);
			$this->load->view('layouts/footer');
		 }
		 public function FillAllAlmacen(){
			 $result['status']=0; 
			 $dfill=$this->serie_almacen_model->FillAllAlmacen(); 
			 if(sizeof($dfill)>0){ 
				 $result['data']=$dfill; 
				 $result['status']=1; 
			 } 
			 echo json_encode($result); 
		 }
		 public function Saveserie_almacen(){
			 $arr['cod_almacen']=trim($this->input->post('vp_cod_almacen'));
			 $arr['TipoDoc']=trim($this->input->post('vp_tipodoc'));
			 $arr['Serie']=trim($this->input->post('vp_serie'));
			 $arr['Correlativo']=trim($this->input->post('vp_correlativo'));
			 $arr['estado']="R";
			 $result=NULL; 
			 $result['status']=0; 						 
			 $dins=$this->serie_almacen_model->Insserie_almacen($arr); 
				 if(sizeof($dins)>0){ 
					 $result=$dins; 
				 }
				 else{
					 $result['status']=2; 
					 $result['msg']='PROBLEMAS AL GUARDAR EL REGISTRO'; 
				 }
			 echo json_encode($result);
		 }/*
		 public function Editserie_almacen(){
			if(ValidarSesion()){
			$arr['vp_ID']=trim($this->request->getPost('vp_id'));
			 $arr['vp_cod_almacen']=trim($this->request->getPost('vp_cod_almacen'));
			 $arr['vp_TipoDoc']=trim($this->request->getPost('vp_tipodoc'));
			 $arr['vp_Serie']=trim($this->request->getPost('vp_serie'));
			 $arr['vp_Correlativo']=trim($this->request->getPost('vp_correlativo'));
			 $$arr['vp_IDEmpresa']=IDEmpresaSeleccionada();
			 $$arr['vp_UsuReg']=IDUsuarioSesion();
			$result=json_decode(RequestServer(URL_SERVICES."/serie_almacenEditar",$arr,TokenSesion()));
			if(!empty($result)){
			$thearray = get_object_vars( $result );			echo json_encode($thearray);
			}
			else{
			$result['CodMsg']=2;
			$result['Msg']='Problemas al realizar la consulta!';
			echo json_encode($result);
			exit(0);
			}
			}
			else{
			$result['CodMsg']=2;
			$result['Msg']='SESSION EXPIRADA, VUELVA A INICIAR!';
			echo json_encode($result);
			exit(0);
			}
		 }*/
		 public function New_serie_almacen(){
			unset($_SESSION['entityid']);
			$data['update']=0;
			$this->load->view('layouts/header');
			$this->load->view('layouts/aside');			
			$this->load->view('admin/serie_almacen/FrmMantSeriaAlmacen',$data);
			$this->load->view('layouts/footer');
		 }
	}
?>