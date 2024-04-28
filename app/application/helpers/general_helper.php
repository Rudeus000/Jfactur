<?php 
require(APP_TENANTPATH.'config.php');
use Luecano\NumeroALetras\NumeroALetras;


function classAgendaCita($estado)
{
	if ($estado==1) { //Atendido
		$res = 'success';
	}elseif($estado==2){ //Anulado
		$res = 'warning';
	}elseif($estado==3){ //Pendiente
		$res = 'info';
	}elseif($estado==4){ //Atendiendose
		$res = 'important';
	}elseif($estado==5){ //Citado
		$res = 'inverse';
	}elseif($estado==6){ //Confirmado por Telefono
		$res = 'special';
	}elseif($estado==7){ //En sala de espera
		$res = 'warning';
	}elseif($estado==8){ //No asiste
		$res = 'warning';
	}elseif($estado==9){ //No confirmado
		$res = 'warning';
	}

	return $res;
}

function filaEstadoCita($estado)
{
	if ($estado==1) { //Atendido
		$res = 'success';
	}elseif($estado==2){ //Anulado
		$res = 'danger';
	}elseif($estado==3){ //Pendiente
		$res = 'info';
	}elseif($estado==4){ //Atendiendose
		$res = 'success';
	}elseif($estado==5){ //Citado
		$res = 'warning';
	}elseif($estado==6){ //Confirmado por Telefono
		$res = 'special';
	}elseif($estado==7){ //En sala de espera
		$res = 'info';
	}elseif($estado==8){ //No asiste
		$res = 'danger';
	}elseif($estado==9){ //No confirmado
		$res = 'danger';
	}

	return $res;
}


function edad($fecha_nacimiento) { 
    $tiempo = strtotime($fecha_nacimiento); 
    $ahora = time(); 
    $edad = ($ahora-$tiempo)/(60*60*24*365.25); 
    $edad = floor($edad); 
    return $edad; 
}

	function dia()
	{
		$dias = ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sábado','Domingo'];
		return $dias[date('N')];
	}

	function mes($index)
	{
		$mes = [
			1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
			5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
			9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
		];
		return $mes[$index];
	}


	function convertir($n)
	{
		$formatter = new NumeroALetras();
		return $formatter->toInvoice($n, 2, 'SOLES','CENTIMOS');
	}

	function base_url_app($string=NULL)
	{
		$res = APP_BASEURL;
		if(!is_null($string)){
			$res .= $string;
		}

		return $res;
	}

	function fechaFormateada() {
		// Días de la semana en español
    $diasSemana = array(
				'Sunday' => 'Domingo',
				'Monday' => 'Lunes',
				'Tuesday' => 'Martes',
				'Wednesday' => 'Miércoles',
				'Thursday' => 'Jueves',
				'Friday' => 'Viernes',
				'Saturday' => 'Sábado'
		);

		// Meses en español
		$meses = array(
				'January' => 'Enero',
				'February' => 'Febrero',
				'March' => 'Marzo',
				'April' => 'Abril',
				'May' => 'Mayo',
				'June' => 'Junio',
				'July' => 'Julio',
				'August' => 'Agosto',
				'September' => 'Septiembre',
				'October' => 'Octubre',
				'November' => 'Noviembre',
				'December' => 'Diciembre'
		);

		$fecha = date('Y-m-d H:i:s');
		// Obtiene los componentes de la fecha y hora
		$timestamp = strtotime($fecha);
		$diaSemana = $diasSemana[date('l', $timestamp)];
		$dia = date('d', $timestamp);
		$mes = $meses[date('F', $timestamp)];
		$anio = date('Y', $timestamp);
		$hora = date('H:i', $timestamp);

		// Formatea la fecha y hora en español
		$fechaFormateada = "$diaSemana, $dia de $mes de $anio, $hora";

		return $fechaFormateada;
	}


?>