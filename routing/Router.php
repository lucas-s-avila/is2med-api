<?php

class Router {
    private static $routes = array(
        'GET' => array(),
        'POST' => array(),
        'PUT' => array(),
        'PATCH' => array(),
        'DELETE' => array()
    );
    private static $notFoundFunction;
    private static $methodNotAllowedFunction;
    private static function add($method, $pattern, $function) {
        $pathArray = explode('/', $pattern);
        $pathArray = array_filter($pathArray, function($s) { return $s != ''; });
        $pathArray = array_map(function($s) { return '('.$s.')'; }, $pathArray);
        $pattern = implode('/', $pathArray);
        if ($pattern == '/') {
            $pattern = '^[/]?$';
        } else {
            $pattern = '^/'.$pattern.'[/]?';
        }
        self::$routes[$method][$pattern] = $function;
    }
    public static function get($pattern, $function) {
        self::add('GET', $pattern, $function);
    }
    public static function post($pattern, $function) {
        self::add('POST', $pattern, $function);
    }
    public static function put($pattern, $function) {
        self::add('PUT', $pattern, $function);
    }
    public static function patch($pattern, $function) {
        self::add('PATCH', $pattern, $function);
    }
    public static function delete($pattern, $function) {
        self::add('DELETE', $pattern, $function);
    }
    public static function notFound($function) {
        self::$notFoundFunction = $function;
    }
    public static function methodNotAllowed($function) {
        self::$methodNotAllowedFunction = $function;
    }
    public static function run($basepath) {
        $parsedURL = parse_url($_SERVER['REQUEST_URI']);
        if(isset($parsed_url['path'])){
            $path = $parsed_url['path'];
            $path = preg_replace('^/'.preg_quote($basepath), '', $path);
        } else {
            $path = '/';
        }
        $method = $_SERVER['REQUEST_METHOD'];
        echo($path);
        foreach (self::$routes[$method] as $pattern => $function) {
            print_r($pattern);
            echo('<br/>');
        }
    }
}

?>