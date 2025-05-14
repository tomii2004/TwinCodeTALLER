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
        $modo  = $_GET['modo'] ?? 'dia';
        $tituloFecha = "Caja de hoy";
        if ($modo === 'rango') {
            $desde = $_GET['desde'] ?? null;
            $hasta = $_GET['hasta'] ?? null;

            if ($desde && $hasta && $desde <= $hasta) {
                $datosCaja = $this->modelo->calcularCajaPorRango($desde, $hasta);
                $detalle   = $this->modelo->obtenerDetallePorRango($desde, $hasta);
                $tituloFecha = "Rango del $desde al $hasta";
            } else {
                $datosCaja = ['ingresos' => 0, 'egresos' => 0, 'total' => 0];
                $detalle = [];
                $tituloFecha = "Fechas inválidas para rango";
            }

        } else {
            $fecha = $_GET['fecha'] ?? null;
            if ($fecha) {
                $datosCaja = $this->modelo->calcularCajaPorFecha($fecha);
                $detalle   = $this->modelo->obtenerDetallePorFecha($fecha);
                $tituloFecha = "Día $fecha";
            } else {
                $datosCaja = ['ingresos' => 0, 'egresos' => 0, 'total' => 0];
                $detalle = [];
                $tituloFecha = "Caja de hoy";
            }
        }

        require_once "vistas/encabezado.php";
        require_once "vistas/caja/caja.php";
        require_once "vistas/pie.php";
    }


}
