<?php
class Productos{
    private $pdo;
    
    public function __CONSTRUCT(){
        $this-> pdo = BasedeDatos::connection();
    }

   
    public function Listar(){
        try {
            $query = $this->pdo->prepare("SELECT p.ID_productos,p.nombre,p.ID_categoria,p.estado,c.nombre as 'nombrecat' FROM productos p INNER JOIN categorias c ON p.ID_categoria = c.ID_categoria");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function AgregarProducto(){
        // Convertir nombre a minúsculas para evitar duplicados con diferente capitalización
        $nombre = strtolower(trim($_POST['nombre']));
        $categoria = strtolower(trim($_POST['categoria']));
        
        // Verificar si la producto ya existe
        $sql = $this->pdo->prepare("SELECT COUNT(*) FROM productos WHERE LOWER(nombre) = ?");
        $sql->execute([$nombre]);
        $existe = $sql->fetchColumn();
        
        if ($existe > 0) {
            return false; // Ya existe, no se agrega
        }
        
        $sql = $this -> pdo ->prepare("INSERT INTO productos(nombre,ID_categoria) VALUES (?,?)");
        $sql -> execute([$nombre,$categoria]);
        return true;
    }


    public function ModificarProducto(){
        $id = $_GET['id'];
        $sql = $this -> pdo -> prepare("SELECT ID_productos,nombre from productos WHERE ID_productos = ? LIMIT 1 ");
        $sql ->execute([$id]);
        $producto = $sql ->fetch(PDO::FETCH_ASSOC);
        return $producto;
    }

    public function ActualizarProductoModelo(){
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $sql = $this -> pdo ->prepare("UPDATE productos SET nombre = ? WHERE ID_productos = ?");
        $sql -> execute([$nombre,$id]);
    }

    public function EliminarProductoModelo($id) {
        $sql = $this->pdo->prepare("DELETE FROM productos WHERE ID_productos = ?");
        $sql->execute([$id]);
        return true;
    }

    public function CambiarEstado($id, $estado){
        try {
            $sql = $this->pdo->prepare("UPDATE productos SET estado = ? WHERE ID_productos = ?");
            $sql->execute([$estado, $id]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

}

?>