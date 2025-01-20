<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regcajaapertura extends CI_Controller {
    private $permisos;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('cajaapertura_model');
		$this->load->model('modelgeneral');
		$this->load->helper('general');
    $this->permisos = $this->backend_lib->control();
	}

	public function index()
	{
		$data['permisos'] =$this->permisos;
		$data['cajas'] = $this->cajaapertura_model->getCajasAperturas();
		$this->load->view('layouts/header');
		$this->load->view('layouts/aside');
		$this->load->view('admin/apertura/panel',$data);    
		$this->load->view('layouts/footer');
	}

	public function jsonApertura()
	{
		$data['start'] = $this->input->get_post('start', true);
		$data['length'] = $this->input->get_post('length', true);
	    $data['sEcho']  = $this->input->get_post('_', true);
	    $columns= ['cod_apertura','nomb_caja'];
		$orderCampo = $this->input->get_post('order', true);
		$orderCampo = $orderCampo[0]['column'];
		$orderCampo = $columns[$orderCampo];
		$orderDireccion = $this->input->get_post('order', true);
		$orderDireccion = $orderDireccion[0]['dir'];
		$data['orderCampo'] = $orderCampo;
		$data['orderDireccion'] = $orderDireccion;
		
		$data['caja'] = $this->input->get_post('caja');
		$data['usuario'] = $this->input->get_post('usuario');

		$datos = $this->cajaapertura_model->getApertura($data);
		header('content-type: application/json; charset=utf-8');
		echo json_encode($datos);
	}

	public function verificaContrasena()
	{
		$contrasena = $this->input->post('contrasena');
		$query = $this->db->from('tb_usuario')
		->where('cod_perfil',1)
		//->where('cod_grupo',5)
		->where('passwoord_usu',sha1($contrasena))
		->get();

		$res = [];

		if($query->num_rows() > 0){
			$res['success'] = true;
		}else{
			$res['success'] = false;
		}

		echo json_encode($res);
	}

	public function getUsuarios()
	{
		$caja = $this->input->get('caja');
		$usuarios = $this->db->from('tb_usuario')
		->select('tb_usuario.cod_usu,apell_usu,nomb_usu')
		->join('tb_usuario_puntoventa','tb_usuario_puntoventa.cod_usu = tb_usuario.cod_usu')
		->join('tb_puntoventa_caja','tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa_caja.cod_puntoventa')
		->where('tb_puntoventa_caja.cod_caja',$caja)
		->where('tb_puntoventa_caja.cod_puntoventa',$this->session->userdata('puntoventa'))
		->get()->result();
		echo json_encode($usuarios);
	}

	public function agregar()
	{
		$data['cod_caja'] = $this->input->post('caja');
		$data['cod_puntoventa'] = $this->session->userdata('puntoventa');
		$data['monto_apertura'] = $this->input->post('monto');
		$data['cod_usu'] = $this->session->userdata('cod_usu');
		$data['turno_apertura'] = $this->input->post('turno');
		$data['fecha_apertura'] = $this->input->post('fecha');
		$data['horainicio_apertura'] = $this->input->post('inicio');
		$data['horafin_apertura'] = $this->input->post('fin');

		$insert = $this->modelgeneral->insertRegist('tb_caja_apertura',$data);
		$resp = [];
		if (!is_null($insert)) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	public function getApertura()
	{
		$id = $this->input->get('id');
		$apertura = $this->modelgeneral->getTableWhereRow('tb_caja_apertura',['cod_apertura'=>$id]);
		$usuarios = $usuarios = $this->db->from('tb_usuario')
								->select('tb_usuario.cod_usu,apell_usu,nomb_usu')
								->join('tb_usuario_puntoventa','tb_usuario_puntoventa.cod_usu = tb_usuario.cod_usu')
								->join('tb_puntoventa_caja','tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa_caja.cod_puntoventa')
								->where('tb_puntoventa_caja.cod_caja',$apertura->cod_caja)
								->where('tb_usuario_puntoventa.pordefecto',1)
								->get()->result();
		$option = '';
		foreach ($usuarios as $u) {
			if ($u->cod_usu==$apertura->cod_usu) {
				$option .= '<option value="'.$u->cod_usu.'" selected>'.$u->apell_usu.' '.$u->nomb_usu.'</option>';
			}else{
				$option .= '<option value="'.$u->cod_usu.'">'.$u->apell_usu.' '.$u->nomb_usu.'</option>';
			}
		}

		$resp = [];
		$resp['usuarios'] = $option;
		$resp['apertura'] = $apertura;
		echo json_encode($resp);
	}

	public function editar()
	{
		$data['cod_caja'] = $this->input->post('caja');
		$data['monto_apertura'] = $this->input->post('monto');
		//$data['cod_usu'] = $this->input->post('usuario');
		$data['turno_apertura'] = $this->input->post('turno');
		$data['fecha_apertura'] = $this->input->post('fecha');
		$data['horainicio_apertura'] = $this->input->post('inicio');
		$data['horafin_apertura'] = $this->input->post('fin');

		$where['cod_apertura'] = $this->input->post('id');
		$edit = $this->modelgeneral->editRegist('tb_caja_apertura',$where,$data);
		$resp = [];
		if ($edit) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
		}

		echo json_encode($resp);
	}

	public function eliminar()
	{
		$where['cod_apertura'] = $this->input->get('id');
		$delete = $this->modelgeneral->deleteRegist('tb_caja_apertura',$where);
		$resp = [];
 		if ($delete) {
 			$resp['success'] = true;
 		}else{
 			$resp['success'] = false;
 		}

 		echo json_encode($resp);
	}

	function verificarDisponibilidadApertura()
  {
    $caja = $this->input->get('caja');
    $usuario = $this->input->get('usuario');
    $fecha = $this->input->get('fecha');
    $inicio = $this->input->get('inicio');
    $fin = $this->input->get('fin');
    $query = $this->db->from('tb_caja_apertura')
    //->where('cod_caja',$caja)
    ->where('cod_usu',$this->session->userdata('cod_usu'))
    ->where('fecha_apertura',$fecha)
    ->where('((horainicio_apertura  BETWEEN "'.$inicio.'" AND "'.$fin.'") OR (horafin_apertura BETWEEN "'.$inicio.'" AND "'.$fin.'"))',null)
    ->where('estado_apertura','A')
    ->get();
    $resp = [];

    $inicioTime = strtotime($this->input->get('inicio'));
    $finTime = strtotime($this->input->get('fin'));

    if ($finTime > $inicioTime) {
	    if ($query->num_rows() == 0) {
	    	$resp['success'] = true;
	    }else{
	    	$resp['success'] = false;
	    	$resp['mensaje'] = 'No se puede guardar este registro porque esta en conflicto con otro existente.';
	    }
    }else{
    	$resp['success'] = false;
	    $resp['mensaje'] = 'La hora de fin debe ser mayor a la hora de inicio.';
    }
    echo json_encode($resp);
	}
	
	function verificarCierreCaja()
	{
		$caja = $this->input->get('caja');
		$usuario = $this->input->get('usuario');
		$query = $this->db->from('tb_caja_apertura')
		->where('cod_caja',$caja)
		->where('cod_usu',$usuario)
		->where('estado_apertura','A')
		->get();
		$resp = [];
		if ($query->num_rows()==0) {
			$resp['success'] = true;
		}else{
			$resp['success'] = false;
			$resp['mensaje'] = 'Hay cajas pendientes por cerrar.';
		}
		echo json_encode($resp);
	}

	public function verificarCashStatus()
	{
		$cod_puntoventa = $this->session->userdata('puntoventa');
		$cod_usu = $this->session->userdata('cod_usu');
	
		if (empty($cod_puntoventa) || empty($cod_usu)) {
			echo json_encode([
				'success' => false,
				'message' => 'El código del punto de venta no está disponible. Verifica tu sesión.'
			]);
			return;
		}
	
		// Obtener estado de cajas
		$cajas_pendientes = $this->cajaapertura_model->validarCajasPendientes($cod_puntoventa, $cod_usu);
		$hora_actual = (int)date('H'); // Hora actual en formato 24 horas
	
		// Validar estado de la caja
		switch ($cajas_pendientes['estado']) {
			case 'pendiente_validacion':
				if ($hora_actual >= 11) {
					echo json_encode([
						'success' => false,
						'block_sales' => true,
						'message' => 'El módulo de ventas está bloqueado porque la caja no ha sido validada. Contacta a tu jefe directo.'
					]);
				} else {
					echo json_encode([
						'success' => true,
						'message' => 'La caja está cerrada y pendiente de validación, pero puedes continuar trabajando.'
					]);
				}
				break;
	
			case 'pendiente_cierre':
				echo json_encode([
					'success' => false,
					'message' => 'No puedes abrir una nueva caja porque: ' . $cajas_pendientes['mensaje']
				]);
				break;
	
			case 'pendiente':
				echo json_encode([
					'success' => false,
					'message' => 'No puedes abrir una nueva caja porque: ' . $cajas_pendientes['mensaje']
				]);
				break;
	
			case 'disponible':
				echo json_encode([
					'success' => true,
					'message' => 'La caja está disponible para apertura. Procede con confianza.'
				]);
				break;
	
			default:
				echo json_encode([
					'success' => false,
					'message' => 'Ocurrió un error inesperado en la validación de cajas.'
				]);
				break;
		}
	}
	
}

/* End of file Regcajaapertura.php */
/* Location: ./application/controllers/administrador/Regcajaapertura.php */
