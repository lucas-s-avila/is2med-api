<?php

require_once("../controller/ExamDB.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getExam($id);
        }
        elseif (!empty($_GET["date"]) or !empty($_GET["labid"]) or !empty($_GET["examtype"]) or !empty($_GET["patientid"])) {
            foreach($_GET as $field => $value) {
                if(!empty($value)) {
                    $search[$field] = $value;
                }
            }
            searchExm($search);
        }
        else {
            getExams();
        }
        break;
    case 'POST':
        insertExam();
        break;
    case 'PUT':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updateExam($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;

    case 'DELETE':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function getExams() {
    $exams = loadExms();
    header('Content-Type: application/json');
    echo json_encode($exams);
}

function searchExms($search) {
    $exms = loadExmsSearch($search);
    header("Content-Type: application/json");
    echo json_encode($exms);
}

function getExam($id) {
    $exam = loadExm($id);
    if (gettype($exam) == "object") {
        header("Content-Type: application/json");
        echo json_encode($exam);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function insertExam() {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeNewExam($data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 201 Created");
        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        header($response);
    }
}

function updateExam($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeExam($id,$data);
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


function deleteExam($id) {
    $response = removeExam($id);
    header($response);
}

?>