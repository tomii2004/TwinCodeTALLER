<?php
class Clientes
{
    private $pdo;

    public function __CONSTRUCT()
    {
        $this->pdo = BasedeDatos::connection(); // Tu clase de conexión personalizada
    }

    public function ObtenerClientes()
    {
        $query = $this->pdo->prepare("SELECT ID_cliente, Nombre, Telefono FROM clientes");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ObtenerClienteId($id)
    {
        $query = $this->pdo->prepare("SELECT ID_cliente, Nombre, Telefono FROM clientes WHERE ID_cliente = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function AgregarCliente()
    {
        // 1) Datos del cliente
        $nombre   = strtolower(trim($_POST['nombre']));
        $telefono = trim($_POST['telefono']);

        // 2) Verificar duplicado
        $sql = $this->pdo->prepare("SELECT COUNT(*) FROM clientes WHERE LOWER(nombre) = ?");
        $sql->execute([$nombre]);
        if ($sql->fetchColumn() > 0) {
            return false;
        }

        // 3) Insertar cliente
        $sql = $this->pdo->prepare("INSERT INTO clientes (nombre, telefono) VALUES (?, ?)");
        $sql->execute([$nombre, $telefono]);

        // 4) Obtener el ID generado
        $idCliente = $this->pdo->lastInsertId();

        // 5) Si vienen datos de vehículo, insertarlos
        $vehNombre    = trim($_POST['vehiculoNombre']   ?? '');
        $numeroChasis = trim($_POST['numeroChasis']     ?? '');
        $numeroMotor  = trim($_POST['numeroMotor']      ?? '');

        if ($vehNombre !== '' && $numeroChasis !== '' && $numeroMotor !== '') {
            $this->AgregarVehiculo(
                $idCliente,
                $vehNombre,
                $numeroChasis,
                $numeroMotor
            );
        }

        return true;
    }


    public function ActualizarCliente()
    {
        $sql = $this->pdo->prepare("UPDATE clientes SET nombre = ?, telefono = ? WHERE ID_cliente = ?");
        $sql->execute([$_POST['nombre'], $_POST['telefono'], $_POST['id']]);
    }

    public function EliminarCliente($id)
    {
        $sql = $this->pdo->prepare("DELETE FROM clientes WHERE ID_cliente = ?");
        return $sql->execute([$id]);
    }

    public function ObtenerTrabajos($id_cliente, $vehiculo = 'todos')
    {
        if ($vehiculo === 'todos') {
            $sql = $this->pdo->prepare("
            SELECT t.ID_trabajo, DATE_FORMAT(t.Fecha, '%d/%m/%Y') AS Fecha, t.Total, v.Nombre AS vehiculo
            FROM trabajos t
            INNER JOIN vehiculo v ON t.ID_vehiculo = v.ID_vehiculo
            WHERE v.ID_cliente = ?
            ORDER BY t.Fecha DESC
        ");
            $sql->execute([$id_cliente]);
        } else {
            $sql = $this->pdo->prepare("
            SELECT t.ID_trabajo, DATE_FORMAT(t.Fecha, '%d/%m/%Y') AS Fecha, t.Total, v.Nombre AS vehiculo
            FROM trabajos t
            INNER JOIN vehiculo v ON t.ID_vehiculo = v.ID_vehiculo
            WHERE v.ID_cliente = ? AND v.Nombre = ?
            ORDER BY t.Fecha DESC
        ");
            $sql->execute([$id_cliente, $vehiculo]);
        }

        $trabajos = $sql->fetchAll(PDO::FETCH_ASSOC);

        // Agregar productos a cada trabajo
        foreach ($trabajos as &$trabajo) {
            $trabajo['Productos'] = $this->ObtenerProductosPorTrabajo($trabajo['ID_trabajo']);
        }

        return $trabajos;
    }

    public function ObtenerProductosPorTrabajo($id_trabajo)
    {
        $sql = $this->pdo->prepare("
        SELECT p.Nombre AS NombreProducto, tp.Cantidad, tp.PrecioUnitario
        FROM trabajos_productos tp
        INNER JOIN productos p ON tp.ID_producto = p.ID_productos
        WHERE tp.ID_trabajo = ?
    ");
        $sql->execute([$id_trabajo]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }




    public function ObtenerVehiculosPorCliente($id_cliente)
    {
        $query = $this->pdo->prepare("SELECT Nombre FROM vehiculo WHERE ID_cliente = ?");
        $query->execute([$id_cliente]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function AgregarVehiculo($id_cliente, $nombre_vehiculo, $numero_chasis, $numero_motor)
    {
        $sql = $this->pdo->prepare("INSERT INTO vehiculo (ID_cliente, Nombre, Numero_chasis, Numero_motor) VALUES (?, ?, ?, ?)");
        return $sql->execute([$id_cliente, $nombre_vehiculo, $numero_chasis, $numero_motor]);
    }
}
