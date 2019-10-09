<?php

require_once("../controller/PatDB.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getPatient($id);
        }
        elseif (!empty($_GET["name"])) {
            foreach($_GET as $field => $value) {
                if(!empty($value)) {
                    $search[$field] = $value;
                }
            }
            searchPats($search);
        }
        else {
            getPatients();
        }
        break;
    case 'POST':
        insertPatient();
        break;
    case 'PUT':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updatePatient($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;

    case 'DELETE':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            deletePatient($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    default:
        break;
}

function getPatients() {
    $patients = loadPats();
    header('Content-Type: application/json');
    echo json_encode($patients);
}

function searchPats($search) {
    $pats = loadPatSearch($search);
    header("Content-Type: application/json");
    echo json_encode($pats);
}

function getPatient($id) {
    $pat = loadPat($id);
    if (gettype($pat) == "object") {
        header("Content-Type: application/json");
        echo json_encode($pat);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function insertPatient() {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeNewPatient($data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 201 Created");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header("HTTP/1.0 400 Bad Request");
        echo json_encode($response);
    }
}

function updatePatient($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writePatient($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else if (gettype($response) == "array"){
        header("HTTP/1.0 400 Bad Request");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function deletePatient($id) {
    $response = removePatient($id);
    header($response);
}

?>