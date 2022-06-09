<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	 class Motivorecepcion extends CI_Controller{
		 private $permisos;
		public function __construct(){
			parent::__construct();
			if(!$this->session->userdata("login")){
				redirect(base_url());
			}
			 $this->load->model('modelgeneral');
			 $this->load->helper('general');
			 //$this->permisos = $this->backend_lib->control();
		 }
		public function index(){
			//$data['permisos'] =$this->permisos;   
			$this->load->view('layouts/header');
			$this->load->view('layouts/aside');
			$this->load->view('admin/motivo_recepcion/FrmLstMotivoRecepcion');    
			$this->load->view('layouts/footer');
		}
		 /*public function Rmvmotivorecepcion(){
			if(ValidarSesion()){
				$arr['vp_ID']=trim($this->request->getPost('vp_id'));
				$arr['vp_IDEmpresa']=IDEmpresaSeleccionada();
				$arr['vp_UsuMod']=IDUsuarioSesion();
				$result=json_decode(RequestServer(URL_SERVICES."/motivorecepcionEliminar",$arr,TokenSesion()));
				if(!empty($result)){
				$thearray = get_object_vars( $result );
				echo json_encode($thearray);				}
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
		 } 
		 public function fillallmotivorecepcion(){
			if(ValidarSesion()){
			$arr=null;
			$arr['IDEmpresa']=IDEmpresaSeleccionada();
			$result=json_decode(RequestServer(URL_SERVICES."/Listar",$arr,TokenSesion()));
			if(!empty($result)){
			$thearray = get_object_vars( $result );
			echo json_encode($thearray);
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
		}
		 public function Findmotivorecepcion($id){
			if(ValidarSesion()){
				$result=json_decode(RequestServer(URL_SERVICES."/motivorecepcionID",array('vp_IDEmpresa'=>IDEmpresaSeleccionada(),'vp_ID'=>$id),TokenSesion()));
				if(!empty($result)){				$thearray = get_object_vars( $result );
				if($thearray["CodMsg"]==1){
				foreach($thearray["Lst"] as $ind=>$val){
				foreach($val as $pk=>$value){
				$entity[$pk]=$value;
				}
				}
				$data['entity']=$entity;				$data['id']=$id;
				$_SESSION['motivorecepcionid']=$id;				$data['update']=1;
				echo view('Frmcabecera');
				echo view('/FrmMantMotivoRecepcion',$data);				echo view('FrmPie');
				}
				else{
				$this->index();
				}
				}
				else{
				$this->index();
				}
				}
				else{
				return view("index");				}
		 }
		 public function Savemotivorecepcion(){
			if(ValidarSesion()){
			 $arr['vp_des_motivo']=trim($this->request->getPost('vp_des_motivo'));
			 $arr['vp_tipo_operacion']=trim($this->request->getPost('vp_tipo_operacion'));
			 $arr['vp_cod_transaccion']=trim($this->request->getPost('vp_cod_transaccion'));
			 $$arr['vp_IDEmpresa']=IDEmpresaSeleccionada();
			 $$arr['vp_Estado']="R";
			 $$arr['vp_UsuReg']=IDUsuarioSesion();
			 $result=json_decode(RequestServer(URL_SERVICES."/motivorecepcionRegistrar",$arr,TokenSesion()));
			if(!empty($result)){
			$thearray = get_object_vars( $result );
			echo json_encode($thearray);
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
		 }
		 public function Editmotivorecepcion(){
			if(ValidarSesion()){
			$arr['vp_ID']=trim($this->request->getPost('vp_id'));
			 $arr['vp_des_motivo']=trim($this->request->getPost('vp_des_motivo'));
			 $arr['vp_tipo_operacion']=trim($this->request->getPost('vp_tipo_operacion'));
			 $arr['vp_cod_transaccion']=trim($this->request->getPost('vp_cod_transaccion'));
			 $$arr['vp_IDEmpresa']=IDEmpresaSeleccionada();
			 $$arr['vp_UsuReg']=IDUsuarioSesion();
			$result=json_decode(RequestServer(URL_SERVICES."/motivorecepcionEditar",$arr,TokenSesion()));
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
		 }
		 public function New_motivorecepcion(){
			if(ValidarSesion()){
			unset($_SESSION['entityid']);
			$data['update']=0;
			echo view('Frmcabecera');
			echo view('/FrmMantMotivoRecepcion',$data);			echo view('FrmPie');			}
			else{
			return view('index');
			}
		 }*/
	}
?>