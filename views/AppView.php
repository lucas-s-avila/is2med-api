<?php

require_once("../controller/AppDB.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getAppoint($id);
        }
        elseif (!empty($_GET["date"]) or !empty($_GET["doctorid"]) or !empty($_GET["patientid"])) {
            foreach($_GET as $field => $value) {
                if(!empty($value)) {
                    $search[$field] = $value;
                }
            }
            searchApps($search);
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
        break;
}

function getAppoints() {
    $appoints = loadApps();
    header('Content-Type: application/json');
    echo json_encode($appoints);
}

function searchApps($search) {
    $apps = loadAppSearch($search);
    header("Content-Type: application/json");
    echo json_encode($apps);
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
    $response = writeNewAppointment($data);
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
    $response = writeAppointment($id,$data);
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

function deleteAppoint($id) {
    $response = removeAppointment($id);
    header($response);
}

?>