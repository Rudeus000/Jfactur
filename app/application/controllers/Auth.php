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
			// $data['empresa'] = $empresa->company_status;
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

	public function enviarCorreo($emailDestino, $asunto, $mensaje)
	{
		$this->load->library('email');

		// Configuración del correo
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'ssl://smtp.gmail.com';
		$config['smtp_timeout'] = '30';
		$config['smtp_port'] = '465';
		$config['smtp_user'] = 'lc578662@gmail.com';
		$config['smtp_pass'] = 'uwco vyht hxpa xxtj';
		$config['mailtype'] = 'html';
		$config['charset'] = 'utf-8';
		$config['newline'] = "\r\n";

		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
		$this->email->set_crlf("\r\n");

		$this->email->from('lc578662@gmail.com', 'Bfacturas');
		$this->email->to($emailDestino);
		$this->email->subject($asunto);
		$this->email->message($mensaje);

		// Agrega un mensaje para verificar si se ejecuta correctamente la inicialización
		// echo "Inicialización del correo realizada correctamente.<br>";

		if ($this->email->send()) {
			// Agrega un mensaje para verificar si el correo se envió correctamente
			// echo "¡Correo enviado exitosamente!<br>";
			return true; // Éxito al enviar el correo
		} else {
			// Agrega un mensaje para verificar si hay errores al enviar el correo
			// echo "Error al enviar el correo: " . $this->email->print_debugger() . "<br>";
			return false; // Error al enviar el correo
		}
	}

	// function generarTokenUnico()
	// {
	// 	$token = bin2hex(random_bytes(32));
	// 	return $token;
	// }

	public function registrarnewusuario()
	{
		$this->load->library('encryption');

		$verificationToken = bin2hex($this->encryption->create_key(16)); // Generar un token único

		$data['apell_usu'] = $this->input->post('newapellido');
		$data['nomb_usu'] = $this->input->post('newnombre');
		$data['direcc_usu'] = "LOS OLVIDADOS";
		$data['telf_usu'] = $this->input->post('newphone');
		$data['docum_usu'] = $this->input->post('newruc');
		$data['email_usu'] = $this->input->post('newemail');
		$data['login_usu'] = $this->input->post('newemail');
		$data['passwoord_usu'] =  sha1($this->input->post('newpassword'));
		$data['fecha_registro'] = date("Y-m-d H:i:s");
		$data['fecha_modificacion'] = date("Y-m-d H:i:s");
		$data['fecha_visita'] = date("Y-m-d H:i:s");
		$data['cod_grupo'] = "12";
		$data['cod_perfil'] = "1";		
		$data['estado_usuario'] =  "0";
		$data['fena_usu'] =  date("Y-m-d");

		// Verificar si los datos ya existen en la base de datos
		$existingUser = $this->modelgeneral->getTableWhereRow('tb_usuario', [

			'login_usu' => $data['login_usu'],
			// 'nomb_usu' => $data['nomb_usu'],
			// 'email_usu' => $data['email_usu'],
		]);


		$resp = [];
		if ($existingUser) {
			$resp['success'] = false;
			$message = "Los siguientes datos ya existen en el sistema: ";

			// Acceder a las propiedades del objeto stdClass
			if ($existingUser->login_usu === $data['login_usu']) {
				$message .= "Login: '" . $data['login_usu'] . "'. ";
			}
			if ($existingUser->nomb_usu === $data['nomb_usu']) {
				$message .= "Nombre: '" . $data['nomb_usu'] . "'. ";
			}
			if ($existingUser->email_usu === $data['email_usu']) {
				$message .= "Email: '" . $data['email_usu'] . "'. ";
			}

			$resp['message'] = $message;
		} else {

			$insert = $this->modelgeneral->insertRegist('tb_usuario', $data);

			if (!is_null($insert)) {

				// Obtener el ID del usuario insertado
				$usuario_id = $insert;

				// Registro del token en la tabla temporal
				$tokenData = [
					'token' => $verificationToken,
					'cod_usu' => $usuario_id,
					'usado' => 0,
					'fecha_creacion' => date("Y-m-d H:i:s")
				];

				$registroToken = $this->modelgeneral->insertarToken('tokens_temporales', $tokenData);

				if ($registroToken) {
					// Envío de correo electrónico con el enlace de verificación
					$emailDestino = $data['email_usu'];
					$asunto = 'Verificación de correo electrónico';
					$mensaje = 'Por favor, haga clic en el siguiente enlace para verificar su correo electrónico: ';
					$mensaje .= '<a href=' . base_url() . 'verificar/verificar_email?token=' . $verificationToken . '>Verificar Email</a>';

					if ($this->enviarCorreo($emailDestino, $asunto, $mensaje)) {

						$resp['success'] = true;
						$resp['message'] = "¡Se envio el correo exitosamente!";
					} else {
						// Si hay un error al enviar el correo						
						$resp['success'] = false;
						$resp['message'] = "Hubo un error al enviar el correo.";
					}

					$punto = $this->modelgeneral->getTableWhereRow('tb_puntoventa', ['pordefecto_puntoventa' => 1]); //POR DEFECTO DEL SISTEMA
					$this->db->set('cod_puntoventa', $punto->cod_puntoventa)
						->set('cod_usu', $insert)
						->set('pordefecto', 1)
						->insert('tb_usuario_puntoventa'); //INSERTAR PUNTO DE VENTA A NUEVO USUARIO
					$resp['success'] = true;
					$resp['message'] = "¡Usuario registrado exitosamente, se envio el correo de activacion!";
				} else {
					$resp['success'] = false;
					$resp['message'] = "Hubo un error al registrar el token.";
				}
			} else {
				$resp['success'] = false;
				$resp['message'] = "Error al registrar el usuario. Inténtalo de nuevo.";
			}


			// redirect(base_url());
		}
		echo json_encode($resp);
	}
}
