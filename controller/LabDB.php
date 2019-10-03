<?php
require_once("../models/Lab.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function mountLab($row) {
    $lab = new Lab($row["LabID"],
                      $row["Name"],
                      $row["Address"],
                      $row["Phone"],
                      $row["Email"],
                      $row["ExamType"],
                      $row["CNPJ"]);
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
    $sql = $sql . "Name LIKE '%" . $search["name"] . "%'";

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

    $sql = "SELECT * FROM Lab WHERE LabID = " . ((string) $id);
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
    $data["LabID"] = $id;

    $lab = mountLab($data);

    global $connection;

    $sql = "INSERT INTO Lab (LabID, Name, Address, Phone, Email, ExamType, CNPJ) 
                        VALUES (" . $lab->getId() .
                        ", '" . $lab->getName() . 
                        "', '" . $lab->getAddress() .
                        "', '" . $lab->getPhone() . 
                        "', '" . $lab->getEmail() . 
                        "', '" . $lab->getExamType() . 
                        "', '" . $lab->getCnpj() . "')";
    if($connection->query($sql) === TRUE) {
        return $lab;
    } else {
        $response["message"] = $connection->error;
        return $response;
    }
}

function writeLab($id, $data) {
    $lab = loadLab($id);
    
    if(gettype($lab) == "object") {
        $lab->setName($data["Name"]);
        $lab->setAddress($data["Address"]);
        $lab->setPhone($data["Phone"]);
        $lab->setEmail($data["Email"]);
        $lab->setExamType($data["ExamType"]);
        $lab->setCnpj($data["CNPJ"]);

        global $connection;
        $sql = "UPDATE Lab SET Name = '" . $lab->getName() . 
               "', Address = '" . $lab->getAddress() . 
               "', Phone = '" . $lab->getPhone() .
               "', Email = '" . $lab->getEmail() .
               "', ExamType = '" . $lab->getExamType() . 
               "', CNPJ = '" . $lab->getCNPJ() .
               "' WHERE LabID = " . ((string) $id);
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

    $sql = "DELETE FROM Lab WHERE LabID = " . ((string) $id);

    if($connection->query($sql) === TRUE) {
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>