<?php

// Definición de la clase UsuariosModel, que extiende de DB
class ReservasModel extends DB {

    // Declaración de propiedades privadas    
    private $table;
    private $conexion;

    // Constructor de la clase
    public function __construct() {
        $this->table = "reserva";
        $this->conexion = $this->getConexion();
    }

    public function getAllReservas() {
        try {
            $sql = "SELECT r.*, c.title FROM $this->table r JOIN clase c ON r.id_clase = c.id_clase";

            $statement = $this->conexion->query($sql);
            $registros = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement = null;
            // Retorna el array de registros
            return $registros;
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }

    public function getReservasMonitor($id_monitor) {
        try {
            $sql = "SELECT c.title, r.* FROM $this->table r JOIN clase c ON r.id_clase = c.id_clase WHERE r.id_monitor = ? GROUP BY r.start, r.hora_clase";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(1, $id_monitor);
            $sentencia->execute();

            // Fetch all records instead of a single record
            $registros = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $sentencia = null;

            if ($registros) {
                return $registros;
            }
            return [];
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }
}
