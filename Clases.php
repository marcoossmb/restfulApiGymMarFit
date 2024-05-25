<?php

// Incluir base de datos y el modelo del vuelo
require_once ('./db/DB.php');
require_once ('./models/ClasesModel.php');
$clas = new ClasesModel();

// Establecer la cabecera de la respuesta como JSON
@header("Content-type: application/json");

// Consultar GET
// devuelve o 1 o todos, dependiendo si recibe o no parámetro
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id_clase'])) {
        $res = $clas->getOneClase($_GET['id_clase']);
        echo json_encode($res);
        exit();
    } elseif (isset($_GET['title'])) {
        $res = $clas->oneClaseByTitle($_GET['title']);
        echo json_encode($res);
        exit();
    } else {
        $res = $clas->getAllClases();
        echo json_encode($res);
        exit();
    }
}

// En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
