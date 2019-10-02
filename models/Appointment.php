<?php
require_once("Doctor.php");
require_once("Pacient.php");

class Appointment implements JsonSerializable {
    private $id;
    private $date;
    private $doctor;
    private $patient;
    private $prescription;
    private $notes;

    function __construct($id, $date, $doctor, $patient, $prescription, $notes) {
        $this->id = $id;
        $this->date = $date;
        $this->doctor = $doctor;
        $this->pacient = $patient;
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

    function getPatient() {
        return $this->patient;
    }

    function setPatient($patient) {
        $this->patient = $patient;
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
            "date" => $this->getDate(),
            "doctor" => $this->getDoctor(),
            "patient" => $this->getPatient(),
            "prescription" => $this->getPrescription(),
            "notes" => $this->getNotes()            
        );
    }
}

?>