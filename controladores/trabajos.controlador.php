<?php

include 'modelos/presupuestos.php';
include 'modelos/vehiculo.php';
include 'modelos/trabajos.php';
include 'modelos/clientes.php';

class TrabajosControlador
{
    private $modelo;
    private $modeloVehiculo;
    private $modeloTrabajo;
    private $modeloCliente;
    public function __CONSTRUCT()
    {
        $this->modelo = new Presupuestos();
        $this->modeloVehiculo = new Vehiculo();
        $this->modeloTrabajo = new Trabajos();
        $this->modeloCliente = new Clientes();
    }



    public function Inicio()
    {
        $clientes = $this->modeloCliente->ObtenerClientes();
        $trabajos = $this->modeloTrabajo->obtenerTrabajos();
        require_once "vistas/encabezado.php";
        require_once "vistas/trabajos/trabajos.php";
        require_once "vistas/pie.php";
    }
    public function ObtenerProductosTrabajoJSON()
    {
        $idTrabajo = $_GET['ID_trabajo'];
        $productos = $this->modeloTrabajo->ObtenerProductosPorTrabajo($idTrabajo);
        echo json_encode($productos);
    }



    public function nuevoTrabajo()
    {
        $productos = $this->modelo->ObtenerProductos();
        require_once "vistas/encabezado.php";
        require_once "vistas/trabajos/nuevo.php";
        require_once "vistas/pie.php";
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

    public function guardarTrabajo()
    {
        // Verificar si se recibieron los datos
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener los datos enviados
            $cliente = $_POST['cliente'];
            $vehiculoId = $_POST['vehiculo'];  // Ahora recibimos el ID del vehículo
            $productos = json_decode($_POST['productos'], true); // Decodificar JSON
            $totalPresupuesto = $_POST['totalPresupuesto'];
            $nota = trim($_POST['nota'] ?? '');



            $trabajoId =  $this->modeloTrabajo->guardarTrabajo($cliente, $vehiculoId, $totalPresupuesto, $nota);

            // Guardar la relación entre trabajo y productos
            $this->modeloTrabajo->guardarProductosTrabajo($trabajoId, $productos);

            // Redirigir o mostrar mensaje de éxito
            header("Location: ?c=trabajos&alerta=success");
            exit();
        }
    }

    public function generarPDF()
    {
        require 'dompdf/vendor/autoload.php';

        // Obtener los datos del JSON
        $datos = json_decode(file_get_contents("php://input"), true);

        // Datos del cliente y vehículo (asegúrate de acceder correctamente a las propiedades)
        $cliente = $datos['cliente']['nombre']; // El nombre del cliente
        $vehiculo = $datos['vehiculo']['nombre']; // El nombre del vehículo
        $datos_pdf = $datos['datos_pdf']; // Los productos de la factura

        $fecha = date('d/m/Y');
        $fechaArchivo = date('Ymd');

        $logo = 'data:image/png;base64,' . base64_encode(file_get_contents('vistas/logo.png'));

        // HTML
        $html = '
        <style>
            body { font-family: Arial, sans-serif; font-size: 13px; }
            .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
            .logo img { width: 130px; }
            .titulo { text-align: right; }
            .titulo h2 { margin: 0; font-size: 22px; }
            .fecha { text-align: right; font-size: 12px; margin-top: 3px; }
            .cliente-vehiculo { margin-top: 10px; font-size: 14px; text-align: right; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #333; padding: 8px 10px; text-align: left; }
            th { background-color: #f2f2f2; }
            tr:nth-child(even) { background-color: #fafafa; }
            .total { text-align: right; font-weight: bold; padding: 10px; margin-top: 10px; }
        </style>
    
        <div class="header">
            <div class="logo">
                <img src="' . $logo . '" alt="Logo">
            </div>
            <div class="titulo">
                <h2>Trabajo</h2>
                <div class="fecha">Fecha: ' . $fecha . '</div>
            </div>
        </div>
    
        <div class="cliente-vehiculo">
            <strong>Cliente:</strong> ' . $cliente . '<br>
            <strong>Vehículo:</strong> ' . $vehiculo . '
        </div>
    
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>';

        $total = 0;
        foreach ($datos_pdf as $item) {
            $subtotal = $item['importe'] * $item['cantidad'];
            $html .= "<tr>
                        <td>{$item['nombre']}</td>
                        <td>{$item['cantidad']}</td>
                        <td>$ " . number_format($item['importe'], 2) . "</td>
                        <td>$ " . number_format($subtotal, 2) . "</td>
                      </tr>";
            $total += $subtotal;
        }

        $html .= '</tbody></table>';
        $html .= '<div class="total">Total: $ ' . number_format($total, 2) . '</div>';

        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="trabajo_' . $fechaArchivo . '.pdf"');
        echo $dompdf->output();

        exit;
    }
}
