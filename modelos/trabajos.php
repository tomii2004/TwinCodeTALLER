<?php

class Trabajos
{

    private $pdo;

    public function __construct()
    {
        // Crear conexión a la base de datos
        $this->pdo = BasedeDatos::connection();
    }

    public function obtenerTrabajos()
    {
        $query = $this->pdo->prepare("
            SELECT t.ID_trabajo, DATE_FORMAT(t.Fecha, '%d/%m/%Y') AS Fecha, t.Total, v.Nombre AS Vehiculo, c.Nombre AS Cliente, t.Nota
            FROM trabajos t
            JOIN vehiculo v ON t.ID_vehiculo = v.ID_vehiculo
            JOIN clientes c ON v.ID_cliente = c.ID_cliente ORDER BY t.Fecha DESC, t.ID_trabajo DESC
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    // Función para guardar el trabajo
    public function guardarTrabajo($cliente, $vehiculoId, $totalTrabajo, $nota)
    {
        $fechaHoy = date('Y-m-d');
        // Preparar la consulta SQL para insertar el trabajo
        $query = "INSERT INTO trabajos (ID_cliente, ID_vehiculo, Total, Nota, Fecha) 
              VALUES (:cliente, :vehiculo_id, :totalTrabajo,:Nota, :fecha)";
        // Preparar la sentencia
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':vehiculo_id', $vehiculoId); // Guardamos solo el ID del vehículo
        $stmt->bindParam(':totalTrabajo', $totalTrabajo);
        $stmt->bindParam(':Nota', $nota);
        $stmt->bindParam(':fecha', $fechaHoy);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el ID del trabajo insertado
        return $this->pdo->lastInsertId();
    }

    // Función para guardar los productos en la tabla de trabajo_producto
    public function guardarProductosTrabajo($trabajoId, $productos)
    {
        foreach ($productos as $producto) {
            // Usamos id_producto e importe, que es lo que manda tu JS
            $idProducto    = $producto['id_producto'];
            $cantidad      = $producto['cantidad'];
            $precioUnitario = $producto['importe'];

            // Insertar en la tabla intermedia trabajos_productos
            $query = "INSERT INTO trabajos_productos 
                      (ID_trabajo, ID_producto, Cantidad, PrecioUnitario) 
                      VALUES 
                      (:trabajo_id, :producto_id, :cantidad, :precio_unitario)";

            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':trabajo_id',     $trabajoId);
            $stmt->bindParam(':producto_id',    $idProducto);
            $stmt->bindParam(':cantidad',       $cantidad);
            $stmt->bindParam(':precio_unitario', $precioUnitario);
            $stmt->execute();
        }
    }

    public function ObtenerProductosPorTrabajo($id_trabajo)
    {
        $sql = $this->pdo->prepare("
        SELECT tp.ID_producto AS ID_producto,p.Nombre AS NombreProducto, tp.Cantidad, tp.PrecioUnitario
        FROM trabajos_productos tp
        INNER JOIN productos p ON tp.ID_producto = p.ID_productos
        WHERE tp.ID_trabajo = ?
    ");
        $sql->execute([$id_trabajo]);
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTrabajosPorFecha($fecha_inicio, $fecha_fin) {
        // Suponiendo que tienes una conexión a la base de datos llamada $db
        $sql = "SELECT 
            t.ID_trabajo, 
            DATE_FORMAT(t.Fecha, '%d/%m/%Y') AS Fecha, 
            t.Total, 
            v.Nombre AS Vehiculo,
            v.ID_vehiculo,
            v.ID_cliente,
            c.Nombre AS Cliente, 
            t.Nota
        FROM trabajos t
        JOIN vehiculo v ON t.ID_vehiculo = v.ID_vehiculo
        JOIN clientes c ON v.ID_cliente = c.ID_cliente
        WHERE t.Fecha BETWEEN :fecha_inicio AND :fecha_fin
        ORDER BY t.Fecha DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->execute();

        // Obtener los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarPrecioProductoTrabajo($idTrabajo, $idProducto, $nuevoPrecio)
    {
        $sql = "UPDATE trabajos_productos 
                SET PrecioUnitario = ? 
                WHERE ID_trabajo = ? AND ID_producto = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$nuevoPrecio, $idTrabajo, $idProducto]);
    }
    public function actualizarTotalTrabajo($idTrabajo)
    {
        $sql = "SELECT SUM(Cantidad * PrecioUnitario) AS total
                FROM trabajos_productos
                WHERE ID_trabajo = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idTrabajo]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = $resultado['total'] ?? 0;

        $sqlUpdate = "UPDATE trabajos SET Total = ? WHERE ID_trabajo = ?";
        $stmtUpdate = $this->pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$total, $idTrabajo]);
    }
    public function borrarProductosDeTrabajo($idTrabajo)
    {
        $stmt = $this->pdo->prepare("DELETE FROM trabajos_productos WHERE ID_trabajo = ?");
        $stmt->execute([$idTrabajo]);
    }

    public function borrarTrabajo($idTrabajo)
    {
        $stmt = $this->pdo->prepare("DELETE FROM trabajos WHERE ID_trabajo = ?");
        $stmt->execute([$idTrabajo]);
    }
}
