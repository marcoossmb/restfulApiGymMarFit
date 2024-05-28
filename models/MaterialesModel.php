<?php

// Definición de la clase MaterialesModel, que extiende de DB
class MaterialesModel extends DB {

    // Declaración de propiedades privadas    
    private $table;
    private $conexion;

    // Constructor de la clase
    public function __construct() {
        $this->table = "material";
        $this->conexion = $this->getConexion();
    }

    public function getAll() {
        try {
            $sql = "SELECT * FROM $this->table";

            $statement = $this->conexion->query($sql);
            $registros = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement = null;
            // Retorna el array de registros
            return $registros;
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }
}