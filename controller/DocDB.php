<?php

require_once("../models/Doctor.php");
require_once("UserDB.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function mountDoc($row) {
    $doc = new Doctor($row["id"],
                      $row["name"],
                      $row["address"],
                      $row["phone"],
                      $row["email"],
                      $row["specialty"],
                      $row["crm"]);
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
        $sql = $sql . " name LIKE '%" . $search["name"] . "%'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["name"]);
    }

    if(!is_null($search["specialty"])) {
        $sql = $sql . " specialty = '" . $search["specialty"] . "'";
        if(count($search) > 1) {
            $sql = $sql . " AND ";
        }
        unset($search["specialty"]);
    }

    if(!is_null($search["crm"])) {
        $sql = $sql . " crm = '" . $search["crm"] . "'";
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

    $sql = "SELECT * FROM Doctor WHERE id = " . ((string) $id);
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
    $data["id"] = $id;

    $doc = mountDoc($data);

    global $connection;

    $sql = "INSERT INTO Doctor (id, name, address, phone, email, specialty, crm) 
                        VALUES (" . ((string) $id) .
                        ", '" . $doc->getName() . 
                        "', '" . $doc->getAddress() .
                        "', '" . $doc->getPhone() . 
                        "', '" . $doc->getEmail() . 
                        "', '" . $doc->getSpecialty() . 
                        "', '" . $doc->getCrm() . "')";
    if($connection->query($sql) === TRUE) {
        $user = array(
            "username" => strtolower(explode(" ",$doc->getName())[0]),
            "password" => $doc->getCrm(),
            "profileId" => $id,
            "groupName" => "doctor"
        );
        $user = writeNewUser($user);
        return $doc;
    } else {
        $response["message"] = $connection->error;
        return $response;
    }
}

function writeDoctor($id, $data) {
    $doc = loadDoc($id);
    
    if(gettype($doc) == "object") {
        $doc->setName($data["name"]);
        $doc->setAddress($data["address"]);
        $doc->setPhone($data["phone"]);
        $doc->setEmail($data["email"]);
        $doc->setSpecialty($data["specialty"]);
        $doc->setCrm($data["crm"]);

        global $connection;
        $sql = "UPDATE Doctor SET name = '" . $doc->getName() . 
               "', address = '" . $doc->getAddress() . 
               "', phone = '" . $doc->getPhone() .
               "', email = '" . $doc->getEmail() .
               "', specialty = '" . $doc->getSpecialty() . 
               "', crm = '" . $doc->getCRM() .
               "' WHERE id = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $doc;
        } else {
            $response["message"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeDoctor($id) {
    global $connection;

    $sql = "DELETE FROM Doctor WHERE id = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>