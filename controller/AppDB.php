<?php

require_once("../models/Appointment.php");
require_once("config/connection.php");
require_once("DocDB.php");
require_once("PatDB.php");

$db = new dbObj();
$connection =  $db->getConn();

function mountApp($row) {
    $patient = loadPat($row["PatientID"]);
    $doc = loadDoc($row["DoctorID"]);

    if(is_null($patient) or is_null($doc)) {
        header("HTTP/1.0 404 Not Found");
        exit();
    }

    $app = new Appointment($row["AppointmentID"],
                           $row["Date"],
                           $doc,
                           $patient,
                           $row["Prescription"],
                           $row["Notes"]);
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
        $sql = $sql . " Date = '" . $search["date"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["date"]);
    }

    if(!is_null($search["patientid"])) {
        $sql = $sql . " PatientID = '" . $search["patientid"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["patientid"]);
    }

    if(!is_null($search["doctorid"])) {
        $sql = $sql . " DoctorID = '" . $search["doctorid"] . "'";
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

    $sql = "SELECT * FROM Appointment WHERE AppointmentID = " . ((string) $id);
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
    $data["AppointmentID"] = $id;

    $app = mountApp($data);

    global $connection;

    $sql = "INSERT INTO Appointment (AppointmentID, DoctorID, PatientID, Date, Prescription, Notes) 
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
        $app->setDate($data["Date"]);
        $app->setDoctor(mountDoc($data["DoctorID"]));
        $app->setPatient(mountPat($data["PatientID"]));
        $app->setPrescription($data["Prescription"]);
        $app->setNotes($data["Notes"]);

        global $connection;
        $sql = "UPDATE Appointment SET Date = '" . $app->getDate() . 
               "', DoctorID = '" . $app->getDoctor()->getId() . 
               "', PatientID = '" . $app->getPatient()->getId() .
               "', Prescription = '" . $app->getPrescription() .
               "', Notes = '" . $app->getNotes() . 
               "' WHERE AppointmentID = " . ((string) $id);
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

    $sql = "DELETE FROM Appointment WHERE AppointmentID = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        echo($connection->error);
        return "HTTP/1.0 404 Not Found";
    }
}

?>