<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Balance extends CI_Controller
{
  // private $permisos;
  public function __construct()
  {
    parent::__construct();
     if(!$this->session->userdata("login")){
      redirect(base_url());
    }
    $this->load->model('balance_model');
    // $this->permisos = $this->backend_lib->control();
  }

  public function index()
  {
     // $data['permisos'] =$this->permisos;
    $fecha = new DateTime();
    $fecha->modify('first day of this month');
    $data['desde'] = $fecha->format('Y-m-d');

    $fecha = new DateTime();
    $fecha->modify('last day of this month');
    $data['hasta'] =  $fecha->format('Y-m-d');
    $data['sedes'] = $this->modelgeneral->getTable('sede');
    $this->load->view('layouts/header');
    $this->load->view('layouts/aside');
    $this->load->view('reports/reportbalance',$data);    
    $this->load->view('layouts/footer');
  }

  public function jsonBalance()
  {
    $data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
		$data['sEcho']  = $this->input->get_post('_', true);

		$columns = array('tipo','fecha');
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		$data['desde'] = $this->input->get_post('desde');
		$data['hasta'] = $this->input->get_post('hasta');
    $data['sede'] = $this->input->get_post('sede');
        
		
		$datos = $this->balance_model->getBalance($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
  }

  public function reportePDF()
  {
    
    $this->mpdf = new mPDF('utf-8','A4-L','','',
      10, //LEFT
      10, //RIGHT
      20, //TOP
      10, //BOTTOM
      10, //HEADER
      10); //FOOTER
    $data['balance'] =  $this->getBalance();

    $html = $this->load->view('reportes/balance/imprimir',$data,TRUE);
    $htmlHeader = $this->load->view('reportes/balance/imprimir_header',$data,TRUE);
    $htmlFooter = $this->load->view('reportes/balance/imprimir_footer',$data,TRUE);
    $css = file_get_contents('assets/styles_pdf.css');
    $this->mpdf->SetTitle('Reportes');
    $this->mpdf->setHTMLHeader($htmlHeader);
    $this->mpdf->setHTMLFooter($htmlFooter);
    $this->mpdf->writeHTML($css,1);
    $this->mpdf->writeHTML($html,2);
    $this->mpdf->Output('assets/reportes.pdf','I');
  }

  public function reporteExcel()
  {
    $data['balance'] = $this->getBalance();
    $this->load->view('reportes/balance/excel',$data);
  }

  private function getBalance()
  {
    $data['desde'] = $this->input->get('desde');
    $data['hasta'] = $this->input->get('hasta');
    $data['sede'] = $this->input->get('sede');
    
    $this->db->from('balance');
    $this->db->where('fecha >=',$data['desde']);
    $this->db->where('fecha <=',$data['hasta']);
    if($data['sede']!='Todos'){
			$this->db->where('cod_sede',$data['sede']);
		}
    $this->db->order_by('fecha','asc');
    return $this->db->get()->result();
  }

}


/* End of file Balance.php */
/* Location: ./application/controllers/mantenimiento/Balance.php */