<?php 
class Authenticated
{
	private $ci;

	public function __construct()
	{
		$this->ci = & get_instance();
	}

	public function checkAccess()
	{
		$session = $this->ci->session->userdata('login');
		$uri01 = $this->ci->uri->segment(1);
		$uri02 = $this->ci->uri->segment(2);
		
	
	  if ($session AND $this->ci->session->userdata('puntoventa_reportes')=='') {
      if(($uri02!='logout' AND $uri02 !='acceder' AND $uri02!='setPuntoVenta')){
		redirect('auth/acceder');

			}
		}
	}


}


?>