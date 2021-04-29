<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\View;

class Admin {

    public function handle($controllerObject, $action) {
        $user = Request::getUser();
        if ($user && $user->isAdmin()) {
            $controllerObject->$action();
        } else {
            // TODO : assigner les erreurs
            header('Location: /connexion');
        }

    }



}