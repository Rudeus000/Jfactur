<?php
defined('BASEPATH') Or exit('No direct script access allowed');


class User_model extends CI_Model
{
    public function login ($username,$paswoord)
    {
        $this->db->where('login_usu',$username);
        $this->db->where('passwoord_usu',$paswoord);
        $this->db->where('estado_usuario','1');

        $resultados=$this->db->get('tb_usuario');
        if ($resultados->num_rows()>0) {
            return $resultados->row();
        }
        else{
            return false;
        }
    }
    public function getUserByUsername($username)
{
    return $this->db->get_where('tb_usuario', ['login_usu' => $username])->row();
}

public function incrementarIntentos($username)
{
    $this->db->set('intentos_fallidos', 'intentos_fallidos+1', FALSE);
    $this->db->where('login_usu', $username);
    $this->db->update('tb_usuario');
}

public function getIntentos($username)
{
    $this->db->select('intentos_fallidos');
    $this->db->from('tb_usuario');
    $this->db->where('login_usu', $username);
    return $this->db->get()->row()->intentos_fallidos;
}

public function bloquearUsuario($username)
{
    $this->db->set('estado_usuario', 0);
    $this->db->where('login_usu', $username);
    $this->db->update('tb_usuario');
}

public function restablecerIntentos($username)
{
    $this->db->set('intentos_fallidos', 0);
    $this->db->set('estado_usuario', 1); // Reactivar el usuario
    $this->db->where('login_usu', $username);
    $this->db->update('tb_usuario');
}

}
?>