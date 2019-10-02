<?php

require_once("../controller/PacXML.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getPacient($id);
        }
        elseif (!empty($_GET["name"])) {
            $name=$_GET["name"];
            getPacientName($name);
        }
        else {
            getPacients();
        }
        break;
    case 'POST':
        insertPacient();
        break;
    case 'PUT':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updatePacient($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case 'PATCH':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updateAttributePac($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case 'DELETE':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            deletePacient($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getPacients() {
    $pacients = loadPacs();
    header('Content-Type: application/json');
    echo json_encode($pacients);
}

function getPacientName($name) {
    $pac = loadPacName((string) $name);
    if (gettype($pac) == "array") {
        header("Content-Type: application/json");
        echo json_encode($pac);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function getPacient($id) {
    $pac = loadPacient($id);
    if (gettype($pac) == "object") {
        header("Content-Type: application/json");
        echo json_encode($pac);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function insertPacient() {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeNewPacient($data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 201 Created");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function updatePacient($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writePacient($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function updateAttributePac($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeAttributePac($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function deletePacient($id) {
    $response = removePacient($id);
    header($response);
}

?>