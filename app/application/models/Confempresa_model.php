<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 */
class Confempresa_model extends CI_Model
{
	var $table = 'tb_empresa ';
	var $column_order = array('nombre_comercial','razon_social','ruc_emp','direcc_emp','ubicacion','telf_emp','email_emp','representante_emp','fecha_emp',null); //set column field database for datatable orderable
	var $column_search = array('nombre_comercial'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('cod_empresa' => 'desc'); // default order 
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		
		$this->db->from($this->table);

		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('cod_empresa',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}


	 function getEmpresa($data){
		$this->db->from('tb_empresa');
		$this->db->where('cod_empresa',1);
		$clinicas = $this->db->get()->row();
		return $clinicas;
	}

	public function getPlanes(){
		$this->db->select("c.*, p.nomb_plan as planes");
		$this->db->from("clinica c");
	 $this->db->join("planes p","c.cod_plan=p.cod_plan");

	 $this->db->where('id_clin',1);
		
		$resultados = $this->db->get()->row();
		return $resultados;
	}

    public function getUserRol($usuario){
		$this->db->select("r.codi_rol,r.nomb_rol as nombrerol");
		$this->db->where('u.codi_usu',$usuario);
		$this->db->from("usuario u");
	     $this->db->join("rol r","u.codi_rol=r.codi_rol");
		
		$resultados = $this->db->get()->row();
		return $resultados;
	}




}