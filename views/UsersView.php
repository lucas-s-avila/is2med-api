<?php

require_once("../controller/UserXML.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getUser($id);
        }
        else {
            getUsers();
        }
        break;
    case 'POST':
        insertUser();
        break;
    case 'PUT':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updateUser($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case 'DELETE':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            deleteUser($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getUsers() {
    $users = loadUsers();
    header('Content-Type: application/json');
    echo json_encode($users);
}

function getUser($id) {
    $user = loadUser($id);
    if (gettype($user) == "object") {
        header("Content-Type: application/json");
        echo json_encode($user);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function insertUser() {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeNewUser($data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 201 Created");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function updateUser($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeUser($id,$data);
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

function deleteUser($id) {
    $response = removeUser($id);
    header($response);
}

?>