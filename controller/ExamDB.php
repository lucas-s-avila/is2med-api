<?php

require_once("../models/Exam.php");
require_once("config/connection.php");
require_once("DocDB.php");
require_once("PatientDB.php");
require_once("LabDB.php");

$db = new dbObj();
$connection =  $db->getConn();

function mountExm($row) {
    $exm = new Exam($row["ExamID"],
                           $row["Date"],
                           loadDoc($row["DoctorID"]),
                           loadPatient($row["PatientID"]),
                           loadLab($row["LabID"]),
                           $row["Type"],
                           $row["Result"]);
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

function loadExmSearch($search) {   //date, exam type, labID, patientID
    global $connection;

    $sql = "SELECT * FROM Exam WHERE ";
    
    if(!is_null($search["date"])) {
        $sql = $sql . " Date = '" . $search["date"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["date"]);
    }

    if(!is_null($search["type"])) {
        $sql = $sql . " Type = '" . $search["type"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["type"]);
    }

    if(!is_null($search["patientid"])) {
        $sql = $sql . " PatientID = '" . $search["patientid"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["patientid"]);
    }

    if(!is_null($search["labid"])) {
        $sql = $sql . " LabID = '" . $search["labid"] . "'";
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

    $sql = "SELECT * FROM Exam WHERE ExamID = " . ((string) $id);
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $exm = mountExm($row);
        return $exm;
    } else {
        return null;
    }
}

function writeNewexam($data) {
    $id = time();
    $data["ExamID"] = $id;

    $exm = mountExm($data);

    global $connection;

    $sql = "INSERT INTO Exam (ExamID, Date, DoctorID, PatientID, LabID, Type, Result) 
                        VALUES (" . $exm->getId() .
                        ", '" . $exm->getDate() . 
                        "', '" . $exm->getDoctor()->getId() .
                        "', '" . $exm->getPatient()->getId() . 
                        "', '" . $exm->getLab() ->getId() .
                        "', '" . $exm->getType() . "')"; 
                        "', '" . $exm->getResult() . "')";
    if($connection->query($sql) === TRUE) {
        return $exm;
    } else {
        $response["Error"] = $connection->error;
        return $response;
    }
}

function writeExam($id, $data) {
    $exm = loadExm($id);
    
    if(gettype($exm) == "object") {
        $exm->setDate($data["Date"]);
        $exm->setDoctor(mountDoc($data["DoctorID"]));
        $exm->setPatient(mountPat($data["PatientID"]));
        $exm->setLab(mountLab($data["LabID"]));
        $exm->setType($data["Type"]);
        $exm->setResult($data["Result"]);

        global $connection;
        $sql = "UPDATE exam SET Date = '" . $exm->getDate() . 
               "', DoctorID = '" . $exm->getDoctor()->getId() . 
               "', PatientID = '" . $exm->getPatient()->getId() .
               "', LabID = '" . $exm->getLab()->getId() .
               "', Type = '" . $exm->getType() .
               "', Result = '" . $exm->getResult() . 
               "' WHERE ExamID = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $exm;
        } else {
            $response["Error"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeExam($id) {
    global $connection;

    $sql = "DELETE FROM exam WHERE ExamID = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>