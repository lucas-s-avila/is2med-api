<?php

require_once("../models/Doctor.php");

function loadDocs() {
    $xmlDoc = simplexml_load_file("../xml/doctors.xml");
    $doctors = array();
    foreach($xmlDoc->children() as $doc) {
        
        $doctors[] = new Doctor(intval($doc->id), (string) $doc->name, (string) $doc->address, (string) $doc->phone, (string) $doc->specialization, (string) $doc->crm);
    }
    return $doctors;
}
/*
$xmlAppoint = simplexml_load_file("../xml/appointments.xml");
$xmlExam = simplexml_load_file("../xml/exams.xml");
$xmlLab = simplexml_load_file("../xml/labs.xml");
$xmlPac = simplexml_load_file("../xml/pacients.xml");
*/

?>