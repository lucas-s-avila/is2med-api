<?php

require_once('routing/Router.php');

Router::get('/', function($request) {
    echo('home');
});

Router::get('/doctors', function($request) {
    echo('doctors');
});

Router::get('/doctors/3/details', function($request) {
    echo('doctors');
});

phpinfo();

Router::run('/is2med-api');

?>