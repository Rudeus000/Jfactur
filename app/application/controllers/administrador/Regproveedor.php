<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Regproveedor extends CI_Controller
{
	private $permisos;
	public function __construct()
	{
		 parent::__construct();
         if(!$this->session->userdata("login")){
			redirect(base_url());
		}
         $this->load->model('proveedor_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
	
	}

	public function index()
     {
        $data['permisos'] =$this->permisos;
        $data['proveedor'] = $this->modelgeneral->getTable('tb_proveedor');
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/proveedor/listgetproveedores',$data);    
        $this->load->view('layouts/footer');
     }


    public function jsonProveedores()
	  {
	  $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
    $data['sEcho']  = $this->input->get_post('_', true);
    $columns= ['tb_proveedor_id','tb_proveedor_tip','tb_proveedor_nom'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;

		$data['nombre'] = $this->input->get_post('nombre');
		$tipo = $this->input->get_post('tipo');
		if ($tipo!='') {
			$data['tipo'] = $tipo;
		}

		$datos = $this->proveedor_model->getProveedor($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	  }

	  function agregarProveedor()
	{
		$this->form_validation->set_rules('tipo','','required');
		$this->form_validation->set_rules('nombre','','required');
		$this->form_validation->set_rules('documento','','required');
		$this->form_validation->set_rules('telefono','','required');
         if($this->form_validation->run() == TRUE){

    	$data['tb_proveedor_tip'] = $this->input->post('tipo');
    	$data['tb_proveedor_nom'] = $this->input->post('nombre');
    	$data['tb_proveedor_doc'] = $this->input->post('documento');
    	$data['tb_proveedor_tel'] = $this->input->post('telefono');
    	$data['tb_proveedor_dir'] = $this->input->post('direccion');
    	$data['tb_proveedor_con'] = $this->input->post('contacto');
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

    function getProveedor()
    {
    $id = $this->input->get('id');
    $proveedor = $this->modelgeneral->getTableWhereRow('tb_proveedor',['tb_proveedor_id'=>$id]);
    echo json_encode($proveedor);
    }


    function editarProveedor()
    {
      $this->form_validation->set_rules('id','','required');
      $this->form_validation->set_rules('nombre','','required');
      $this->form_validation->set_rules('documento','','required');
      $this->form_validation->set_rules('telefono','','required');
      if($this->form_validation->run() == TRUE){
        $data['tb_proveedor_nom'] = $this->input->post('nombre');
         $data['tb_proveedor_tip'] = $this->input->post('tipo');
        $data['tb_proveedor_doc'] = $this->input->post('documento');
        $data['tb_proveedor_tel'] = $this->input->post('telefono');
        $data['tb_proveedor_dir'] = $this->input->post('direccion');
        $data['tb_proveedor_con'] = $this->input->post('contacto');
        $data['tb_proveedor_ema'] = $this->input->post('email');
        $data['tb_proveedor_xac'] = $this->input->post('estado');
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


      function anularProveedor()
          {
                $data['tb_proveedor_xac'] = 2; //ANULAR  
                $where['tb_proveedor_id'] = $this->input->get('id');  
            
            $edit = $this->modelgeneral->editRegist('tb_proveedor',$where,$data);
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
  	$data['almacen'] = $this->modelgeneral->getTableWhereRow('tb_almacen',['cod_almacen'=>$this->input->get('almacen')]);
	 	$data['datos'] = $this->getInventarioInicialReporte();
		$html = $this->load->view('admin/inventarioinicial/reporte_pdf',$data,TRUE);
		$css = $css = file_get_contents('assets/styles_pdf.css');
		$this->mpdf->SetTitle('Compras');
		$this->mpdf->writeHTML($css,1);
		$this->mpdf->writeHTML($html,2);
		$this->mpdf->Output('Reporte','I');
 	 }

  function reporteExcel()
  {
  	$data['datos'] = $this->getInventarioInicialReporte();
  	$this->load->view('admin/inventarioinicial/reporte_excel',$data);
  }

  function getInventarioInicialReporte()
  {
  	$this->db->from('tb_producto');
    $this->db->select('tb_producto.*,nomb_marca,nomb_categoria,nomb_unid,tb_producto_stock.stock_inicial,stock,tb_producto_stock.cod_almacen');
    $this->db->join('tb_marca','tb_producto.cod_marca = tb_marca.cod_marca');
    $this->db->join('tb_categoria','tb_producto.cod_categoria = tb_categoria.cod_categoria');
    $this->db->join('tb_unidades','tb_producto.cod_unid = tb_unidades.cod_unid');
    $this->db->join('tb_producto_stock','tb_producto.cod_producto = tb_producto_stock.cod_producto AND tb_producto_stock.cod_almacen='.$this->input->get('almacen'),'left');
    if ($this->input->get('producto')!='') {
      $this->db->like('nomb_product',$this->input->get('producto'));
    }
    if ($this->input->get('categoria')!='') {
    	$this->db->where('tb_categoria.cod_categoria',$this->input->get('categoria'));
    }
    if ($this->input->get('marca')!='') {
    	$this->db->where('tb_marca.cod_marca',$this->input->get('marca'));
    }
	  return $this->db->get()->result();
  }



}
