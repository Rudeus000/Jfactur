<?php 
@session_start();
class Serie_almacen_model extends CI_Model{
	 function __construct()
	 { 
		 parent::__construct();
	 } 
	 public function fillallseriealmacen($params=NULL){
		$query = $this->db->from('tb_almacen_serie')
		->select("IDAlmacenSerie,tb_almacen.nomb_almacen as cod_almacen,case when TipoDoc='NI' then 'Nota de ingreso' when TipoDoc='NS' then 'Nota de salida' when TipoDoc='BI' then 'Boleta de ingreso' else 'Boleta de salida' end as TipoDoc,Serie,Correlativo")
		->join('tb_almacen', 'tb_almacen_serie.cod_almacen = tb_almacen.cod_almacen')
		->where('tb_almacen_serie.estado',"R");
		return $query->get()->result();
	} 
	public function Rmvserie_almacen($params=NULL){
		 $data = [
            'estado' => 'E',
        ];
        $this->db->where('IDAlmacenSerie', $params["vp_id"]);
        $this->db->update('tb_almacen_serie', $data);
	 }
	 public function FillAllAlmacen($params=NULL){
		 $this->db->select('cod_almacen, nomb_almacen');
		 $query = $this->db->get('tb_almacen');
		 return $query->result_array();
	 }
	 public function Findserie_almacen($params=NULL){
		$query = $this->db->get_where('tb_almacen_serie', array('IDAlmacenSerie' => $params['IDAlmacenSerie']));
		return $query->result_array();
	 }
	 public function Insserie_almacen($params=NULL){
		$this->db->trans_begin();
		$this->db->insert('tb_almacen_serie', $params);
		$id=$this->db->insert_id();
		if(!empty($id)){
			$this->db->trans_commit(); 
			$result['status']=1; 
			$result['msg']='Se registro la transacción'; 
			return $result;
		} 
		else{ 
			$this->db->trans_rollback(); 
			$result['status']=0; 
			$result['msg']='Problemas al ejecutar la transacción'; 
			return $result; 
		} 

	 } 
	 public function Editserie_almacen($params=NULL){
		 $store=$this->prepareStore('call Editar_tb_almacen_serie',$params);
		 $consulta = $this->db->query($store);
	 } 
	}
?>