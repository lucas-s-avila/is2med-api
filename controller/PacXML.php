<?php

require_once("../models/Pacient.php");
libxml_use_internal_errors(true);

$idNum =  1000;

function loadPacs() {
    $xmlPac = simplexml_load_file("../xml/pacients.xml");
    $pacients = array();
    foreach($xmlPac->children() as $pac) {
        $pacients[] = new Pacient(intval($pac->id), (string) $pac->name, (string) $pac->address, (string) $pac->phone, (string) $pac->gender, (string) $pac->birthday, (string) $pac->cpf);
    }
    return $pacients;
}

function loadPacient($id) {
    $pacients = loadPacs();
    foreach($pacients as $pac) {
        if($pac->getId() == $id) {
            return $pac;
        }
    }
    return false;
}


function writeNewPacient($data) {
    global $idNum;
    $idNum++;
    $pacient = new Pacient($idNum, (string) $data["name"], (string) $data["address"], (string) $data["phone"], (string) $data["gender"], (string) $data["birthday"], (string) $data["cpf"]);
    $xmlPac = simplexml_load_file("../xml/pacients.xml");
    $pac = $xmlPac->addChild("pacient");
    $doc->addChild("id",(string) $idNum);
    $pac->addChild("name",(string) $pacient->getName());
    $pac->addChild("address",(string) $pacient->getAddress());
    $pac->addChild("phone",(string) $pacient->getPhone());
    $pac->addChild("gender",(string) $pacient->getGender());
    $pac->addChild("birthday",(string) $pacient->getBirthday());
    $pac->addChild("cpf",(string) $pacient->getCpf());

    if ($xmlPac === false or $pac === false) {
        return "HTTP/1.0 400 Bad Request";
    }

    $write = simplexml_import_dom($xmlPac);
    $write->saveXML("../xml/pacients.xml");

    return "HTTP/1.0 201 Created";
}

function writePacient($id, $data) {
    $pac = loadPacient($id);
    if(gettype($pac) == "object") {
        $pac->setName((string) $data["name"]);
        $pac->setAddress((string) $data["address"]);
        $pac->setPhone((string) $data["phone"]);
        $pac->setGender((string) $data["gender"]);
        $pac->setBirthday((string) $data["birthday"]);
        $pac->setCpf((string) $data["cpf"]);

        $xmlPac = simplexml_load_file("../xml/pacients.xml");
        foreach($xmlPac as $pacient) {
            if(intval($pacient->id) == $pac->getId()) {
                $pacient->name = $pac->getName();
                $pacient->address = $pac->getAddress();
                $pacient->phone = $pac->getPhone();
                $pacient->gender = $pac->getGender();
                $pacient->birthday = $pac->getBirthday();
                $pacient->cpf = $pac->getCpf();
            }
        }
        $write = simplexml_import_dom($xmlPac);
        $write->saveXML("../xml/pacients.xml");

        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removepacient($id) {
    $pac = loadPacient($id);
    if(gettype($pac) == "object") {
        $xmlPac = simplexml_load_file("../xml/pacients.xml");
        foreach($xmlPac as $pacient) {
            if(intval($pacient->id) == $pac->getId()) {
                unset($pacient[0]);
            }
        }
        $write = simplexml_import_dom($xmlPac);
        $write->saveXML("../xml/pacients.xml");
        return "HTTP/1.0 200 OK";
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}


?>