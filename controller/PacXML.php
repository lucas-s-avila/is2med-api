<?php

require_once("../models/Pacient.php");

function loadPacs() {
    $xmlPac = simplexml_load_file("../xml/pacients.xml");
    $pacients = array();
    foreach($xmlPac->children() as $pac) {
        $pacients[] = new Pacient((string) $pac->id, 
                                (string) $pac->name, 
                                (string) $pac->address, 
                                (string) $pac->phone, 
                                (string) $pac->email, 
                                (string) $pac->gender, 
                                (string) $pac->birthday, 
                                (string) $pac->cpf);
    }
    return $pacients;
}

function loadPacName($name) {
    $name = strtoupper($name);
    $pacients = loadPacs();
    $pacs = array();
    foreach($pacients as $pac) {
        if(strpos($pac->getName(), $name) === 0) {
            $pacs[] = $pac;
        }
    }
    if(sizeof($pacs)>0) {
        return $pacs;
    } else {
        return false;
    }
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
    $id = time();
    $pacient = new Pacient((string) $id, 
                        strtoupper((string) $data["name"]), 
                        (string) $data["address"], 
                        (string) $data["phone"], 
                        (string) $data["email"], 
                        (string) $data["gender"], 
                        (string) $data["birthday"], 
                        (string) $data["cpf"]);
    $xmlPac = simplexml_load_file("../xml/pacients.xml");
    $pac = $xmlPac->addChild("pacient");

    if(!array_key_exists("name", $data) || 
        !array_key_exists("address", $data) || 
        !array_key_exists("phone", $data) || 
        !array_key_exists("email", $data) || 
        !array_key_exists("gender", $data) || 
        !array_key_exists("birthday", $data) || 
        !array_key_exists("cpf", $data)) 
    {
        return "HTTP/1.0 400 Bad Request";
    }

    $pac->addChild("id",$pacient->getId());
    $pac->addChild("name",$pacient->getName());
    $pac->addChild("address",$pacient->getAddress());
    $pac->addChild("phone",$pacient->getPhone());
    $pac->addChild("email",$pacient->getEmail());
    $pac->addChild("gender",$pacient->getGender());
    $pac->addChild("birthday",$pacient->getBirthday());
    $pac->addChild("cpf",$pacient->getCpf());

    $write = simplexml_import_dom($xmlPac);
    $write->saveXML("../xml/pacients.xml");

    return $pacient;
}

function writePacient($id, $data) {
    $pac = loadPacient($id);
    if(gettype($pac) == "object") {
        if(!array_key_exists("name", $data) || 
            !array_key_exists("address", $data) || 
            !array_key_exists("phone", $data) || 
            !array_key_exists("email", $data) || 
            !array_key_exists("gender", $data) || 
            !array_key_exists("birthday", $data) || 
            !array_key_exists("cpf", $data)) 
        {
            return "HTTP/1.0 400 Bad Request";
        }

        $pac->setName(strtoupper((string) $data["name"]));
        $pac->setAddress((string) $data["address"]);
        $pac->setPhone((string) $data["phone"]);
        $pac->setEmail((string) $data["email"]);
        $pac->setGender((string) $data["gender"]);
        $pac->setBirthday((string) $data["birthday"]);
        $pac->setCpf((string) $data["cpf"]);

        $xmlPac = simplexml_load_file("../xml/pacients.xml");
        foreach($xmlPac as $pacient) {
            if($pacient->id == $pac->getId()) {
                $pacient->name = $pac->getName();
                $pacient->address = $pac->getAddress();
                $pacient->phone = $pac->getPhone();
                $pacient->email = $pac->getEmail();
                $pacient->gender = $pac->getGender();
                $pacient->birthday = $pac->getBirthday();
                $pacient->cpf = $pac->getCpf();
            }
        }
        $write = simplexml_import_dom($xmlPac);
        $write->saveXML("../xml/pacients.xml");

        return $pac;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function writeAttributePac($id, $data) {
    $pac = loadPacient($id);
    if(gettype($pac) == "object") {
        foreach($data as $key => $value) {
            switch ($key) {
                case 'name':
                    $pac->setName(strtoupper((string) $value));
                    break;
                case 'address':
                    $pac->setAddress((string) $value);
                    break;
                case 'phone':
                    $pac->setPhone((string) $value);
                    break;
                case 'email':
                    $pac->setEmail((string) $value);
                    break;
                case 'gender':
                    $pac->setGender((string) $value);
                    break;
                case 'birthday':
                    $pac->setBirthday((string) $value);
                    break;
                case 'cpf':
                    $pac->setCpf((string) $value);
                    break;
                default:
                    return "HTTP/1.0 200 OK";
                    break;
            }
        }

        $xmlPac = simplexml_load_file("../xml/pacients.xml");
        foreach($xmlPac as $pacient) {
            if($pacient->id == $pac->getId()) {
                $pacient->name = $pac->getName();
                $pacient->address = $pac->getAddress();
                $pacient->phone = $pac->getPhone();
                $pacient->email = $pac->getEmail();
                $pacient->gender = $pac->getGender();
                $pacient->birthday = $pac->getBirthday();
                $pacient->cpf = $pac->getCpf();
            }
        }

        $write = simplexml_import_dom($xmlPac);
        $write->saveXML("../xml/pacients.xml");

        return $pac;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removepacient($id) {
    $pac = loadPacient($id);
    if(gettype($pac) == "object") {
        $xmlPac = simplexml_load_file("../xml/pacients.xml");
        foreach($xmlPac as $pacient) {
            if($pacient->id == $pac->getId()) {
                unset($pacient[0]);
                $write = simplexml_import_dom($xmlPac);
                $write->saveXML("../xml/pacients.xml");
                return "HTTP/1.0 200 OK";
            }
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>