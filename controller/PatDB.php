<?php
require_once("../models/Patient.php");
require_once("UserDB.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function mountPat($row) {
    $pat = new Patient($row["id"],
                      $row["name"],
                      $row["address"],
                      $row["phone"],
                      $row["email"],
                      $row["gender"],
                      $row["birthday"],
                      $row["cpf"]);
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
        $sql = $sql . " name LIKE '%" . $search["name"] . "%'";
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

    $sql = "SELECT * FROM Patient WHERE id = " . ((string) $id);
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
    $data["id"] = $id;

    $pat = mountPat($data);

    global $connection;

    $sql = "INSERT INTO Patient (id, name, address, phone, email, gender, birthday, cpf) 
                        VALUES (" . ((string) $id) .
                        ", '" . $pat->getName() . 
                        "', '" . $pat->getAddress() .
                        "', '" . $pat->getPhone() . 
                        "', '" . $pat->getEmail() . 
                        "', '" . $pat->getGender() . 
                        "', '" . $pat->getBirthday() . 
                        "', '" . $pat->getCpf() . "')";
    if($connection->query($sql) === TRUE) {
        $user = array(
            "username" => strtolower(explode(" ",$pat->getName())[0]),
            "password" => $pat->getCpf(),
            "profileId" => $id,
            "groupName" => "patient"
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
        $pat->setName($data["name"]);
        $pat->setAddress($data["address"]);
        $pat->setPhone($data["phone"]);
        $pat->setEmail($data["email"]);
        $pat->setGender($data["gender"]);
        $pat->setBirthday($data["birthday"]);
        $pat->setCpf($data["cpf"]);

        global $connection;
        $sql = "UPDATE Patient SET name = '" . $pat->getName() . 
               "', address = '" . $pat->getAddress() . 
               "', phone = '" . $pat->getPhone() .
               "', email = '" . $pat->getEmail() .
               "', gender = '" . $pat->getGender() . 
               "', birthday = '" . $pat->getBirthday() . 
               "', cpf = '" . $pat->getCPF() .
               "' WHERE id = " . ((string) $id);
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

    $sql = "DELETE FROM Patient WHERE id = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>