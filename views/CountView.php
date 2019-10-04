<?php

require_once("../controller/CountDB.php");

$request_method=$_SERVER["REQUEST_METHOD"];
$request = $_SERVER['REQUEST_URI'];

echo($request); // Testar para conferir se o contém o /is2med-api/

if($request_method == 'GET') {
    header("Content-Type: application/json");
    switch($request) {
        case '/doctors':
            echo json_encode(countDocs());
            break;
        case '/labs':
            echo json_encode(countLabs());
            break;
        case '/patients':
            echo json_encode(countPats());
            break;
        case '/appointments':
            if(!empty($_GET["patientid"]) or !empty($_GET["doctorid"])) {
                foreach($_GET as $field => $value) {
                    if(!empty($value)) {
                        $search[$field] = $value;
                    }
                }
                echo json_encode(countAppsID($search));
            } else {
                echo json_encode(countApps());
            }
            break;
        case '/exams':
            if(!empty($_GET["patientid"]) or !empty($_GET["labid"])) {
                foreach($_GET as $field => $value) {
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