<?php

namespace App\Controller;

use App\Core\View;

class Main
{

	public function defaultAction(){
		$view = new View("dashboard");
		$view->assign('title', 'Back office');
		$view->assign('headScript', 'src/js/headScripts/home.js');
		$view->assign('bodyScript', 'src/js/bodyScripts/home.js');
	}

	public function page404Action(){
		$view = new View("404", "front");
	}

}