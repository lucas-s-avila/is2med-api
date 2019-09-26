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

?>