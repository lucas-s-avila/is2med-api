<?php

require_once("../models/Doctor.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function mountDoc($row) {
    $doc = new Doctor($row["DoctorID"],
                      $row["Name"],
                      $row["Address"],
                      $row["Phone"],
                      $row["Email"],
                      $row["Specialty"],
                      $row["CRM"]);
    return $doc;
}

function loadDocs() {
    global $connection;

    $sql = "SELECT * FROM Doctor";
    $result = $connection->query($sql);

    $doctors = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $doc = mountDoc($row);
            $doctors[] = $doc;
        }
        return $doctors;
    } else {
        return null;
    }
}

function loadDocSearch($search) {
    global $connection;

    $sql = "SELECT * FROM Doctor WHERE ";
    
    if(!is_null($search["name"])) {
        $sql = $sql . " Name LIKE '%" . $search["name"] . "%'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["name"]);
    }

    if(!is_null($search["specialty"])) {
        $sql = $sql . " Specialty = '" . $search["specialty"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["specialty"]);
    }

    if(!is_null($search["crm"])) {
        $sql = $sql . " CRM = '" . $search["crm"] . "'";
    }

    $result = $connection->query($sql);

    $doctors = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $doc = mountDoc($row);
            $doctors[] = $doc;
        }
        return $doctors;
    } else {
        return null;
    }
}

function loadDoc($id) {
    global $connection;

    $sql = "SELECT * FROM Doctor WHERE DoctorID = " . ((string) $id);
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $doc = mountDoc($row);
        return $doc;
    } else {
        return null;
    }
}

function writeNewDoctor($data) {
    $id = time();
    $data["DoctorID"] = $id;

    $doc = mountDoc($data);

    global $connection;

    $sql = "INSERT INTO Doctor (DoctorID, Name, Address, Phone, Email, Specialty, CRM) 
                        VALUES (" . $doc->getId() .
                        ", '" . $doc->getName() . 
                        "', '" . $doc->getAddress() .
                        "', '" . $doc->getPhone() . 
                        "', '" . $doc->getEmail() . 
                        "', '" . $doc->getSpecialty() . 
                        "', '" . $doc->getCrm() . "')";
    if($connection->query($sql) === TRUE) {
        return $doc;
    } else {
        $response["Error"] = $connection->error;
        return $response;
    }
}

function writeDoctor($id, $data) {
    $doc = loadDoc($id);
    
    if(gettype($doc) == "object") {
        $doc->setName($data["Name"]);
        $doc->setAddress($data["Address"]);
        $doc->setPhone($data["Phone"]);
        $doc->setEmail($data["Email"]);
        $doc->setSpecialty($data["Specialty"]);
        $doc->setCrm($data["CRM"]);

        global $connection;
        $sql = "UPDATE Doctor SET Name = '" . $doc->getName() . 
               "', Address = '" . $doc->getAddress() . 
               "', Phone = '" . $doc->getPhone() .
               "', Email = '" . $doc->getEmail() .
               "', Specialty = '" . $doc->getSpecialty() . 
               "', CRM = '" . $doc->getCRM() .
               "' WHERE DoctorID = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $doc;
        } else {
            $response["Error"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeDoctor($id) {
    global $connection;

    $sql = "DELETE FROM Doctor WHERE DoctorID = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>