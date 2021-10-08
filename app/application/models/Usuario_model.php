<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

    function getUsuario($data)
    {
        $this->db->from('tb_usuario');
        $queryTotal = $this->db->get();
        $this->db->flush_cache();

        $this->db->from('tb_usuario');
        $this->db->join('tb_grupo','tb_usuario.cod_grupo = tb_grupo.cod_grupo');
        $this->db->join('tb_perfil','tb_usuario.cod_perfil = tb_perfil.cod_perfil');
        $this->db->select('tb_usuario.* , CONCAT(apell_usu, " ", nomb_usu) as NombreUsuario, 
        nombre_grupo as grupo, nomb_perfil as perfil');

        if(isset($data['tb_usuario'])){
            $this->db->having("NombreUsuario LIKE '%".$data['tb_usuario']."%'");
        }

        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_registro >=',$data['desde']);
			$this->db->where('fecha_registro <=',$data['hasta']);
        }
        if (isset($data['tb_grupo'])) {
			$this->db->where('tb_grupo.cod_grupo',$data['tb_grupo']);
		}
        $queryLike = $this->db->get();
        $this->db->flush_cache();

        $this->db->from('tb_usuario');
        $this->db->select('tb_usuario.* , CONCAT(apell_usu, " ", nomb_usu) as NombreUsuario, 
        nombre_grupo as grupo, nomb_perfil as perfil, pordefecto,cod_puntoventa');
        $this->db->join('tb_grupo','tb_usuario.cod_grupo = tb_grupo.cod_grupo');
        $this->db->join('tb_perfil','tb_usuario.cod_perfil = tb_perfil.cod_perfil');
        $this->db->join('tb_usuario_puntoventa','tb_usuario.cod_usu = tb_usuario_puntoventa.cod_usu AND tb_usuario_puntoventa.pordefecto = 1','left');
        $this->db->select('tb_usuario.* , CONCAT(apell_usu, " ", nomb_usu) as NombreUsuario, 
        nombre_grupo as grupo, nomb_perfil as perfil');

        if ($data['length']!=-1) {
			$this->db->limit($data['length'],$data['start']);
        }
        if (isset($data['orderCampo'])) {
			$this->db->order_by($data['orderCampo'],$data['orderDireccion']);
        }
        if(isset($data['tb_usuario'])){
            $this->db->having("NombreUsuario LIKE '%".$data['tb_usuario']."%'");
        }

        if (isset($data['desde']) AND isset($data['hasta'])) {
			$this->db->where('fecha_registro >=',$data['desde']);
			$this->db->where('fecha_registro <=',$data['hasta']);
        }
        if (isset($data['tb_grupo'])) {
			$this->db->where('tb_grupo.cod_grupo',$data['tb_grupo']);
        }
        
        $query = $this->db->get();
        $last = $this->db->last_query();

		$result = array();
		$result['sEcho'] = $data['sEcho'];
		$result['iTotalRecords'] = $queryTotal->num_rows();
        $result['iTotalDisplayRecords'] = $queryLike->num_rows();
        
        $row = [];
		foreach ($query->result() as $q) {

			if ($q->estado_usuario=='1') {
				$estado = '<label class="label label-success">Activo</label>';
			}elseif($q->estado_usuario=='2'){
				$estado = '<label class="label label-info">Inactivo</label>';
			}	


			$botones = '<div class="btn-footer text-center">';
            
            if (!is_null($q->pordefecto)) {
                $botones .= '<button title="Caja - Documento" data-id="'.$q->cod_usu.'" data-punto="'.$q->cod_puntoventa.'" class="asignar-cajadocumento btn waves-effect waves-light" data-toggle="modal" data-target="#ModalAsignarCajaDocumento" style="padding:2px 4px;margin:0px 2px"><i class="fas fa-ion ion-md-paper"></i></button>';
            }
        $botones .= '
        
		<button data-id="'.$q->cod_usu.'" class="editar-usuario btn btn-success waves-effect waves-light" 
		data-toggle="modal" data-target="#ModalEditarUsuario" style="padding:2px 4px;margin:0px 2px"><i class="fas fa-pencil-alt"></i></button>';                                      
		$botones .= '<a></a> <button data-id="'.$q->cod_usu.'" class="anular-usuario btn btn-danger waves-effect waves-light" style="padding:2px 4px;margin:0px 2px"><i class="fa fa-trash"></i></button>';


                                                  

			$row[] = [$q->cod_usu,$q->NombreUsuario,$q->login_usu,$q->fena_usu,$q->fecha_visita,$q->grupo,$q->perfil,$estado,$botones];
		}
		$result['aaData'] = $row;
		return $result;
    }

    
}