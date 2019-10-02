<?php

require_once("../controller/LoginCtrl.php");

$request_method=$_SERVER["REQUEST_METHOD"];

if($request_method == "GET") {

    $data = json_decode(file_get_contents('php://input'), true);

    $response = login($data);

    if(gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }

} else {
    header("HTTP/1.0 405 Method Not Allowed");
}

?>