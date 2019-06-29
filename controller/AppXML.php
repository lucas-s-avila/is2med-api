<?php

require_once("../models/Appointment.php");

function loadApps() {
    $xmlApp = simplexml_load_file("../xml/appointments.xml");
    $appoints = array();
    foreach($xmlApp as $appoint) {
        $appoints[] = new Appointment((string) $appoint->id,
                                      (string) $appoint->date, 
                                      (string) $appoint->doctor, 
                                      (string) $appoint->pacient, 
                                      (string) $appoint->prescription, 
                                      (string) $appoint->notes);
    }
    return $appoints;
}

function loadDocApp($doctor) {
    $appoints = loadApps();
    $apps = array();
    foreach($appoints as $app) {
        if($app->getDoctor() == $doctor) {
            $apps[] = $app;
        }
    }
    if(sizeof($apps)>0) {
        return $apps;
    } else {
        return false;
    }
}

function loadPacApp($pacient) {
    $appoints = loadApps();
    $apps = array();
    foreach($appoints as $app) {
        if($app->getPacient() == $pacient) {
            $apps[] = $app;
        }
    }
    if(sizeof($apps)>0) {
        return $apps;
    } else {
        return false;
    }
}

function loadApp($id) {
    $appoints = loadApps();
    foreach($appoints as $app) {
        if($app->getId() == $id) {
            return $app;
        }
    }
    return false;
}

function writeNewAppoint($data) {
    $id = time();
    $appoint = new Appointment( (string) $id,
                                (string) $data["date"],
                                (string) $data["doctor"], 
                                (string) $data["pacient"], 
                                (string) $data["prescription"], 
                                (string) $data["notes"]); 
    $xmlApp = simplexml_load_file("../xml/appointments.xml");
    $app = $xmlApp->addChild("appointment");
    
    if(!array_key_exists("date", $data) || 
        !array_key_exists("doctor", $data) || 
        !array_key_exists("pacient", $data) || 
        !array_key_exists("prescription", $data) || 
        !array_key_exists("notes", $data)) 
    {
        return "HTTP/1.0 400 Bad Request";
    }

    $app->addChild("id", $appoint->getId());
    $app->addChild("date", $appoint->getDate());
    $app->addChild("doctor", $appoint->getDoctor());
    $app->addChild("pacient", $appoint->getPacient());
    $app->addChild("prescription",  $appoint->getPrescription());
    $app->addChild("notes", $appoint->getNotes());

    $write = simplexml_import_dom($xmlApp);
    $write->saveXML("../xml/appointments.xml");

    return $appoint;
}

function writeAppoint($id, $data) {
    $app = loadApp($id);
    if(gettype($app) == "object") {

        if(!array_key_exists("date", $data) || 
            !array_key_exists("doctor", $data) || 
            !array_key_exists("pacient", $data) || 
            !array_key_exists("prescription", $data) || 
            !array_key_exists("notes", $data)) 
        {
            return "HTTP/1.0 400 Bad Request";
        }

        $app->setDate((string) $data["date"]);
        $app->setDoctor((string) $data["doctor"]);
        $app->setPacient((string) $data["pacient"]);
        $app->setPrescription((string) $data["prescription"]);
        $app->setNotes((string) $data["notes"]);

        $xmlApp = simplexml_load_file("../xml/appointments.xml");
        foreach($xmlApp as $appoint) {
            if($appoint->id == $app->getId()) {
                $appoint->date = $app->getDate();
                $appoint->doctor = $app->getDoctor();
                $appoint->pacient = $app->getPacient();
                $appoint->prescription = $app->getPrescription();
                $appoint->notes = $app->getNotes();
            }
        }

        $write = simplexml_import_dom($xmlApp);
        $write->saveXML("../xml/appointments.xml");

        return $app;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function writeAttributeApp($id, $data) {
    $app = loadApp($id);
    if(gettype($app) == "object") {
        foreach($data as $key => $value) {
            switch ($key) {
                case 'date':
                    $app->setDate((string) $value);
                    break;
                case 'doctor':
                    $app->setDoctor((string) $value);
                    break;
                case 'pacient':
                    $app->setPacient((string) $value);
                    break;
                case 'prescription':
                    $app->setPrescription((string) $value);
                    break;
                case 'notes':
                    $app->setNotes((string) $value);
                    break;
                default:
                    return "HTTP/1.0 200 OK";
                    break;
            }
        }

        $xmlApp = simplexml_load_file("../xml/appointments.xml");
        foreach($xmlApp as $appoint) {
            if($appoint->id == $app->getId()) {
                $appoint->date = $app->getDate();
                $appoint->doctor = $app->getDoctor();
                $appoint->pacient = $app->getPacient();
                $appoint->prescription = $app->getPrescription();
                $appoint->notes = $app->getNotes();
            }
        }

        $write = simplexml_import_dom($xmlApp);
        $write->saveXML("../xml/appointments.xml");

        return $app;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeAppoint($id) {
    $app = loadApp($id);
    if(gettype($app) == "object") {
        $xmlApp = simplexml_load_file("../xml/appointments.xml");
        foreach($xmlApp as $appoint) {
            if($appoint->id == $app->getId()) {
                unset($appoint[0]);
                $write = simplexml_import_dom($xmlApp);
                $write->saveXML("../xml/appointments.xml");
                return "HTTP/1.0 200 OK";
            }
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>