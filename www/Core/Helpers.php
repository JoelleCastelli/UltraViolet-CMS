<?php

namespace App\Core;

class Helpers{

	public static function cleanFirstname($firstname){
		return ucwords(mb_strtolower(trim($firstname)));
	}

	public static function getCurrentTimestamp(){
        $dateNow = new \DateTime('now');
        return $dateNow->format("Y-m-d H:i:s");
    }

    public static function slugify($text) : string {

        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        if (empty($text)) return '-1';
        return $text;
    }

    public static function dd($data) {
        echo "<pre>";
        var_dump($data);die;
        echo "</pre>";
    }

    public static function sanitizeString($url) {
        return htmlspecialchars(strip_tags($url));
    }

    public static function redirect($url, $statusCode = 0) {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    public static function setFlashMessage($key, $msg) {
        if(gettype($msg) == 'array'){
            foreach ($msg as $item) {
                $_SESSION['flash'][$key][] = $item;
            }
        } else {
            $_SESSION['flash'][$key] = $msg;
        }
    }

    /* URL Helpers */

    public static function urlBase()
    {
        return  $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] . '/';
    }

    public static function urlJS(string $url) {
        return self::urlBase() . "src/js/" . $url . ".js";
    }

    public static function callRoute(string $name, array $params = [], bool $fullPath = false): string {

        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
            "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . 
            $_SERVER['REQUEST_URI'];

        foreach (Router::$routes as $office => $routes) {
            foreach ($routes as $routeName => $routeData) {
                if ($name == $routeName) {
                    if(array_key_exists('requirements', $routeData)) {
                        foreach ($routeData['requirements'] as $paramName => $regex) {
                            $routeData['path'] = str_replace('{' . $paramName . '}', $params[$paramName], $routeData['path']);
                        }
                        if($fullPath == true) {
                            return $link.$routeData['path'];
                        }
                    }
                    return $routeData['path'];
                }
            }
        }
        die($name.': route name not found');
    }

    public static function slugify($text) : string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) return false;
        return $text;
    }

    public static function convertToSnakeCase($input) {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('_', $ret);
    }

}