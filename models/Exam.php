<?php
require_once("Doctor.php");
require_once("Patient.php");

class Exam implements JsonSerializable {
    private $id;
    private $date;
    private $lab;
    private $patient;
    private $type;
    private $result;

    function __construct($id, $date, $lab, $patient, $type, $result) {
        $this->id = $id;
        $this->date = $date;
        $this->lab = $lab;
        $this->patient = $patient;
        $this->type = $type;
        $this->result = $result;
    }

    function getId() {
        return $this->id;
    }

    function getDate() {
        return $this->date;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function getLab() {
        return $this->lab;
    }

    function setLab($lab) {
        $this->lab = $lab;
    }

    function getPatient() {
        return $this->patient;
    }

    function setPatient($patient) {
        $this->patient = $patient;
    }

    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }

    function getResult() {
        return $this->result;
    }

    function setResult($result) {
        $this->result = $result;
    }

    public function jsonSerialize() {
        return array(
            "id" => $this->getId(),
            "date" => $this->getDate(),
            "lab" => $this->getLab(),
            "patient" => $this->getPatient(),
            "type" => $this->getType(),
            "result" => $this->getResult()            
        );
    }
}

?>