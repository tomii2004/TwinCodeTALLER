<?php
//este front controller recibe las peticiones por la url e intancia cualquier elemento
session_start();

require_once "modelos/basededatos.php";

// Verifica si el usuario está autenticado
function VerificarLogin() {
    // Lista de controladores y acciones permitidas sin login
    $AccionesPermitidas = [
        'usuarios' => ['Inicio', 'login']
    ];

    $ControladorActual = isset($_GET['c']) ? $_GET['c'] : 'Inicio';

    $AccionActual = isset($_GET['a']) ? $_GET['a'] : 'Inicio';

    // Si el usuario no está autenticado
    if (!isset($_SESSION['Autenticado']) || $_SESSION['Autenticado'] !== true) {
        // Si el controlador actual NO es Usuario o la acción no es permitida, redirige al login
        if (!isset($AccionesPermitidas[$ControladorActual]) || !in_array($AccionActual, $AccionesPermitidas[$ControladorActual])) {
            header('Location: ?c=usuarios&a=Inicio'); // Redirige al login
            exit();
        }
    }
}

// Verifica la autenticación antes de determinar el controlador
VerificarLogin();


if(!isset($_GET['c'])){ //esto cuando no le pasamos un valor del controlador
    require_once "controladores/inicio.controlador.php";
    $controlador = new InicioControlador();
    call_user_func(array($controlador,"Inicio")); // va a llamar un metodo del controlador llamado Inicio
}else{
    $controlador = $_GET['c'];
    require_once "controladores/$controlador.controlador.php"; //va a tomar el nombre que le pasemos por la url y el prefijo .controlador
    $controlador = ucwords($controlador)."Controlador"; // ucword pasa el primer caracter a mayuscula
    $controlador = new $controlador; //instancio un objeto
    $accion = isset($_GET['a'])?$_GET['a'] : "Inicio";
    call_user_func(array($controlador,$accion));
}