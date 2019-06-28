<?php

require_once("../controller/LabXML.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getLab($id);
        }
        elseif (!empty($_GET["name"])) {
            $name=$_GET["name"];
            getLabName($name);
        }
        else {
            getLabs();
        }
        break;
    case 'POST':
        insertLab();
        break;
    case 'PUT':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updateLab($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case 'PATCH':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updateAttributeLab($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case 'DELETE':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            deleteLab($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getLabs() {
    $labs = loadLabs();
    header('Content-Type: application/json');
    echo json_encode($labs);
}

function getLabName($name) {
    $labs = loadLabName((string) $name);
    if (gettype($labs) == "array") {
        header("Content-Type: application/json");
        echo json_encode($labs);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function getLab($id) {
    $lab = loadLab($id);
    if (gettype($lab) == "object") {
        header("Content-Type: application/json");
        echo json_encode($lab);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function insertLab() {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeNewLab($data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 201 Created");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function updateLab($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeLab($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function updateAttributeLab($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeAttributeLab($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function deleteLab($id) {
    $response = removeLab($id);
    header($response);
}

?>