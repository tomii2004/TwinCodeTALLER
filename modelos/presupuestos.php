
<?php
class Presupuestos{
    private $pdo;
    
    public function __CONSTRUCT(){
        $this-> pdo = BasedeDatos::connection();
    }

    public function ObtenerProductos(){
        try {
            $query = $this->pdo->prepare("SELECT p.ID_productos,p.nombre,p.ID_categoria,c.nombre as 'nombrecat' FROM productos p INNER JOIN categorias c ON p.ID_categoria = c.ID_categoria WHERE p.estado = 1 ;");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}