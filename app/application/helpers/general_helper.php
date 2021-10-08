<?php 
require(APP_TENANTPATH.'config.php');


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
    $tiempo = strtotime($fecha); 
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

	function basico($numero)
	{
		$valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
		'nueve','diez',
		'once','doce','trece','catorce','quince','dieciseis','diecisiete','dieciocho','diecinueve',
		'veinte','veintiuno','veintidos','veintitres','veinticuatro','veinticinco','veintiséis','veintisiete','veintiocho','veintinueve');
		return $valor[$numero - 1];
	}

	function decenas($n)
	{
		$decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',
			60=>'sesenta',70=>'setenta',80=>'ochenta',90=>'noventa');
		if( $n <= 29) return basico($n);
			$x = $n % 10;
		if ( $x == 0 ) {
			return $decenas[$n];
		} else return $decenas[$n - $x].' y '. basico($x);
	}

	function centenas($n) {
		$cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos',
		400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
		700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
		if( $n >= 100) {
			if ( $n % 100 == 0 ) {
				return $cientos[$n];
			} else {
				$u = (int) substr($n,0,1);
				$d = (int) substr($n,1,2);
				return (($u == 1)?'ciento':$cientos[$u*100]).' '.decenas($d);
			}
		} else return decenas($n);
	}

	function miles($n)
	{
		if($n > 999) {
			if( $n == 1000) {
				return 'mil';
			}
			else {
				$l = strlen($n);
				$c = (int)substr($n,0,$l-3);
				$x = (int)substr($n,-3);
				if($c == 1) {
					$cadena = 'mil '.centenas($x);
				}else if($x != 0) {
					$cadena = centenas($c).' mil '.centenas($x);
				}
				else $cadena = centenas($c). ' mil';
					return $cadena;
			}
		} else return centenas($n);
	}

	function millones($n)
	{
		if($n == 1000000) {
			return 'un millón';
		}else{
			$l = strlen($n);
			$c = (int)substr($n,0,$l-6);
			$x = (int)substr($n,-6);
			if($c == 1) {
				$cadena = ' millón ';
			}else{
				$cadena = ' millones ';
			}
			return miles($c).$cadena.(($x > 0)?miles($x):'');
		}
	}
	function convertir($n) {
		$n = floatval($n);
		switch (true) {
			case ( $n >= 1 && $n <= 29) : return basico($n); break;
			case ( $n >= 30 && $n < 100) : return decenas($n); break;
			case ( $n >= 100 && $n < 1000) : return centenas($n); break;
			case ($n >= 1000 && $n <= 999999): return miles($n); break;
			case ($n >= 1000000): return millones($n);
		}
	}

	function base_url_app($string=NULL)
	{
		$res = APP_BASEURL;
		if(!is_null($string)){
			$res .= $string;
		}

		return $res;
	}

?>
