<?php

require_once("../controller/AppXML.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getAppoint($id);
        }
        elseif (!empty($_GET["doctor"])) {
            $doctor=$_GET["doctor"];
            getAppointDoctor($doctor);
        }
        elseif (!empty($_GET["pacient"])) {
            $pacient=$_GET["pacient"];
            getAppointPacient($pacient);
        }
        else {
            getAppoints();
        }
        break;
    case 'POST':
        insertAppoint();
        break;
    case 'PUT':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updateAppoint($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case 'PATCH':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updateAttributeApp($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case 'DELETE':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            deleteAppoint($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getAppoints() {
    $appoints = loadApps();
    header('Content-Type: application/json');
    echo json_encode($appoints);
}

function getAppointDoctor($doctor) {
    $apps = loadDocApp((string) $doctor);
    if (gettype($apps) == "array") {
        header("Content-Type: application/json");
        echo json_encode($apps);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function getAppointPacient($pacient) {
    $apps = loadPacApp((string) $pacient);
    if (gettype($apps) == "array") {
        header("Content-Type: application/json");
        echo json_encode($apps);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function getAppoint($id) {
    $app = loadApp($id);
    if (gettype($app) == "object") {
        header("Content-Type: application/json");
        echo json_encode($app);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function insertAppoint() {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeNewAppoint($data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 201 Created");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function updateAppoint($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeAppoint($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function updateAttributeApp($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeAttributeApp($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function deleteAppoint($id) {
    $response = removeAppoint($id);
    header($response);
}

?>