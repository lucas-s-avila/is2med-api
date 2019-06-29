<?php
require_once("Doctor.php");
require_once("Pacient.php");

class Exam implements JsonSerializable {
    private $id;
    private $date;
    private $lab;
    private $pacient;
    private $type;
    private $result;

    function __construct($id, $date, $lab, $pacient, $type, $result) {
        $this->id = $id;
        $this->date = $date;
        $this->lab = $lab;
        $this->pacient = $pacient;
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

    function getPacient() {
        return $this->pacient;
    }

    function setPacient($pacient) {
        $this->pacient = $pacient;
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
            "pacient" => $this->getPacient(),
            "type" => $this->getType(),
            "result" => $this->getResult()            
        );
    }
}

?>