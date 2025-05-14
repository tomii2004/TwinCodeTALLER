<?php
require_once 'modelos/presupuestos.php';

class PresupuestosControlador {

    private $modelo;

    public function __CONSTRUCT(){
        $this-> modelo = new Presupuestos();
    }

    public function Inicio() {
        $productos = $this->modelo->ObtenerProductos();
        require_once "vistas/encabezado.php";
        require_once "vistas/presupuestos/presupuestos.php";
        require_once "vistas/pie.php";
    }

    public function generarPDF() {
        require 'dompdf/vendor/autoload.php';
    
        $input = json_decode(file_get_contents("php://input"), true)['datos_pdf'];
    
        $productos = $input['productos'];
        $propietario = $input['propietario'] ?? 'No especificado';
        $vehiculo = $input['vehiculo'] ?? 'No especificado';
        
        $fecha = date('d/m/Y');
        $fechaArchivo = date('Ymd');
        
        $logo = 'data:image/png;base64,' . base64_encode(file_get_contents('vistas/logojb1.png'));
    
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
                <img src="'.$logo.'" alt="Logo">
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

        <h3 style="text-align: center;">Presupuesto</h3>
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
      
        <p><strong>Importante:</strong> El siguiente presupuesto tendra vigencia 15 dias luego de su emision </p>

        ';

    
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="presupuesto_'.$fechaArchivo.'.pdf"');
        echo $dompdf->output();
        exit;
    }
}