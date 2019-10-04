<?php

require_once("../controller/CountDB.php");

$request_method=$_SERVER["REQUEST_METHOD"];
$request = $_SERVER["REQUEST_URI"];

$request = parse_url($request);
parse_str($request["query"], $query);
$request = $request["path"];

if($request_method == 'GET') {
    header("Content-Type: application/json");
    switch($request) {
        case '/is2med-api/count/doctors':
            echo json_encode(countDocs());
            break;
        case '/is2med-api/count/labs':
            echo json_encode(countLabs());
            break;
        case '/is2med-api/count/patients':
            echo json_encode(countPats());
            break;
        case '/is2med-api/count/appointments':
            if(!empty($query["patientid"]) or !empty($query["doctorid"])) {
                foreach($query as $field => $value) {
                    if(!empty($value)) {
                        $search[$field] = $value;
                    }
                }
                echo json_encode(countAppsID($search));
            } else {
                echo json_encode(countApps());
            }
            break;
        case '/is2med-api/count/exams':
            if(!empty($query["patientid"]) or !empty($query["labid"])) {
                foreach($query as $field => $value) {
                    if(!empty($value)) {
                        $search[$field] = $value;
                    }
                }
                echo json_encode(countExmsID($search));
            } else {
                echo json_encode(countExms());
            }
            break;
        default:
            header("HTTP/1.0 405 Method Not Allowed");
            break;
    }
} else {
    header("HTTP/1.0 405 Method Not Allowed");
}

?>