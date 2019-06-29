<?php

require_once("../models/Exam.php");

function loadExams() {
    $xmlExam = simplexml_load_file("../xml/exams.xml");
    $exams = array();
    foreach($xmlExam as $exam) {
        $exams[] = new Exam((string) $exam->id,
                            (string) $exam->date, 
                            (string) $exam->lab, 
                            (string) $exam->pacient, 
                            (string) $exam->type, 
                            (string) $exam->result);
    }
    return $exams;
}

function loadLabExam($lab) {
    $exams = loadExams();
    $exs = array();
    foreach($exams as $exam) {
        if($exam->getLab() == $lab) {
            $exs[] = $exam;
        }
    }
    if(sizeof($exs)>0) {
        return $exs;
    } else {
        return false;
    }
}

function loadPacExam($pacient) {
    $exams = loadExams();
    $exs = array();
    foreach($exams as $exam) {
        if($exam->getPacient() == $pacient) {
            $exs[] = $exam;
        }
    }
    if(sizeof($exs)>0) {
        return $exs;
    } else {
        return false;
    }
}

function loadExam($id) {
    $exams = loadExams();
    foreach($exams as $exam) {
        if($exam->getId() == $id) {
            return $exam;
        }
    }
    return false;
}

function writeNewExam($data) {
    $id = time();
    $exam = new Exam((string) $id,
                     (string) $data["date"],
                     (string) $data["lab"], 
                     (string) $data["pacient"], 
                     (string) $data["type"], 
                     (string) $data["result"]); 
    $xmlExam = simplexml_load_file("../xml/exams.xml");
    $examNode = $xmlExam->addChild("exam");
    
    if(!array_key_exists("date", $data) || 
        !array_key_exists("lab", $data) || 
        !array_key_exists("pacient", $data) || 
        !array_key_exists("type", $data) || 
        !array_key_exists("result", $data)) 
    {
        return "HTTP/1.0 400 Bad Request";
    }

    $examNode->addChild("id", $exam->getId());
    $examNode->addChild("date", $exam->getDate());
    $examNode->addChild("lab", $exam->getLab());
    $examNode->addChild("pacient", $exam->getPacient());
    $examNode->addChild("type",  $exam->getType());
    $examNode->addChild("result", $exam->getResult());

    $write = simplexml_import_dom($xmlExam);
    $write->saveXML("../xml/exams.xml");

    return $exam;
}

function writeExam($id, $data) {
    $exam = loadExam($id);
    if(gettype($exam) == "object") {

        if(!array_key_exists("date", $data) || 
            !array_key_exists("lab", $data) || 
            !array_key_exists("pacient", $data) || 
            !array_key_exists("type", $data) || 
            !array_key_exists("result", $data)) 
        {
            return "HTTP/1.0 400 Bad Request";
        }

        $exam->setDate((string) $data["date"]);
        $exam->setLab((string) $data["lab"]);
        $exam->setPacient((string) $data["pacient"]);
        $exam->setType((string) $data["type"]);
        $exam->setResult((string) $data["result"]);

        $xmlExam = simplexml_load_file("../xml/exams.xml");
        foreach($xmlExam as $examNode) {
            if($examNode->id == $exam->getId()) {
                $examNode->date = $exam->getDate();
                $examNode->lab = $exam->getLab();
                $examNode->pacient = $exam->getPacient();
                $examNode->type = $exam->getType();
                $examNode->result = $exam->getResult();
            }
        }

        $write = simplexml_import_dom($xmlExam);
        $write->saveXML("../xml/exams.xml");

        return $exam;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function writeAttributeExam($id, $data) {
    $exam = loadExam($id);
    if(gettype($exam) == "object") {
        foreach($data as $key => $value) {
            switch ($key) {
                case 'date':
                    $exam->setDate((string) $value);
                    break;
                case 'lab':
                    $exam->setLab((string) $value);
                    break;
                case 'pacient':
                    $exam->setPacient((string) $value);
                    break;
                case 'type':
                    $exam->setType((string) $value);
                    break;
                case 'result':
                    $exam->setResult((string) $value);
                    break;
                default:
                    return "HTTP/1.0 200 OK";
                    break;
            }
        }

        $xmlExam = simplexml_load_file("../xml/exams.xml");
        foreach($xmlExam as $examNode) {
            if($examNode->id == $exam->getId()) {
                $examNode->date = $exam->getDate();
                $examNode->lab = $exam->getLab();
                $examNode->pacient = $exam->getPacient();
                $examNode->type = $exam->getType();
                $examNode->result = $exam->getResult();
            }
        }

        $write = simplexml_import_dom($xmlExam);
        $write->saveXML("../xml/exams.xml");

        return $exam;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeExam($id) {
    $exam = loadExam($id);
    if(gettype($exam) == "object") {
        $xmlExam = simplexml_load_file("../xml/exams.xml");
        foreach($xmlExam as $examNode) {
            if($examNode->id == $exam->getId()) {
                unset($examNode[0]);
                $write = simplexml_import_dom($xmlExam);
                $write->saveXML("../xml/exams.xml");
                return "HTTP/1.0 200 OK";
            }
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>