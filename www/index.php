<?php

namespace App;

if(session_id() == ''){
    session_start();
}

use App\Core\Router;
use App\Core\ConstantManager;

require 'Autoload.php';
Autoload::register();

new ConstantManager();

// Récupération du slug dans la variable super globale SERVER
$slug = mb_strtolower($_SERVER['REQUEST_URI']);

// Instance de la classe router (dossier CORE) avec le slug en paramètre
$route = new Router($slug);
$controller = $route->getController();
$action = $route->getAction();

// Vérification que le fichier du controller existe
if(file_exists('./Controllers/'.$controller.'.php')) {
	include './Controllers/'.$controller.'.php';
    $controller = 'App\\Controller\\'.$controller;

    // Vérification que la classe du controller existe
	if(class_exists($controller)){
		$controllerObject = new $controller();
        // Vérification que l'action existe
		if(method_exists($controllerObject, $action)) {
			$controllerObject->$action();
		} else {
			die('Erreur : la méthode '.$action.' n\'existe pas.');
		}
	} else {
		die('Erreur : la classe '.$controller.' n\'existe pas dans le fichier '.$controller.'.php');
	}
} else {
	die('Erreur : le fichier www/Controllers/'.$controller.' n\'existe pas.');
}