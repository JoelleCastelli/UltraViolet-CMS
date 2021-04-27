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

}