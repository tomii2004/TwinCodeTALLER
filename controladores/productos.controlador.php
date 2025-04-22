<?php
session_start();

include 'modelos/productos.php';
require_once "modelos/categorias.php";

class ProductosControlador{
    private $modelo;

    public function __CONSTRUCT(){
        $this-> modelo = new Productos();
    }

    public function Inicio(){
        

        $productos = $this->modelo->Listar();
        require_once "vistas/encabezado.php";
        require_once "vistas/productos/productos.php";
        require_once "vistas/pie.php";
    }
    

    public function FormNuevo(){
      
        
        $categoriasModelo = new Categorias();
        $categorias = $categoriasModelo -> Listar();
        require_once "vistas/encabezado.php";
        require_once "vistas/productos/nuevo.php";
        require_once "vistas/pie.php";
    }

    public function FormEditar(){
      
        $producto = $this->modelo->ModificarProducto();
        require_once "vistas/encabezado.php";
        require_once "vistas/productos/editar.php";
        require_once "vistas/pie.php";
    }

    public function AñadirProducto(){
        $producto = $this->modelo->AgregarProducto();
        if ($producto) {
            header("Location: ?c=productos&alerta=success");
        } else {
            header("Location: ?c=productos&alerta=error");
        }
        exit();
    }

    public function ActualizarProducto(){

        $producto = $this->modelo->ActualizarProductoModelo();
        header('Location: ?c=productos');
    }

    public function EliminarProducto() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultados = $this->modelo->EliminarProductoModelo($id); // Llama al modelo para eliminar la categoría
            
            if (!$resultados) {
                header("Location: ?c=productos&alerta=uso"); // Mostrar mensaje de error
                exit;
            }

            header('Location: ?c=productos'); // Redirige al listado
            exit;
        } else {
            echo "Error: No se recibió un ID válido.";
        }
    }

}


?>