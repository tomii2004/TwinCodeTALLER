<?php
require_once 'modelos/turnos.php';

class TurnosControlador {
    private $modelo;

    public function __CONSTRUCT() {
        $this->modelo = new Turnos();
    }

    public function Inicio() {
        $turnos = $this->modelo->obtenerTurnos();
        require_once 'vistas/encabezado.php';
        require_once 'vistas/turnos/turnos.php';
        require_once 'vistas/pie.php';
    }

    public function guardarTurnoDesdeCalendario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $fecha = $_POST['fecha']; // Viene como 'YYYY-MM-DD' 
            $this->modelo->agregarTurnos($nombre, $fecha);
    
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function obtenerTurnosJson() {
        $turnos = $this->modelo->obtenerTurnos();
    
        $eventos = array_map(function($turno) {
            return [
                'id' => $turno['ID_turnos'], 
                'title' => $turno['nombre'],
                'start' => $turno['fecha'],
                'color' => '#e31414' // Rosa claro (Diseño/Fachada)
            ];
        }, $turnos);
    
        header('Content-Type: application/json');
        echo json_encode($eventos);
    }
    public function eliminarTurnoDesdeCalendario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->modelo->eliminarTurno($id);
    
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
?>
