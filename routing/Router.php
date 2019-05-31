<?php

class Router {
    private static $instance;
    private $routes = array();
    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}
    public function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}

?>