<?php

// Incluir base de datos y el modelo del vuelo
require_once ('./db/DB.php');
require_once ('./models/UsuariosModel.php');
$usu = new UsuariosModel();

// Establecer la cabecera de la respuesta como JSON
@header("Content-type: application/json");

// Consultar GET
// devuelve o 1 o todos, dependiendo si recibe o no parámetro
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['email']) && isset($_GET['password'])) {

        $email = $_GET['email'];
        $password = $_GET['password'];
        $res = $usu->getUnUsuario($email, $password);
        echo json_encode($res);
        exit();
    } else if (isset($_GET['email'])) {

        $email = $_GET['email'];
        $res = $usu->getUsuarioGoogle($email);
        echo json_encode($res);
        exit();
    } else {

        $res = $usu->getAll();
        echo json_encode($res);
        exit();
    }
}

// POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // se cargan toda la entrada que venga en php://input
    $post = json_decode(file_get_contents('php://input'), true);
    $res = $usu->insertUsuario($post);
    $resul['resultado'] = $res;
    echo json_encode($resul);
    exit();
}

// Actualizar PUT, se reciben los datoc como en el put
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($_GET['id_usuario'])) {
        if (isset($_GET['actualizafecha'])) {
            $put = json_decode(file_get_contents('php://input'), true);
            $res = $usu->actualizaFecha($put, $_GET['id_usuario']);
            $resul['mensaje'] = $res;
            echo json_encode($resul);
            exit();
        } else {
            $put = json_decode(file_get_contents('php://input'), true);
            $res = $usu->actualizaImc($put, $_GET['id_usuario']);
            $resul['mensaje'] = $res;
            echo json_encode($resul);
            exit();
        }
    } elseif (isset($_GET['email'])) {
        $put = json_decode(file_get_contents('php://input'), true);
        $res = $usu->actualizaPassword($put, $_GET['email']);
        $resul['mensaje'] = $res;
        echo json_encode($resul);
        exit();
    }
}

// En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
