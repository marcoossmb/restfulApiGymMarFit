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

    public function eliminarMonitor($id_monitor) {
        try {
            $sql = "DELETE FROM $this->table WHERE id_monitor = ?";
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->bindParam(1, $id_monitor);
            $sentencia->execute();
            if ($sentencia->rowCount() == 0)
                return false;
            else
                return true;
        } catch (PDOException $e) {
            return "ERROR AL BORRAR.<br>" . $e->getMessage();
        }
    }

    public function comprobaciones($email) {
        try {
            // Comprobación de existencia del email
            $sql1 = "SELECT * FROM $this->table WHERE email = ?";
            $stmt1 = $this->conexion->prepare($sql1);
            $stmt1->execute([$email]);
            $resultados['email_existente'] = $stmt1->fetch();

            return $resultados;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function agregarMonitor($post) {
        try {
            $comprobaciones = $this->comprobaciones($post['email']);

            if ($comprobaciones && $comprobaciones['email_existente']) {
                return "El email ya esta registrado";
            }

            $sql = "INSERT INTO $this->table (username, password, nombre, apellido, email, fecha_nac, rol, imc) VALUES (?, ?, ?, ?, ?, ?, 2, 0)";
            $sentencia = $this->conexion->prepare($sql);

            $sentencia->bindParam(1, $post['username']);
            $sentencia->bindParam(2, $post['password']);
            $sentencia->bindParam(3, $post['nombre']);
            $sentencia->bindParam(4, $post['apellido']);
            $sentencia->bindParam(5, $post['email']);
            $sentencia->bindParam(6, $post['fecha_nac']);

            $sentencia->execute();

            return "REGISTRO INSERTADO CORRECTAMENTE";
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }
}
