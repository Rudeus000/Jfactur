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
}
?>