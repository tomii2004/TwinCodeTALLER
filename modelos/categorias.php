<?php
class Categorias{
    private $pdo;
    
    public function __CONSTRUCT(){
        $this-> pdo = BasedeDatos::connection();
    }

  

    public function Listar(){
        try {
            $query = $this->pdo->prepare("SELECT ID_categoria,nombre as Nombre FROM categorias");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function AgregarCategoria(){
        // Convertir nombre a minúsculas para evitar duplicados con diferente capitalización
        $nombre = strtolower(trim($_POST['nombre']));
        
        // Verificar si la categoria ya existe
        $sql = $this->pdo->prepare("SELECT COUNT(*) FROM categorias WHERE LOWER(nombre) = ?");
        $sql->execute([$nombre]);
        $existe = $sql->fetchColumn();
        
        if ($existe > 0) {
            return false; // Ya existe, no se agrega
        }
        
        $sql = $this -> pdo ->prepare("INSERT INTO categorias(nombre) VALUES (?)");
        $sql -> execute([$nombre]);
        return true;
    }


    public function ModificarCategoria(){
        $id = $_GET['id'];
        $sql = $this -> pdo -> prepare("SELECT ID_categoria,nombre from categorias WHERE ID_categoria = ? LIMIT 1 ");
        $sql ->execute([$id]);
        $categoria = $sql ->fetch(PDO::FETCH_ASSOC);
        return $categoria;
    }

    public function ActualizarCategoriaModelo(){
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $sql = $this -> pdo ->prepare("UPDATE categorias SET nombre = ? WHERE ID_categoria = ?");
        $sql -> execute([$nombre,$id]);
    }

    public function EliminarCategoriaModelo($id) {
        if($this->CategoriaEnUso($id)){
            return false;
        }
        $sql = $this->pdo->prepare("DELETE FROM categorias WHERE ID_categoria = ?");
        $sql->execute([$id]);
        return true;
    }

    public function CategoriaEnUso($id){
        $sql = $this->pdo->prepare("SELECT COUNT(*) FROM productos WHERE ID_categoria = ?");
        $sql->execute([$id]);
        return $sql->fetchColumn() > 0; // Retorna true si está en uso
    }
}

?>