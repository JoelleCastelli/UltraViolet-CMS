<?php

namespace App\Core;

use App\Models\Person;

class Request {

    private static $uri = null;
    private static $type = null;
    private static $segments = null;
    private static $user;

     // Prevent the class from being called 'non-statically'
    private function __construct() {}

    //Returns the Request object, so it can be used as a dependency
    public static function getRequest() {
        return new self;
    }

    // Stores all the request info into the class
    public static function init() {
        self::$uri = '/'.Helpers::sanitizeString(rtrim(substr($_SERVER["REQUEST_URI"], 1), '/'));
        self::$type = $_SERVER['REQUEST_METHOD'];
        self::$segments = explode('/', self::$uri);
        self::$user = new Person();
        if(isset($_SESSION['user_id'])) {
            self::$user = self::$user->findOneById($_SESSION['user_id']);
        }
    }

    public static function getURI() {
        return self::$uri;
    }

    public static function getType() {
        return self::$type;
    }

    public static function getSegments() {
        return self::$segments;
    }

    public static function getUser() {
        return self::$user;
    }

}