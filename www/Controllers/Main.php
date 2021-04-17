<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Database;


class Main
{

	public function defaultAction(){
		$pseudo = "Prof";
		$sql = new Database();
		$view = new View("home"); 
		$view->assign("pseudo", $pseudo);
	}

	public function page404Action(){
		$view = new View("404"); 
	}

}