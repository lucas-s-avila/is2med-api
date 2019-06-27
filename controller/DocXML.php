<?php

require_once("../models/Doctor.php");
libxml_use_internal_errors(true);

function loadDocs() {
    $xmlDoc = simplexml_load_file("../xml/doctors.xml");
    $doctors = array();
    foreach($xmlDoc as $doc) {
        $doctors[] = new Doctor(intval($doc->id), (string) $doc->name, (string) $doc->address, (string) $doc->phone, (string) $doc->email, (string) $doc->specialization, (string) $doc->crm);
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
    $doctor = new Doctor($id, (string) $data["name"], (string) $data["address"], (string) $data["phone"], (string) $data["email"], (string) $data["specialization"], (string) $data["crm"]);
    $xmlDoc = simplexml_load_file("../xml/doctors.xml");
    $doc = $xmlDoc->addChild("doctor");
    
    $doc->addChild("id",(string) $doctor->getId());
    $doc->addChild("name",(string) $doctor->getName());
    $doc->addChild("address",(string) $doctor->getAddress());
    $doc->addChild("phone",(string) $doctor->getPhone());
    $doc->addChild("email", (string) $doctor->getEmail());
    $doc->addChild("specialization",(string) $doctor->getSpecialization());
    $doc->addChild("crm",(string) $doctor->getCrm());

    if ($xmlDoc === false or $doc === false) {
        return "HTTP/1.0 400 Bad Request";
    }

    $write = simplexml_import_dom($xmlDoc);
    $write->saveXML("../xml/doctors.xml");

    return $doctor;
}

function writeDoctor($id, $data) {
    $doc = loadDoc($id);
    if(gettype($doc) == "object") {
        $doc->setName((string) $data["name"]);
        $doc->setAddress((string) $data["address"]);
        $doc->setPhone((string) $data["phone"]);
        $doc->setEmail((string) $data["email"]);
        $doc->setSpecialization((string) $data["specialization"]);
        $doc->setCrm((string) $data["crm"]);

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

function writeAttribute($id, $data) {
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
                    return "HTTP/1.0 400 Bad Request";
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
            if(intval($doctor->id) == $doc->getId()) {
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

/*
$xmlAppoint = simplexml_load_file("../xml/appointments.xml");
$xmlExam = simplexml_load_file("../xml/exams.xml");
$xmlLab = simplexml_load_file("../xml/labs.xml");
$xmlPac = simplexml_load_file("../xml/pacients.xml");
*/

?>