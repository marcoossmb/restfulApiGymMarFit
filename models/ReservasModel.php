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
            $sql = "SELECT c.title, r.* 
                FROM $this->table r 
                JOIN clase c ON r.id_clase = c.id_clase 
                WHERE r.id_monitor = ? AND r.start > CURDATE()
                GROUP BY r.start, r.hora_clase";
            
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

    public function obtenerIdMonitor($start, $hora_clase) {
        try {
            $sql = "SELECT m.id_monitor FROM reserva r JOIN monitor m ON r.id_monitor = m.id_monitor JOIN clase c ON r.id_clase = c.id_clase WHERE r.start = ? AND r.hora_clase = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$start, $hora_clase]);
            $id_monitor_result = $stmt->fetchColumn();

            if ($id_monitor_result === false) {
                return false;
            }

            return $id_monitor_result;
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el id del monitor: " . $e->getMessage());
        }
    }

    public function comprobarDuplicado($start, $hora_clase, $id_usuario) {
        try {
            $sql = "SELECT * FROM reserva WHERE start = ? AND hora_clase = ? AND id_usuario=?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute([$start, $hora_clase, $id_usuario]);
            $existeReserva = $stmt->fetchColumn();

            if ($existeReserva === false) {
                return false;
            } else {
                return true;
            }
        } catch (PDOException $e) {
            throw new Exception("Error al obtener el id del monitor: " . $e->getMessage());
        }
    }

    public function insertarReserva($post) {
        try {
            // Obtener el id_monitor
            $id_monitor_result = $this->obtenerIdMonitor($post['start'], $post['hora_clase']);

            if ($id_monitor_result === false) {
                return "No se encontró un monitor para la clase y hora especificadas";
            }

            $existeReserva = $this->comprobarDuplicado($post['start'], $post['hora_clase'], $post['id_usuario']);

            if ($existeReserva) {
                return 'Ya has realizado esta reserva';
            }

            // Insertar la reserva utilizando el id_monitor obtenido
            $sql = "INSERT INTO $this->table (id_usuario, id_monitor, id_clase, start, hora_clase) VALUES (?, ?, ?, ?, ?)";
            $sentencia = $this->conexion->prepare($sql);

            $sentencia->bindParam(1, $post['id_usuario']);
            $sentencia->bindParam(2, $id_monitor_result);
            $sentencia->bindParam(3, $post['id_clase']);
            $sentencia->bindParam(4, $post['start']);
            $sentencia->bindParam(5, $post['hora_clase']);

            $sentencia->execute();

            return "REGISTRO INSERTADO CORRECTAMENTE";
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }
}
