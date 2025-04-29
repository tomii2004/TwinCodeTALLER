<?php
require_once 'modelos/caja.php';
class CajaControlador {
    private $modelo;

    public function __CONSTRUCT()
    {
        $this->modelo = new Caja();
    }

    public function Inicio()
    {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $servicios = $this->modelo->obtenerServiciosPorFecha($fecha);
        $productos = $this->modelo->obtenerCostoProductosPorFecha($fecha);

        $datos = [
            'fecha' => $fecha,
            'servicios' => $servicios['total_servicios'] ?? 0,
            'productos' => $productos['total_productos'] ?? 0,
        ];
        require_once "vistas/encabezado.php";
        require_once "vistas/caja/caja.php";
        require_once "vistas/pie.php";
    }

    public function cerrarCaja($fecha, $usuario_id, $observaciones = '') {
        if ($this->modelo->cierreExiste($fecha)) {
            echo "Ya se ha cerrado la caja para esta fecha.";
            return;
        }
    
        $servicios = $this->modelo->obtenerServiciosPorFecha($fecha)['total_servicios'] ?? 0;
        $productos = $this->modelo->obtenerCostoProductosPorFecha($fecha)['total_productos'] ?? 0;
        $pagos = $this->modelo->obtenerPagosPorFecha($fecha)['total_pagos'] ?? 0;
        $utilidad = $servicios - $productos;
    
        $datos = compact('fecha', 'servicios', 'productos', 'pagos', 'utilidad', 'observaciones', 'usuario_id');
    
        $this->modelo->guardarCierreCaja($datos);
        echo "Cierre de caja guardado correctamente.";
    }

    public function registrarMovimiento() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo = $_POST['tipo'];
            $concepto = $_POST['concepto'];
            $monto = $_POST['monto'];
            $fecha = $_POST['fecha'];
            $usuario_id = $_SESSION['usuario_id']; // Asegúrate de tenerlo en sesión
    
            $this->modelo->registrarMovimientoManual($tipo, $concepto, $monto, $fecha, $usuario_id);
            header("Location: ?c=caja&fecha=" . $fecha);
        }
    }

    
}
