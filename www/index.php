<?php

namespace App;

session_start();

use App\Core\Helpers;
use App\Core\Request;
use App\Core\Router;
use App\Core\ConstantManager;

require 'Autoload.php';
Autoload::register();
new ConstantManager();
Request::init();

$slug = mb_strtolower(Request::getURI());
$route = new Router($slug);
$controller = $route->getController();
$action = $route->getAction();
$middlewares = $route->getMiddlewares();
$office = $route->getOffice();
$params = $route->getParameters();

// Check privileges
if($office == 'back') {
    $user = Request::getUser();
    if (!($user && $user->isLogged() && $user->canAccessBackOffice())) {
        Helpers::redirect(Helpers::callRoute('404'), 404);
    }
}

// Check if controller file exists
if(file_exists('./Controllers/'.$controller.'.php')) {
	include './Controllers/'.$controller.'.php';
    $controller = 'App\\Controller\\'.$controller;

    // Check if controller class exists
	if(class_exists($controller)) {
		$controllerObject = new $controller();

        // Check if action exists
		if(method_exists($controllerObject, $action)) {

		    if(!empty($middlewares)) {
		        foreach ($middlewares as $middleware) {

                    // Check if middleware file exists
                    if(file_exists('./Middlewares/'.$middleware.'.php')) {
                        include './Middlewares/'.$middleware.'.php';
                        $middleware = 'App\\Middleware\\'.$middleware;

                        // Check if middleware class exists
                        if(class_exists($middleware)) {
                            $middlewareObject = new $middleware();
                            $middlewareObject->handle();
                        } else {
                            die('Error: class '.$middleware.' doesn\'t exist in file Middlewares/'.$middleware.'.php');
                        }
                    } else {
                        die('Error: file Middlewares/'.$middleware.'.php doesn\'t exist');
                    }
                }
            }
		    empty($params) ? $controllerObject->$action() : $controllerObject->$action(...$params);
		} else {
			die('Error: method '.$action.' doesn\'t exist');
		}
	} else {
		die('Error: class '.$controller.' doesn\'t exist in file Controllers/'.$controller.'.php');
	}
} else {
	die('Error: file Controllers/'.$controller.'.php doesn\'t exist');
}