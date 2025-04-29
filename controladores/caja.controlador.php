<?php
require_once 'modelos/Caja.php';

class CajaControlador {
    private $modelo;

    public function __CONSTRUCT()
    {
        $this->modelo = new Caja();
    }

    public function Inicio()
    {
        // Fecha seleccionada o la de hoy si no se pasa
        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        // Totales automáticos
        $datosCaja = $this->modelo->calcularCajaPorFecha($fecha);
        // Detalle de cada línea
        $detalle   = $this->modelo->obtenerDetallePorFecha($fecha);

        require_once "vistas/encabezado.php";
        require_once "vistas/caja/caja.php";
        require_once "vistas/pie.php";
    }
}
