<?php

// Incluir base de datos y el modelo del vuelo
require_once ('./db/DB.php');
require_once ('./models/MaterialesModel.php');
$mats = new MaterialesModel();

// Establecer la cabecera de la respuesta como JSON
@header("Content-type: application/json");

// Consultar GET
// devuelve o 1 o todos, dependiendo si recibe o no parámetro
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $res = $mats->getAll();
    echo json_encode($res);
    exit();
}

// En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
