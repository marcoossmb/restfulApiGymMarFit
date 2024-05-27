<?php

// Incluir base de datos y el modelo del vuelo
require_once ('./db/DB.php');
require_once ('./models/MonitoresModel.php');
$moni = new MonitoresModel();

// Establecer la cabecera de la respuesta como JSON
@header("Content-type: application/json");

// Consultar GET
// devuelve o 1 o todos, dependiendo si recibe o no parámetro
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $res = $moni->getAll();
    echo json_encode($res);
    exit();
}

// Actualizar PUT, se reciben los datoc como en el put
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($_GET['id_monitor'])) {
        $put = json_decode(file_get_contents('php://input'), true);
        $res = $moni->actualizaImcMonitor($put, $_GET['id_monitor']);
        $resul['mensaje'] = $res;
        echo json_encode($resul);
        exit();
    }
}

// Borrar DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id_monitor = $_GET['id_monitor'];
    $res = $moni->eliminarMonitor($id_monitor);
    $resul['resultado'] = $res;
    echo json_encode($resul);
    exit();
}

// POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // se cargan toda la entrada que venga en php://input
    $post = json_decode(file_get_contents('php://input'), true);
    $res = $moni->agregarMonitor($post);
    $resul['resultado'] = $res;
    echo json_encode($resul);
    exit();
}

// En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
