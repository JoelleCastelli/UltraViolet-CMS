<?php

namespace App;

if(session_id() == ''){
    session_start();
}

use App\Core\Request;
use App\Core\Router;
use App\Core\ConstantManager;

require 'Autoload.php';
Autoload::register();
Request::init();

new ConstantManager();

// Récupération du slug dans la variable super globale SERVER
$slug = mb_strtolower($_SERVER['REQUEST_URI']);

// Instance de la classe router (dossier CORE) avec le slug en paramètre
$route = new Router($slug);
$controller = $route->getController();
$action = $route->getAction();
$middleware = $route->getMiddleware();

// Vérification que le fichier du controller existe
if(file_exists('./Controllers/'.$controller.'.php')) {
	include './Controllers/'.$controller.'.php';
    $controller = 'App\\Controller\\'.$controller;

    // Vérification que la classe du controller existe
	if(class_exists($controller)){
		$controllerObject = new $controller();

        // Vérification que l'action existe
		if(method_exists($controllerObject, $action)) {

            // Si middleware dans le fichier de route
		    if(isset($middleware)) {

		        // Vérification que le fichier du middleware existe
                if(file_exists('./Middlewares/'.$middleware.'.php')) {
                    include './Middlewares/'.$middleware.'.php';
                    $middleware = 'App\\Middleware\\'.$middleware;

                    // Vérification que la classe du middleware existe
                    if(class_exists($middleware)){
                        $middlewareObject = new $middleware();
                        $middlewareObject->handle($controllerObject, $action);
                    }
                }
            } else {
                $controllerObject->$action();
            }
		} else {
			die('Erreur : la méthode '.$action.' n\'existe pas.');
		}
	} else {
		die('Erreur : la classe '.$controller.' n\'existe pas dans le fichier Controllers/'.$controller.'.php');
	}
} else {
	die('Erreur : le fichier Controllers/'.$controller.'.php n\'existe pas.');
}