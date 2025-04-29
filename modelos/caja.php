<?php


class Caja
{
    private $pdo;

    public function __CONSTRUCT()
    {
        $this->pdo = BasedeDatos::connection(); // Tu clase de conexión personalizada
    }

    public function obtenerServiciosPorFecha($fecha) {
        $sql = "SELECT SUM(tp.preciounitario * tp.cantidad) AS total_servicios
                FROM trabajos_productos tp
                JOIN trabajos t ON tp.ID_trabajo = t.ID_trabajo
                JOIN productos p ON tp.ID_producto = p.ID_productos
                JOIN categorias c ON p.ID_categoria = c.ID_categoria
                WHERE DATE(t.fecha) = ? AND LOWER(c.nombre) = 'servicio'";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fecha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerCostoProductosPorFecha($fecha) {
        $sql = "SELECT SUM(tp.preciounitario * tp.cantidad) AS total_productos
                FROM trabajos_productos tp
                JOIN trabajos t ON tp.ID_trabajo = t.ID_trabajo
                JOIN productos p ON tp.ID_producto = p.ID_productos
                JOIN categorias c ON p.ID_categoria = c.ID_categoria
                WHERE DATE(t.fecha) = ? AND LOWER(c.nombre) != 'servicio'";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fecha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarCierreCaja($datos) {
        $sql = "INSERT INTO cierres_caja 
                (fecha, ingresos_servicios, costo_productos, total_pagos, utilidad_bruta, observaciones, creado_por) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->pdo->execute($sql, [
            $datos['fecha'],
            $datos['servicios'],
            $datos['productos'],
            $datos['pagos'],
            $datos['utilidad'],
            $datos['observaciones'],
            $datos['usuario_id']
        ]);
    }
    
    public function cierreExiste($fecha) {
        $sql = "SELECT COUNT(*) as total FROM cierres_caja WHERE fecha = ?";
        $res = $this->pdo->fetchOne($sql, [$fecha]);
        return $res['total'] > 0;
    }

    public function registrarMovimientoManual($tipo, $concepto, $monto, $fecha, $usuario_id) {
        $sql = "INSERT INTO movimientos_caja (fecha, tipo, concepto, monto, creado_por)
                VALUES (?, ?, ?, ?, ?)";
        return $this->pdo->execute($sql, [$fecha, $tipo, $concepto, $monto, $usuario_id]);
    }
    
    public function obtenerMovimientosPorFecha($fecha) {
        $sql = "SELECT * FROM movimientos_caja WHERE fecha = ? ORDER BY creado_en";
        return $this->pdo->fetchAll($sql, [$fecha]);
    }
}