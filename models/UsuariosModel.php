<?php

// Definición de la clase UsuariosModel, que extiende de DB
class UsuariosModel extends DB {

    // Declaración de propiedades privadas    
    private $table;
    private $conexion;

    // Constructor de la clase
    public function __construct() {
        $this->table = "usuario";
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

    public function getUnUsuario($email, $password) {
        try {
            $sql = "SELECT * FROM $this->table WHERE email=? AND password=?";

            $sentencia = $this->conexion->prepare($sql);

            $sentencia->bindParam(1, $email);
            $sentencia->bindParam(2, $password);

            $sentencia->execute();

            $row = $sentencia->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row;
            } else {
                $sql2 = "SELECT * FROM monitor WHERE email=? AND password=?";

                $sentencia2 = $this->conexion->prepare($sql2);

                $sentencia2->bindParam(1, $email);
                $sentencia2->bindParam(2, $password);

                $sentencia2->execute();

                $row2 = $sentencia2->fetch(PDO::FETCH_ASSOC);

                if ($row2) {
                    return $row2;
                } else {
                    return "SIN DATOS";
                }
            }
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
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

    public function insertUsuario($post) {
        try {
            $comprobaciones = $this->comprobaciones($post['email']);

            if ($comprobaciones && $comprobaciones['email_existente']) {
                return "El email ya esta registrado";
            }

            $sql = "INSERT INTO $this->table (username, password, nombre, apellido, email, fecha_nacim, rol, imc) VALUES (?, ?, ?, ?, ?, ?, 0, 0)";
            $sentencia = $this->conexion->prepare($sql);

            $sentencia->bindParam(1, $post['username']);
            $sentencia->bindParam(2, $post['password']);
            $sentencia->bindParam(3, $post['nombre']);
            $sentencia->bindParam(4, $post['apellido']);
            $sentencia->bindParam(5, $post['email']);
            $sentencia->bindParam(6, $post['fecha_nacim']);

            $sentencia->execute();

            return "REGISTRO INSERTADO CORRECTAMENTE";
        } catch (PDOException $e) {
            return "ERROR AL CARGAR.<br>" . $e->getMessage();
        }
    }

    public function getUsuarioGoogle($email) {
        try {
            $sql = "SELECT * FROM $this->table WHERE email=?";

            $sentencia = $this->conexion->prepare($sql);

            $sentencia->bindParam(1, $email);

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

    public function actualizaFecha($put, $id_usuario) {
        try {

            // Actualización del pasaje
            $sql_update = "UPDATE $this->table SET fecha_nacim = ? WHERE id_usuario = ?";
            $stmt_update = $this->conexion->prepare($sql_update);
            $stmt_update->execute([$put['fecha_nacim'], $id_usuario]);

            if ($stmt_update->rowCount() > 0) {
                return "REGISTRO ACTUALIZADO CORRECTAMENTE";
            } else {
                return "ERROR AL ACTUALIZAR";
            }
        } catch (PDOException $e) {
            return "ERROR SQL al actualizar: " . $e->getMessage();
        }
    }

    public function actualizaImc($put, $id_usuario) {
        try {

            // Actualización del pasaje
            $sql_update = "UPDATE $this->table SET imc = ? WHERE id_usuario = ?";
            $stmt_update = $this->conexion->prepare($sql_update);
            $stmt_update->execute([$put['imc'], $id_usuario]);

            if ($stmt_update->rowCount() > 0) {
                return "REGISTRO ACTUALIZADO CORRECTAMENTE";
            } else {
                return "ERROR AL ACTUALIZAR";
            }
        } catch (PDOException $e) {
            return "ERROR SQL al actualizar: " . $e->getMessage();
        }
    }

    public function actualizaPassword($put, $email) {
        try {

            $comprobaciones = $this->comprobaciones($email);

            if ($comprobaciones && $comprobaciones['email_existente']) {

                // Actualización del pasaje
                $sql_update = "UPDATE $this->table SET password = ? WHERE email = ?";
                $stmt_update = $this->conexion->prepare($sql_update);
                $stmt_update->execute([$put['password'], $email]);

                if ($stmt_update->rowCount() > 0) {
                    return "REGISTRO ACTUALIZADO CORRECTAMENTE";
                } else {
                    return "ERROR AL ACTUALIZAR";
                }
            } else {
                return "El email no esta registrado";
            }
        } catch (PDOException $e) {
            return "ERROR SQL al actualizar: " . $e->getMessage();
        }
    }
}
