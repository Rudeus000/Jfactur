<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 
 */
class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Lima");
		$this->load->model('user_model');
		$this->load->model('confempresa_model');
		# code...
	}



	public function index()
	{
		if ($this->session->userdata("login")) {
			redirect(base_url() . "perfil");
		} else {
			$empresa = $this->modelgeneral->getTableWhereRow('tb_empresa', ['cod_empresa' => 1]);
			$data['empresa'] = $empresa->nombre_comercial;
			$this->load->view('admin/login', $data);
		}
	}

	public function login()
	{
		$username = $this->input->post('username');
		$paswoord = $this->input->post('paswoord');
		$perfil = $this->input->post('perfil');
		$res = $this->user_model->login($username, sha1($paswoord));
		$logo = $this->confempresa_model->getEmpresa($data);
		$empresa = $this->modelgeneral->getTableWhereRow('tb_empresa', ['cod_empresa' => 1]);
		if (!$res) {
			$this->session->set_flashdata('message', 'Acceso denegado, contacte con el administrador del sistema 921842183');
			redirect(base_url());
		} else {
			$puntoventa = $this->modelgeneral->getTableWhereRow('tb_puntoventa', ['pordefecto_puntoventa' => 1]);
			$almacen = $this->modelgeneral->getTableWhereRow('tb_puntoventa_almacen', ['cod_puntoventa' => $puntoventa->cod_puntoventa, 'pordefecto' => 1]);

			$data = array(
				'cod_usu' => $res->cod_usu,
				'apell_usu' => $res->apell_usu,
				'nomb_usu' => $res->nomb_usu,
				'perfil' => $res->cod_perfil,
				'login_usu' => $res->login_usu,
				'foto' => $logo->photo,
				'puntoventa' => $puntoventa->cod_puntoventa,
				//'puntoventa_reportes' => $puntoventa->cod_puntoventa,
				'almacen' => $almacen->cod_almacen,
				'login' => TRUE,
				'stock_minimo' => TRUE,
				'movil_expert' => $empresa->movilexpert_emp,
				'alerta_stock' => $empresa->alerta_stock_emp,
				'alerta_vencimiento' => $empresa->alerta_vencimiento_emp
			);
			$this->session->set_userdata($data);
			redirect(base_url('perfil'));
		}
	}

	public function acceder()
	{
		if (!$this->session->userdata('login')) {
			redirect('/');
		}
		$data['nuevos'] =  $this->db->from('tb_nuevo')
			->order_by('id', 'desc')
			->get()->result();
		$data['usuario'] = $this->modelgeneral->getTableWhereRow('tb_usuario', ['cod_usu' => $this->session->userdata('cod_usu')]);
		$data['perfil'] = $this->modelgeneral->getTableWhereRow('tb_perfil', ['cod_perfil' => $this->session->userdata('perfil')]);
		if ($this->session->userdata('perfil') == '1') {
			$data['sucursales'] = $this->modelgeneral->getTableWhere('tb_puntoventa', ['estad_pto' => 1]);
		} else {
			$data['sucursales'] = $this->db->from('tb_usuario_puntoventa')
				->select('tb_puntoventa.cod_puntoventa, tb_puntoventa.nomb_puntoventa')
				->join('tb_puntoventa', 'tb_usuario_puntoventa.cod_puntoventa = tb_puntoventa.cod_puntoventa')
				->where('tb_usuario_puntoventa.cod_usu', $this->session->userdata('cod_usu'))
				->where('estad_pto', 1)
				->get()->result();
		}
		$this->load->view('admin/acceder', $data);
	}

	public function setPuntoVenta($punto)
	{
		if ($punto == 'admin' and $this->session->userdata('perfil') != '1') {
			redirect(base_url() . 'reportes/regdashboard');
		}
		$queryPunto = $this->modelgeneral->getTableWhereRow('tb_puntoventa', ['cod_puntoventa' => $punto]);
		$this->session->set_userdata('puntoventa_nombre', $queryPunto->nomb_puntoventa);
		$this->session->set_userdata('puntoventa_reportes', $punto);
		$this->session->set_userdata('puntoventa', $punto);
		redirect(base_url() . 'reportes/regdashboard');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}

	public function registrarnewusuario()
	{

		
		$data['apell_usu'] = $this->input->post('newapellido');
		$data['nomb_usu'] = $this->input->post('newnombre');
		$data['direcc_usu'] = "LOS OLVIDADOS";
		$data['telf_usu'] = $this->input->post('newphone');
		$data['docum_usu'] = $this->input->post('newruc');
		$data['email_usu'] = $this->input->post('newemail');
		$data['login_usu'] = $this->input->post('newemail');
		$data['passwoord_usu'] =  sha1($this->input->post('newpasswoord'));
		$data['fecha_registro'] = date("Y-m-d H:i:s");
		$data['fecha_modificacion'] = date("Y-m-d H:i:s");
		$data['fecha_visita'] = date("Y-m-d H:i:s");
		$data['cod_grupo'] = "12";
		$data['cod_perfil'] = "1";
		$data['estado_usuario'] =  "1";
		$data['fena_usu'] =  date("Y-m-d");

			// Verificar si los datos ya existen en la base de datos
			$existingUser = $this->modelgeneral->getTableWhereRow('tb_usuario', [
				'docum_usu' => $data['docum_usu'],
				'email_usu' => $data['email_usu']
			]);

		$resp = [];
		if ($existingUser) {
			// Si los datos existen, mostrar un mensaje de error
			$resp['success'] = false;
			$resp['message'] = "Los datos ya existen en el sistema.";
		} else {

			$insert = $this->modelgeneral->insertRegist('tb_usuario', $data);

			if (!is_null($insert)) {

				$punto = $this->modelgeneral->getTableWhereRow('tb_puntoventa', ['pordefecto_puntoventa' => 1]); //POR DEFECTO DEL SISTEMA
				$this->db->set('cod_puntoventa', $punto->cod_puntoventa)
					->set('cod_usu', $insert)
					->set('pordefecto', 1)
					->insert('tb_usuario_puntoventa'); //INSERTAR PUNTO DE VENTA A NUEVO USUARIO
				$resp['success'] = true;
				$resp['message'] = "¡Usuario registrado exitosamente!";
			} else {
				$resp['success'] = false;
				$resp['message'] = "Error al registrar el usuario. Inténtalo de nuevo.";
			}
		}
		echo json_encode($resp);

		// redirect(base_url());
	}
}
