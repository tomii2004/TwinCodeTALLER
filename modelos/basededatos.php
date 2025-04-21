<?php

class BasedeDatos {
  const server = "localhost";
  const user = "root";
  const password = "";
  const nameDB = "taller_mecanicojb";

    public static function connection() {
        try {

            $conexion = new PDO("mysql:host=".self::server.";dbname=".self::nameDB.";charset=utf8",
            self::user,self::password);

            $conexion ->setAttribute(PDO::ATTR_ERRMODE,
            PDO:: ERRMODE_EXCEPTION);

            return $conexion;

        }catch(PDOException $e) {
            return "Fallo".$e->getMessage();

        }
    }
}