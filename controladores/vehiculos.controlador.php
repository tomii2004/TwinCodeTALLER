<?php


include 'modelos/vehiculo.php';

class VehiculosControlador
{
   
    private $modeloVehiculo;
    public function __CONSTRUCT()
    {
     
        $this->modeloVehiculo = new Vehiculo();
    }



        public function obtenerVehiculosPorCliente()
    {
        if (isset($_GET['id_cliente'])) {
            $id_cliente = $_GET['id_cliente'];
            $vehiculos = $this->modeloVehiculo->obtenerVehiculosPorCliente($id_cliente);
            echo json_encode($vehiculos);
        }
    }

    // Obtener datos de un vehículo (chasis y motor)
    public function obtenerDatosVehiculo()
    {
        if (isset($_GET['id_vehiculo'])) {
            $id_vehiculo = $_GET['id_vehiculo'];
            $datosVehiculo = $this->modeloVehiculo->obtenerDatosVehiculo($id_vehiculo);
            echo json_encode($datosVehiculo);
        }
    }
}
