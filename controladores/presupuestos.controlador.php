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
    
        $datos = json_decode(file_get_contents("php://input"), true)['datos_pdf'];
    
        $fecha = date('d/m/Y');
        $fechaArchivo = date('Ymd');
    
        $logo = 'data:image/png;base64,' . base64_encode(file_get_contents('vistas/logo.png'));
    
        $html = '
        <style>
            body { font-family: Arial, sans-serif; font-size: 13px; }
            .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
            .logo img { width: 130px; }
            .titulo { text-align: right; }
            .titulo h2 { margin: 0; font-size: 22px; }
            .fecha { text-align: right; font-size: 12px; margin-top: 3px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #333; padding: 8px 10px; text-align: left; }
            th { background-color: #f2f2f2; }
            tr:nth-child(even) { background-color: #fafafa; }
            .total { text-align: right; font-weight: bold; padding: 10px; margin-top: 10px; }
        </style>
    
        <div class="header">
            <div class="logo">
                <img src="'.$logo.'" alt="Logo">
            </div>
            <div class="titulo">
                <h2>Presupuesto</h2>
                <div class="fecha">Fecha: '.$fecha.'</div>
            </div>
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
        foreach ($datos as $item) {
            $subtotal = $item['importe'] * $item['cantidad'];
            $html .= "<tr>
                        <td>{$item['producto']}</td>
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
        header('Content-Disposition: attachment; filename="presupuesto_'.$fechaArchivo.'.pdf"');
        echo $dompdf->output();
    
        exit;
    }
}
