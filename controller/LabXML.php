<?php

require_once("../models/Lab.php");

function loadLabs() {
    $xmlLab = simplexml_load_file("../xml/labs.xml");
    $labs = array();
    foreach($xmlLab as $lab) {
        $labs[] = new Lab((string) $lab->id, 
                          (string) $lab->name, 
                          (string) $lab->address, 
                          (string) $lab->phone,
                          (string) $lab->email, 
                          (string) $lab->examType, 
                          (string) $lab->cnpj);
    }
    return $labs;
}

function loadLabName($name) {
    $name = strtoupper($name);
    $allLabs = loadLabs();
    $labs = array();
    foreach($allLabs as $lab) {
        if(strpos($lab->getName(), $name) === 0) {
            $labs[] = $lab;
        }
    }
    if(sizeof($labs)>0) {
        return $labs;
    } else {
        return false;
    }
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
    $id = time();
    $lab = new Lab((string) $id, 
                   strtoupper((string) $data["name"]), 
                   (string) $data["address"], 
                   (string) $data["phone"],
                   (string) $data["email"], 
                   (string) $data["examType"], 
                   (string) $data["cnpj"]);
    $xmlLab = simplexml_load_file("../xml/labs.xml");
    $labNode = $xmlLab->addChild("lab");

    if(!array_key_exists("name", $data) || 
        !array_key_exists("address", $data) || 
        !array_key_exists("phone", $data) || 
        !array_key_exists("email", $data) || 
        !array_key_exists("examType", $data) || 
        !array_key_exists("cnpj", $data)) 
    {
        return "HTTP/1.0 400 Bad Request";
    }

    $labNode->addChild("id", $lab->getId());
    $labNode->addChild("name", $lab->getName());
    $labNode->addChild("address", $lab->getAddress());
    $labNode->addChild("phone", $lab->getPhone());
    $labNode->addChild("examType", $lab->getExamType());
    $labNode->addChild("cnpj", $lab->getCnpj());

    $write = simplexml_import_dom($xmlLab);
    $write->saveXML("../xml/labs.xml");

    return $lab;
}

function writeLab($id, $data) {
    $lab = loadLab($id);
    if(gettype($lab) == "object") {

        if(!array_key_exists("name", $data) || 
            !array_key_exists("address", $data) || 
            !array_key_exists("phone", $data) || 
            !array_key_exists("email", $data) || 
            !array_key_exists("examType", $data) || 
            !array_key_exists("cnpj", $data)) 
        {
            return "HTTP/1.0 400 Bad Request";
        }

        $lab->setName(strtoupper((string) $data["name"]));
        $lab->setAddress((string) $data["address"]);
        $lab->setPhone((string) $data["phone"]);
        $lab->setExamType((string) $data["examType"]);
        $lab->setCnpj((string) $data["cnpj"]);

        $xmlLab = simplexml_load_file("../xml/labs.xml");
        foreach($xmlLab as $labNode) {
            if($labNode->id == $lab->getId()) {
                $labNode->name = $lab->getName();
                $labNode->address = $lab->getAddress();
                $labNode->phone = $lab->getPhone();
                $labNode->email = $lab->getEmail();
                $labNode->examType = $lab->getExamType();
                $labNode->cnpj = $lab->getCnpj();
            }
        }
        $write = simplexml_import_dom($xmlLab);
        $write->saveXML("../xml/labs.xml");

        return $lab;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function writeAttributeLab($id, $data) {
    $lab = loadLab($id);
    if(gettype($lab) == "object") {
        foreach($data as $key => $value) {
            switch ($key) {
                case 'name':
                    $lab->setName(strtoupper((string) $value));
                    break;
                case 'address':
                    $lab->setAddress((string) $value);
                    break;
                case 'phone':
                    $lab->setPhone((string) $value);
                    break;
                case 'email':
                    $lab->setEmail((string) $value);
                    break;
                case 'examType':
                    $lab->setExamType((string) $value);
                    break;
                case 'cnpj':
                    $lab->setCnpj((string) $value);
                    break;
                default:
                    return "HTTP/1.0 200 OK";
                    break;
            }
        }

        $xmlLab = simplexml_load_file("../xml/labs.xml");
        foreach($xmlLab as $LabNode) {
            if($LabNode->id == $lab->getId()) {
                $LabNode->name = $lab->getName();
                $LabNode->address = $lab->getAddress();
                $LabNode->phone = $lab->getPhone();
                $LabNode->email = $lab->getEmail();
                $LabNode->specialization = $lab->getSpecialization();
                $LabNode->crm = $lab->getCrm();
            }
        }

        $write = simplexml_import_dom($xmlLab);
        $write->saveXML("../xml/labs.xml");

        return $lab;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeLab($id) {
    $lab = loadLab($id);
    if(gettype($lab) == "object") {
        $xmlLab = simplexml_load_file("../xml/labs.xml");
        foreach($xmlLab as $labNode) {
            if($labNode->id == $lab->getId()) {
                unset($labNode[0]);
                $write = simplexml_import_dom($xmlLab);
                $write->saveXML("../xml/labs.xml");
                return "HTTP/1.0 200 OK";
            }
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}


?>