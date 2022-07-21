<?php

class Kardex extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata("login")){
			redirect(base_url());
		}
		$this->load->model('kardex_model');
	}	
	public function index()
	{
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('reports/reportdashboard', $data);
		$this->load->view('layouts/footer');
	}
	public function CierreKdxUnd()
	{
		$CodProd = $this->input->post('CodProd');
		$almacen = $this->input->post('almacen');
		$fecha = $this->input->post('fecha');
		$anio = explode('-', $fecha)[0];
		$mes = explode('-', $fecha)[1];
		$res_cou = $this->kardex_model->CierreKdxUnd(array('CodProd'=>$CodProd,'CodAlmacen'=>$almacen,'anio'=>$anio,'mes'=>$mes));		
		echo  json_encode($res_cou);
	}
	public function CierreKdxValorizado()
	{
		$CodProd = $this->input->post('CodProd');
		$almacen = $this->input->post('almacen');
		$fecha = $this->input->post('fecha');
		$anio = explode('-', $fecha)[0];
		$mes = explode('-', $fecha)[1];
		$res_cou = $this->kardex_model->CierreKdxValorizado(array('CodProd'=>$CodProd,'anio'=>$anio,'mes'=>$mes));		
		echo  json_encode($res_cou);
	}
	public function consultakardexunid()
	{
		$result=NULL;
		$CodProd = $this->input->post('CodProd');
		$almacen = $this->input->post('almacen');
		$fecha = $this->input->post('fecha');
		$anio = explode('-', $fecha)[0];
		$mes = explode('-', $fecha)[1];
		$res_cou = $this->kardex_model->ConsultarKdxUnd(array('CodProd'=>$CodProd,'CodAlmacen'=>$almacen,'anio'=>$anio,'mes'=>$mes));		
		$result['status']=0;
		if(sizeof($res_cou)>0){
			$result['status']=1;	
			$result['data']=$res_cou;
		}
		echo  json_encode($result);
	}
	public function consultakardexvalorizado()
	{
		$result=NULL;
		$CodProd = $this->input->post('CodProd');
		//$almacen = $this->input->post('almacen');
		$fecha = $this->input->post('fecha');
		$anio = explode('-', $fecha)[0];
		$mes = explode('-', $fecha)[1];
		$res_cou = $this->kardex_model->consultakardexvalorizado(array('CodProd'=>$CodProd,'anio'=>$anio,'mes'=>$mes));		
		$result['status']=0;
		if(sizeof($res_cou)>0){
			$result['status']=1;	
			$result['data']=$res_cou;
		}
		echo  json_encode($result);
	}
	
	
}