<?php
require_once("../models/Lab.php");
require_once("UserDB.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function mountLab($row) {
    $lab = new Lab($row["id"],
                      $row["name"],
                      $row["address"],
                      $row["phone"],
                      $row["email"],
                      $row["examType"],
                      $row["cnpj"]);
    return $lab;
}

function loadLabs() {
    global $connection;

    $sql = "SELECT * FROM Lab";
    $result = $connection->query($sql);

    $labs = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $lab = mountLab($row);
            $labs[] = $lab;
        }
        return $labs;
    } else {
        return null;
    }
}


function loadLabSearch($search) {
    global $connection;

    $sql = "SELECT * FROM Lab WHERE ";
    $sql = $sql . "name LIKE '%" . $search["name"] . "%'";

    $result = $connection->query($sql);

    $labs = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $lab = mountLab($row);
            $labs[] = $lab;
        }
        return $labs;
    } else {
        return null;
    }
}


function loadLab($id) {
    global $connection;

    $sql = "SELECT * FROM Lab WHERE id = " . ((string) $id);
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $lab = mountLab($row);
        return $lab;
    } else {
        return null;
    }
}

function writeNewLab($data) {
    $id = time();
    $data["id"] = $id;

    $lab = mountLab($data);

    global $connection;

    $sql = "INSERT INTO Lab (id, name, address, phone, email, examType, cnpj) 
                        VALUES (" . ((string) $id) .
                        ", '" . $lab->getName() . 
                        "', '" . $lab->getAddress() .
                        "', '" . $lab->getPhone() . 
                        "', '" . $lab->getEmail() . 
                        "', '" . $lab->getExamType() . 
                        "', '" . $lab->getCnpj() . "')";
    if($connection->query($sql) === TRUE) {
        $user = array(
            "username" => strtolower(explode(" ",$lab->getName())[0]),
            "password" => $lab->getCnpj(),
            "profileId" => $id,
            "groupName" => "lab"
        );
        $user = writeNewUser($user);
        return $lab;
    } else {
        $response["message"] = $connection->error;
        return $response;
    }
}

function writeLab($id, $data) {
    $lab = loadLab($id);
    
    if(gettype($lab) == "object") {
        $lab->setName($data["name"]);
        $lab->setAddress($data["address"]);
        $lab->setPhone($data["phone"]);
        $lab->setEmail($data["email"]);
        $lab->setExamType($data["examType"]);
        $lab->setCnpj($data["cnpj"]);

        global $connection;
        $sql = "UPDATE Lab SET name = '" . $lab->getName() . 
               "', address = '" . $lab->getAddress() . 
               "', phone = '" . $lab->getPhone() .
               "', email = '" . $lab->getEmail() .
               "', examType = '" . $lab->getExamType() . 
               "', cnpj = '" . $lab->getCNPJ() .
               "' WHERE id = " . ((string) $id);
        if($connection->query($sql) === TRUE) {
            return $lab;
        } else {
            $response["message"] = $connection->error;
            return $response;
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeLab($id) {
    global $connection;

    $sql = "DELETE FROM Lab WHERE id = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        echo $connection->error;
        return "HTTP/1.0 404 Not Found";
    }
}

?>