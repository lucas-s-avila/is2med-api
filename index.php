<?php

require_once('routing/Router.php');
require_once('models/BasicInfo.php');

Router::get('/', function($request) {
    echo json_encode(new BasicInfo(1, "Lucas Avila", "Rua Teste, 123", "53999999999"), JSON_PRETTY_PRINT);
});

$listDoctors = function($request) {
    header('Content-Type: application/json');
    echo json_encode(new BasicInfo(1, "Lucas Avila", "Rua Teste, 123", "53999999999"), JSON_PRETTY_PRINT);
};

Router::get('/doctors', $listDoctors);

Router::post('/doctors', $listDoctors);

Router::get('/doctors/[0-9]+', function($request, $id) {
    echo('doctors-id: '.$id);
    echo('<br>');
    print_r($request);
});

Router::notFound(function($request) {
    echo("NOT FOUND");
});

Router::run('/is2med-api');

?>