<?php
require_once("Doctor.php");
require_once("Pacient.php");

class Appointment {
    private $id;
    private $date;
    private $doctor;
    private $pacient;
    private $prescription;
    private $notes;

    function __construct($id, $date, $doctor, $pacient, $prescription, $notes) {
        $this->id = $id;
        $this->date = $date;
        $this->doctor = $doctor;
        $this->pacient = $pacient;
        $this->prescription = $prescription;
        $this->notes = $notes;
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

    function getDoctor() {
        return $this->doctor;
    }

    function setDoctor($doctor) {
        $this->doctor = $doctor;
    }

    function getPacient() {
        return $this->pacient;
    }

    function setPacient($pacient) {
        $this->pacient = $pacient;
    }

    function getPrescription() {
        return $this->prescription;
    }

    function setPrescription($prescription) {
        $this->prescription = $prescription;
    }

    function getNotes() {
        return $this->notes;
    }

    function setNotes($notes) {
        $this->notes = $notes;
    }

    public function jsonSerialize() {
        return array(
            "id" => $this->getId(),
            "doctor" => $this->getDoctor(),
            "pacient" => $this->getPacient(),
            "prescription" => $this->getPrescription(),
            "notes" => $this->getNotes()            
        );
    }
}

?>