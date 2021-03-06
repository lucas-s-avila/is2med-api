<?php

require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function countDocs() {
    global $connection;

    $sql = "SELECT COUNT(*) FROM Doctor";
    $result = $connection->query($sql);

    $response = array();
    $response["count"] = $result->fetch_array()["0"];
    return $response;
}

function countLabs() {
    global $connection;

    $sql = "SELECT COUNT(*) FROM Lab";
    $result = $connection->query($sql);

    $response = array();
    $response["count"] = $result->fetch_array()["0"];
    return $response;
}

function countPats() {
    global $connection;

    $sql = "SELECT COUNT(*) FROM Patient";
    $result = $connection->query($sql);

    $response = array();
    $response["count"] = $result->fetch_array()["0"];
    return $response;
}

function countApps() {
    global $connection;

    $sql = "SELECT COUNT(*) FROM Appointment";
    $result = $connection->query($sql);

    $response = array();
    $response["count"] = $result->fetch_array()["0"];
    return $response;
}

function countExms() {
    global $connection;

    $sql = "SELECT COUNT(*) FROM Exam";
    $result = $connection->query($sql);

    $response = array();
    $response["count"] = $result->fetch_array()["0"];
    return $response;
}

function countAppsID($search) {
    global $connection;

    $sql = "SELECT COUNT(*) FROM Appointment WHERE ";
    
    if(count($search) > 1) {
        $sql = $sql . "doctorId = '" . $search["doctorId"] . "' AND patientId = '" . $search["patientId"] . "'";
    } else {
        if(!empty($search["doctorid"])) {
            $sql = $sql . " doctorId = '" . $search["doctorId"] . "'";
        } else {
            $sql = $sql . " patientId = '" . $search["patientId"] . "'";
        }
    }

    $result = $connection->query($sql);
    
    $response = array();
    $response["count"] = $result->fetch_array()["0"];
    return $response;
}

function countExmsID($search) {
    global $connection;

    $sql = "SELECT COUNT(*) FROM Exam WHERE ";
    
    if(count($search) > 1) {
        $sql = $sql . "labId = '" . $search["labId"] . "' AND patientId = '" . $search["patientId"] . "'";
    } else {
        if(!empty($search["labId"])) {
            $sql = $sql . "labId = '" . $search["labId"] . "'";
        } else {
            $sql = $sql . "patientId = '" . $search["patientId"] . "'";
        }
    }
    
    $result = $connection->query($sql);

    $response = array();
    $response["count"] = $result->fetch_array()["0"];
    return $response;
}

?>