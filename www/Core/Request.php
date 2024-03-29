<?php

namespace App\Core;

use App\Models\Person;

class Request {

    private static ?string $uri = null;
    private static ?string $type = null;
    private static ?array $segments = null;
    private static Person $user;

     // Prevents the class from being called 'non-statically'
    private function __construct() {}

    // Returns the Request object, so it can be used as a dependency
    public static function getRequest(): Request {
        return new self;
    }

    // Stores all the request info into the class
    public static function init() {
        self::$uri = '/'.Helpers::sanitizeString(rtrim(substr($_SERVER["REQUEST_URI"], 1), '/'));
        self::$type = $_SERVER['REQUEST_METHOD'];
        self::$segments = explode('/', self::$uri);
        self::$user = new Person();
        if(isset($_SESSION['user_id'])) {
            if(isset($_SESSION['user_id']) && self::$user->findOneBy('id', $_SESSION['user_id'])) {
                self::$user = self::$user->findOneBy('id', $_SESSION['user_id']);
            }elseif(isset($_SESSION['user_id']) && !self::$user->findOneBy('id', $_SESSION['user_id'])) {
                session_destroy();
                Helpers::namedRedirect('logout');
            }
        }
    }

    public static function getURI(): ?string {
        return self::$uri;
    }

    public static function getType(): ?string {
        return self::$type;
    }

    public static function getSegments(): ?array {
        return self::$segments;
    }

    public static function getUser(): ?Person {
        return self::$user;
    }

}