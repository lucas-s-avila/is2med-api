<?php

require_once('routing/Router.php');

Router::get('/', function($request) {
    print_r($request);
});

Router::get('/doctors', function($request) {
    echo('doctors');
    echo($request.'<br>');
});

Router::get('/doctors/[0-9]*', function($request, $id) {
    echo('doctors-id: '.$id);
    echo($request.'<br>');
});

Router::run('/is2med-api');

?>