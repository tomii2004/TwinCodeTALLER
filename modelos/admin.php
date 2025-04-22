<?php

class Admin {
    private $IdUsuario;
    private $Usuario;
    private $clave;
    private $pdo;

    public function __construct() {
        $this-> pdo = BasedeDatos::connection();
    }

 
 
    public function ConsultarUsuario($Usuario) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE Nombre = ?");
        $stmt->execute([$Usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

        

      

    
    
    

}
