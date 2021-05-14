<?php

namespace App\Controller;

use App\Core\Helpers;
use App\Core\View;

class Main
{

	public function defaultAction(){
		$view = new View("dashboard");
		$view->assign('title', 'Back office');
        $view->assignFlash();
		$view->assign('headScript', Helpers::urlJS('headScripts/home'));
		$view->assign('bodyScript',  Helpers::urlJS('bodyScripts/home'));
	}

	public function page404Action(){
		$view = new View("404", "front");
	}

	public function frontHomeAction(){
        $view = new View("home", "front");
    }

}