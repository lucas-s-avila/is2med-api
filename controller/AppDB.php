<?php

require_once("../models/Appointment.php");
require_once("config/connection.php");
require_once("DocDB.php");
require_once("PatDB.php");

$db = new dbObj();
$connection =  $db->getConn();

function mountApp($row) {
    $patient = loadPat($row["patientId"]);
    $doc = loadDoc($row["doctorId"]);

    if(is_null($patient) or is_null($doc)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    $app = new Appointment($row["id"],
                           $row["date"],
                           $doc,
                           $patient,
                           $row["prescription"],
                           $row["notes"]);
    return $app;
}

function loadApps() {
    global $connection;

    $sql = "SELECT * FROM Appointment";
    $result = $connection->query($sql);

    $appoints = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $app = mountApp($row);
            $appoints[] = $app;
        }
        return $appoints;
    } else {
        return null;
    }
    return $appoints;
}

function loadAppSearch($search) {
    global $connection;

    $sql = "SELECT * FROM Appointment WHERE ";
    
    if(!is_null($search["date"])) {
        $sql = $sql . " date = '" . $search["date"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["date"]);
    }

    if(!is_null($search["patientId"])) {
        $sql = $sql . " patientId = '" . $search["patientId"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["patientId"]);
    }

    if(!is_null($search["doctorId"])) {
        $sql = $sql . " doctorId = '" . $search["doctorId"] . "'";
    }

    $result = $connection->query($sql);

    $appoints = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $app = mountApp($row);
            $appoints[] = $app;
        }
        return $appoints;
    } else {
        return null;
    }
}

function loadApp($id) {
    global $connection;

    $sql = "SELECT * FROM Appointment WHERE id = " . ((string) $id);
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $app = mountApp($row);
        return $app;
    } else {
        return null;
    }
}

function writeNewAppointment($data) {
    $id = time();
    $data["id"] = $id;

    $app = mountApp($data);

    global $connection;

    $sql = "INSERT INTO Appointment (id, doctorId, patientId, date, prescription, notes) 
                        VALUES (" . $app->getId() .
                        ", '" . $app->getDoctor()->getId() .
                        "', '" . $app->getPatient()->getId() .
                        "', '" . $app->getDate() . 
                        "', '" . $app->getPrescription() . 
                        "', '" . $app->getNotes() . "')";

    if($connection->query($sql) === TRUE) {
        return $app;
    } else {
        $response["message"] = $connection->error;
        return $response;
    }
}

function writeAppointment($id, $data) {
    $app = loadApp($id);
    
    if(gettype($app) == "object") {
        $app->setDate($data["date"]);
        $app->setDoctor(mountDoc($data["doctorId"]));
        $app->setPatient(mountPat($data["patientId"]));
        $app->setPrescription($data["prescription"]);
        $app->setNotes($data["notes"]);

        global $connection;
        $sql = "UPDATE Appointment SET date = '" . $app->getDate() . 
               "', doctorId = '" . $app->getDoctor()->getId() . 
               "', patientId = '" . $app->getPatient()->getId() .
               "', prescription = '" . $app->getPrescription() .
               "', notes = '" . $app->getNotes() . 
               "' WHERE id = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $app;
        } else {
            $response["message"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeAppointment($id) {
    global $connection;

    $sql = "DELETE FROM Appointment WHERE id = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        echo($connection->error);
        return "HTTP/1.0 404 Not Found";
    }
}

?>