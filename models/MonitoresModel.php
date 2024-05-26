<?php

// Definición de la clase MonitoresModel, que extiende de DB
class MonitoresModel extends DB {

    // Declaración de propiedades privadas    
    private $table;
    private $conexion;

    // Constructor de la clase
    public function __construct() {
        $this->table = "monitor";
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

    public function actualizaImcMonitor($put, $id_monitor) {
        try {

            // Actualización del pasaje
            $sql_update = "UPDATE $this->table SET imc = ? WHERE id_monitor = ?";
            $stmt_update = $this->conexion->prepare($sql_update);
            $stmt_update->execute([$put['imc'], $id_monitor]);

            if ($stmt_update->rowCount() > 0) {
                return "REGISTRO ACTUALIZADO CORRECTAMENTE";
            } else {
                return "ERROR AL ACTUALIZAR";
            }
        } catch (PDOException $e) {
            return "ERROR SQL al actualizar: " . $e->getMessage();
        }
    }
}
