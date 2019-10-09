<?php

require_once("../models/Exam.php");
require_once("config/connection.php");
require_once("PatDB.php");
require_once("LabDB.php");

$db = new dbObj();
$connection =  $db->getConn();

function mountExm($row) {
    $patient = loadPat($row["patientId"]);
    $lab = loadLab($row["labId"]);

    if(is_null($patient) or is_null($lab)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    $exm = new Exam($row["id"],
                    $row["date"],
                    $patient,
                    $lab,
                    $row["examType"],
                    $row["result"]);
    return $exm;
}

function loadExms() {
    global $connection;

    $sql = "SELECT * FROM Exam";
    $result = $connection->query($sql);

    $exams = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $exm = mountExm($row);
            $exams[] = $exm;
        }
        return $exams;
    } else {
        return null;
    }
    return $exams;
}

function loadExmsSearch($search) {   //date, exam type, labID, patientID
    global $connection;

    $sql = "SELECT * FROM Exam WHERE ";
    
    if(!is_null($search["date"])) {
        $sql = $sql . " date = '" . $search["date"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["date"]);
    }

    if(!is_null($search["examtType"])) {
        $sql = $sql . " examType = '" . $search["examtype"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["examtype"]);
    }

    if(!is_null($search["patientId"])) {
        $sql = $sql . " patientId = '" . $search["patientId"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["patientId"]);
    }

    if(!is_null($search["labId"])) {
        $sql = $sql . " labId = '" . $search["labId"] . "'";
    }

    $result = $connection->query($sql);

    $exams = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $exm = mountExm($row);
            $exams[] = $exm;
        }
        return $exams;
    } else {
        return null;
    }
}

function loadExm($id) {
    global $connection;

    $sql = "SELECT * FROM Exam WHERE id = " . ((string) $id);
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $exm = mountExm($row);
        return $exm;
    } else {
        return null;
    }
}

function writeNewExam($data) {
    $id = time();
    $data["id"] = $id;

    $exm = mountExm($data);

    global $connection;

    $sql = "INSERT INTO Exam (id, labId, patientId, date, examType, result) 
                        VALUES (" . $exm->getId() .
                        ", '" . $exm->getLab()->getId() .
                        "', '" . $exm->getPatient()->getId() . 
                        "', '" . $exm->getDate() . 
                        "', '" . $exm->getType() . 
                        "', '" . $exm->getResult() . "')";
    
    if($connection->query($sql) === TRUE) {
        return $exm;
    } else {
        $response["message"] = $connection->error;
        return $response;
    }
}

function writeExam($id, $data) {
    $exm = loadExm($id);
    
    if(gettype($exm) == "object") {
        $exm->setDate($data["date"]);
        $exm->setPatient(mountPat($data["patientId"]));
        $exm->setLab(mountLab($data["labId"]));
        $exm->setType($data["examType"]);
        $exm->setResult($data["result"]);

        global $connection;
        $sql = "UPDATE Exam SET Date = '" . $exm->getDate() . 
               "', patientId = '" . $exm->getPatient()->getId() .
               "', labId = '" . $exm->getLab()->getId() .
               "', examType = '" . $exm->getType() .
               "', result = '" . $exm->getResult() . 
               "' WHERE id = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $exm;
        } else {
            $response["message"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeExam($id) {
    global $connection;

    $sql = "DELETE FROM Exam WHERE id = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>