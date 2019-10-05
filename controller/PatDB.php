<?php
require_once("../models/Patient.php");
require_once("UserDB.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function mountPat($row) {
    $pat = new Patient($row["PatientID"],
                      $row["Name"],
                      $row["Address"],
                      $row["Phone"],
                      $row["Email"],
                      $row["Gender"],
                      $row["Birthday"],
                      $row["CPF"]);
    return $pat;
}

function loadPats() {
    global $connection;

    $sql = "SELECT * FROM Patient";
    $result = $connection->query($sql);

    $patients = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $pat = mountPat($row);
            $patients[] = $pat;
        }
        return $patients;
    } else {
        return null;
    }
}


function loadPatSearch($search) {
    global $connection;

    $sql = "SELECT * FROM Patient WHERE ";
    
    if(!is_null($search["name"])) {
        $sql = $sql . " Name LIKE '%" . $search["name"] . "%'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["name"]);
    }

    $result = $connection->query($sql);

    $patients = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $pat = mountPat($row);
            $patients[] = $pat;
        }
        return $patients;
    } else {
        return null;
    }
}


function loadPat($id) {
    global $connection;

    $sql = "SELECT * FROM Patient WHERE PatientID = " . ((string) $id);
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $pat = mountPat($row);
        return $pat;
    } else {
        return null;
    }
}

function writeNewPatient($data) {
    $id = time();
    $data["PatientID"] = $id;

    $pat = mountPat($data);

    global $connection;

    $sql = "INSERT INTO Patient (PatientID, Name, Address, Phone, Email, Gender, Birthday, CPF) 
                        VALUES (" . $pat->getId() .
                        ", '" . $pat->getName() . 
                        "', '" . $pat->getAddress() .
                        "', '" . $pat->getPhone() . 
                        "', '" . $pat->getEmail() . 
                        "', '" . $pat->getGender() . 
                        "', '" . $pat->getBirthday() . 
                        "', '" . $pat->getCpf() . "')";
    if($connection->query($sql) === TRUE) {
        $user = array(
            "Username" => $pat->getName(),
            "Password" => $pat->getCpf(),
            "ProfileID" => $pat->getId(),
            "Group" => "Patient"
        );
        $user = writeNewUser($user);
        return $pat;
    } else {
        $response["message"] = $connection->error;
        return $response;
    }
}

function writePatient($id, $data) {
    $pat = loadPat($id);
    
    if(gettype($pat) == "object") {
        $pat->setName($data["Name"]);
        $pat->setAddress($data["Address"]);
        $pat->setPhone($data["Phone"]);
        $pat->setEmail($data["Email"]);
        $pat->setGender($data["Gender"]);
        $pat->setBirthday($data["Birthday"]);
        $pat->setCpf($data["CPF"]);

        global $connection;
        $sql = "UPDATE Patient SET Name = '" . $pat->getName() . 
               "', Address = '" . $pat->getAddress() . 
               "', Phone = '" . $pat->getPhone() .
               "', Email = '" . $pat->getEmail() .
               "', Gender = '" . $pat->getGender() . 
               "', Birthday = '" . $pat->getBirthday() . 
               "', CPF = '" . $pat->getCPF() .
               "' WHERE PatientID = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $pat;
        } else {
            $response["message"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removePatient($id) {
    global $connection;

    $sql = "DELETE FROM Patient WHERE PatientID = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>