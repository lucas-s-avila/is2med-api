<?php

require_once("../models/Doctor.php");

function loadDocs() {
    $xmlDoc = simplexml_load_file("../xml/doctors.xml");
    $doctors = array();
    foreach($xmlDoc as $doc) {
        $doctors[] = new Doctor((string) $doc->id, (string) $doc->name, (string) $doc->address, (string) $doc->phone, (string) $doc->email, (string) $doc->specialization, (string) $doc->crm);
    }
    return $doctors;
}

function loadDoc($id) {
    $doctors = loadDocs();
    foreach($doctors as $doc) {
        if($doc->getId() == $id) {
            return $doc;
        }
    }
    return false;
}

function writeNewDoctor($data) {
    $id = time();
    $doctor = new Doctor((string) $id, (string) $data["name"], (string) $data["address"], (string) $data["phone"], (string) $data["email"], (string) $data["specialization"], (string) $data["crm"]);
    $xmlDoc = simplexml_load_file("../xml/doctors.xml");
    $doc = $xmlDoc->addChild("doctor");
    
    if(!array_key_exists("name", $data) || !array_key_exists("address", $data) || !array_key_exists("phone", $data) || !array_key_exists("email", $data) || !array_key_exists("specialization", $data) || !array_key_exists("crm", $data)) {
        return "HTTP/1.0 400 Bad Request";
    }

    $doc->addChild("id", $doctor->getId());
    $doc->addChild("name", $doctor->getName());
    $doc->addChild("address", $doctor->getAddress());
    $doc->addChild("phone", $doctor->getPhone());
    $doc->addChild("email",  $doctor->getEmail());
    $doc->addChild("specialization", $doctor->getSpecialization());
    $doc->addChild("crm", $doctor->getCrm());

    $write = simplexml_import_dom($xmlDoc);
    $write->saveXML("../xml/doctors.xml");

    return $doctor;
}

function writeDoctor($id, $data) {
    $doc = loadDoc($id);
    if(gettype($doc) == "object") {

        if(!array_key_exists("name", $data) || !array_key_exists("address", $data) || !array_key_exists("phone", $data) || !array_key_exists("email", $data) || !array_key_exists("specialization", $data) || !array_key_exists("crm", $data)) {
            return "HTTP/1.0 400 Bad Request";
        }

        $doc->setName((string) $data["name"]);
        $doc->setAddress((string) $data["address"]);
        $doc->setPhone((string) $data["phone"]);
        $doc->setEmail((string) $data["email"]);
        $doc->setSpecialization((string) $data["specialization"]);
        $doc->setCrm((string) $data["crm"]);

        $xmlDoc = simplexml_load_file("../xml/doctors.xml");
        foreach($xmlDoc as $doctor) {
            if($doctor->id == $doc->getId()) {
                $doctor->name = $doc->getName();
                $doctor->address = $doc->getAddress();
                $doctor->phone = $doc->getPhone();
                $doctor->email = $doc->getEmail();
                $doctor->specialization = $doc->getSpecialization();
                $doctor->crm = $doc->getCrm();
            }
        }

        $write = simplexml_import_dom($xmlDoc);
        $write->saveXML("../xml/doctors.xml");

        return $doc;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function writeAttributeDoc($id, $data) {
    $doc = loadDoc($id);
    if(gettype($doc) == "object") {
        foreach($data as $key => $value) {
            switch ($key) {
                case 'name':
                    $doc->setName((string) $value);
                    break;
                case 'address':
                    $doc->setAddress((string) $value);
                    break;
                case 'phone':
                    $doc->setPhone((string) $value);
                    break;
                case 'email':
                    $doc->setEmail((string) $value);
                    break;
                case 'specialization':
                    $doc->setSpecialization((string) $value);
                    break;
                case 'crm':
                    $doc->setCrm((string) $value);
                    break;
                default:
                    return "HTTP/1.0 200 OK";
                    break;
            }
        }

        $xmlDoc = simplexml_load_file("../xml/doctors.xml");
        foreach($xmlDoc as $doctor) {
            if(intval($doctor->id) == $doc->getId()) {
                $doctor->name = $doc->getName();
                $doctor->address = $doc->getAddress();
                $doctor->phone = $doc->getPhone();
                $doctor->email = $doc->getEmail();
                $doctor->specialization = $doc->getSpecialization();
                $doctor->crm = $doc->getCrm();
            }
        }

        $write = simplexml_import_dom($xmlDoc);
        $write->saveXML("../xml/doctors.xml");

        return $doc;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeDoctor($id) {
    $doc = loadDoc($id);
    if(gettype($doc) == "object") {
        $xmlDoc = simplexml_load_file("../xml/doctors.xml");
        foreach($xmlDoc as $doctor) {
            if($doctor->id == $doc->getId()) {
                unset($doctor[0]);
                $write = simplexml_import_dom($xmlDoc);
                $write->saveXML("../xml/doctors.xml");
                return "HTTP/1.0 200 OK";
            }
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>