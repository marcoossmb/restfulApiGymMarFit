<?php

// Definición de la clase UsuariosModel, que extiende de DB
class ClasesModel extends DB {

    // Declaración de propiedades privadas    
    private $table;
    private $conexion;

    // Constructor de la clase
    public function __construct() {
        $this->table = "clase";
        $this->conexion = $this->getConexion();
    }

    public function getAllClases() {
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

    public function getOneClase($id_clase) {
        try {
            $sql = "SELECT c.*, m.nombre AS 'moninombre', m.apellido AS 'moniapellido'
                FROM $this->table c
                JOIN tiene t ON c.id_clase = t.id_clase 
                JOIN monitor m ON m.id_monitor = t.id_monitor 
                WHERE c.id_clase=?";

            $sentencia = $this->conexion->prepare($sql);

            $sentencia->bindParam(1, $id_clase);

            $sentencia->execute();

            $row = $sentencia->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row;
            }
            return "SIN DATOS";
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }

    public function oneClaseByTitle($title) {
        try {
            $sql = "SELECT id_clase FROM $this->table WHERE title=?";

            $sentencia = $this->conexion->prepare($sql);

            $sentencia->bindParam(1, $title);

            $sentencia->execute();

            $row = $sentencia->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row;
            }
            return "SIN DATOS";
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }

    public function getClasesByUsuario($id_usuario) {
        try {
            $sql = "SELECT c.id_clase, c.title, c.tipo, c.descripcion, c.duracion, r.start, r.hora_clase 
            FROM reserva r 
            JOIN clase c ON r.id_clase = c.id_clase 
            WHERE r.id_usuario = ? AND r.start > CURDATE()";

            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(1, $id_usuario);
            $sentencia->execute();

            $rows = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            if ($rows) {
                return $rows;
            }
            return "SIN DATOS";
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }
}
