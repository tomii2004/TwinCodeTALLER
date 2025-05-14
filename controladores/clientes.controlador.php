<?php
include 'modelos/clientes.php';

class ClientesControlador
{
    private $modelo;

    public function __CONSTRUCT()
    {
        $this->modelo = new Clientes();
    }

    public function Inicio()
    {
        $clientes = $this->modelo->ObtenerClientes();
        require_once "vistas/encabezado.php";
        require_once "vistas/clientes/clientes.php";
        require_once "vistas/pie.php";
    }

    // Obtener todos los clientes
    public function obtenerClientes()
    {
        $clientes = $this->modelo->ObtenerClientes();
        echo json_encode($clientes);
    }


    public function Detalles()
    {
        $idCliente = $_GET['id']; // o como lo estés recibiendo
        $cliente = $this->modelo->ObtenerClienteId($idCliente);
        $vehiculoSeleccionado = $_GET['vehiculo'] ?? 'todos';
        $trabajos = $this->modelo->ObtenerTrabajos($idCliente, $vehiculoSeleccionado);
        $vehiculos = $this->modelo->ObtenerVehiculosPorCliente($idCliente);

        require_once "vistas/encabezado.php";
        require_once "vistas/clientes/detalles.php";
        require_once "vistas/pie.php";
    }



    public function FormNuevo()
    {
        require_once "vistas/encabezado.php";
        require_once "vistas/clientes/nuevo.php";
        require_once "vistas/pie.php";
    }

    public function FormEditar()
    {
        $cliente = $this->modelo->ObtenerClienteId($_GET['id']);
        require_once "vistas/encabezado.php";
        require_once "vistas/clientes/editar.php";
        require_once "vistas/pie.php";
    }

    public function AgregarCliente()
    {
        $resultado = $this->modelo->AgregarCliente($_POST);
        header("Location: ?c=clientes&alerta=" . ($resultado ? "success" : "error"));
        exit();
    }

    public function AgregarClienteEnTrabajo()
    {
        $resultado = $this->modelo->AgregarCliente($_POST);
        header("Location: ?c=trabajos&a=nuevoTrabajo&alerta=" . ($resultado ? "success" : "error"));
        exit();
    }

    public function ActualizarCliente()
    {
        $this->modelo->ActualizarCliente($_POST);
        header("Location: ?c=clientes");
    }

    public function EliminarCliente()
    {
        if (isset($_POST['id'])) {
            $resultado = $this->modelo->EliminarCliente($_POST['id']);
            header("Location: ?c=clientes" . (!$resultado ? "&alerta=uso" : ""));
            exit;
        } else {
            echo "Error: No se recibió un ID válido.";
        }
    }

    public function AgregarVehiculo()
    {
        $idcliente = $_POST['clienteID'];
        $nombre = $_POST['vehiculoNombre'];
        $chasis = $_POST['numeroChasis'];
        $motor = $_POST['numeroMotor'];

        $resultado = $this->modelo->AgregarVehiculo($idcliente, $nombre, $chasis, $motor);
        header("Location: ?c=clientes&alerta=" . ($resultado ? "success" : "error"));
        exit();
    }

    public function ObtenerTrabajosJSON()
    {
        $id_cliente = $_GET['cliente'];
        $vehiculo   = $_GET['vehiculo'] ?? 'todos';
        header('Content-Type: application/json');
        echo json_encode($this->modelo->ObtenerTrabajos($id_cliente, $vehiculo));
        exit;
    }
    public function CambiarEstado(){
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $id = $_GET['id'];
            $estado = $_GET['estado'];
            $this->modelo->CambiarEstado($id, $estado);
            header('Location: ?c=clientes');
            exit();
        }
    }
}
