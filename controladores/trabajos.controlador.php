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

        // Recibimos los datos via JSON
        $input = json_decode(file_get_contents("php://input"), true)['datos_pdf'];

        // Datos básicos del trabajo
       
        $fechaTrabajo = $input['fecha']       ?? 'N/D';
        $propietario = $input['propietario'] ?? 'No especificado';
        $vehiculo    = $input['vehiculo']    ?? 'No especificado';
        $Nota        = $input['nota']        ?? 'No especificado';
        

        // Productos
        $productos = $input['productos'] ?? [];

        // Fechas
        $fecha       = date('d/m/Y');
        $fechaArchivo = date('Ymd_His');

        // Logo en base64
        $logo = 'data:image/png;base64,' . base64_encode(file_get_contents('vistas/logojb1.png'));

        // Construimos el HTML
        $html = '
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
            .header { margin-bottom: 20px; text-align: center; }
            .logo img { width: 120px; }
            .header-title { font-size: 22px; margin: 5px 0 0 0; }
            .header-table { 
                width: 100%; 
                margin-top: 10px; 
                font-size: 11px; 
                border: none; 
                border-collapse: collapse;
            }
            .header-table td { 
                vertical-align: top; 
                border: none;
            }
            .products-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            .products-table th {
                background-color: #eee;
                border: 1px solid #000;
                padding: 5px;
                text-align: center;
            }
            .products-table td {
                padding: 5px;
                text-align: center;
                border: none;
            }
            .products-table tfoot td {
                border-top: 1px solid #000;
                padding: 5px;
            }
        </style>

        <div class="header">
            <div class="logo">
                <img src="' . $logo . '" alt="Logo">
            </div>
            <h2 class="header-title">Taller de Motos JB</h2>

            <table class="header-table">
                <tr>
                    <td style="text-align: left;">
                        Larriera 1425<br>
                        Venado Tuerto, Santa Fe
                    </td>
                    <td style="text-align: right;">
                        MONOTRIBUTO: Cat. A<br>
                        CUIL: 20-39110870-7
                    </td>
                </tr>
            </table>
        </div>

        <hr>

        <h3 style="text-align: center;">Trabajo Realizado</h3>

        <p><strong>Fecha:</strong> ' . $fechaTrabajo . '</p>
        <p><strong>Propietario:</strong> ' . $propietario . '</p>
        <p><strong>Vehiculo:</strong> ' . $vehiculo . '</p>

        <table class="products-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Importe Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>';
        $total = 0;
        foreach ($productos as $item) {
            $subtotal = $item['importe'] * $item['cantidad'];
            $total += $subtotal;
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($item['producto']) . '</td>
                    <td>$' . number_format($item['importe'], 2) . '</td>
                    <td>' . intval($item['cantidad']) . '</td>
                    <td>$' . number_format($subtotal, 2) . '</td>
                </tr>';
        }
        $html .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>$' . number_format($total, 2) . '</strong></td>
                </tr>
            </tfoot>
        </table>
      
        <p><strong>Nota:</strong> ' . $Nota . '</p>

        ';

        // Renderizamos con Dompdf
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        if (ob_get_length()) ob_clean();
        flush();

        // Enviamos el PDF al navegador
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="trabajo_' . $fechaArchivo . '.pdf"');
        echo $dompdf->output();
        exit;
    }


    public function filtrarPorFecha()
    {
        // Obtener las fechas desde la URL
        $fecha_inicio = $_GET['fecha_inicio'];  // Formato YYYY-MM-DD
        $fecha_fin = $_GET['fecha_fin'];  // Formato YYYY-MM-DD

        // Asegurarse de que las fechas son válidas
        if (empty($fecha_inicio) || empty($fecha_fin)) {
            echo json_encode(['error' => 'Fechas inválidas']);
            return;
        }

        // Convertir las fechas a formato de base de datos (si es necesario)
        $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio)); // Asegurarse de que el formato sea correcto
        $fecha_fin = date('Y-m-d', strtotime($fecha_fin));

        // Consultar los trabajos dentro de ese rango de fechas

        $trabajosFiltrados = $this->modeloTrabajo->obtenerTrabajosPorFecha($fecha_inicio, $fecha_fin);

        // Devolver los resultados como JSON
        echo json_encode(['trabajos' => $trabajosFiltrados]);
    }
}
