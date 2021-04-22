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
		$view->assign('title', 'Back office');
		$view->assign('headScript', 'Resources/scripts/headScripts/home.js');
		$view->assign('bodyScript', 'Resources/scripts/bodyScripts/home.js');
	}

	public function page404Action(){
		$view = new View("404"); 
	}

}