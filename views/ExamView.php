<?php

require_once("../controller/ExamDB.php");

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            getExam($id);
        }
        elseif (!empty($_GET["lab"])) {
            $lab=$_GET["lab"];
            getExamLab($lab);
        }
        elseif (!empty($_GET["pacient"])) {
            $pacient=$_GET["pacient"];
            getExamPacient($pacient);
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
    case 'PATCH':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            updateAttributeExam($id);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
        break;
    case 'DELETE':
        if(!empty($_GET["id"])) {
            $id=$_GET["id"];
            deleteExam($id);
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
    $exams = loadExams();
    header('Content-Type: application/json');
    echo json_encode($exams);
}

function getExamLab($lab) {
    $exams = loadLabExam((string) $lab);
    if (gettype($exams) == "array") {
        header("Content-Type: application/json");
        echo json_encode($exams);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function getExamPacient($pacient) {
    $exams = loadPacExam((string) $pacient);
    if (gettype($exams) == "array") {
        header("Content-Type: application/json");
        echo json_encode($exams);
    } else {
        header("HTTP/1.0 404 Not Found");
    }
}

function getExam($id) {
    $exam = loadExam($id);
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
    } else {
        header($response);
    }
}

function updateAttributeExam($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = writeAttributeExam($id,$data);
    if (gettype($response) == "object") {
        header("HTTP/1.0 200 OK");
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