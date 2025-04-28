
<?php
class Vehiculo
{
    private $pdo;

    public function __CONSTRUCT()
    {
        $this->pdo = BasedeDatos::connection();
    }
    // Obtener vehículos de un cliente
    public function obtenerVehiculosPorCliente($id_cliente)
    {
        $sql = "SELECT * FROM vehiculo WHERE id_cliente = :id_cliente";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener datos del vehículo (chasis y motor)
    public function obtenerDatosVehiculo($id_vehiculo)
    {
        $sql = "SELECT Numero_Chasis, Numero_Motor, ID_vehiculo,Nombre, ID_cliente FROM vehiculo WHERE id_vehiculo = :id_vehiculo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_vehiculo', $id_vehiculo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
