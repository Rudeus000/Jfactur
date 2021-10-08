<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gastos_model extends CI_Model {

    function getGastos($data)
    {
        $this->db->from('tb_gastos');
        $queryTotal = $this->db->get();
        $this->db->from('tb_gastos');
        $this->db->join('tb_tipo_gastos','tb_gastos.cod_tipgastos = tb_tipo_gastos.cod_tipgastos');
        $this->db->join('tb_banco','tb_gastos.cod_ban = tb_banco.cod_ban');    
        $this->db->select('tb_gastos.* , cod_gastos,descripcion as TipoGastos, nomb_gastos as NombreGastos, 
        , fecha_registro as fechagastos ,total_gastos as total,observacion_gastos');

        $this->db->where('tb_gastos.fecha_registro >=',$data['desde']);
        $this->db->where('tb_gastos.fecha_registro <=',$data['hasta']);
        if(isset($data['tb_gastos'])){
            $this->db->having("nomb_gastos LIKE '%".$data['tb_gastos']."%'");
        }
      
        if (isset($data['tb_tipo_gastos'])) {
            $this->db->where('tb_tipo_gastos.cod_tipgastos',$data['tb_tipo_gastos']);
        }

        if ($data['estado']!='') {
            $this->db->where('tb_gastos.est_gastos',$data['estado']);
         }
     
        $queryLike = $this->db->get();
        $total = 0;
        foreach ($queryLike->result() as $r) {
            $total += $r->total_gastos;
        }
        
        $this->db->from('tb_gastos');
        $this->db->join('tb_tipo_gastos','tb_gastos.cod_tipgastos = tb_tipo_gastos.cod_tipgastos');
        $this->db->join('tb_banco','tb_gastos.cod_ban = tb_banco.cod_ban');    
        $this->db->select('tb_gastos.* , cod_gastos,descripcion as TipoGastos, nomb_gastos as NombreGastos, 
        , fecha_registro as fechagastos ,total_gastos as total,observacion_gastos');
 
        $this->db->where('tb_gastos.fecha_registro >=',$data['desde']);
        $this->db->where('tb_gastos.fecha_registro <=',$data['hasta']);
        if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
        }
        if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
        }
     
       if(isset($data['tb_gastos'])){
            $this->db->having("nomb_gastos LIKE '%".$data['tb_gastos']."%'");
        }
      
        
         if (isset($data['tb_tipo_gastos'])) {
            $this->db->where('tb_tipo_gastos.cod_tipgastos',$data['tb_tipo_gastos']);
        }

        if ($data['estado']!='') {
            $this->db->where('tb_gastos.est_gastos',$data['estado']);
         }
        
        $query = $this->db->get();

		$result = array();
		$result['sEcho'] = $data['sEcho'];
		$result['iTotalRecords'] = $queryTotal->num_rows();
        $result['iTotalDisplayRecords'] = $queryLike->num_rows();
        $result['total'] = $total;
        
        $row = [];
		foreach ($query->result() as $q) {

				if ($q->est_gastos=='1') {
				$estado = '<label class="label label-success">Gastado</label>';
			}elseif($q->est_gastos=='2'){
				$estado = '<label class="label label-info">Anulado</label>';
			}	


			$botones = '<div class="btn-footer text-center">
		<a data-id="'.$q->cod_gastos.'" class="editar-gastos on-default edit-row hidden" 
		data-toggle="modal" data-target="#ModalEditarGastos" data-placement="top" ><i style="color:#4285F4;" class="fas fa-pencil-alt"></i></a>';                                      
		$botones .= '&nbsp;&nbsp;&nbsp;<a data-id="'.$q->cod_gastos.'" class="anular-gastos on-default remove-row"><i style="color:#ff4444;"class="far fa-trash-alt"></i></a>';


                                                  

            $row[] = [$q->cod_gastos,$q->TipoGastos,$q->NombreGastos,$q->fechagastos,$q->observacion_gastos,$q->total
            ,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;
    }

    
}