<?php

require_once("../models/Doctor.php");

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
    $xml = simplexml_load_file("../xml/doctors.xml");
    foreach($xml->children() as $doc) {
        //$objDoctor = new Doctor($doc->id, $doc->name, $doc->address, $doc->phone, $doc->specialization, $doc->crm);
        $objDoctor = new BasicInfo($doc->id, $doc->name, $doc->address, $doc->phone);
    }
    header('Content-Type: application/json');
    echo json_encode($objDoctor);
}

?>