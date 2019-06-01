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
        if ($pattern == '') {
            $pattern = '^[/]?$';
        } else {
            $pattern = '^/'.$pattern.'[/]?$';
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
        $path = preg_replace('#^'.$basepath.'#', '', $_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];
        foreach (self::$routes[$method] as $pattern => $function) {
            if (preg_match('#'.$pattern.'#', $path, $matches)) {
                array_shift($matches);
                $args = array_unshift($matches, $_REQUEST);
                call_user_func_array($function, $matches);
                break;
            }
        }
    }
}

?>