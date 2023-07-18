<?php
class Tokensunat_model extends CI_Model {
    public function generateToken() {
        // Configurar los datos de la solicitud POST
        $this->db->from('tb_empresa');
        $query = $this->db->get();
        // $this->db->flush_cache();
        if ($query->num_rows() > 0) {
            $row = $query->row();
        
        $scope= 'https://api-cpe.sunat.gob.pe';

        $postData = array(
            'grant_type' => 'password',
            'scope' => $scope,
            'client_id' =>$row->cliente_id,
            'client_secret' =>$row->cliente_secret,
            'username' => $row->user_sol,
            'password' => $row->pass_sol
        );     
        

        // Configurar la URL de la API
        $url = "https://gre-test.nubefact.com/v1/clientessol/{$row->cliente_id}/oauth2/token/";

        // Realizar la solicitud POST
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Decodificar la respuesta JSON
        $responseData = json_decode($response, true);

        if (isset($responseData['access_token'])) {
            return $responseData['access_token'];
        } else {
            return false;
        }
    }
}
}