<?php

include 'modelos/categorias.php';

class CategoriasControlador{
    private $modelo;

    public function __CONSTRUCT(){
        $this-> modelo = new Categorias();
    }

    public function Inicio(){
        

        $categorias = $this->modelo->Listar();
        require_once "vistas/encabezado.php";
        require_once "vistas/categorias/categorias.php";
        require_once "vistas/pie.php";
    }
    

    public function FormNuevo(){
       
        
        require_once "vistas/encabezado.php";
        require_once "vistas/categorias/nuevo.php";
        require_once "vistas/pie.php";
    }

    public function FormEditar(){
        
        $categoria = $this->modelo->ModificarCategoria();
        require_once "vistas/encabezado.php";
        require_once "vistas/categorias/editar.php";
        require_once "vistas/pie.php";
    }

    public function AñadirCategoria(){
        $categoria = $this->modelo->AgregarCategoria();
        if ($categoria) {
            header("Location: ?c=categorias&alerta=success");
        } else {
            header("Location: ?c=categorias&alerta=error");
        }
        exit();
    }

    public function ActualizarCategoria(){

        $categoria = $this->modelo->ActualizarCategoriaModelo();
        header('Location: ?c=categorias');
    }

    public function EliminarCategoria() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $resultados = $this->modelo->EliminarCategoriaModelo($id); // Llama al modelo para eliminar la categoría
            
            if (!$resultados) {
                header("Location: ?c=categorias&alerta=uso"); // Mostrar mensaje de error
                exit;
            }

            header('Location: ?c=categorias'); // Redirige al listado
            exit;
        } else {
            echo "Error: No se recibió un ID válido.";
        }
    }
    public function CambiarEstado(){
        if (isset($_GET['id']) && isset($_GET['estado'])) {
            $id = $_GET['id'];
            $estado = $_GET['estado'];
    
            if ($estado == 0) { // Solo validar cuando se intenta desactivar
                if ($this->modelo->CategoriaEnUso($id)) {
                    header("Location: ?c=categorias&alerta=uso");
                    exit();
                }
            }
    
            $this->modelo->CambiarEstado($id, $estado);
            header('Location: ?c=categorias');
            exit();
        }
    }
}


?>