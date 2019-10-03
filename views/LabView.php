<?php

require_once("../controller/LabDB.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getLab($id);
        }
        elseif (!empty($_GET["name"])) {
            foreach($_GET as $field => $value) {
                if(!empty($value)) {
                    $search[$field] = $value;
                }
            }
            searchLabs($search);
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

function searchLabs($search) {
    $docs = loadLabSearch($search);
    header("Content-Type: application/json");
    echo json_encode($labs);
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
        echo json_encode($response);
    }
}


function updateLab($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeLab($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        echo json_encode($response);
    } else if (gettype($response) == "array"){
        header("HTTP/1.0 400 Bad Request");
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