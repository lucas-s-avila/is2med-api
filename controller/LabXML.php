<?php

require_once("../models/Lab.php");
libxml_use_internal_errors(true);

$idNum =  8000;

function loadLabs() {
    $xmlLab = simplexml_load_file("../xml/labs.xml");
    $labs = array();
    foreach($xmlLab->children() as $lab) {
        $labs[] = new Lab(intval($lab->id), (string) $lab->name, (string) $lab->address, (string) $lab->phone, (string) $lab->examType, (string) $lab->cnpj);
    }
    return $labs;
}

function loadLab($id) {
    $labs = loadLabs();
    foreach($labs as $lab) {
        if($lab->getId() == $id) {
            return $lab;
        }
    }
    return false;
}

function writeNewLab($data) {
    global $idNum;
    $idNum++;
    $lab = new Lab($idNum, (string) $data["name"], (string) $data["address"], (string) $data["phone"], (string) $data["examType"], (string) $data["cnpj"]);
    $xmlLab = simplexml_load_file("../xml/labs.xml");
    $lab = $xmlLab->addChild("lab");
    $doc->addChild("id",(string) $idNum);
    $lab->addChild("name",(string) $lab->getName());
    $lab->addChild("address",(string) $lab->getAddress());
    $lab->addChild("phone",(string) $lab->getPhone());
    $lab->addChild("examType",(string) $lab->getExamType());
    $lab->addChild("cnpj",(string) $lab->getCnpj());

    if ($xmlLab === false or $lab === false) {
        return "HTTP/1.0 400 Bad Request";
    }

    $write = simplexml_import_dom($xmlLab);
    $write->saveXML("../xml/labs.xml");

    return "HTTP/1.0 201 Created";
}

function writelab($id, $data) {
    $lab = loadlab($id);
    if(gettype($lab) == "object") {
        $lab->setName((string) $data["name"]);
        $lab->setAddress((string) $data["address"]);
        $lab->setPhone((string) $data["phone"]);
        $lab->setExamType((string) $data["examType"]);
        $lab->setCnpj((string) $data["cnpj"]);

        $xmlLab = simplexml_load_file("../xml/labs.xml");
        foreach($xmlLab as $lab) {
            if(intval($lab->id) == $lab->getId()) {
                $lab->name = $lab->getName();
                $lab->address = $lab->getAddress();
                $lab->phone = $lab->getPhone();
                $lab->examType = $lab->getExamType();
                $lab->cnpj = $lab->getCnpj();
            }
        }
        $write = simplexml_import_dom($xmlLab);
        $write->saveXML("../xml/labs.xml");

        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removelab($id) {
    $lab = loadlab($id);
    if(gettype($lab) == "object") {
        $xmlLab = simplexml_load_file("../xml/labs.xml");
        foreach($xmlLab as $lab) {
            if(intval($lab->id) == $lab->getId()) {
                unset($lab[0]);
            }
        }
        $write = simplexml_import_dom($xmlLab);
        $write->saveXML("../xml/labs.xml");
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}


?>