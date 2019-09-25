<?php
require_once("../models/Doctor.php");
require_once("config/connection.php");
$db = new dbObj();
$connection =  $db->getConn();

function loadDocs() {
    global $connection;

    $sql = "SELECT * FROM Doctor";
    $result = $connection->query($sql);

    $doctors = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $doc = new Doctor($row["DoctorID"],
                              $row["Name"],
                              $row["Address"],
                              $row["Phone"],
                              $row["Email"],
                              $row["Specialty"],
                              $row["CRM"]);
            $doctors[] = $doc;
        }
        return $doctors;
    } else {
        return null;
    }
}



?>