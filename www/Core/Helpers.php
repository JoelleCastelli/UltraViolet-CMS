<?php

namespace App\Core;

use App\Core\PhpMailer\SMTP;
use App\Models\Category;
class Helpers{

	public static function cleanFirstname($firstname): string
    {
		return ucwords(mb_strtolower(trim($firstname)));
	}

	public static function getCurrentTimestamp(): string
    {
        $dateNow = new \DateTime('now');
        return $dateNow->format("Y-m-d H:i:s");
    }

    public static function isStrictlyEmpty(&$var) : bool
    {
        if (isset($var) && empty($var)) {
            return (is_numeric($var) || is_string($var)) && $var != "0";
        } else
            return false;
    }

    public static function dd($data) {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        die;
    }

    public static function cleanDump($data, $title = null) {
        echo "<div class='debug'>";

        if ($title) {
            echo "<br>";
            echo "### ".$title." ###";
        }
        echo "<br>";
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        echo "<br>";

        echo "</div>";
    }

    public static function cleanDumpArray($data, $title = null) {
        echo "<div class='debug'>";

        if ($title) {
            echo "<br>";
            echo "### ".$title." ###";
        }
        echo "<br>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        echo "<br>";

        echo "</div>";
    }

    public static function sanitizeString($url): string
    {
        return htmlspecialchars(strip_tags($url));
    }

    public static function redirect($url, $statusCode = 0) {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }

    public static function namedRedirect($routeName, $params = null, $statusCode = 0) {
        if (empty($params)) {
            Helpers::redirect(Helpers::callRoute($routeName), $statusCode);
        } else {
            Helpers::redirect(Helpers::callRoute($routeName, $params), $statusCode);
        }
    }

    public static function redirect404()
    {
        header('HTTP/1.0 404 Not Found');
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

    public static function getBaseUrl(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
    }

    public static function callRoute(string $name, array $params = [], bool $fullPath = false): string {
        if($name === '') return '#';

        foreach (Router::$routes as $office => $routes) {
            foreach ($routes as $routeName => $routeData) {
                if ($name == $routeName) {
                    if(array_key_exists('requirements', $routeData)) {
                        foreach ($routeData['requirements'] as $paramName => $regex) {
                            $routeData['path'] = str_replace('{' . $paramName . '}', $params[$paramName], $routeData['path']);
                        }
                        if($fullPath == true) {
                            return self::getBaseUrl().$routeData['path'];
                        }
                    }
                    return $routeData['path'];
                }
            }
        }
        die($name.': route name not found');
    }

    public static function slugify($text) : string {
        // Clean accents
        $accentTranslation = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'];
        $text = strtr($text, $accentTranslation);
        // Replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // Trim
        $text = trim($text, '-');
        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // Set to lowercase
        $text = strtolower($text);

        if (empty($text)) return false;

        return $text;
    }

    public static function convertToSnakeCase($input): string
    {
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

    /**
     * Read config file and store data into array
     * Example: $settings['APP_NAME'] = 'MyApp'
     */
    public static function readConfigFile(): array
    {
        $settings = [];
        $envFile = Helpers::getEnv() == 'dev' ? '.env.dev' : '.env';
        chmod($envFile, 0777);
        $config = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($config as $setting) {
            if(substr($setting, 0, 1) !== '#') {
                $pieces = explode("=", $setting);
                $settings[$pieces[0]] = htmlspecialchars($pieces[1]);
            }
        }
        return $settings;
    }

    /**
     * Update a specific field in config file
     */
    public static function updateConfigField($field, $newValue) {
        $settings = Helpers::readConfigFile();
        foreach ($settings as $name => $value) {
            if($name == $field) {
                $envFile = Helpers::getEnv() == 'dev' ? '.env.dev' : '.env';
                $currentSetting = "$name=$value";
                $newSetting = "$name=$newValue";
                $str = file_get_contents($envFile);
                $str = str_replace($currentSetting, $newSetting, $str);
                file_put_contents($envFile, $str);
            }
        }
    }

    /**
     * Get the ENV value in .env file
     */
    public static function getEnv() {
        $config = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($config as $setting) {
            if(substr($setting, 0, 1) !== '#') {
                $pieces = explode("=", $setting);
                if($pieces[0] == "ENV") return $pieces[1];
            }
        }
    }

}