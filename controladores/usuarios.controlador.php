<?php
require_once 'modelos/admin.php';

class UsuariosControlador {
    private $modeloAdmin;

    public function __construct() {
        $this->modeloAdmin = new Admin();
    }

    public function Inicio() {
       
        require_once "vistas/usuarios/login.php";
    
    }   

   

    public function login() {
        $Usuario = $_POST['Nombre'];
        $password = $_POST['Password'];

        $user = $this->modeloAdmin->ConsultarUsuario($Usuario);
        
        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['Autenticado'] = true;

            $_SESSION['Nombre'] = $user['Nombre'];
            
            header('Location: ?c=turnos'); 
            exit();
        } else {
            $error = 'Nombre de usuario o contraseña incorrectos';
            include 'vistas/usuarios/login.php';
        }
    }
    

    public function cerrarSesion() {
       
        session_unset(); 
        session_destroy(); 

        header('Location: ?c=usuarios&a=Inicio');
        exit();
    }



   
}