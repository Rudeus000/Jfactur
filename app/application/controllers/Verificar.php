<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Verificar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Carga el modelo dentro del constructor si no lo has hecho ya
        $this->load->model('Modelgeneral');
    }


    // En tu archivo verificar_email.php

    public function verificar_email()
    {
        // Suponiendo que recibes el token a través de la URL
        $token = $_GET['token'];

        // Realizar la consulta para verificar el token en la tabla tokens_temporales
        $tokenData = $this->Modelgeneral->getTableWhereRow('tokens_temporales', ['token' => $token]);

        if ($tokenData) {
            // Verificar si el token no ha sido usado
            if ($tokenData->usado == 0) {
                // El token existe y no ha sido usado, puedes marcarlo como usado en la base de datos
                // Actualizar el estado de 'usado' a 1 para indicar que ha sido utilizado
                $updateData = ['usado' => 1];
                $this->modelgeneral->editRegist('tokens_temporales', ['id' => $tokenData->id], $updateData);

                // Verificar si el usuario existe en la tabla de usuarios
                $userId = $tokenData->cod_usu;
                $userData = $this->Modelgeneral->getTableWhereRow('tb_usuario', ['cod_usu' => $userId]);

                if ($userData && $userData->estado_usuario == 0) {


                    // Si el usuario existe y su estado de verificación es 0, actualiza su estado
                    $updateUserData = ['estado_usuario' => 1];
                    $this->Modelgeneral->editRegist('tb_usuario', ['cod_usu' => $userId], $updateUserData);

                    // Acciones adicionales si es necesario
                    // ...

                    echo "¡Tu correo electrónico ha sido verificado correctamente!";
                } elseif (!$userData) {
                    echo "El usuario no existe. Error al verificar el correo electrónico.";
                } else {
                    echo "El usuario ya ha verificado su correo electrónico.";
                }
            } else {
                // El token ya ha sido utilizado previamente
                echo "El enlace de verificación ya ha sido utilizado.";
            }
        } else {
            // El token no existe o es inválido
            echo "El enlace de verificación es inválido o ha expirado. Por favor, solicita uno nuevo.";
        }
    }
}
