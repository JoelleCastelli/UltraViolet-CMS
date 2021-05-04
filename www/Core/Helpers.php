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

   public static function dd($data) {
        echo "<pre>";
            var_dump($data);die;
        echo "</pre>";
    }

    public static function sanitizeString($url) {
        return htmlspecialchars(strip_tags($url));
    }

    public static function redirect($url, $statusCode = 303) {
        header('Location: ' . $url, true, $statusCode);
        die();
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

    public static function getFlashMessage($key) {
        if(isset($_SESSION['flash'][$key])) {
            if(gettype($_SESSION['flash'][$key]) == 'array') {
                echo "<div class='flash-$key'>";
                foreach ($_SESSION['flash'][$key] as $item) {
                    echo "<li>$item</li>";
                }
                echo "</div>";
            } else {
                echo "<div class='flash-$key'>".$_SESSION['flash'][$key]."</div>";
            }
        }
        unset($_SESSION['flash'][$key]);
    }

}