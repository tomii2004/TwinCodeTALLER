<?php
class Caja
{
    private $pdo;

    public function __CONSTRUCT()
    {
        $this->pdo = BasedeDatos::connection();
    }

    /**
     * Calcula totales de ingresos (Servicios) y egresos (otros productos)
     * para la fecha dada.
     */
    public function calcularCajaPorFecha($fecha)
    {
        $sql = "
            SELECT 
                c.nombre AS categoria,
                p.nombre AS producto,
                tp.cantidad,
                tp.preciounitario,
                (tp.cantidad * tp.preciounitario) AS total
            FROM trabajos_productos tp
            INNER JOIN productos p   ON p.ID_productos   = tp.ID_producto
            INNER JOIN categorias c  ON c.ID_categoria   = p.ID_categoria
            INNER JOIN trabajos t    ON t.ID_trabajo      = tp.ID_trabajo
            WHERE DATE(t.Fecha) = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fecha]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ingresos = 0;
        $egresos  = 0;

        foreach ($productos as $p) {
            if (strtolower(trim($p['categoria'])) === 'servicio') {
                $ingresos += $p['total'];
            } else {
                $egresos += $p['total'];
            }
        }

        return [
            'fecha'    => $fecha,
            'ingresos' => $ingresos,
            'egresos'  => $egresos,
            'total' => $ingresos + $egresos
        ];
    }

    /**
     * Devuelve el detalle de cada trabajo para la fecha dada,
     * agrupando los ítems por trabajo y luego por producto,
     * y usando el Total ya calculado en la tabla trabajos.
     */
    public function obtenerDetallePorFecha($fecha)
    {
        $sql = "
            SELECT
                t.ID_trabajo,
                t.Nota,
                t.Total AS total_trabajo,
                c.nombre                          AS categoria,
                p.nombre                          AS producto,
                SUM(tp.cantidad)                  AS cantidad,
                tp.preciounitario,
                SUM(tp.cantidad * tp.preciounitario) AS total
            FROM trabajos_productos tp
            INNER JOIN trabajos t    ON t.ID_trabajo     = tp.ID_trabajo
            INNER JOIN productos p   ON p.ID_productos    = tp.ID_producto
            INNER JOIN categorias c  ON c.ID_categoria    = p.ID_categoria
            WHERE DATE(t.Fecha) = ?
            GROUP BY 
                t.ID_trabajo, t.Nota, t.Total,
                c.nombre, p.nombre, tp.preciounitario
            ORDER BY t.ID_trabajo, c.nombre, p.nombre
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fecha]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
