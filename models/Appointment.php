<?php
require_once("Doctor.php");
require_once("Patient.php");

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
        $this->patient = $patient;
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
            "ID" => $this->getId(),
            "Date" => $this->getDate(),
            "Doctor" => $this->getDoctor(),
            "Patient" => $this->getPatient(),
            "Prescription" => $this->getPrescription(),
            "Notes" => $this->getNotes()            
        );
    }
}

?>