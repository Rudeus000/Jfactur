<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Regcliente extends CI_Controller
{
  private $permisos;
  public function __construct()
  {
     parent::__construct();
         if(!$this->session->userdata("login")){
      redirect(base_url());
    }
         $this->load->model('cliente_model');
         $this->load->model('modelgeneral');
         $this->load->helper('general');
         $this->permisos = $this->backend_lib->control();
  
  }

  public function index()
     {
        $data['permisos'] =$this->permisos;
        $data['cliente'] = $this->modelgeneral->getTable('tb_cliente');
        $data['tb_empresa'] = $this->modelgeneral->getTableWhereRow('tb_empresa',['cod_empresa' => 1]);
        $this->load->view('layouts/header');
        $this->load->view('layouts/aside');
        $this->load->view('admin/cliente/listgetcliente',$data);    
        $this->load->view('layouts/footer');
     }


    public function jsonClientes()
    {
    $data['start'] = $this->input->get_post('start', true);
    $data['length'] = $this->input->get_post('length', true);
    $data['sEcho']  = $this->input->get_post('_', true);
    $columns= ['id_cliente','nomb_cliente'];
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

    $datos = $this->cliente_model->getCliente($data);
    header('content-type: application/json; charset=utf-8');
    echo json_encode($datos);
    }


    public function validaClienteUnico()
  {
    $documento = $this->input->post('documento');
    $this->db->from('tb_cliente');
    $this->db->where('doc_cliente',$documento);
    if($this->input->post('id')!=''){
      $this->db->where_not_in('id_cliente',[$this->input->post('id')]);
    }
    $query = $this->db->get();

    if($query->num_rows() == 0){
      echo 'true';
    }else{
      echo 'false';
    }
  }




    function agregarCliente()
  {
    $this->form_validation->set_rules('tipo','','required');
    $this->form_validation->set_rules('documento','','required');
    $this->form_validation->set_rules('nombre','','required');
    $this->form_validation->set_rules('telefono','','required');
         if($this->form_validation->run() == TRUE){

      $data['cod_tipdocucli'] = $this->input->post('tipo');
      $data['doc_cliente'] = $this->input->post('documento');
      $data['nomb_cliente'] = $this->input->post('nombre');
      $data['fena_pac'] = $this->input->post('fnacimiento');
      $data['direc_cliente'] = $this->input->post('direccion');
      $data['contac_cliente'] = $this->input->post('contacto');
      $data['telf_cliente'] = $this->input->post('telefono');
			$data['email_cliente'] = $this->input->post('email');
			$data['precio_cliente'] = $this->input->post('precio_venta');
      $insert = $this->modelgeneral->insertRegist('tb_cliente',$data);

      $resp = [];
      if (!is_null($insert)) {
        $resp['success'] = true;
      }else{
        $resp['success'] = false;
      }

      echo json_encode($resp);
    }
  }

    function getCliente()
    {
    $id = $this->input->get('id');
    $cliente = $this->modelgeneral->getTableWhereRow('tb_cliente',['id_cliente'=>$id]);
    echo json_encode($cliente);
    }


    function editarCLiente()
    {
      $this->form_validation->set_rules('id','','required');
      $this->form_validation->set_rules('nombre','','required');
      $this->form_validation->set_rules('documento','','required');
      $this->form_validation->set_rules('telefono','','required');
      if($this->form_validation->run() == TRUE){
        $data['cod_tipdocucli'] = $this->input->post('tipo');
        $data['nomb_cliente'] = $this->input->post('nombre');
        $data['fena_pac'] = $this->input->post('fnacimiento');
        $data['doc_cliente'] = $this->input->post('documento');
        $data['telf_cliente'] = $this->input->post('telefono');
        $data['direc_cliente'] = $this->input->post('direccion');
        $data['contac_cliente'] = $this->input->post('contacto');
        $data['email_cliente'] = $this->input->post('email');
				$data['estado_cliente'] = $this->input->post('estado');
				$data['precio_cliente'] = $this->input->post('precio_venta');
        $where['id_cliente'] = $this->input->post('id');
        $editar = $this->modelgeneral->editRegist('tb_cliente',$where,$data);

        $resp = [];
        if ($editar) {
          $resp['success'] = true;
        }else{
          $resp['success'] = false;
        }

        echo json_encode($resp);
      }
    }


      function anularCliente()
      {
        $data['estado_cliente'] = 2; //ANULAR  
        $where['id_cliente'] = $this->input->get('id');  
        
        $edit = $this->modelgeneral->editRegist('tb_cliente',$where,$data);
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

/*   public function cumpleanos()
	{
		if($this->input->post('tipo')=='mes'){
			$query = $this->db->from('tb_cliente')
			->select('id_cliente,nomb_cliente,fena_pac')
			->where("Month(fena_pac)",$this->input->post('mes'))
			->get()->result();
		}else{
			$query = $this->db->query("SELECT id_cliente,nomb_cliente,fena_pac FROM tb_cliente WHERE MONTH(fena_pac) = ".date('m')." AND DAY(fena_pac) = ".date('d'))
			->result();
		}

		header('content-type: application/json; charset=utf-8');
		$data['success'] = true;
		$data['query'] = $query;
		echo json_encode($data);
	 }*/


   public function cumpleanos()
	{
		if($this->input->post('tipo')=='mes'){
			$query = $this->db->from('tb_cliente')
			->select('id_cliente,nomb_cliente,fena_pac,telf_cliente')
			->where("Month(fena_pac)",$this->input->post('mes'))
			->get()->result();
		}else{
			$query = $this->db->query("SELECT id_cliente,nomb_cliente,fena_pac,telf_cliente FROM tb_cliente WHERE MONTH(fena_pac) = ".date('m')." AND DAY(fena_pac) = ".date('d'))
			->result();
		}

		header('content-type: application/json; charset=utf-8');
		$data['success'] = true;
		$data['query'] = $query;
		echo json_encode($data);
	}

  public function botonWhatsapp($query)
  {
    foreach ($query as $q) {
      if($q->telf_cliente!=''){
        $q->whatsapp = '<button type="button" data-celular="'.$q->telf_cliente.'" class="btn btn-success btn-whatsapp"><i class="fa fa-whatsapp"></i></button>';
      }else{
        $q->whatsapp = '';
      }
    }

    return $query;
  }

}
