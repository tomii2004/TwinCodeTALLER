<?php
class Turnos {
    private $pdo;

    public function __CONSTRUCT() {
        $this->pdo = BasedeDatos::connection();
    }

    public function obtenerTurnos() {
        $stmt = $this->pdo->prepare("SELECT * FROM turnos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarTurnos($nombre, $fecha) {
        $stmt = $this->pdo->prepare("INSERT INTO turnos (nombre,fecha) VALUES (?, ?)");
        return $stmt->execute([$nombre,$fecha]);
    }
    public function eliminarTurno($id) {
        $stmt = $this->pdo->prepare("DELETE FROM turnos WHERE ID_turnos = ?");
        return $stmt->execute([$id]);
    }
}
?>
