<?php

require_once("../controller/DocXML.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=intval($_GET["id"]);
            getDoctor($id);
        }
        else {
            getDoctors();
        }
        break;
    case 'POST':
        insertDoctor();
        break;
    case 'PUT':
        if(!empty($_GET["id"])) {
            $id=intval($_GET["id"]);
            updateDoctor($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
    case 'DELETE':
        if(!empty($_GET["id"])) {
            $id=intval($_GET["id"]);
            deleteDoctor($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getDoctors() {
    $doctors = loadDocs();
    header('Content-Type: application/json');
    echo json_encode($doctors);
}

function getDoctor($id) {
    $doc = loadDoc($id);
    if (gettype($doc) == "object") {
        header("Content-Type: application/json");
        echo json_encode($doc);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function insertDoctor() {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeNewDoctor($data);
    header($response);
}

function updateDoctor($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeDoctor($id,$data);
    header($response);
}

function deleteDoctor($id) {
    $response = removeDoctor($id);
    header($response);
}
?>