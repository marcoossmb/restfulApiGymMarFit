<?php

// Incluir base de datos y el modelo del vuelo
require_once ('./db/DB.php');
require_once ('./models/ReservasModel.php');
$reserv = new ReservasModel();

// Establecer la cabecera de la respuesta como JSON
@header("Content-type: application/json");

// Consultar GET
// devuelve o 1 o todos, dependiendo si recibe o no parámetro
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_monitor'])) {
        $res = $reserv->getReservasMonitor($_GET['id_monitor']);
        echo json_encode($res);
        exit();
    } else {
        $res = $reserv->getAllReservas();
        echo json_encode($res);
        exit();
    }
}

// POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // se cargan toda la entrada que venga en php://input
    $post = json_decode(file_get_contents('php://input'), true);
    $res = $reserv->insertarReserva($post);
    $resul['resultado'] = $res;
    echo json_encode($resul);
    exit();
}

// En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");